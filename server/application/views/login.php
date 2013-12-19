<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<div id="signinarea">
					<h3>Sign in!</h3>
					<p>
						To access all awesome features that Mote.fm offers, sign in!
					</p>
					<form action="<?php echo $redir ? '?redir='.$redir : ''; ?>" method="post" class="login">
						<?php
						if(isset($activate) && is_array($activate) && $activate['error'] == 'activatefirst')
						{
							?>
							<div class="alert alert-danger">
								<p>
									<strong>Uh-oh!</strong>
									We found you account! However, you need to activate it first.
								</p>
								<p>
									Check you email and click the activation link we sent
									you there. Go <a href="<?php echo base_url().'user/resend'; ?>">here</a>
									if you have not recieved an activation email.
								</p>
							</div>
							<?php
						}
						elseif(isset($email))
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
				<div id="signuparea" style="display:none;">
					<h2 id="signuptitle"></h2>
					<h3>Sign up!</h3>
					<p>
						Sign up by entering the fields below. You'll need to confirm your email within three days.
					</p>
					<form action="<?php echo base_url(); ?>user/signup/" method="post" id="signupform" data-redir="<?php echo $redir ? $redir : ''; ?>">
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
						<p>
							<a href="#" data-toggle="signupsignin">Sign in instead!</a>
						</p>
					</form>
				</div><!-- /#signuparea -->
			</div>
		</div>
	</div>
</main><!-- end #main -->
