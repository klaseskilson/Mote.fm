$(document).ready(function(){ // boring needed stuff
	// change the background color of the header when scrolling
	$(document).scroll(function(){
		// $('body.fancypane #head').toggleClass('color', ($(this).scrollTop() > $(window).height()*0.1));
	});

	// nice scroll animation
	$("a[href*=#]").click(function(e) {
		e.preventDefault();
		if(this.hash)
		{
			window.history.pushState("string", "Title", this.hash);
			$("html, body").animate({ scrollTop: $(this.hash).offset().top }, 1000);
		}
	});

	// nice fancy slide look when switching from sign in to sign up
	$(document).on('click', 'a[data-toggle="signupsignin"]', function(e) {
		e.preventDefault();
		if($('#signinarea').is(':hidden'))
		{
			// change url!
			window.history.pushState("string", "Title", BASE_URL+"user/signin");

			// hide/show
			$('#signuparea').slideUp('slow');
			$('#signinarea').slideDown('slow', function(){
				// focus on input field
				$('#login_email').focus();
			});
		}
		else
		{
			// change url!
			window.history.pushState("string", "Title", BASE_URL+"user/signup/web");

			// hide/show
			$('#signinarea').slideUp('slow');
			$('#signuparea').slideDown('slow', function(){
				// focus on input field
				$('#name').focus();
			});
		}
	});

	// password change from reset link, validation!
	$('.newpwd input').on('input', function(){
		// are the password inputs the same?
		if($('input#newpwd_confirm').val() !== '' && $('input#newpwd_confirm').val() !== $('input#newpwd_password').val())
		{
			// show error message
			if($(this).closest('.newpwd').children('.alert').is(':hidden'))
				$(this).closest('.newpwd').children('.alert').slideDown('fast');

			$('input#newpwd_submit').attr('disabled', true);
		}
		else
		{
			// hide error message!
			$(this).closest('.newpwd').children('.alert').slideUp('fast');
			$('input#newpwd_submit').attr('disabled',false);
		}
	});

	// bootstrap tooltip hover thingy
	$('*[data-toggle="tooltip"]').tooltip({html:true});
	$('*[data-toggle="tooltip-bottom"]').tooltip({html:true, placement:'bottom'});

	/**
	 * when submitting signup!
	 */
	$('form#signupform').submit(function(event){
    	// prevent form from beeing sent
		event.preventDefault();
		$('button[type=submit], input[type=submit]').attr('disabled',true);
		console.log("form sent, default prevented");

		var postdata = {
			'email': $('#email').val(),
			'name': $('#name').val(),
			'password': $('#password').val()
		};

		// send postdata to server
		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/signup/json',
			data: postdata,
			dataType: 'json'
		})
		.fail(function(errordata){
			console.log(errordata.responseText);
		})
		.done(function(data){
			console.log(data);
			// what is the return message from server?
			if(data.status === 'success')
			{
				console.log('Done!');
				// tell the user about our success!
				// change sign up title to something nice
				$('#signuptitle').fadeOut('fast', function() {
					$(this).text('All right!').fadeIn();
				});
				// tell the user what to do now
				$('#signuparea').fadeOut(function(){
					$(this).html('<h3>You now have an account. <a href="'+BASE_URL+'">Continue!</a></h3><p>We sent an email to you confirming this. You\'ll need to activate your account by clicking the link in the email within three days.').fadeIn()
				});
			}
			else
			{
				// make sure we don't show multiple error messages
				$('#signuperror').remove();
				// create an error div
				var $errordiv = $("<div>", {id: "signuperror", class: "alert alert-danger"});
				// prepend it to signup area
				console.log($('#signuparea h3').length);
				if($('#signuparea h3').length > 0)
					$errordiv.hide().insertAfter('#signuparea h3');
				else
					$errordiv.hide().prependTo('#signuparea');

				// add common error message
				$errordiv.append('<p><strong>Oh noes!</strong> There are some things you need to check before we continue.</p>');
				// add specific error messages
				if(!data.errors.email)
					$errordiv.append('<p>There is something wrong with that email. Have you entered it correctly? Do you already have an account, but <a href="'+BASE_URL+'user/reset">forgot your password</a>?</p>');
				if(!data.errors.name)
					$errordiv.append('<p>That name is too short. We think you want at least two characters there.</p>');
				if(!data.errors.password)
					$errordiv.append('<p>Please choose a stronger password. Like at least six characters.</p>');

				// show message
				$errordiv.show();
				$('button[type=submit], input[type=submit]').attr('disabled',false);
			}
		}, 'json');
	});
});
