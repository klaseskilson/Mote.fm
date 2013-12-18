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
	<body class="landing">
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser.
			Please <a href="http://browsehappy.com/">upgrade your browser</a> or
			<a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a>
			to improve your experience.</p>
		<![endif]-->
		<div id="container">
			<main id="main">
				<div id="first">
					<div class="container">
						<div class="row margintop">
							<div class="col-xs-4 col-xs-offset-4 center-text">
								<img src="<?php echo base_url(); ?>web/img/logo/color_medium.png" alt="Mote.fm">
							</div>
							<div class="col-xs-4">
								<a href="<?php echo base_url(); ?>user/signin" class="pull-right btn btn-default">Sign in</a>
							</div>
						</div>
						<div class="row fit-to-bottom">
							<div class="col-sm-12 center-text">
								<h2>Democratize music</h2>
							</div>
						</div>
					</div>
				</div>

				<div class="container big">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-4">
							<p>
								Mote.fm helps you to easily let your guest control what's playing.
							</p>
							<p>
								<a href="#second" class="btn btn-default btn-lg btn-block">Get mote.fm!</a>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4 col-sm-offset-2">
							<p>
								No more skipped songs halfway through, and no more fighting over the play queue.
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4 col-sm-offset-6">
							<p>
								Use Spotify's extensive library to let people select what to listen to. Together.
							</p>
						</div>
					</div>
				</div>

				<div id="second">
					<div class="container">
						<div class="row">
							<div class="col-sm-4 col-sm-offset-4">
								<h2 id="signuptitle">Sign up today</h2>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4 col-sm-offset-4">
								<div id="signuparea">
									<form action="<?php echo base_url(); ?>user/signup/" method="post" id="signupform">
										<p>
											<input type="text" name="name" id="name" class="form-control input-lg square-bottom" placeholder="Full name" required>
											<input type="email" name="email" id="email" class="form-control input-lg square-bottom square-top" placeholder="Email" required>
											<input type="password" name="password" id="password" class="form-control input-lg square-top" placeholder="Password" required>
										</p>
										<p>
											<input type="submit" name="submit" id="submit" value="Go!" class="btn btn-default btn-lg btn-block">
										</p>
										<p>
											By creating a account you accept our <a href="#">terms of service</a>. We never share your information with anybody.
										</p>
									</form>
								</div><!-- /#signuparea -->
							</div>
						</div> <!-- end .row -->
					</div><!-- end .container -->
				</div><!-- end #second -->
			</main><!-- end main -->

<?php
// footer will be loaded here from controller
