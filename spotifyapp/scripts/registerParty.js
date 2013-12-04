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
					'name':'erikscoolafest' //FIXME
				}

			models.session.load('country').done(function(){
				var locale = models.session.country;
						
				postData.uid = uid;
				postData.locale = locale;

				$.post('http://127.0.0.1/Hathor/api/create_party', postData , function(data, textStatus) {
					var jsonobj= eval ("(" + data + ")");
					console.log(jsonobj.result.partyid);
					localStorage.partyid = jsonobj.result.partyid;
					localStorage.partyname = jsonobj.result.partyname;
					localStorage.partyhash = jsonobj.result.hash;

					postPlayingSong.registerHathorCallback(data); 
					$('#party').html(getParty());
				});



			});
		}

		exports.RegisterParty = RegisterParty;

	});