require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){

			
		var musicTrack = {
			"partyID" : 0,
			"trackURI" : ""
		}

		jQuery(document).ready(function() {
			console.log("HEJ!");

			models.player.addEventListener('change:index', function(stuff){
				models.player.load('track').done(function(){
					console.log(stuff.target);
					console.log(stuff.target.track.uri);
					var track = models.player.track;
					console.log(track.uri)
					musicTrack.partyID = 123456;
					musicTrack.trackURI = track.uri;
				});

				$.post("http://127.0.0.1/hathor/index.php/spotifyPost/",musicTrack,function(resp){
					console.log(resp);
				});
			});
		});
	});