<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<form action="<?php echo $redir ? '?redir='.$redir : ''; ?>" method="post">
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
						<input type="email" name="email" id="email" class="form-control input-lg square-bottom"
							   placeholder="Email" <?php echo isset($email) ? ' value="'.$email.'"' : ''; ?>required autofocus>
						<input type="password" name="password" id="password" class="form-control input-lg square-top" placeholder="Password" required>
					</p>
					<p>
						<input type="submit" name="submit" id="submit" value="Go!" class="btn btn-default btn-lg btn-block">
					</p>
					<p>
						<a href="">Forgot your password?
					</p>
				</form>
			</div>
		</div>
	</div>
</main><!-- end #main -->
