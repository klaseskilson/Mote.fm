require([
  '$api/models',
  '$views/image#Image'
], function(models, Image) {
	'use strict';

	var insertImage = function(trackURI) {
		var album, link;
		link = models.Track.fromURI(trackURI).load('album').done(function(track) {
			models.Track.fromURI(trackURI).load('album').done(function(track) {
                        var album = models.Album.fromURI(track.album);
                        var image = Image.forAlbum(album, {player:false});
                        var id = 'cover';
                        document.getElementById(trackURI.substr(14,36)).getElementsByClassName(id)[0].appendChild(image.node);
                        image.setSize(150,150);
            });	
		});
	};

	exports.insertImage = insertImage;
});