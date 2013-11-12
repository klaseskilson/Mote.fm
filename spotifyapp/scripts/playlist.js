require([
  '$api/models',
  '$views/list#List'
], function(models, List) {
  'use strict';

  var setPlaylist = function() {
    var playlist = models.Playlist.fromURI('spotify:user:papo196:playlist:1FDeaQc4aWSIjUNJaySzaX');
    var list = List.forPlaylist(playlist);
    document.getElementById('playlistContainer').appendChild(list.node);
    list.init();
  };

  exports.setPlaylist = setPlaylist;
});
