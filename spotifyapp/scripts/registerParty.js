require([
	'$api/models',
	'scripts/jquery.min',
	'scripts/postPlayingSong'
	], function(models, jquery, postPlayingSong){

		var RegisterParty = function(uid){
			if(localStorage.user)

			var postData = {
					'uid' : 0,
					'locale' : '',
					'partyname':'erikscoolafest'
				}

			models.session.load('country').done(function(){
				var locale = models.session.country;
						
				postData.uid = uid;
				postData.locale = locale;

				$.post('http://127.0.0.1/hathor/index.php/registerParty/', postData , function(data, textStatus) {
					localStorage.partyId = data;	
					postPlayingSong.registerHathorCallback(data); 
					console.log("regist:" + data);
					$('#party').html(getParty());
				});



			});
		}

		exports.RegisterParty = RegisterParty;

	});