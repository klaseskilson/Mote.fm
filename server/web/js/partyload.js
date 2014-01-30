var time = 1;
var successTime = 1;
var partyarray = new Array();
var firstrun = false;
// run things when page is ready
$(document).ready(function(){
	song_count($('#newpartyque').attr('data-partyhash'), $('#nowPlaying'));
	load_party($('#newpartyque').attr('data-partyhash'), $('#newpartyque'));
	get_played_song($('#nowPlaying').attr('data-partyhash'), $('#nowPlaying').children('div').eq(0).attr('data-songid'));
});

/**
 *	Get the number of songs related to this party, if its zero, then we show fill_empty()
 */
function song_count(partyhash, theobject)
{
 	var postData = {
		'partyhash' : partyhash
	}
	$.ajax({
		type: "POST",
		url: BASE_URL + "api/party/get_song_count",
		data: postData, 
		dataType: 'json'
	})
	.fail(function (data){})
	.done(function (data){
		if(data.status === 'success')
		{
			if(data.result === '0')
			{
				fill_empty(theobject);
			}
		}
	});
}


/**
 *	Load party data async via ajaj. Can be recursive or called once
 *  TODO: Some bugs causes function to load multiple tipmes.
 */
function load_party(partyhash, theobject)
{
	console.log('running load_party("'+partyhash+'","'+theobject+'");');
	console.log('Filling ' + theobject + ' with party stuff from ' + partyhash);

	console.log('Time is ' + time + ', initiating loading');

	var postdata = {
		'time': time,
		'partyhash': partyhash
	};

	$.ajax({
		type: "POST",
		url: BASE_URL + 'api/party/load_party',
		data: postdata,
		dataType: 'json'
	})
	.fail(function(errordata){
		console.log(errordata.responseText);
	})
	.done(function(answer){
		time = Math.floor(new Date().getTime() / 1000);
		if(answer.status === 'success')
		{
			console.log(answer);
			var i = 0;
			while(answer.response.result[i] && answer.response.result[i].played !== '1')
			{
				i += 1;
			}
			// Song to be played
			redraw(answer.response.result.slice(1,i), theobject);
			//Song that already has been played
			redraw(answer.response.result.slice(i), $('#playedqueue')); 
			//Currently playing track
			redraw(answer.response.result.slice(0,1), $('#nowPlaying'));
			
			firstrun = true;

			load_party(partyhash, theobject);
		}
		else if(answer.status === 'empty')
		{
			console.log('No songs found');
			fill_empty(theobject);
			load_party(partyhash, theobject);
		}
		else
		{
			console.log('failed');
			console.log(answer);
			load_party(partyhash, theobject);
		}
	});
}

/**
 * Fills object with text saying that there is no songs at the party
 */

function fill_empty(theobject)
{
	theobject.empty();

	theobject.slideUp('fast');

	theobject.append('<p>This party seems to be empty! Add some songs straight away, and start dancing!</p>');

	theobject.slideDown('fast');
}

function get_played_song(partyhash, songid)
{
	var postdata = {
		partyhash : partyhash,
		songid : songid
	}
	console.log('check if played!');

	$.ajax({
		type: "POST",
		url: BASE_URL + 'api/party/is_song_played',
		data: postdata,
		dataType: 'json'
	})
	.fail(function(errordata){
		console.log('errordata!!!');
		console.log(errordata);
	})
	.done(function(answer){
		if(answer.status === 'success')
		{
			time = 1;
			load_party($('#newpartyque').attr('data-partyhash'), $('#newpartyque'), false);
			console.log(answer);
		}
		else
		{
			console.log(answer);
		}
		get_played_song(partyhash, $('#nowPlaying').children('div').eq(0).attr('data-songid'))
	});
}

/**
 * test if user already has voted on the current song
 */
function has_voted(users, uid)
{
	if(Array.isArray(users))
	{
		for(var k = 0; k < users.length; k++)
		{
			if(users[k].uid == uid)
				return true;
		}
	}

	return false;
}

/**
 * Redraws theobject with the queue inside of it
 */
function redraw(queue, theobject)
{
	if(Array.isArray(queue))
	{
		console.log("Awesome queue: ");
		console.log(queue);

		theobject.empty();
		theobject.slideUp('fast');
	
		for(var i = 0; i < queue.length; i++)
		{
			console.log('song: ');
			console.log(queue[i]);

			var song = queue[i];

			var $songelement = $('<div></div>').addClass('row track').attr('data-songid', song.songid);
			$('<div></div>').addClass('col-xs-4 col-sm-3').appendTo($songelement)
				.append('<img class="img-responsive pull-right cover">').children('img').attr('src', song.image).attr('width', '60%');

			var $middlecolon = $('<div></div>').addClass('col-xs-8 col-sm-5').appendTo($songelement);

			$('<div></div>').addClass('hidden-xs').appendTo($middlecolon)
				.append('<h3>'+song.songname+'</h3>')
				.append('<h4>'+song.artistname+'</h4>');
			var $xscontent = $('<div></div>').addClass('visible-xs').appendTo($middlecolon)
				.append('<h4>'+song.songname+'</h4>')
				.append('<h5>'+song.artistname+'</h5>');

			$('<h5></h5>').appendTo($xscontent)
				.append('<a href="#" class="vote btn btn-success ' + (has_voted(thevoters, uid) ? 'hidden' : '') +'" data-toggle="tooltip" title="Add your vote to this song!">Vote!</a>')
				.children('a').attr('data-songid', song.songid)
				.parent()
					.append(' <span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span>');

			var $rightcolon = $('<div></div>').addClass('col-sm-4 hidden-xs').appendTo($songelement);

			var thevoters = song.voters;
			var uid = theobject.attr('data-uid');

			$rightcolon.append('<h3></h3>').children('h3')
				.append('<span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span> ')
				.append('<a href="#" class="vote label label-success '+ (has_voted(thevoters, uid) ? 'hidden' : '')+'" data-toggle="tooltip" title="Add your vote to this song!">+1</a>')
				.children('a').attr('data-songid', song.songid);

			$images = $('<div></div>').appendTo($rightcolon);

			for(var k = 0; k < thevoters.length; k++)
			{
				$('<img class="img-circle">').appendTo($images)
					.attr('src', 'http://www.gravatar.com/avatar/' + song.voters[k].mailhash + '?s=25&d=mm')
					.attr('alt', song.voters[k].name + (uid == song.voters[k].uid ? ' &mdash; you!' : ''))
					.attr('title', song.voters[k].name + (uid == song.voters[k].uid ? ' &mdash; you!' : ''))
					.attr('data-toggle', 'tooltip');
			}
			$songelement.appendTo(theobject);	
		}

		theobject.slideDown('slow');
	}
	else
	{
		console.log('Expected queue to be array, found ' + typeof queue);
	}
}
