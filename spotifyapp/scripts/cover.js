require([
  '$api/models',
  '$views/image#Image'
], function(models, Image) {
	'use strict';

	var insertImage = function(trackURI, index) {
		models.Track.fromURI(trackURI).load('album').done(function(track) {
			var album = models.Album.fromURI(track.album);
			var image = Image.forAlbum(album, {player:false});
			var id = 'coverContainer' + index;
			document.getElementById(id).appendChild(image.node);
			image.setSize(150, 150);
		});
	}

	exports.insertImage = insertImage;
});