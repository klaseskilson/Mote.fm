$(document).ready(function(){ // boring needed stuff
	var windowheight = $(window).height();

	// change the background color of the header when scrolling
	$(document).scroll(function(){
		$('#head').toggleClass('color', ($(this).scrollTop() > $(window).height()*0.1));
    });
});
