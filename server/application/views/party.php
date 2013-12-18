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
				<h4>
					Hosted by <?php echo $party['hostname']; ?>
				</h4>
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
				<div id="newpartyque" data-partyhash="<?php echo $party['hash']; ?>">
					<div class="well loader">Loading party...</div>
				</div>
			</div>
		</div>
	</div>
</main>
