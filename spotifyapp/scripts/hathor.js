require([
	'$api/models',
	'$views/image#Image',
	'scripts/cover',
	'scripts/trackInfo',
	'scripts/jquery.min'
	], function(models, Image, cover, trackInfo, jquery){

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
					//models.player.playTrack(models.Track.fromURI(data.result[0].uri));
					console.log(data);
					$('#queue').html("");
					for(var i = 0; i < data.result.length; i++)
					{
						var track = data.result[i];
						if(i == 0)
							var section = '<div id="' + track.uri.substr(14,36) + '" class="track row first">';
						else
							var section = '<div id="' + track.uri.substr(14,36) + '" class="track row">';  
    					section += '<div class="cover"></div>';
				    	section += '<div class="row trackmeta">';
				    	section += '<div class="songName">';
				    	section += '</div>';
				    	section += '<div class="songArtist">';
				    	section += '</div>';
				    	section += '<div class="numberOfVotes"></div>';
					    section += '<div class="delete glyphicon glyphicon-remove"></div>';
					    section += '<div class="vote glyphicon glyphicon-chevron-up"></div>';
					    section += '</div>';
					    section += '</div>';
					    $('#queue').append(section);

					    cover.insertImage(track.uri);
					    trackInfo.insertSongInfo(track.uri);
					}
					//store queuehash on sesionsstorage
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
