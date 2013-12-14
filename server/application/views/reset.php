<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<h3>Reset password?</h3>
				<?php
				if(isset($success) && $success)
				{
					?>
					<div class="alert alert-success">
						<strong>All right!</strong> We've sent an email to <?php echo $email; ?> with instructions
						on how to proceed.
					</div>
					<p>
						Go check you email!<br />
						See you soon!
					</p>
					<?php
				}
				else
				{
					if(isset($success))
					{
						?>
						<div class="alert alert-danger">
							<strong>Oh no!</strong> We couldn't find a user with the email <?php echo $email; ?>. Are you
							sure you entered everything correctly?
						</div>
						<?php
					}
					?>
					<p>
						So you forgot your password? No worries, we've got you covered. Simply enter you email adress below,
						and we'll send you instructions of how to set a new password.
					</p>
					<form action="" method="post" class="reset">
						<p>
							<input type="email" name="email" id="reset_email" class="form-control input-lg"
								   placeholder="Email" <?php echo isset($email) ? ' value="'.$email.'"' : ''; ?>required autofocus>
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
				?>
			</div>
		</div>
	</div>
</main><!-- end #main -->
