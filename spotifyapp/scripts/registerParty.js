require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){

		var RegisterParty = function(uid){
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
					console.log(textStatus);
					console.log(data);
				});



			});
		}

		exports.RegisterParty = RegisterParty;

	});