require([
  '$api/models',
  'scripts/cover',
  'scripts/trackInfo'
], function(models, cover, trackInfo) {
  'use strict';

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
    if(URI.length == 36 &&
       URI.substr(0,14) == 'spotify:track:') // If the whole link is pasted
      this.URI = URI;
    else if(URI.length == 22) //if only the id is pasted
      this.URI = "spotify:track:" + URI;
    else
      this.URI = "spotify:track:6JEK0CvvjDjjMUBFoXShNZ"; // RICK ROLL
    // Creates new object vote 
    this.votes = new vote();
    this.section = createSection(this.URI.substr(15,36));
  }

  // Funktion som skapar en html-sektion för en track
  var createSection = function(index) {
    var section = '<div id="' + index + '" class="track">';
    section += '<div class="cover"></div>';
    section += '<div class="delete"></div>';
    section += '<div class="vote"></div>';
    section += '<div class="songName"></div>';
    section += '<div class="songArtist"></div>';
    section += '</div>';
    return section;
  };

  // Funktion som avnänds vid sortering av tracks beroende
  // på votes i första hand, sedan timestamp.
  function compare(a, b) {
    var votesA = a.votes.amount; var votesB = b.votes.amount;
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

  var tracks = new Array();
  tracks[0] = new track('spotify:track:0Rynk2V7LyLgBUjTMxvbEJ');
  tracks[1] = new track('spotify:track:4qw6yAygswKYFsO5GMybWu');
  tracks[2] = new track('spotify:track:3vS2Jsk6g4Y8QMFsYZXr3z');
  tracks[3] = new track('spotify:track:3zBgPi9s8iroxNQ5rNYeQR');
  tracks[4] = new track('spotify:track:1r9mGafUiSgumJoRqyLrSt');
  tracks[5] = new track('spotify:track:3YXUMVKfRy4mwPEAslWg1p');
  tracks[6] = new track('spotify:track:2xaNOCsGBhFJ3bp6mvSqXz'); 

  var size  = tracks.length;
  tracks.sort(compare);
  // Loop som skriver ut de låtar som finns i tracks
  // från början.
  // Att göra:
  // * Skriva ut låtarna sorterat på antal röster.
  for(var i = 0; i < size; i++)
  {
    document.getElementById('queue').innerHTML += tracks[i].section;
    trackInfo.insertSongInfo(tracks[i].URI);
    cover.insertImage(tracks[i].URI);
  }

  models.player.setShuffle(false);
  models.player.setRepeat(false);
  models.player.playTrack(models.Track.fromURI(tracks[0].URI));

  // Funktion för att ta bort ett "Track"-elemnent
  // Att göra:
  // * Funktion som endast ska vara tillgänglig för
  //   feststartaren.
  $(document).on('click', '.delete', function() {
    $(this).parent().fadeOut(200);
    // $(this).parent().delay(1600).remove();
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
  });

  // Funktion som lägger till Spotify URI i tracks
  // samt nytt element i votes
  // Att göra:r
  // * Se till så den placeras på rätt plats i listan
  //   beroende på antalet röster
  // * Bytas ut/justeras så att man söker efter en
  //   låttitel eller artist istället. Endast låtar
  //   ska visas i resultaten
  $(document).on('click', '.addTrack', function() {
    if($('#trackSearch').val() != "" &&
       $('#trackSearch').val().substr(0,14) == 'spotify:track:' &&
       $('#trackSearch').val().length == 36)
    {
      tracks[size] = new track($('#trackSearch').val());
      tracks.sort(compare);
      document.getElementById('queue').innerHTML += tracks[size].section;
      $('#trackSearch').val("");
      trackInfo.insertSongInfo(tracks[size].URI);
      cover.insertImage(tracks[size].URI);
      size++;
    }
    
  });

});
