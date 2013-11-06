require([
  '$api/models',
  '$views/image#Image'
], function(models, Image) {
	'use strict';

	var insertImage = function(trackURI) {
		models.Track.fromURI(trackURI).load('album').done(function(track) {
			var album = models.Album.fromURI(track.album);
			var image = Image.forAlbum(album, {player:false});
			document.getElementById('coverContainer').appendChild(image.node);
			image.setSize(150, 150);
		});
	}

	exports.insertImage = insertImage;
});