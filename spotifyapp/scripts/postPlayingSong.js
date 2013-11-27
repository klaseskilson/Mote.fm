require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){
		
		/**
		 * RegisterHathorCallback() creates an listener that reports current 
		 * played track to Hathor
		 *
		 */
		var registerHathorCallback = function(partyID) {
			models.player.addEventListener('change:index', function(stuff){
				models.player.load('track').done(function(){

					//Spotify is a retard, we have to wait one second to get
					//correct song
					setTimeout(function() { 

						var musicTrack = {
							'partyID' : 0,
							'trackURI' : ''
						}

						var track = models.player.track;

						musicTrack.partyID = partyID;
						musicTrack.trackURI = track.uri;
						console.log(musicTrack);
						//FIXME: Hardcoded adress
						$.post("http://127.0.0.1/hathor/index.php/spotifyPost/",musicTrack);

					}, 1000);
				});
			});
		};

		exports.registerHathorCallback = registerHathorCallback;

	});