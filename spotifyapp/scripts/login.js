
require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
  $(document).ready(function(){

  	$.ajax({
	 url: constants.SERVER_URL,
	 cache: false,
	 async : false,
	 error: function(XMLHttpRequest, textStatus, errorThrown) {
	       $('#loginstatus').html("Server connection is down. Unfortunatly you can't log in at this moment.");
	        },
	 success: function(html){
	          // Server is up and running, hurray!
	        }
	});

	//test if user already is in the session
	if(sessionStorage.username !== undefined)
	{
	  //if it is, no need to login again
	  window.location.href = "landing.html";
	}
	})

	var situation = "#signIn";

	var switchButtons = function(id) {
		console.log(id);
		console.log(situation);
		if($(id).hasClass('loginLeft'))
		{
			$(id).removeClass('loginLeft');
			$(situation).addClass('loginLeft');
		}
		if($(id).hasClass('loginRight'))
		{
			$(id).removeClass('loginRight');
			$(situation).addClass('loginRight');
		}
	}

	$('#signUp').click(function() {
		$('#password').show({duration: 400, queue: false});
		$('#name').show({duration: 400, queue: false});
		$('#name').attr('required', '');
		switchButtons('#signUp');
		situation = "#signUp";
		$('#name').focus();
		document.getElementById('submit').setAttribute('value', 'Sign up!');
	});

	$('#forgotPwd').click(function() {
		$('#password').hide({duration: 400, queue: false});
		$('#name').hide({duration: 400, queue: false});
		switchButtons('#forgotPwd');
		situation = "#forgotPwd";
		$('#email').focus();
		document.getElementById('submit').setAttribute('value', 'Recover!');
	});

	$('#signIn').click(function() {
		$('#password').show({duration: 400, queue: false});
		$('#name').hide({duration: 400, queue: false});
		switchButtons('#signIn');
		situation = "#signIn";
		$('#email').focus();
		document.getElementById('submit').setAttribute('value', 'Sign in!');
	});

  $('#submit').click(function() {
  	console.log(situation);
    event.preventDefault();
     var $inputs = $('#login :input');
     var values = {};
     $inputs.each(function() {
            values[this.name] = $(this).val();
        });

  	if(situation == "#signIn")
  	{

      $.post(constants.SERVER_URL + '/api/user/signin',values, function(data, textstatus)
      { 
        var json = data;
        if(json.status == "success")
        {
          // Only login
          sessionStorage.uid = json.result.uid;
          sessionStorage.useremail = json.result.email;
          sessionStorage.username = json.result.name;
          window.location.href = "landing.html";
        }
        else
        {
          $('#loginstatus').html(json.status + ": " + json.response);
        }

      });
  	}
  	else if(situation == "#signUp")
  	{
  		$.post(constants.SERVER_URL + '/api/user/signUp',values, function(data, textstatus)
		{ 
			var json = data;
			if(json.status == "success")
			{
				$('#password').show({duration: 400, queue: false});
				$('#name').hide({duration: 400, queue: false});
				switchButtons('#signIn');
				situation = "#signIn";
				document.getElementById('submit').setAttribute('value', 'Sign in!');

				$('#loginstatus').html("Congratulations! You are now a member of our family.");
			}
			else
			{
			  $('#loginstatus').html(json.status + ": " + json.response);
			}

		});
  	}
  	else if(situation == "#forgotPwd")
  	{
  		$.post(constants.SERVER_URL + '/api/user/reset',values, function(data, textstatus)
		{ 
			var json = data;
			if(json.status == "success")
			{
				$('#password').show({duration: 400, queue: false});
				$('#name').hide({duration: 400, queue: false});
				switchButtons('#signIn');
				situation = "#signIn";
				document.getElementById('submit').setAttribute('value', 'Sign in!');

				$('#loginstatus').html("We have sent you a lifeboat to your email, please check your inbox.");
			}
			else
			{
			  $('#loginstatus').html(json.status + ": " + json.response);
			}

		});
  	}
  });
});
