require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){
		/**
		 * Register a party on Hathor serverside
		 * @param {int} uid User unique Id, can be found in sessionStorage.uid after login
		 * @param {string} partyname Name of the party
		 */
		var RegisterParty = function(uid, partyname){
			var postData = {
					'uid' : 0,
					'locale' : '',
					'name': partyname
				}

			models.session.load('country').done(function(){
				var locale = models.session.country;
						
				postData.uid = uid;
				postData.locale = locale;
				$.post(constants.SERVER_URL + 'api/party/create_party', postData , function (data, textStatus) {
					var jsonobj= data;
					console.log(data);
					localStorage.partyid = jsonobj.result.partyid;
					localStorage.partyname = jsonobj.result.name;
					localStorage.partyhash = jsonobj.result.hash;

					registerHathorCallback(localStorage.partyid); 
				});
			});
		}


		var registerHathorQueueCallback = function(partyid, partyhash)
		{
			var postData = {
				'partyid' : partyid,
				'partyhash' : partyhash
			}

			$.post(constants.SERVER_URL + "api/party/get_party_list", postData, function (data) {
				if(data.status == 'error')
				{
					//something went wrong
					console.log(data);	
				}
				else
				{

					console.log(data);	
					//store queuehash on localstorage
					localStorage.queuehash = data.hash;
		
					//create a new temporary playlist to add queue to
					models.Playlist.createTemporary("hathor").done(function(pl){
							localStorage.playlist = pl;
							pl.load("tracks").done(function(pl){
								for(var i = 0; i < data.result.length; i++)
								{
									//pl.tracks.remove(models.Track.fromURI(data.result[i].uri));
								}

								//start playing!
								models.player.playContext(pl);	
							});
						});
				}
				
			});
		}
		// 		models.player.addEventListener('change:index', function(stuff){
		// 		models.player.load('track').done(function(){
		// 			var postData = {
		// 				'partyid' : partyid
		// 			}
		// 			$.Post(constants.SERVER_URL + "api/party/get_party_list", postData, function (data) {
		// 				console.log(data);
		// 			});
		// 		});
		// 	});
		// }


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
						$.post(constants.SERVER_URL + 'api/party/spotify_song', musicTrack , function (data, textStatus) {
							console.log(data);
						});
					}, 1000);
				});
			});
		};
		exports.registerHathorQueueCallback = registerHathorQueueCallback;
		exports.registerHathorCallback = registerHathorCallback;
		exports.RegisterParty = RegisterParty;
	});
