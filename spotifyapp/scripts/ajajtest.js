require([
	'$api/models',
	'scripts/jquery.min'
	], function(models, jquery){
		'use strict';

		var musicTrack = {
			"partyID" : 
			"name":"",
			"artist":"",
			"duration":0,
			"image":"",

		}
		jQuery(document).ready(function() {
			models.player.addEventListener('change', function(){
				models.player.load('track').done(function(){
					var track = models.player.track;
					musicTrack.name = track.name;
					musicTrack.artist = track.artists[0].name;
					musicTrack.duration = track.duration;
					console.log(musicTrack);
				});

				$.post("http://127.0.0.1/hathorbb/cool.php",musicTrack,function(resp){
					console.log(resp);
				});
			});
		});
	});