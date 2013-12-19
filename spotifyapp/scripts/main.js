require([
  '$api/models',
  'scripts/cover',
  'scripts/hathor',
  'scripts/trackInfo'
], function(models, cover, hathor,trackInfo) {
  'use strict';

  var numReloads = 0;

  //just to clear users playqueue
  models.player.stop();
  
  //this will send a request to hathor to get current playqueue.
  hathor.startParty(sessionStorage.partyhash);

  var partyadress = constants.SERVER_URL + "/" + sessionStorage.partyhash

  var facebooklink = "http://www.facebook.com/sharer/sharer.php?s=100&p[url]=";
  facebooklink += partyadress;
  facebooklink += "&p[images][0]=https://pbs.twimg.com/profile_images/378800000858096968/BsKG4Iy_.png&p[title]=Join%20my%20party%20on%20Mote.fm&p[summary]=I%20have%20a%20party%20on%20Mote.fm,%20together%20we%20will%20create%20an%20awesome%20party.";
  $('#facebookButton').attr('href', facebooklink);

  var tweetlink = "https://twitter.com/intent/tweet?url=";
  tweetlink += partyadress;
  tweetlink += "&text=Join my party at &via=motefm";
  $('#twitterButton').attr('href', tweetlink);

  console.log(document.width);
  $('#linkButton').click(function() {
    $('.link').show(200);
    $(document).click(function(event) {
        // console.log(event);
        if(event.target.className == "link" || event.target.id == "linkButton" ||
           event.target.id == "linkBox")
        {
            $('.link').show();
        }
        else
        {
            $('.link').hide(200);
        } 
    });
  });
  $("input[type='url']").attr('value',partyadress);
  $("input[type='url']").on("click", function () {
   $(this).select();
  });

  $("#partyname").html(sessionStorage.partyname);

});
