(function ($, undefined)
{

	$(function()
	{
		var check = function()
		{
			if ( $('input[type=radio][name=file_or_link][value=file]').prop('checked') )
			{
				$("#rss_file").show('slow');
				$("#rss_link").hide('slow');
			}
			else
			{
				$("#rss_file").hide('slow');
				$("#rss_link").show('slow');
			}
		}
		
		check();
		
		$('input[type=radio][name=file_or_link]').change(check);
		//$('input[type=radio][name=file_or_link][value=link]').change(check);
	});

})(jQuery);