(function($)
{

  // Here is the entry point for your front javascript

$('.dm_zone').mouseover(function() {
	$(this).addClass('mouseOver');
});
$('.dm_zone').mouseout(function() {
	$(this).removeClass('mouseOver');
});


$('.dm_widget').mouseover(function() {
	$(this).addClass('mouseOver');
});
$('.dm_widget').mouseout(function() {
	$(this).removeClass('mouseOver');
});


  
})(jQuery);