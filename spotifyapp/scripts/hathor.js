require([
	'$api/models',
	'$views/image#Image',
	'scripts/cover',
	'scripts/trackInfo',
	'scripts/jquery.min'
	], function(models, Image, cover, trackInfo, jquery){

		var startParty = function(partyid, partyqueuehash)
		{
			getPlaylist(partyid,partyqueuehash);
		}
		var getPlaylist = function(partyid, partyqueuehash){
			var postData = {
				'partyid' : partyid,
				'partyqueuehash' : partyqueuehash
			}

			$.post(constants.SERVER_URL + "/api/party/get_party_list", postData, function (data) {
				console.log(data.hashdata);
				console.log(data.hash);
				if(data.status == 'error')
				{
					//something went wrong
					console.log(data);	
					playNextSong(partyid, sessionStorage.queuehash, false);	

				}
				else
				{
					if(data.result.length == 0)
					{
						//divs doesnt work here?!
						$('#queue').html("<div class='track row'>No songs at party, what a boring party!</div>");
						return;
					}
					$('#queue').html("");
					for(var i = 0; i < data.result.length; i++)
					{
						var track = data.result[i];

						var section = '<div id="' + track.uri.substr(14,36) + '" class="track row">';  
    					section += '<div class="cover"></div>';
				    	section += '<div class="row trackmeta">';
				    	section += '<div class="songName">';
				    	section += '</div>';
				    	section += '<div class="songArtist">';
				    	section += '</div>';
				    	section += '<div class="numberOfVotes">'+ track.vote_count + '</div>';
				    	section += '<div class="voters">';
				    	for(var j = 0; j < track.voter.length; j++)
				    	{
				    		var voter = track.voter[j];
				    		var gravatarurl = 'http://www.gravatar.com/avatar/' + voter.email + '?s=25&d=mm"';
				    		section+= '<img src="'+ gravatarurl + '"alt="' + voter.name + '" title="'+ voter.name + '">';
				    	}

				    	section += '</div>';
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
					playNextSong(partyid, sessionStorage.queuehash, true);	
				}
				
			});
		}
		/**
		 * Will play next song in the queue list
		 */
		var playNextSong = function(partyid, partyhash, playlistchanged)
		{
			if(!playlistchanged)
			{
				$('#queue').children('div')[0].remove();							
			}
			if($('#queue').children('div').length != 0)
			{
				var child = $('#queue').children('div')[0];
				var track = child.id;
				$('#queue').children('div').eq(0).css('background-color', '#CEC0B3');
					
				track = 'spotify:track:' + track;
				var spTrack = models.Track.fromURI(track);
				models.player.playTrack(spTrack);
				registerSong(partyid);
				
				spTrack.load('duration').done(function(spTrack){
					setTimeout(function(){
						getPlaylist(partyid, partyhash);												
					},  spTrack.duration);
				});
			}
			else
			{
				console.log("no tracks!!");
				//
			}
		}
		/**
		 * Register a callback to report playing song to Hathor server
		 * @param  {[type]} partyID The partyID that the callback should relates to
		 */
		var registerSong = function(partyID) {
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
		}
		exports.startParty = startParty;
	});
