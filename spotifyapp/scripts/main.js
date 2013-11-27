require([
  '$api/models',
  'scripts/cover',
  'scripts/postPlayingSong',
  'scripts/registerParty',
  'scripts/trackInfo',
], function(models, cover, postPlayingSong, registerParty,trackInfo) {
  'use strict';

  var numReloads = 0;

  //FIXME: partyID is hardcoded
  registerParty.RegisterParty(localStorage.user);

  if(typeof(Storage) !== "undefined")
  {
    setUser(1);
    getUserName(getUser(), '#user');
  }


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
    this.section = createSection(this.URI);
    this.active = true;
    this.image = '';
  }

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
    section += '<div class="deleteCheck"></div>';
    section += '<div class="delete glyphicon glyphicon-remove"></div>';
    section += '<div class="vote glyphicon glyphicon-chevron-up"></div>';
    section += '</div>';
    section += '</div>';
    return section;
  }

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
<<<<<<< HEAD
  // tracks[0] = new track('spotify:track:0Rynk2V7LyLgBUjTMxvbEJ');
  // tracks[1] = new track('spotify:track:4qw6yAygswKYFsO5GMybWu');
  // tracks[2] = new track('spotify:track:3vS2Jsk6g4Y8QMFsYZXr3z');
  // tracks[3] = new track('spotify:track:3zBgPi9s8iroxNQ5rNYeQR');
  // tracks[4] = new track('spotify:track:1r9mGafUiSgumJoRqyLrSt');
  // tracks[5] = new track('spotify:track:3YXUMVKfRy4mwPEAslWg1p');
  // tracks[6] = new track('spotify:track:2xaNOCsGBhFJ3bp6mvSqXz'); 

  var size  = tracks.length;
  tracks.sort(compare);
  // Loop som skriver ut de låtar som finns i tracks
  // från början.
  // Att göra:
  // * Skriva ut låtarna sorterat på antal röster.
  for(var i = 0; i < size; i++)
=======
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
  console.log("Array initialized");

  listUpdate();

  console.log('bajs');

  for(var i = 0; i < tracks.length; i++)
>>>>>>> 6b9c6aad7aaae7120ffa583a4c7fd41bca79107b
  {
    console.log('kuk');
    tracks[i].image = document.getElementsByClassName('cover')[i].innerHTML.toString();
    var aux = tracks[i].section.toString();
    tracks[i].section = [aux.slice(0,aux.indexOf('cover')+7), tracks[i].image, aux.slice(aux.indexOf('cover')+7)].join();
    console.log(tracks[i].image);
  }

<<<<<<< HEAD
  models.player.setShuffle(false);
  models.player.setRepeat(false);
  // models.player.playTrack(models.Track.fromURI(tracks[0].URI));

=======
>>>>>>> 6b9c6aad7aaae7120ffa583a4c7fd41bca79107b
  // Funktion för att ta bort ett "Track"-elemnent
  // Att göra:
  // * Funktion som endast ska vara tillgänglig för
  //   feststartaren.
  $(document).on('click', '.delete', function() {
    var pos = $('.delete').index(this);
    // var deleteCheck = '.deleteCheck:eq('+pos+')';
    // var height = $(this).parent().parent().height();
    // console.log(height);
    // document.getElementsByClassName('deleteCheck')[pos].innerHTML = 'DELETE';
    // $(deleteCheck).css('padding-left', '18px');
    // $(deleteCheck).animate({
    //   width: '+=135px'
    // }, 500, function() {
    //   $(this).click(function(){
    //     $(this).parent().parent().fadeOut(300);
    //     setTimeout(function(){
    //         if(pos==0)
    //         {
    //           listUpdate();
    //         }
    //       },300);
    //   });
    //   $(document).click(function() {
    //     $(deleteCheck).animate({
    //       width: '0px',
    //       paddingLeft: '0px'
    //     }, 300, function() {
    //       // DONE
    //       document.getElementsByClassName('deleteCheck')[pos].innerHTML = '';
    //     });
        
    //   });
    // });
    
    // 
    // console.log(pos);
    tracks[pos].active = false;
    $(this).parent().parent().fadeOut(200);
    // listUpdate();
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
    //listUpdate();
  });

});
