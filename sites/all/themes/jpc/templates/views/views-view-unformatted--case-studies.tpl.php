<?php foreach ($rows as $key => $reference) { ?>
  <article class="item pos-<?php print $key + 1 ?>">
    <?php print $reference ?>
  </article>
<?php } ?>