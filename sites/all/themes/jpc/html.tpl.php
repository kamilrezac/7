<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="cs" class="ie6"> <![endif]-->
<!--[if lt IE 8 ]><html lang="cs" class="ie7"> <![endif]-->
<!--[if lt IE 9 ]><html lang="cs" class="ie8"> <![endif]-->
<!--[if lt IE 10 ]><html lang="cs" class="ie9"> <![endif]-->
<!--[if (gt IE 10)|!(IE)]><!--><html lang="cs"> <!--<![endif]-->
<head profile="<?php print $grddl_profile; ?>">
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0;" />
	<meta name="author" content="HTML by SuperKodéři (www.superkoderi.cz)" />
	<meta name="description" content="" />
	<title><?php print $head_title; ?></title>
    <!--  <meta property="fb:app_id" content="241275169287888"/>-->
    <!--  <meta property="og:title" content="--><?php //print $head_title; ?><!--" />-->
    <!--  <meta property="og:type" content="website" />-->
    <!--  <meta property="og:locale" content="cs_CZ" />-->
    <!--  <meta property="og:image" content="--><?php //print url(path_to_theme() . '/images/sikola-logo.jpg', array('absolute'=>true))  ?><!--" />-->
    <!--  <meta property="og:url" content="--><?php //print url($_GET['q'], array('absolute'=>true))  ?><!--" />-->
	<!-- ideálně sloučit všechny CSS do jednoho souboru -->
    <link rel="shortcut icon" href="<?php print theme_path() ?>favicon.ico?v=0" />
    <?php print $styles; ?>
    <script>document.documentElement.className += ' js';</script>
    <?php print $scripts; ?>
<!--  <script type="text/javascript" src="--><?php //print theme_path() ?><!--js/analytics.js"></script>-->
<!--  <script type="text/javascript">-->
<!--     _gas.push(['_setDomainName', '.aksikola.cz']);-->
<!--     _gas.create('UA-26282544-1', 'orig');-->
<!--     _gas.create('UA-28919479-1', 'new');-->
<!--  </script>-->
</head>
<body<?php if ($is_front) print ' class="homepage"' ?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
<!--  <script>-->
<!--    window.fbAsyncInit = function() {-->
<!--      FB.init({appId: '241275169287888', status: true, cookie: true, xfbml: true});-->
<!--    };-->
<!--    (function(d, s, id) {-->
<!--      var js, fjs = d.getElementsByTagName(s)[0];-->
<!--      if (d.getElementById(id)) return;-->
<!--      js = d.createElement(s); js.id = id;-->
<!--      js.src = "//connect.facebook.net/cs_CZ/all.js#xfbml=1&appId=241275169287888";-->
<!--      fjs.parentNode.insertBefore(js, fjs);-->
<!--    }(document, 'script', 'facebook-jssdk'));-->
<!--  </script>-->
</body>
</html>