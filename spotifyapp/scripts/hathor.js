require([
	'$api/models',
	'$views/image#Image',
	'scripts/cover',
	'scripts/trackInfo',
	'scripts/jquery.min'
	], function(models, Image, cover, trackInfo, jquery){
		'use strict';

		var time = 1;
		var firstLoad = true;
		
		/**
		 * Called when party site is loaded, starts recursive ajaj and register switching of songs
		 * @param  partyhash the hash of the party
		 */		
		var startParty = function(partyhash)
		{
			check_song_count(partyhash);
			load_party(partyhash);
			registerPlayback(partyhash);
		}

		var check_song_count = function(partyhash)
		{
			var postData = {
				'partyhash' : partyhash
			}

			$.ajax({
				type: "POST",
				url: constants.SERVER_URL + "/api/party/get_song_count",
				data: postData, 
				dataType: 'json'
			})
			.fail(function (data){
				console.log(data);
			})
			.done(function (data){
				if(data.status === 'success')
				{
					if(data.result === '0')
					{
						fill_empty($('#queue'));
					}
				}
				else
				{
					console.log(data);

				}
			});
		}

		/**
		 * Fills play queue with information that there are no songs at the party
		 * @param  the object to fill
		 */
		function fill_empty(theobject)
		{
			theobject.empty();

			theobject.slideUp('fast');

			theobject.append('<p>This party seems to be empty! Add some songs straight away, and start dancing!</p>');

			theobject.slideDown('fast');
		}

		/**
		 * Fills play queue with information about queue
		 * @param  the object to fill
		 */
		var redraw = function(queue, theobject)
		{
			if(Array.isArray(queue))
			{
				theobject.empty();
				theobject.slideUp('fast');

				for(var i = 0; i < queue.length; i++)
				{
					var song = queue[i];
					var img = cover.insertImage(song.uri);
					var $section = $('<div></div>').addClass('row songrow').attr('id', song.uri.substr(14,36));
					$('<div></div>').addClass('col-xs-4 col-sm-3 cover').appendTo($section);
					
					var $middlecolon = $('<div></div>').addClass('col-xs-8 col-sm-5').appendTo($section);
					$('<div></div>').addClass('hidden-xs').appendTo($middlecolon)
					.append('<h3>'+song.songname+'</h3>')
					.append('<h4>'+song.artistname+'</h4>')

					var $xscontent = $('<div></div>').addClass('visible-xs').appendTo($middlecolon)
					.append('<h4>'+song.songname+'</h4>')
					.append('<h5>'+song.artistname+'</h5>')
					
					$('<h5></h5>').appendTo($xscontent)
					.append(' <span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span>');
					var $rightcolon = $('<div></div>').addClass('col-sm-4 hidden-xs').appendTo($section);
					$rightcolon.append('<h3></h3>').children('h3')

					.append('<span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span> ');
					var thevoters = song.voters;
					var $images = $('<div></div>').appendTo($rightcolon);

					for(var k = 0; k < thevoters.length; k++)
					{
						$('<img class="img-circle">').appendTo($images)
							.attr('src', 'http://www.gravatar.com/avatar/' + song.voters[k].mailhash + '?s=25&d=mm')
							.attr('alt', song.voters[k].name)
							.attr('title', song.voters[k].name)
							.attr('data-toggle', 'tooltip');
					}

					theobject.append($section);
					cover.insertImage(song.uri);
				}

				theobject.slideDown('slow');
			}
		}

		/**
		 * ajaj loop to get partqueue
		 * @param  partyhash the hash of the party
		 */
		var load_party = function(partyhash){

			var postData = {
				'time' : time,
				'partyhash' : partyhash
			}
			$.ajax({
				type: "POST",
				url: constants.SERVER_URL + "/api/party/load_party",
				data: postData, 
				dataType: 'json'
			})
			.fail(function(data) {
				console.log(data.responseText);
			})
			.done(function(answer){
				time = Math.floor(new Date().getTime() / 1000);

				if(answer.status === 'success')
				{
					var i = 0;
					while(answer.response.result[i] && answer.response.result[i].played !== '1')
					{
						i += 1;
					}
					redraw(answer.response.result.slice(0,i), $('#queue'));
					redraw(answer.response.result.slice(i), $('#pastqueue')); 
					if(firstLoad)
					{
						playNextSong(partyhash);
						firstLoad = false;
					}
					else
					{
						highlightFirst();
					}
					console.log('Success!');
					console.log(answer);
					load_party(partyhash);

				}
				else
				{
					console.log('failed');
					console.log(answer);
					load_party(partyhash);
				}
			});
		}
		var highlightFirst = function()
		{
            if($('#queue').children('div').length != 0)
			{
				var $child = $('#queue').children('div').eq(0);
            	var track = $child.attr('id');
            	$child.addClass('first');
			}
		}
		/**
		 * Will play next song in the queue list
		 */
		var playNextSong = function(partyhash)
		{
            if($('#queue').children('div').length != 0)
            {
					highlightFirst();    
					var $child = $('#queue').children('div').eq(0);
            		var track = $child.attr('id');                        
                    track = 'spotify:track:' + track;
                    var spTrack = models.Track.fromURI(track);
                    models.player.playTrack(spTrack);
            }
            else
            {
                    console.log("no tracks!!");
                    $('#pastqueue').empty();
                    resetPlaylist(partyhash);
            }
		}

		/**
		 * Remove played song from queue
		 */
		var removeSong = function()
		{
			$('#queue').children('div').eq(0).removeClass('first');
			$('#pastqueue').prepend($('#queue').children('div').eq(0));
		}

		/**
		 * checks if spotify start or stops playing. If track is null, then a song
		 * has finished playing, if track is not null, user paused the track.
		 * @param  partyhash the hash of the party
		 */
		var registerPlayback = function(partyhash)
		{
			models.player.addEventListener('change:playing', function(){
				console.log("Callback!");
				models.player.load('track').done(function(){
					if(models.player.track)
					{
						console.log('end of song or user paused');
						setAsPlayed(partyhash, models.player.track.uri);
					}
					setTimeout(function(){
						if(!models.player.track)
						{
							console.log('end of song');
							removeSong();
							playNextSong(partyhash);
						}
						else
						{
							console.log('start of song');
							registerSong(partyhash);
						}
					},1);
				});	
			});
		}

		/**
		 * Resets the playlist server-side, the queue will start from the beginning again
		 * @param  partyhash the hash of the party
		 */
		var resetPlaylist = function(partyhash){
			var post = {
				'partyhash' : partyhash
			}
			$.post(constants.SERVER_URL + '/api/party/reset_playlist', post, function (data){
				console.log("restarting playlist");
				if(data.status == "error")
				{
					console.log(data);
					console.log("Playlist empty!!");
				}
				else
				{
					time = 1;
					load_party(partyhash,false);
				}
			});
		}

		/**
		 *  report playing song to mote.fm server
		 * @param  partyhash the hash of the party
		 */
		var registerSong = function(partyhash) {
			models.player.load('track').done(function(){

				//Spotify is a retard, we have to wait one second to get correct song
				setTimeout(function() { 
					var musicTrack = {
						'partyhash' : partyhash,
						'trackuri' : ''
					}

					var track = models.player.track;
					if(track)
					{
						musicTrack.trackuri = track.uri;
						console.log('Registering');
						$.post(constants.SERVER_URL + '/api/party/spotify_song', musicTrack , function (data) {
							console.log(data);
						});
					}
				}, 1000);
			});
		}

		var setAsPlayed = function(partyhash, trackURI)
		{
			models.player.load('track').done(function(){
				var musicTrack = {
					'partyhash' : partyhash,
					'trackuri' : trackURI
						}
				
				if(models.player.position/models.player.track.duration < 0.5) //<--- high number beacause the api i slower than the UI
				{
					console.log("user pause");
				}
				else
				{
					$.post(constants.SERVER_URL + '/api/party/set_song_as_played', musicTrack , function (data) {
						console.log(data);
					});
				}
			});
		}
		exports.startParty = startParty;
	});
