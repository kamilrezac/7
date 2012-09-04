<section id="main">
	<div class="row-main">
		<?php include "partials/_logo.tpl.php" ?>
		<h1 class="vhide">Případová studie</h1>
		<div class="casestudy-box caserow-4 caserow-tablet-7">
      <?php print $rows; ?>
		</div>
		<hr />
		<div class="sub-menu">
			<p>
				<strong>Prozkoumejte také:</strong>
				<a href="<?php print url('') ?>">naše služby</a> /
				<?php print ll(t('Case studies'), 'pripadove-studie') ?>
			</p>
		</div>
	</div>
</section>
