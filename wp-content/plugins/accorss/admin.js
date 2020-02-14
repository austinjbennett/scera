jQuery(document).ready(function($)
{
 
 
    var custom_uploader;
	
	$('.check_boxer').each(function() 
	{
		if( $(this).prop('checked') )
		{
			var others = '.' + $(this).attr('others');
			$(others).each(function() 
			{
				$(this).toggleClass("display-true");
			});
		}
	});
	
	$('.check_boxer').change(function()
	{
		var who = '.' + $(this).attr('others');
		console.log(who);
		$(who).each(function() 
		{
			$(this).toggleClass("display-true");
		});
	});
	
 
    $('.upload_image_button').click(function(e) 
	{
 
        e.preventDefault();
		
		var field_name = $(this).attr('name');
		var the_class = '.' + field_name + '_display';
		var the_id = '#' + field_name;
		
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media(
		{
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() 
		{
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $(the_id).val(attachment.url);
			$(the_class).attr('src',attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
});