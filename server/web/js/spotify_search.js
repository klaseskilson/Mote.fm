function parsespotify(query, theobject)
{
	// make sure our query is long enough
	if(query.length < 3)
	{
		return false;
	}

	var spotifyAPI = 'http://ws.spotify.com/search/1/track.json?&q=' + query;

	$.getJSON(spotifyAPI)
		.done(function(data)
		{
			var searchresults = theobject.parent('.spotifysearch').children('.searchresults');
			searchresults.empty();
			if(data.info.num_results > 0)
			{
				// loop through the results
				for(var i = 0; i < data.tracks.length && i < 10; i++)
				{
					// save what we want in variables!
					// save string with artists
					var artists = '',
						name = data.tracks[i].name,
						uri = data.tracks[i].href,
						img = '',
						imgAPI = "https://embed.spotify.com/oembed/?url="+uri;

					// get album art -- not working
					// var img = $.ajax(imgAPI)
					// 	.done(function(imgdata)
					// 	{
					// 		console.log('img');
					// 		//img = imgdata.thumbnail_url.replace('\\/cover\\/','\\/60\\/');
					// 	});
					// loop through the artists and add name to string
					for(var k = 0; k < data.tracks[i].artists.length; k++)
						artists += (k !== 0 ? ', ' : '' ) + data.tracks[i].artists[k].name;

					// debug printout
					// console.log("Entry " + i + ": " + name + " by " + artists + " [" + uri + ", " + img + "]");

					// create object and append to search results
					$('<a></a>').attr('href', '#').attr('data-uri', uri)
						.html(name + " <span>" + artists + "</span>").appendTo(searchresults);
				}

				console.log("Nr of results: " + data.info.num_results);
			}
			else
			{
				console.log("Nothing found for " + query);
			}

		});

	// theobject.parent('.spotifysearch').children('.searchresults').show();
}


$(document).ready(function(){
	$('.spotifysearch input').on('input', function(){
		// if($(this).val().length > 2)
		// 	$(this).wrap('<div class="input-group"></div>').parent().append('<span class="input-group-btn"><button class="btn btn-default" type="button"><span class="glyphicon glyphicon-remove"></span></button></span>');
		parsespotify($(this).val(), $(this));
	});

	// listen to clicks on searchresults
	$(document).on('click', '.spotifysearch .searchresults a', function(event){
		event.preventDefault();
		console.log($(this).attr('data-uri'));
	});

});
