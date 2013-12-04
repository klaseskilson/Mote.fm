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

				$.post('http://127.0.0.1/Hathor/api/party/create_party', postData , function (data, textStatus) {
					var jsonobj= data;
					console.log(data);
					localStorage.partyid = jsonobj.result.partyid;
					localStorage.partyname = jsonobj.result.partyname;
					localStorage.partyhash = jsonobj.result.hash;

					registerHathorCallback(localStorage.partyid); 
				});
			});
		}

		/**
		 * Register a callback to report playing song to Hathor server
		 * @param  {[type]} partyID The partyID that the callback should relates to
		 */
		
		var registerHathorCallback = function(partyID) {
			console.log("Callback!");
			models.player.addEventListener('change:index', function(stuff){
				models.player.load('track').done(function(){

					//Spotify is a retard, we have to wait one second to get
					//correct song
					setTimeout(function() { 

						var musicTrack = {
							'partyid' : 0,
							'trackuri' : ''
						}

						var track = models.player.track;

						musicTrack.partyid = partyID;
						musicTrack.trackuri = track.uri;
						console.log("Sending");
						$.post('http://127.0.0.1/Hathor/api/party/spotify_song', musicTrack , function (data, textStatus) {
							console.log(data);
						});
					}, 1000);
				});
			});
		};

		exports.registerHathorCallback = registerHathorCallback;
		exports.RegisterParty = RegisterParty;
	});
