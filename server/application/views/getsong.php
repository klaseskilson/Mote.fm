<main id="main">
	<div id="second" class="pane">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 screamer">
					<h2>Your party is now playing:</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 screamer">
					<p>
						<h3><div id="songdata"><?php echo isset($track) ? $track : 'Nopes!'?></div></h3>
						<h3><div id="trackName"><?php echo isset($trackname) ? $trackname : 'Nopes!'?></div></h3>
						<h3><div id="artistName"><?php echo isset($artistname) ? $artistname : 'Nopes!'?></div></h3>
						<div id="songInfo" style="height:200px"><?php echo isset($trackdata) ? '<img src="'.$trackdata.'">' : 'Nopes!' ?></h3></div>
					</p>
				</div>
			</div> <!-- end .row -->
			<div class="bottom">
				<p class="pull-right">
					<span class="miniinfo"><a href="http://www.flickr.com/photos/31355686@N00/4701196608" target="_blank">Photo</a></span>
				</p>
			</div>
		</div><!-- end .container -->
	</div><!-- end .pane#first -->
</main><!-- end #main -->
