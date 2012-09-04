<article class="item <?php print strtolower($fields['field_shortcut_title']->content) ?>">
  <a href="<?php print $fields['path']->content ?>">
    <div class="name">
      <h2><?php print $fields['field_shortcut_title']->content ?></h2>
      <?php print $fields['field_subtitle']->content ?>
    </div>
    <span class="square"></span>
  </a>
</article>