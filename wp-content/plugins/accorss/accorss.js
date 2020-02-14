jQuery(document).ready(function()
{

	jQuery('#accorss .slide').click(function()
	{
		jQuery('#accorss .slide.active').removeClass('active');
		jQuery(this).addClass('active');
	});
	
});