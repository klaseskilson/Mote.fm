$(document).ready(function(){ // boring needed stuff
	// change the background color of the header when scrolling
	$(document).scroll(function(){
		$('body.fancypane #head').toggleClass('color', ($(this).scrollTop() > $(window).height()*0.1));
    });

    $('form#signupform').submit(function(event){
    	// prevent form from beeing sent
		event.preventDefault();
		console.log("form sent, default prevented");

		var postdata = {
			'email': $('#email').val(),
			'name': $('#name').val(),
			'password': $('#password').val()
		};

		// send postdata to server
		$.post(BASE_URL + 'user/signup/json', postdata, function(data){
			// what is the return message from server?
			if(data.status === 'success')
			{
				// tell the user about our success!
				// change sign up title to something nice
				$('#signuptitle').fadeOut('fast', function() {
					$(this).text('All right!').fadeIn();
				});
				// tell the user what to do now
				$('#signuparea').fadeOut(function(){
					$(this).html('<h3>You now have an acoount. <a href="'+BASE_URL+'">Continue!</a></h3><p>We sent an email to you confirming this. You\'ll need to activate your account by clicking the link in the email within three days.').fadeIn()
				});
			}
			else
			{
				// make sure we don't show multiple error messages
				$('#signuperror').remove();
				// create an error div
				var $errordiv = $("<div>", {id: "signuperror", class: "alert alert-danger"});
				// prepend it to signup area
				$errordiv.hide().prependTo('#signuparea');

				// add common error message
				$errordiv.append('<p><strong>Oh noes!</strong> There are some things you need to check before we continue.</p>');
				// add specific error messages
				if(!data.errors.email)
					$errordiv.append('<p>There is something wrong with that email. Have you entered it correctly? Do you allready have an account, but <a href="'+BASE_URL+'user/recover">forgot your password</a>?</p>');
				if(!data.errors.name)
					$errordiv.append('<p>That name is too short. We think you want at least two characters there.</p>');
				if(!data.errors.password)
					$errordiv.append('<p>Please choose a stronger password. Like at least six characters.</p>');

				// show message
				$errordiv.show();
			}
		}, 'json');
    });
});
