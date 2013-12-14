require([
  '$api/models',
  'scripts/cover',
  'scripts/hathor',
  'scripts/trackInfo'
], function(models, cover, hathor,trackInfo) {
  'use strict';

  var numReloads = 0;

  //Register party to send playing song to Hathor
  hathor.registerHathorCallback(sessionStorage.partyid);
  
  //this will send a request to hathor to get current playqueue.
  hathor.registerHathorQueueCallback(sessionStorage.partyid, sessionStorage.queuehash);

  // Each track has ha vote. Here the id of the voter and
  // timestamp is stored in arrays.
  function vote()
  {
    this.amount = 1;
    this.timestamp = new Array();
    this.timestamp[0] = new Date().getTime();
    this.voter = new Array();
    this.voter[0] = "Contributer";
    this.addVoter = function(id)
    {
      var numVotes = this.voter.length;
      this.voter[numVotes] = id;
      this.timestamp[numVotes] = new Date().getTime();
      this.amount++;
    }
  }

  // Struct for the object track. Used for dealing
  // with info about tracks. URI, votes and timestamp.
  function track(URI)
  {
    this.URI = URI;
    this.songid;
    // Creates new object vote 
    this.votes = new vote();
    this.section = createSection(this.URI);
    this.active = true;
    this.image = '';
  }

  // Uppdtareas konstigt. Bilderna försvinner.
  // Undersök hurvida endast de påverkade objekten ska uppdateras eller ej
  // Just nu töms listan och skrivs ut igen, är det bästa sättet?
  var listUpdate = function() 
  {
    tracks.sort(compare);
    var size = tracks.length;
    document.getElementById('queue').innerHTML = "";
    var control = false, reset;
    for(var i = 0; i < size; i++)
    {
      if(tracks[i].active)
      {
        if(!control)
        {
          control = true;
          var element = tracks[i].section;
          reset = element;
          var pos = element.indexOf("track row") + 9;
          element = [element.slice(0, pos), " first", element.slice(pos)].join('');
          tracks[i].section = element;
        }
        document.getElementById('queue').innerHTML += tracks[i].section;
        if(numReloads==0)
        {
          cover.insertImage(tracks[i].URI);
          trackInfo.insertSongInfo(tracks[i].URI);
        }
      }
    }
    tracks[0].section = reset;
    numReloads+=1;
  }

  // Funktion som skapar en html-sektion för en track
  var createSection = function(index) 
  {
    var section = '<div id="' + index.substr(15,36) + '" class="track row">';  
    section += '<div class="cover"></div>';
    section += '<div class="row trackmeta">';
    section += '<div class="songName">';
    section += '</div>';
    section += '<div class="songArtist">';
    section += '</div>';
    section += '<div class="numberOfVotes"></div>';
    section += '<div class="delete glyphicon glyphicon-remove"></div>';
    section += '<div class="vote glyphicon glyphicon-chevron-up"></div>';
    section += '</div>';
    section += '</div>';
    return section;
  }

  // Funktion som används vid sortering av tracks beroende
  // på votes i första hand, sedan timestamp.
  function compare(a, b) {
    var votesA = a.votes.amount; var votesB = b.votes.amount; // Creates variables for easy and neat handling
    var timeA = a.votes.timestamp; var timeB = b.votes.timestamp;
    if(votesA < votesB)
    {
      return 1;
    }
    else if(votesA > votesB)
    {
      return -1;
    }
    else
    {
      if(timeA < timeB)
      {
        return -1;
      }
      else if(timeA > timeB)
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }
  }

  // var values = {};
  // values["spotifyuri"] = "spotify:track:4qw6yAygswKYFsO5GMybWu";
  // values["partyid"] = sessionStorage.partyId;
  // values["uid"] = sessionStorage.uid;
  // $.post(constants.SERVER_URL + '/api/party/add_song',values, function(data, textstatus)
  // { 
  //   var json = data;
  //   if(json.status == "success")
  //   {
  //     console.log("song added");
  //     var info = {};
  //     info["partyid"] = sessionStorage.partyId;

  //     $.post(constants.SERVER_URL + '/api/party/get_party_list',info, function(data, textstatus)
  //     { 
  //       var json = data;
  //         console.log("get party");
  //       if(json.status == "success")
  //       {
  //         for(var i = 0; i < json.size(); i++)
  //         {
  //           tracks[i] = new track(json.result.quesong[i]);
  //         }
  //       }
  //       else
  //       {
  //         $('#createError').html(json.status + ": " + json.response);
  //       }
  //     });
  //   }
  //   else
  //   {
  //     console.log("song wasn't added");
  //   }
  // });

  var tracks = new Array(); // Initializes an array of spotify URIs

  tracks[0] = new track('spotify:track:4qw6yAygswKYFsO5GMybWu');
  tracks[1] = new track('spotify:track:3vS2Jsk6g4Y8QMFsYZXr3z');
  tracks[2] = new track('spotify:track:3zBgPi9s8iroxNQ5rNYeQR');
  tracks[3] = new track('spotify:track:1r9mGafUiSgumJoRqyLrSt');
  tracks[4] = new track('spotify:track:3YXUMVKfRy4mwPEAslWg1p');
  tracks[5] = new track('spotify:track:2xaNOCsGBhFJ3bp6mvSqXz');

  // models.player.setShuffle(false);
  // models.player.setRepeat(false);
  // models.player.playContext(models.Track.fromURI('spotify:track:2xaNOCsGBhFJ3bp6mvSqXz'));
  // var country = models.session.load('country').done(function(country){
    // document.getElementById('subheading').innerHTML = country.country.decodeForHtml();
  // });

  listUpdate();

  // Funktion för att ta bort ett "Track"-elemnent
  // Att göra:
  // * Funktion som endast ska vara tillgänglig för
  //   feststartaren.
  var active;
  $(document).on('click', '.delete', function() {
    // Simple test to clear other active deletions
    var trackLength = document.getElementsByClassName('track');
    for(var i = 0; i < trackLength.length; i++)
    {
      if($(trackLength[i]).hasClass('blur'))
      {
        $(trackLength[i]).removeClass('blur');
      }
    }
    if($('.deleteActive').length != 0)
    {
      $('.deleteActive').remove();
    }
    // end of test
    var pos = $('.delete').index(this); // Wich position the clicked track is in
    active = document.getElementsByClassName('track')[pos+1]; // Creates objects for easy and neat handling
  
    var top = document.getElementsByClassName("trackmeta")[pos].offsetTop; // Gets the correct position for the buttons
    var left = document.getElementsByClassName("trackmeta")[pos].offsetLeft;

    var qtop = document.getElementById("queue").offsetTop;
    var qleft = document.getElementById('queue').offsetLeft;

    var checkDiv = "<div class='deleteCheck deleteActive'>delete</div>"; // the html for the buttons
    var cancelDiv = "<div class='deleteCancel deleteActive'>cancel</div>";
    
    var $check = $(checkDiv).prependTo('#queue');
    var $cancel = $(cancelDiv).prependTo('#queue');

    var delWidth = $check.css("width").replace('px',''); // width of the buttons

    var w = window.innerWidth - left-delWidth-qleft; // Correct values for the css
    var w2 = w-delWidth-15;
    top -= qtop;

    $check.css({'margin-top': top, // Sets the correct margins for clicked track
                  'margin-left': w
    });
    $cancel.css({'margin-top': top,
                   'margin-left': w2
    });

    if(pos == 0) // The first track row is a bit special
      active.setAttribute('class', 'track row blur');    
    else
      active.setAttribute('class', 'track row first blur');

    $(document).click(function(event) { // Activates when a click is done.
      if(event.target.className == "deleteCheck deleteActive") // Checks if the click was made on the deleteCheck button
      {
        $(active).hide({duration: 200, queue: false});
        active.remove();
        $check.remove();
        $cancel.remove();
      }
      else // If the click wasn't on the track or on the cancelbutton. Turn back to normal
      {
        $check.remove();
        $cancel.remove();
        $(active).removeClass('blur');
      } 
    });

    console.log(pos+1);
    
  });


  // Funktion för att lägga en röst på en låt
  // Att göra:
  // * Skicka rösten till databasen
  // * Uppdatera listan vid röstning
  // * Man ska endast kunna rösta en gång,
  //   nu kan man rösta oändligt många gånger
  // * Spara ID på personen som lade en röst
  $(document).on('click', '.vote', function() {
    var voteIndex = $('.vote').index(this);
    tracks[voteIndex].votes.addVoter("Daniel");
    console.log("#" + voteIndex + " has " + tracks[voteIndex].votes.amount +
     " number of votes. Voted by " + tracks[voteIndex].votes.voter[tracks[voteIndex].votes.voter.length-1]
     + ". Voted on time: " + tracks[voteIndex].votes.timestamp[tracks[voteIndex].votes.voter.length-1]);
    listUpdate();
  });

});
