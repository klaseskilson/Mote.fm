<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<div id="signuparea">
					<h2 id="signuptitle"></h2>
					<h3>Sign up, soon!</h3>
					<p>
						Currently, Mote.fm is in closed alpha testing. If you want to get notified when
						you can try it out by yourself, <?php echo anchor('/', 'add your email here'); ?>!
					</p>
					<p>
						<a href="#" data-toggle="signupsignin">Sign in instead!</a>
					</p>
				</div><!-- /#signuparea -->
				<div id="signinarea" style="display:none;">
					<h3>Sign in!</h3>
					<p>
						To access all awesome features that Mote.fm offers, sign in!
					</p>
					<form action="<?php echo (isset($redir) && $redir) ? '?redir='.$redir : ''; ?>" method="post" class="login">
						<?php
						if(isset($email))
						{
							?>
							<div class="alert alert-danger">
								<strong>Uh-oh!</strong>
								We could not log you in with those credentials. Make sure you
								entered the correct email and password combination and try again.
							</div>
							<?php
						}
						?>
						<p>
							<input type="email" name="email" id="login_email" class="form-control input-lg square-bottom"
								   placeholder="Email" <?php echo isset($email) ? ' value="'.$email.'"' : ''; ?>required autofocus>
							<input type="password" name="password" id="login_password" class="form-control input-lg square-top" placeholder="Password" required>
						</p>
						<p>
							<input type="submit" name="submit" id="login_submit" value="Go!" class="btn btn-default btn-lg btn-block">
						</p>
						<p>
							<a href="<?php echo base_url().'user/reset';?>" data-toggle="reset">Forgot your password?</a>
							<a href="#" data-toggle="signupsignin" class="pull-right">I want to sign up!</a>
						</p>
					</form>
				</div><!-- /#signinarea -->
			</div>
		</div>
	</div>
</main><!-- end #main -->
