require([
  '$api/models'
], function(models, Image) {
	'use strict';

	var insertSongInfo = function(trackURI, index) {
		models.Track.fromURI(trackURI).load('name', 'artists').done(function(track) {
			var id = 'songName' + index;
			document.getElementById(id).innerHTML = track.name.decodeForHtml();
			var artist = models.Artist.fromURI(track.artists).load('name').done(function(artist) {
				var id = 'songArtist' + index;
				document.getElementById(id).innerHTML = artist.name.decodeForHtml();
			});
		});
	};

	exports.insertSongInfo = insertSongInfo;
});