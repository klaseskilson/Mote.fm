require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){

		var registerHathorQueueCallback = function(partyid, partyhash)
		{
			var postData = {
				'partyid' : partyid,
				'partyhash' : partyhash
			}

			$.post(constants.SERVER_URL + "/api/party/get_party_list", postData, function (data) {
				if(data.status == 'error')
				{
					//something went wrong
					console.log(data);	
				}
				else
				{
					models.player.playTrack(models.Track.fromURI(data.result[0].uri));
					//store queuehash on localstorage
					sessionStorage.queuehash = data.hash;
			
				}
				
			});
		}

		/**
		 * Register a callback to report playing song to Hathor server
		 * @param  {[type]} partyID The partyID that the callback should relates to
		 */
		
		var registerHathorCallback = function(partyID) {
			models.player.addEventListener('change:index', function(stuff){
				models.player.load('track').done(function(){

					//Spotify is a retard, we have to wait one second to get correct song
					setTimeout(function() { 

						var musicTrack = {
							'partyid' : 0,
							'trackuri' : ''
						}

						var track = models.player.track;

						musicTrack.partyid = partyID;
						musicTrack.trackuri = track.uri;
						$.post(constants.SERVER_URL + '/api/party/spotify_song', musicTrack , function (data, textStatus) {
							console.log(data);
						});
					}, 1000);
				});
			});
		};
		exports.registerHathorQueueCallback = registerHathorQueueCallback;
		exports.registerHathorCallback = registerHathorCallback;
	});
