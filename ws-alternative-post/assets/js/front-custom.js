(function($)
{
	$(document).ready(function()
	{
		$('.list-more-button').click(function()
		{
			var $button=$(this);var pages=$button.attr('data-pages');var page=$button.attr('data-page');var platform=$button.attr('data-platform')
			page++;$button.css('display','none');$('.list-more-loading').css('display','inline-block');$.ajax({url:more.url,method:'post',data:'pageno='+page+'&platform='+platform,}).success(function(data)
			{$('.list-content').append(data);$button.attr('data-page',page);$('.list-more-loading').css('display','none');if(page<pages)
			{$button.css('display','inline-block');}}).fail(function(jqXHR,textStatus)
			{alert("error in request!");});
		});

		

		$('.platform-dropdown-button').click(function(){
			var state=$('.platform-dropdown-content').css('display');
			if(state=='none'){
				$('.platform-dropdown-content').css({'display':'block'});
				$('.platform-dropdown-caret').addClass('platform-dropdown-caret-up');
			}
			else
			{
				$('.platform-dropdown-content').css({'display':'none'});
				$('.platform-dropdown-caret').removeClass('platform-dropdown-caret-up');
			}
		});
		
		$(document).click(function(event)
		{
			if(!$(event.target).closest(".platform-dropdown-button, .platform-dropdown-content").length){
				$('.platform-dropdown-content').css({'display':'none'});
				$('.platform-dropdown-caret').removeClass('platform-dropdown-caret-up');
			}
		});

		$('.license-dropdown-button').click(function(){
			var state=$('.license-dropdown-content').css('display');
			if(state=='none'){
				$('.license-dropdown-content').css({'display':'block'});
				$('.license-dropdown-caret').addClass('license-dropdown-caret-up');
			}
			else
			{
				$('.license-dropdown-content').css({'display':'none'});
				$('.license-dropdown-caret').removeClass('license-dropdown-caret-up');
			}
		});
		
		$(document).click(function(event)
		{
			if(!$(event.target).closest(".license-dropdown-button, .license-dropdown-content").length){
				$('.license-dropdown-content').css({'display':'none'});
				$('.license-dropdown-caret').removeClass('license-dropdown-caret-up');
			}
		});

	});


	
})(jQuery);