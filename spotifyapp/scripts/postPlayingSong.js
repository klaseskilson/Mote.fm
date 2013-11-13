require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){

		var musicTrack = {
			"partyID" : 0,
			"trackURI" : ""
		}

		jQuery(document).ready(function() {
			models.player.addEventListener('change:index', function(stuff){
				console.log(stuff);
				models.player.load('track').done(function(){
					var track = models.player.track;
					musicTrack.partyID = 123456;
					musicTrack.trackURI = track.uri;
					console.log(musicTrack);
				});

				$.post("http://127.0.0.1/hathor/index.php/spotifyPost/",musicTrack,function(resp){
					console.log(resp);
				});
			});
		});
	});