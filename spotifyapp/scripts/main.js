require([
  '$api/models',
  'scripts/button',
  'scripts/playlist',
  'scripts/cover',
  'scripts/track'
], function(models, button, playlist, cover, track) {
  'use strict';

  var trackURI = 'spotify:track:0Rynk2V7LyLgBUjTMxvbEJ';

  track.insertSongInfo(trackURI);
  cover.insertImage(trackURI);
});
