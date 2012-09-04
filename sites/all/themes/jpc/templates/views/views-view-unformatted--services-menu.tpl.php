<?php foreach ($rows as $key => $row) { ?>
  <?php print $row ?> <?php if ($key + 1 != count($rows)) print "/" ?>
<?php } ?>