require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
  $('#signUp').click(function() {
  	if($('#name').css('display') == 'none')
  	{
	  	$('#email').animate({
	      marginTop: '+=45px'
	    }, 300, 'linear', function() {
	    	$('#email').css('margin-top', '0px');
			$('#name').css('display', 'inline');
			$('#name').animate({
				opacity: '1'
			}, 500);
	    });	
  	}
    
  });

  $('#submit').click(function() {
  	if($('#name').css('display') == 'none')
  	{
  		// Only login
  		document.getElementById('xyz').innerHTML = "LOGIN";
  		window.location.href = "party.html";
  	}
  	else
  	{
  		// Sign up
  		document.getElementById('xyz').innerHTML = "SIGN UP";
  	}
  });

});
