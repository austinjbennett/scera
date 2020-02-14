jQuery(document).ready(function(){
	_ppsUpdateSubIcLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubIcLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ic_app_id]"]').change(function(){
		_ppsUpdateSubIcLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ic_app_user]"]').change(function(){
		_ppsUpdateSubIcLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ic_app_pass]"]').change(function(){
		_ppsUpdateSubIcLists();
	});
});
function _ppsUpdateSubIcLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'icontact') {
		var appId = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ic_app_id]"]').val())
		,	appUser = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ic_app_user]"]').val())
		,	appPass = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ic_app_pass]"]').val());
		jQuery('#ppsSubIcListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubIcMsg'
		,	data: {mod: 'icontact', action: 'getLists', app_id: appId, app_user: appUser, app_pass: appPass}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubIcLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_ic_lists ? ppsPopup.params.tpl.sub_ic_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubIcLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubIcListsShell').show();
					jQuery('#ppsSubIcLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}