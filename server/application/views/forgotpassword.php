<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<?php
				if(isset($user) && !$user)
				{
					?>
					<h3>Reset password</h3>
					<div class="alert alert-danger">
						<p>
							<strong>Oh no!</strong> The link you've used seems to be old. Are you sure you clicked the
							latest reset link? You know they can only be used once, right?
						</p>
						<p>
							No worries, though! Check your spam folder or enter your email once again, and we'll send you a new link.
						</p>
					</div>
					<p>
						So you forgot your password? No worries, we've got you covered. Simply enter you email adress below,
						and we'll send you instructions of how to set a new password.
					</p>
					<form action="<?php echo base_url().'user/reset'; ?>" method="post" class="reset">
						<p>
							<input type="email" name="email" id="reset_email" class="form-control input-lg"
								   placeholder="Email" value="<?php echo $email; ?>" required autofocus>
						</p>
						<p>
							<input type="submit" name="submit" id="reset_submit" value="Reset password!" class="btn btn-default btn-lg btn-block">
						</p>
						<p>
							<a href="<?php echo base_url().'user/signin';?>" data-toggle="reset">Sign in instead!</a>
						</p>
					</form>
					<?php
				}

				//
				if(isset($success) && $success)
				{
					?>
					<h3>Choose a new password</h3>
					<div class="alert alert-success">
						<strong>All right!</strong> You're password has been changed.
					</div>
					<p>
						<a href="<?php echo base_url(); ?>user/signin">Continue and sign in!</a>
					</p>
					<?php
				}
				else
				{
					?>
					<h3>Choose a new password</h3>
					<p>
						Great, you've allmost recovered your account. All you need to do now is to choose
						a new password.
					</p>
					<form action="<?php echo base_url().'user/forgotpassword/'.urlencode($email).'/'.$hash; ?>" method="post" class="newpwd">
						<div class="alert alert-danger" style="<?php echo isset($success) ? '' : 'display:none;'; ?>">
							<strong>Passwords do not match.</strong> Make sure the passwords you
							entered are exactly the same.
						</div>
						<p>
							<input type="password" name="password" id="newpwd_password" class="form-control input-lg square-bottom"
								placeholder="Choose new password" required autofocus>
							<input type="password" name="confirm" id="newpwd_confirm" class="form-control input-lg square-top"
								placeholder="Confirm new password" required>
						</p>
						<p>
							<input type="submit" name="submit" id="newpwd_submit" value="Change password!" class="btn btn-default btn-lg btn-block">
						</p>
					</form>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</main><!-- end #main -->
