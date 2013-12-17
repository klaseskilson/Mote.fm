<main id="main">
	<div class="container">
		<form action="" method="post">
			<div class="row">
				<div class="col-sm-12">
					<h1>
						Change profile
					</h1>
				</div>
			</div>

		</div>
		<div class="row">

			<div class="col-sm-4">
				<?php 
					$gravatarMd5 = md5(strtolower($user['email']));
					echo("<img src='http://www.gravatar.com/avatar/$gravatarMd5?s=200' width=200 alt=''/>");

			<?php
			if(isset($sent) && $sent)
			{

				?>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<?php
						if(isset($errors))
						{
							?>
							<div class="alert alert-danger">
								<strong>Oh no!</strong> Something went wrong, please check
								that you entered everything correctly and try again.
							</div>
							<?php
						}
						else
						{
							?>
							<div class="alert alert-success">
								<strong>Yeah!</strong> The changes were saved. We've sent you
								an email confirming this. Check that out so that everything
								is as it should be.
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
			<div class="row">
				<div class="col-sm-4">
					<h3 class="hidden-xs">
						&nbsp;
					</h3>
					<?php
						$gravatarMd5 = md5(strtolower($user['email']));
						echo '<img src="http://www.gravatar.com/avatar/'.$gravatarMd5.'?s=600&d=mm" class="img-responsive img-circle" alt=""/>';
					?>
					<p>
						We use <a href="http://gravatar.com/" target="_blank">Gravatar</a> together with
						your <label for="email" data-toggle="tooltip" title="Change you email">email</label>
						for profile images. Head over there to choose a profile image!
					</p>
				</div>
				<div class="col-sm-4">
					<h3>
						Name
					</h3>
					<?php
					if(isset($errors) && isset($errors['name']) && $errors['name'])
						echo '<div class="alert alert-danger">
								<strong>Uh-oh!</strong> There is something wrong with this
								name. Is it at least one character?
							</div>';
					?>
					<p>
						<input type="text" name="name" id="name" class="form-control input-lg"
							placeholder="Full name" value="<?php echo $input['name'] ? $input['name'] : $user['name']; ?>" required>
					</p>
					<h3>
						Email
					</h3>
					<?php
					if(isset($errors) && isset($errors['email']) && $errors['email'])
						echo '<div class="alert alert-danger">
								<strong>POW!</strong> This email is not valid or allready used
								by an other account.
							</div>';
					?>
					<p>
						Note: if you change your email adress, you'll need to confirm the new one.
					</p>
					<p>
						<input type="email" name="email" id="email" class="form-control input-lg"
							placeholder="Email" value="<?php echo $input['email'] ? $input['email'] : $user['email']; ?>" required>
					</p>
					<h3>
						Password
					</h3>
					<?php
					if(isset($errors) && isset($errors['newpwd']) && $errors['newpwd'])
						echo '<div class="alert alert-danger">
								<strong>Snap!</strong> The passwords you entered don\'t match. Also, make sure
								you choose a password longer than six characters.
							</div>';
					?>
					<p>
						<input type="password" name="new_password" id="newpwd_password" class="form-control input-lg square-bottom"
							placeholder="Choose new password">
						<input type="password" name="new_confirm" id="newpwd_confirm" class="form-control input-lg square-top"
							placeholder="Confirm new password">
					</p>
					<p>
						To change your password, enter it twice below. If you don't want to change it,
						leave these fields blank.
					</p>
				</div>
				<div class="col-sm-4">
					<h3>
						Save changes
					</h3>
					<?php
					if(isset($errors) && isset($errors['oldpwd']) && $errors['oldpwd'])
						echo '<div class="alert alert-danger">
								<strong>The old password you entered is incorrect.</strong>
								Make sure you entered it correctly and try again.
							</div>';
					?>
					<p>
						<input type="password" name="password" id="password" class="form-control input-lg"
							placeholder="Current password" required>
					</p>
					<p>
						To save the changes, enter you password above and click <em>Save changes</em>.
					</p>
					<p>
						<input type="submit" name="submit" id="edit_submit" value="Save changes" class="btn btn-default btn-lg btn-block">
					</p>
				</div>
			</div>
		</form>
	</div>
</main>
