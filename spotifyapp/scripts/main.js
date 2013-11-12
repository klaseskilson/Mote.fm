require([
  '$api/models',
  'scripts/cover',
  'scripts/track',
], function(models, cover, track) {
  'use strict';
  var tracks = new Array();
  tracks[0] = 'spotify:track:0Rynk2V7LyLgBUjTMxvbEJ';
  tracks[1] = 'spotify:track:4qw6yAygswKYFsO5GMybWu';
  tracks[2] = 'spotify:track:3vS2Jsk6g4Y8QMFsYZXr3z';
  var size  = tracks.length;

  for(var i = size-1; i >= 0; i--)
  {
    var section = '<div class="track">';
    section += '<div id="coverContainer';
    section += i.toString();
    section += '"></div>';
    section += '<div id="songName';
    section += i.toString(); 
    section += '"></div>';
    section += '<div id="songArtist';
    section += i.toString();
    section += '"></div>';
    section += '</div>';
    document.getElementById('queue').innerHTML += section;
    track.insertSongInfo(tracks[i], i);
    cover.insertImage(tracks[i], i);
  }

});