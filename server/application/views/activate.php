<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
				<h3>Activate account</h3>
				<?php
				if(isset($success) && $success)
				{
					?>
					<div class="alert alert-success">
						<strong>Awesome!</strong> Your account has been activated. Now,
						head over to the <a href="<?php echo base_url().'user/signin';?>">sign in page</a>
						and get started!
					</div>
					<?php
				}
				else
				{
					?>
					<div class="alert alert-danger">
						<p>
							<strong>Oh no!</strong> Thah link appears to be outdated, and we could
							not activate you account. Maby you need to send yourself a new activation
							link?
						</p>
						<p>
							If so, <a href="<?php echo base_url().'user/resend';?>">head over here</a>.
						</p>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</main><!-- end #main -->
