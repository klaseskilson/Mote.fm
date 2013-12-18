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
                        $('#' + trackURI.substr(14,36)).children('div').eq(0).html(image.node);
                        image.setSize(150,150);
            });	
		});
	};

	exports.insertImage = insertImage;
});