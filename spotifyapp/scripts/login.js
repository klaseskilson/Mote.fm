require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
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
	});

	$('#forgotPwd').click(function() {
		$('#password').hide({duration: 400, queue: false});
		$('#name').hide({duration: 400, queue: false});
		switchButtons('#forgotPwd');
		situation = "#forgotPwd";
		$('#email').focus();
	});

	$('#signIn').click(function() {
		$('#password').show({duration: 400, queue: false});
		$('#name').hide({duration: 400, queue: false});
		switchButtons('#signIn');
		situation = "#signIn";
		$('#email').focus();
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
