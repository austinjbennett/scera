jQuery(document).ready(function(){
	_ppsUpdateSubAcLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubAcLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ac_api_url]"]').change(function(){
		_ppsUpdateSubAcLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ac_api_key]"]').change(function(){
		_ppsUpdateSubAcLists();
	});
	// This don't work, need to use jquery.on() here, but it is too much processor time for this small feature
	/*jQuery('.ppsAcHelpApiKeyLink').click(function(){
		var $dialog = jQuery('<div />').html('<img src="'+ jQuery(this).attr('href')+ '" />').dialog({
			modal:    true
		,	width: 800
		,	height: 333
		,	buttons: {
				OK: function() {
					$dialog.dialog('close');
				}
			}
		,	close: function() {
				$dialog.remove();
			}
		});
		return false;
	});*/
});
function _ppsUpdateSubAcLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'activecampaign') {
		var apiKey = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ac_api_key]"]').val())
		,	apiUrl = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ac_api_url]"]').val());
		jQuery('#ppsSubAcListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubAcMsg'
		,	data: {mod: 'activecampaign', action: 'getLists', api_key: apiKey, api_url: apiUrl}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubAcLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_ac_lists ? ppsPopup.params.tpl.sub_ac_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubAcLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubAcListsShell').show();
					jQuery('#ppsSubAcLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}