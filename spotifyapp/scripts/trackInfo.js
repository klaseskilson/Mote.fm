require([
  '$api/models'
], function(models, Image) {
	'use strict';

	var insertSongInfo = function(trackURI) {
                models.Track.fromURI(trackURI).load('name', 'artists').done(function(track) {
                        var id = 'songName';
                        document.getElementById(trackURI.substr(14,36)).getElementsByClassName(id)[0].innerHTML = track.name.decodeForHtml();
                        var artist = models.Artist.fromURI(track.artists).load('name').done(function(artist) {
                                id = 'songArtist';
                                document.getElementById(trackURI.substr(14,36)).getElementsByClassName(id)[0].innerHTML = artist.name.decodeForHtml();
                        });
                });
        };

        exports.insertSongInfo = insertSongInfo;
});