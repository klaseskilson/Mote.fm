<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>
					Parties!
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 col-sm-offset-4">
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
				?>
			</div>
		</div>
	</div>
</main>
