require([
  '$api/models'
], function(models, Image) {
	'use strict';

	var insertSongInfo = function(trackURI) {
		models.Track.fromURI(trackURI).load('name', 'artists').done(function(track) {
			document.getElementById('songName').innerHTML = track.name.decodeForHtml();
			var artist = models.Artist.fromURI(track.artists).load('name').done(function(artist) {
				document.getElementById('songArtist').innerHTML = artist.name.decodeForHtml();
			});
		});
	};

	exports.insertSongInfo = insertSongInfo;
});