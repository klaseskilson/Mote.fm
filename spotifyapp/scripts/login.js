require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
  $(document).ready(function(){
    
    //test if user already is in the session
    if(sessionStorage.username !== undefined)
    {
      //if it is, no need to login again
      window.location.href = "party.html";
    }
  })
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
    event.preventDefault();
     var $inputs = $('#login :input');
     var values = {};
     $inputs.each(function() {
            values[this.name] = $(this).val();
        });

  	if($('#name').css('display') == 'none')
  	{


      $.post('http://127.0.0.1/Hathor/api/user/signin',values, function(data, textstatus)
      { 
        var json = data;
        if(json.status == "success")
        {
          // Only login
          sessionStorage.uid = json.result.uid;
          sessionStorage.useremail = json.result.email;
          sessionStorage.username = json.result.name;
          document.getElementById('xyz').innerHTML = "LOGIN";
          window.location.href = "party.html";
        }
        else
        {
          $('#loginstatus').html(json.status + ": " + json.response);
        }

      });
  	}
  	else
  	{
  		//TODO:Sign up 
  		document.getElementById('xyz').innerHTML = "SIGN UP";
  	}
  });

});
