
$(function(){
        $('.sub-menu').hide();
	$('.j1').on('click',function(e){
		$('.sub-menu-j1').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j2').on('click',function(e){
		$('.sub-menu-j2').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j3').on('click',function(e){
		$('.sub-menu-j3').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j4').on('click',function(e){
		$('.sub-menu-j4').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j5').on('click',function(e){
		$('.sub-menu-j5').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j6').on('click',function(e){
		$('.sub-menu-j6').slideToggle(400);
		e.stopPropagation();		
		});
	$('.j7').on('click',function(e){
		$('.sub-menu-j7').slideToggle(400);
		e.stopPropagation();		
		});
	$('html').on('click',function(e){
		e.stopPropagation();		
		});

});
//console.log($('.sub-menu-config').children());