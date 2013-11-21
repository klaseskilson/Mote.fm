$(document).ready(function(){ // boring needed stuff
	// change the background color of the header when scrolling
	$(document).scroll(function(){
		$('body.fancypane #head').toggleClass('color', ($(this).scrollTop() > $(window).height()*0.1));
    });
});
