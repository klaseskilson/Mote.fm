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
			<div class="col-sm-4">
				<form role="form">
					<div class="form-group spotifysearch">
						<input type="hidden" class="partyid" value="<?php echo $party['partyid']; ?>" />
						<input type="text" class="form-control" class="searchinput" placeholder="Begin typing to search..." autocomplete="off" autofocus>
						<div class="searchresults"></div>
					</div>
					<!-- <button type="submit" class="btn btn-default">Submit</button> -->
				</form>
			</div>
			<div class="col-sm-4">
				<p>hej</p>
			</div>
		</div>
	</div>
</main>
