var time = 1;

// run things when page is ready
$(document).ready(function(){
	load_party($('#newpartyque').attr('data-partyhash'), $('#newpartyque'));
});

function load_party(partyhash, theobject)
{
	console.log('running load_party("'+partyhash+'","'+theobject+'");');
	console.log('Filling ' + theobject + ' with party stuff from ' + partyhash);

	console.log('Time is ' + time + ', initiating loading');

	var postdata = {
		'time': time,
		'partyhash': partyhash
	};

	time = Math.floor(new Date().getTime() / 1000);

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
		if(answer.status === 'success')
		{
			redraw(answer.response.result, theobject);
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

function fill_empty(theobject)
{
	theobject.slideUp('fast');

	theobject.append('<p>This party seems to be empty! Add some songs straight away, and start dancing!</p>');

	theobject.slideDown('fast');
}

function redraw(queue, theobject)
{
	if(Array.isArray(queue))
	{
		console.log("Awesome queue: ");
		console.log(queue);

		theobject.empty();
		theobject.slideUp('fast');
		// theobject.children('.loader').slideUp({duration: 'fast', queue: false}, function(){
		// });

		for(var i = 0; i < queue.length; i++)
		{
			console.log('song: ');
			console.log(queue[i]);

			var song = queue[i];

			var $songelement = $('<div></div>').addClass('row');
			$('<div></div>').addClass('col-xs-4 col-sm-3').appendTo($songelement)
				.append('<img class="img-responsive">').children('img').attr('src', song.image);

			var $middlecolon = $('<div></div>').addClass('col-xs-8 col-sm-5').appendTo($songelement);

			$('<div></div>').addClass('hidden-xs').appendTo($middlecolon)
				.append('<h3>'+song.songname+'</h3>')
				.append('<h4>'+song.artistname+'</h4>');
			var $xscontent = $('<div></div>').addClass('visible-xs').appendTo($middlecolon)
				.append('<h4>'+song.songname+'</h4>')
				.append('<h5>'+song.artistname+'</h5>');

			$('<h5></h5>').appendTo($xscontent)
				.append('<a href="#" class="vote btn btn-success" data-toggle="tooltip" title="Add your vote to this song!">Vote!</a>')
				.children('a').attr('data-songid', song.songid)
				.parent()
					.append(' <span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span>');

			var $rightcolon = $('<div></div>').addClass('col-sm-4 hidden-xs').appendTo($songelement);

			$rightcolon.append('<h3></h3>').children('h3')
				.append('<span><strong>'+song.vote_count+'</strong> '+(song.vote_count == 1 ? 'vote' : 'votes' )+'</span> ')
				.append('<a href="#" class="vote label label-success" data-toggle="tooltip" title="Add your vote to this song!">+1</a>')
				.children('a').attr('data-songid', song.songid);

			$images = $('<div></div>').appendTo($rightcolon);

			var thevoters = song.voters;

			for(var k = 0; k < thevoters.length; k++)
			{
				$('<img class="img-circle">').appendTo($images)
					.attr('src', 'http://www.gravatar.com/avatar/' + song.voters[k].mailhash + '?s=25&d=mm')
					.attr('alt', song.voters[k].name)
					.attr('title', song.voters[k].name)
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
