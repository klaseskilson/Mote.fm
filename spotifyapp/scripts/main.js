require([
  '$api/models',
  'scripts/button'
], function(models, button) {
  'use strict';

  button.doPlayButtonForTrack();
  models.player.addEventListener('change', function()
  {
  	console.log(models.player);
  	console.log(models.player.track);
  	var track = document.getElementById('track');
  	if(models.player.track != null)
  	{
  		track.innerHTML = models.player.track.name;
  	}
  });
});