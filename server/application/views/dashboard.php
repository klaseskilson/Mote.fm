<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<h3>Welcome back, <?php echo $user['names'][0]; ?></h3>
				<div class="row">
					<div class="col-xs-4">
						<a href="<?php echo base_url().'user/profile/';?>" data-toggle="tooltip"
							title="<span class='glyphicon glyphicon-cog'></span> Edit account">
							<?php
								$gravatarMd5 = md5(strtolower($user['email']));
								echo '<img src="http://www.gravatar.com/avatar/'.$gravatarMd5.'?s=600&d=mm"
									  class="img-responsive img-circle" alt="" />';
							?>
						</a>
					</div>
					<div class="col-xs-8">
						<p>Name: <?php echo $user['name'] ?></p>
						<p>Email: <?php echo $user['email'] ?></p>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<h3>Your Parties</h3>
				<?php
				// do we have any parties to show?
				if($parties)
				{
					// loop through the parties
					foreach ($parties as $party) {
						?>
						<p>
							<a href="<?php echo base_url().'party/view/'.$party['hash']; ?>">
								<?php echo $party['name']; ?>
							</a>
						</p>
						<?php
					}
				}
				else
				{
					?>
					<p>
						It appears as if you have not created any parties.
					</p>
					<?php
				}
				?>
			</div>
			<div class="col-sm-4">
				<h3>Parties you've attended</h3>
			<?php
				if($party_contrib)
				{
					foreach ($party_contrib as $party)
					{
						?>
						<p>
							<a href="<?php echo base_url().'party/view/'.$party['hash']; ?>">
								<?php echo $party['name']; ?>
							</a>
						</p>
						<?php
					}
				}
				else
				{
					?>
					<p>
						It appears as if you have not attended any parties. Other than your own, that is.
					</p>
					<?php
				}
			?>
			</div>
		</div>
	</div>
</main>
