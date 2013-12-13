<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>
					<?php echo $party['name']; ?>
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
			<div class="col-sm-8 col-sm-pull-4">
				<h4>Play queue</h4>
				<?php
					if($party_queue)
					{
						foreach ($party_queue as $entry)
						{
							?>
							<p>
								<?php echo $entry['uri']; ?>, <?php echo $entry['vote_count']; ?> votes
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
