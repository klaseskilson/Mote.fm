<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>
					<?php echo $party['name']; ?>
					<small>
						<a href="<?php echo base_url().$party['hash']; ?>" data-toggle="tooltip"
							title="Share this link with your friends to let them add songs!">
							<?php echo str_replace('http://', '', base_url()).$party['hash']; ?>
						</a>
					</small>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 col-sm-push-8">
				<h4>Add songs</h4>
				<form role="form">
					<div class="form-group spotifysearch">
						<input type="hidden" class="partyid" value="<?php echo $party['partyid']; ?>" />
						<input type="text" class="form-control" class="searchinput" placeholder="Begin typing to search..." autocomplete="off" autofocus>
						<div class="searchresults"></div>
					</div>
					<!-- <button type="submit" class="btn btn-default">Submit</button> -->
				</form>
			</div>
			<div class="col-sm-8 col-sm-pull-4" id="partyqueue">
				<h4>Play queue</h4>
				<?php
					if($party_queue)
					{
						foreach ($party_queue as $entry)
						{
							?>
							<p>
								<img src="<?php echo $entry['albumart']?>" alt="" width ="50"><?php echo $entry['artistname']; ?> - <?php echo $entry['trackname']; ?> , <?php echo $entry['vote_count']; ?> votes
								<a href="#" class="vote" data-songid="<?php echo $entry['songid']?>">vote!</a>
								<?php
								foreach ($entry['voters'] as $voter)
								{
									$gravatarMd5 = md5(strtolower($voter['email']));
									echo '<img class="voteavatar" src="http://www.gravatar.com/avatar/' . $gravatarMd5 . '?s=25&d=mm" alt="'. $voter['name'] . '" title="'. $voter['name'] . '">';
								}
								?>
							</p>
							<?php
						}
					}
					else
					{
						?>
						<p>
							This party is missing songs! Add some straight away!
						</p>
						<?php
					}
				?>
			</div>
		</div>
	</div>
</main>