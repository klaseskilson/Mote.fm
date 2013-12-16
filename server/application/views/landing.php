<main id="main">
	<div id="first" class="pane">
		<div class="container">
			<div class="row">
				<div class="col-sm-7 screamer">
					<h2>Democratize music</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 screamer">
					<p>
						Mote.fm helps you to easily let your guest control what's playing.
						No more skipped songs halfway through, and no more fighting over the play queue.
					</p>
					<p>
						<a href="#second">Sign up!</a>
					</p>
				</div>
				<div class="col-sm-6 screamer">
					<img src="<?php echo base_url().'web/img/landing/screenshot_iphone.png'; ?>" alt="iPhone screenshot"
						title="Apapts to your screen!" data-toggle="tooltip" />
				</div>
			</div> <!-- end .row -->
			<div class="bottom">
				<p class="pull-right">
					<span class="miniinfo"><a href="http://www.flickr.com/photos/31355686@N00/4701196608" target="_blank">Photo</a></span>
				</p>
			</div>
		</div><!-- end .container -->
	</div><!-- end .pane#first -->
	<div class="between">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h1>
						<span class="glyphicon glyphicon-calendar"></span>
					</h1>
				</div>
				<div class="col-sm-6 screamer">
					<h2>
						Hosting a party?
					</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-6 screamer">
					<p>
						Use Spotify's extensive library to let people select what to listen to.
					</p>
				</div>
			</div>
		</div>
	</div> <!-- end .between -->
	<div id="second" class="pane">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 screamer">
					<h2 id="signuptitle">Sign up today</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-3">
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
			<div class="bottom">
				<p class="pull-right">
					<span class="miniinfo"><a href="http://www.flickr.com/photos/stignygaard/12630269/" target="_blank">Photo</a></span>
				</p>
			</div>
		</div><!-- end .container -->
	</div><!-- end .pane#second -->
	<div class="between">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-sm-push-6">
					<h1>
						<span class="glyphicon glyphicon-circle-arrow-up"></span>
					</h1>
				</div>
				<div class="col-sm-6 col-sm-pull-6 screamer">
					<h2>
						Select music. Together.
					</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 screamer">
					<p>
						Let your guest vote on the playlist!
					</p>
				</div>
			</div>
		</div>
	</div> <!-- end .between -->
</main><!-- end #main -->
