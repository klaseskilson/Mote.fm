require([
  '$api/models',
  '$views/buttons'
], function(models, buttons) {
  'use strict';

  var doPlayButtonForTrack = function() {
    var track = models.Track.fromURI('spotify:track:0wSeOObVidQ2V2f9uHX1jK');
    var button = buttons.PlayButton.forItem(track); 
    document.getElementById('buttonContainer').appendChild(button.node);
  };

  exports.doPlayButtonForTrack = doPlayButtonForTrack;
});
