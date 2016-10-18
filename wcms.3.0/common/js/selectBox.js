jQuery(function($)
{	
	// Common
	var select_root 	= $('div.selectBox');
	var select_value 	= $('.selectValue');
	var select_a 		= $('div.selectBox>ul>li>a');
	var select_input 	= $('div.selectBox>ul>li>input[type=radio]');
	var select_label 	= $('div.selectBox>ul>li>label');
	
	// Line
	select_value.bind('focusin',function(){$(this).addClass('outLine')});
	select_value.bind('focusout',function(){$(this).removeClass('outLine')});
	select_input.bind('focusin',function(){$(this).parents('div.selectBox').children('div.selectValue').addClass('outLine')});
	select_input.bind('focusout',function(){$(this).parents('div.selectBox').children('div.selectValue').removeClass('outLine')});
	
	// Show
	function show_option()
	{
		$(this).parents('div.selectBox:first').toggleClass('open');
	}
	
	// Hover
	function i_hover()
	{
		$(this).parents('ul:first').children('li').removeClass('hover');
		$(this).parents('li:first').toggleClass('hover');
	}
	
	// Hide
	function hide_option()
	{
		var t = $(this);
		setTimeout(function(){
			t.parents('div.selectBox:first').removeClass('open');
		}, 1);
	}
	
	// Set Input
	function set_label()
	{
		var v = $(this).next('label').text();
		$(this).parents('ul:first').prev('.selectValue').text('').append(v);
		$(this).parents('ul:first').prev('.selectValue').addClass('selected');
	}
	
	// Set Anchor
	function set_anchor()
	{
		var v = $(this).text();
		$(this).parents('ul:first').prev('.selectValue').text('').append(v);
		$(this).parents('ul:first').prev('.selectValue').addClass('selected');
	}
 
	// Anchor Focus Out
	$('*:not("div.selectBox a")').focus(function()
	{
		$('.selectList1').parent('.selectBox').removeClass('open');
	});
			
	select_value.click(show_option);
	$('.selectCtrl').click(show_option);
	select_root.removeClass('open');
	select_root.mouseleave(function(){$(this).removeClass('open')});
	select_a.click(set_anchor).click(hide_option).focus(i_hover).hover(i_hover);
	select_input.change(set_label).focus(set_label);
	select_label.hover(i_hover).click(hide_option);
	
});
