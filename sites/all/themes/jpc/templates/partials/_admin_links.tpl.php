<?php global $language ?>
<?php if (user_is_logged_in()) { ?>
  <div class="admin-bar">
    <?php if (isset($node) && node_access('update', $node)) { ?>
      <a href="#overlay=<?php print $language->language ?>/node/<?php print $node->nid ?>/edit">Upravit tuto stránku</a>
    <?php } ?>
    <a href="/user/logout" class="system">Odhlásit</a>
  </div>
<?php } ?>