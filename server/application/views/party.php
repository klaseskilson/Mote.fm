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
			<div class="col-sm-8 col-sm-pull-4" id="partyqueue">
				<h4>Play queue</h4>
				<?php
					if($party_queue)
					{
						foreach ($party_queue as $entry)
						{
							// var_dump($entry);
							?>
							<div class="row">
								<div class="col-xs-4 col-sm-3">
									<img src="<?php echo $entry['albumart']; ?>" alt="" class="img-responsive" />
								</div>
								<div class="col-xs-8 col-sm-5">
									<div class="hidden-xs">
										<h3>
											<?php echo $entry['trackname']; ?>
										</h3>
										<h4>
											<?php echo $entry['artistname']; ?>
										</h4>
									</div>
									<div class="visible-xs">
										<h4>
											<?php echo $entry['trackname']; ?>
										</h4>
										<h5>
											<?php echo $entry['artistname']; ?>
										</h5>
										<h5>
											<a href="#" class="vote btn btn-success" data-toggle="tooltip" title="Add your vote to this song!"
												data-songid="<?php echo $entry['songid']?>">
												Vote!
											</a>
											<span>
												<strong>
													<?php echo $entry['vote_count']; ?>
												</strong>
												<?php echo ($entry['vote_count'] == 1 ? ' vote': ' votes'); ?>
											</span>
										</h5>
									</div>
								</div>
								<div class="col-sm-4 hidden-xs">
									<h3>
										<span>
											<strong>
												<?php echo $entry['vote_count']; ?>
											</strong>
											<?php echo ($entry['vote_count'] == 1 ? ' vote': ' votes'); ?>
										</span>
										<a href="#" class="vote label label-success" data-toggle="tooltip" title="Add your vote to this song!"
											data-songid="<?php echo $entry['songid']?>">
											+1
										</a>
									</h3>
									<?php
									$counter = 0;
									foreach ($entry['voters'] as $voter)
									{
										$gravatarMd5 = md5(strtolower($voter['email']));
										echo '<img class="img-circle" src="http://www.gravatar.com/avatar/' . $gravatarMd5 . '?s=25&d=mm"
											alt="'. $voter['name'] . '" title="'. $voter['name'] . '" data-toggle="tooltip">';
										$counter++;
										if($counter > 5)
											break;
									}
									?>
								</div>
							</div>
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
