<?php $classes = array('group', 'earth', 'money', 'certificate') ?>
<div class="ico-list content-760">
  <ul class="reset">
    <?php foreach ($items as $delta => $item) { ?>
       <li class="<?php print $classes[$delta] ?>">
          <span><?php print render($item); ?></span>
       </li>
    <?php } ?>
  </ul>
</div>