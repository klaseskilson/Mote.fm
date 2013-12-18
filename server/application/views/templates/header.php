<!DOCTYPE html>
	<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
	<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
	<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Mote.fm<?php echo isset($title) ? ' &mdash; '.$title : ''; ?></title>
		<meta name="description" content="">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta http-equiv="cleartype" content="on">

		<!-- iOS Touch Icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="web/img/touch/apple-touch-icon-144x144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="web/img/touch/apple-touch-icon-114x114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="web/img/touch/apple-touch-icon-72x72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="web/img/touch/apple-touch-icon-57x57-precomposed.png">
		<!-- Uncomment to include a launch screen for iOS
		<link rel="apple-touch-startup-image" href="image.jpg"> -->

		<!-- Tile icon for Win8 (144x144 + tile color) -->
		<meta name="msapplication-TileImage" content="web/img/touch/apple-touch-icon-144x144-precomposed.png">
		<meta name="msapplication-TileColor" content="#333333">

		<!-- Browser favicon -->
		<link rel="shortcut icon" href="web/img/favicon.png">

		<!-- For iOS web apps. Delete if not needed.
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="">
		-->

		<!-- CSS files -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css" />
		<link rel="stylesheet/less" rel="stylesheet" href="<?php echo base_url(); ?>web/css/style.less" />

		<!-- LESS YO -->
		<script src="<?php echo base_url(); ?>web/js/less.js"></script>
	</head>
	<body<?php echo isset($bodystyle) ? ' class="'.$bodystyle.'"' : ''; ?>>
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser.
			Please <a href="http://browsehappy.com/">upgrade your browser</a> or
			<a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a>
			to improve your experience.</p>
		<![endif]-->
	<div id="container">
		<header id="head">
			<div class="container">
				<nav class="navbar navbar-hathor navbar-default" role="navigation">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo base_url(); ?>">
							<img src="<?php echo base_url(); ?>web/img/logo/color_small.png" alt="Logo">
							<!-- mote.fm -->
						</a>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
							<?php if(isset($user['uid']) && $user['uid'])
							{
								$gravatarMd5 = md5(strtolower($user['email']));
								?>
								<li>
									<a href="<?php echo base_url(); ?>user/profile">
										<img class="dashboardavatar img-circle"
										src="<?php echo "http://www.gravatar.com/avatar/$gravatarMd5?s=25&d=mm"?>" alt="">
										<?php echo $user['name'];?>
									</a>
								</li>
								<li><a href="<?php echo base_url(); ?>party">Parties</a></li>
								<li><a href="<?php echo base_url(); ?>about">About</a></li>
								<li><a href="<?php echo base_url(); ?>user/signout">Sign out</a></li>
								<?php
							}
							else
							{
								?>
								<li><a href="<?php echo base_url(); ?>about">About</a></li>
								<li><a href="<?php echo base_url(); ?>user/signin">Sign in</a></li>
								<?php
							}
							?>
						</ul>
					</div><!-- /.navbar-collapse -->
				</nav>
			</div>
		</header><!-- end #head -->


