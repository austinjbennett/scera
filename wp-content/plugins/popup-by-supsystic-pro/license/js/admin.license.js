jQuery(document).ready(function(){
	jQuery('#ppsLicenseForm').submit(function(){
		jQuery(this).sendFormPps({
			btn: jQuery(this).find('button.button-primary')
		,	onSuccess: function(res) {
				if(!res.error) {
					toeReload();
				}
			}
		});
		return false;
	});
});
