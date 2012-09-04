<?php
// $Id: views_handler_filter_node_access.inc,v 1.2.4.2 2010/08/26 09:41:55 dereine Exp $
/**
 * Filter by node_access records.
 */
class views_handler_filter_node_access extends views_handler_filter_in_operator {
  function admin_summary() { }
  function can_expose() {
    return FALSE;
  }

  function operators() {
    return array(
      'in' => array(
        'title' => t('User can access'),
        'short' => t('access'),
        'method' => 'op_simple',
        'values' => 1,
      ),
    );
  }

  function get_value_options() {
     $this->value_options = array(
      'view' => 'View',
      'update' => 'Edit',
      'delete' => 'Delete',
    );
  }

  /**
   * See _node_access_where_sql() for a non-views query based implementation.
   */
  function query() {

	/*
	 * TODO: there is an issue with a spurious null condition being added to the query->where array before the current handler method is called
	 *  I can't work out where this condition is coming from.
	 *  The following is a dirty hack to make the handler work, but means that
	 *  this handler must the first in the list, otherwise it removes other conditions ... 
	 *  maybe somebody can help :) ?
	*/
	$this->query->where = null; 
	/*
	 * end of hack
	*/
	if (!user_access('administer nodes') && !user_access('bypass node access')) {
      $table = $this->ensure_my_table();
      $grants = db_or(); 

		if (!user_access('access content'))	{
			$grants->condition('1','0','='); // will return false for all nodes so user will not see any content
		} else {
			// add node_access permissions to query
			foreach ($this->value as $taccess) {
				foreach (node_access_grants($taccess) as $realm => $gids) {
					foreach ($gids as $gid) {
				    	$grants->condition(db_and()
				      	->condition($table.'.gid', $gid)
					      ->condition($table.'.realm', $realm)
							->condition($table.'.grant_'.$taccess, 1, '>=')
					    );
					}
				} 
			}

			// add node permissions to query
			global $user;
			foreach(_node_types_build()->types as $node_type => $node_types) {
				foreach($this->value as $taccess) {
					if ($taccess == 'view') continue; // view access not covered by general permissions
					$taccess = $this->fixed_access_identifiers($taccess);
					if (user_access($taccess.' any '.$node_type.' content'))	{
						$grants->condition('node.type', $node_type, '=');
					} elseif (user_access($taccess.' own '.$node_type.' content'))	{
						$grants->condition(db_and()
							->condition('node.type', $node_type, '=')
							->condition('node.uid', $user->uid, '=')
						);
					}
				}
			}
		}
		
      $this->query->add_where('AND', $grants);
    }
  }

	function fixed_access_identifiers($access)
	{
		// sadly, in node_access, permissions are view, update, delete and in general permissions they are access, edit, delete. 
		// this function maps between the two when adding the node permissions to the query :(
		switch ($access)
		{
			case "view":
				return "access";
			case "update":
				return "edit";
			default:
				return $access;
		}
	}
}
