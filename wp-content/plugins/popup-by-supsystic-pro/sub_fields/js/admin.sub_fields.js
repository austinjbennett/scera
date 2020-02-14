var g_ppsSfCurrEditCell = null;
jQuery(document).ready(function(){
	ppsInitSubFieldsPopup();
	jQuery('#ppoPopupSubFields .ppsSubFieldShell').each(function(){
		_ppsSfInitFieldToolbar(jQuery(this));
	});
	jQuery('.ppsSfFieldSelectOptAddBtn').click(function(){
		_ppsSfAddSelectOptRow();
		return false;
	});
	jQuery('#ppsSfEditFieldsWnd select[name="html"]').change(function(){
		if(jQuery(this).val() == 'selectbox') {
			jQuery('.ppsSfFieldSelectOptsRow').slideDown( g_ppsAnimationSpeed );
		} else {
			jQuery('.ppsSfFieldSelectOptsRow').slideUp( g_ppsAnimationSpeed );
		}
		// MailChimp goup should have unique name
		if(jQuery(this).val() == 'mailchimp_groups_list') {
			_ppsSetMcGroupsName();
		} else {
			_ppsbackFromMcGroupName();
		}
	});
	jQuery('#ppsSfFieldSelectOptsShell').sortable({
		revert: true
	,	placeholder: 'ui-state-highlight-sub-field-select-opt'
	,	axis: 'y'
	,	items: '.ppsSfFieldSelectOptShell'
	});
});
function _ppsSetMcGroupsName() {
	var $name = jQuery('#ppsSfEditFieldsWnd [name="name"]');
	$name.data('mc-back-name', $name.val()).val('mailchimp_goup').attr('readonly', 1);
}
function _ppsbackFromMcGroupName() {
	var $name = jQuery('#ppsSfEditFieldsWnd [name="name"]')
	,	backName = $name.data('mc-back-name');
	if(backName || backName === '') {
		$name.data('mc-back-name', 0).val( backName ).removeAttr('readonly');
	}
}
function ppsInitSubFieldsPopup() {
	var $wnd = jQuery('#ppsSfEditFieldsWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 540
	,	height: jQuery(window).height() - 60
	,	buttons: {
			'Ok': function() {
				if(_ppsSfAddSubField($wnd.serializeAnythingPps(false, true), $wnd)) {
					$wnd.dialog('close');
				}
			}
		,	'Cancel': function() {
				$wnd.dialog('close');
			}
		}
	});
	jQuery('#ppsSubAddFieldBtn').click(function(){
		g_ppsSfCurrEditCell = null;
		_ppsSfClearEditForm();
		$wnd.dialog('open');
		return false;
	});
}
function _ppsSfClearEditForm() {
	jQuery('#ppsSfEditFieldsWnd').find('input:not([type="checkbox"])').val('');
	jQuery('#ppsSfEditFieldsWnd').find('select').each(function(){
		this.selectedIndex = 0;
	}).trigger('change');
	ppsCheckUpdate(jQuery('#ppsSfEditFieldsWnd').find('input[type=checkbox]').removeAttr('checked'));
	jQuery('#ppsSfEditFieldsWnd').find('.ppsSfFieldSelectOptShell:not(#ppsSfFieldSelectOptShellExl)').remove();
}
function _ppsSfAddSubField(data, $wnd) {
	var errors = {};
	data.name = data.name ? data.name : false;
	if(data.name) {
		data.name = data.name.replace(/[^0-9a-z\-_+ ]/ig, '');
	}
	if(data.name && data.name != '') {
		if(data.label) {
			var cell = g_ppsSfCurrEditCell ? g_ppsSfCurrEditCell : null
			,	isNewCell = false;
			if(!cell) {
				isNewCell = true;
				var copyFromCell = jQuery('#ppoPopupSubFields .ppsSubFieldShell:not(.ppsSubFieldEmailShell):first');
				ppsCheckDestroyArea( copyFromCell );
				cell = copyFromCell.clone();
			}
			data.custom = 1;
			_ppsSfFillInSubFieldCell(cell, data);
			if(isNewCell) {
				cell.insertBefore( jQuery('#ppoPopupSubFields').find('#ppsSubAddFieldShell') );
				cell.find('input[type=checkbox][name*="enb"]')
					.attr('checked', 'checked')
					.change(function(){
						ppsSavePopupChanges();
					});
				ppsInitCustomCheckRadio( copyFromCell );
				ppsInitCustomCheckRadio( cell );
				_ppsSfInitFieldToolbar( cell );
			}
			ppsSavePopupChanges();
			return true;
		} else
			errors['label'] = toeLangPps('Please enter Label');
	} else
		errors['name'] = toeLangPps('Please enter Name');
	if($wnd && errors) {
		toeProcessAjaxResponsePps({error: true, errors: errors}, false, $wnd, true, {btn: $wnd.find('.ui-button:first')});
	}
	return false;
}
function _ppsSfAddSelectOptRow(data) {
	var newCell = jQuery('#ppsSfFieldSelectOptShellExl').clone().removeAttr('id');
	newCell.hide();
	jQuery('#ppsSfFieldSelectOptsShell').append( newCell );
	newCell.slideDown( g_ppsAnimationSpeed );
	newCell.find('.ppsSfFieldSelectOptRemoveBtn').click(function(){
		newCell.slideUp(g_ppsAnimationSpeed, function(){
			newCell.remove();
		});
		return false;
	});
	var i = 0
	,	iter = 0;
	newCell.find('input').removeAttr('disabled');
	if(data) {
		newCell.find('input[name="options[][name]"]').val( data.name );
		newCell.find('input[name="options[][label]"]').val( data.label );
		newCell.find('input[name="options[][value]"]').val( data.value );
	}
	jQuery('#ppsSfFieldSelectOptsShell input').each(function(){
		if(jQuery(this).attr('disabled')) return;
		var nameArr = jQuery(this).attr('name').split('][');
		if(nameArr.length == 2 && nameArr[0].substr(-1) == '[') {
			nameArr[0] += iter;
		}
		jQuery(this).attr('name', nameArr.join(']['));
		i++;
		if(i % 2 == 0 && i)
			iter++;
	});
}
function _ppsSfFillInSubFieldCell(cell, data) {
	cell.find('input').each(function(){
		//params[tpl][sub_fields][name][html] === ["params[tpl", "sub_fields", "name", "html]"] for example
		var nameArr = jQuery(this).attr('name').split('][');
		if(nameArr.length == 4) {
			nameArr[ 2 ] = data.name;
			jQuery(this).attr('name', nameArr.join(']['));
		}
	});
	cell.attr('data-name', data.name);
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][name]"]').val( data.name );
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][html]"]').val( data.html );
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][label]"]').val( data.label );
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][value]"]').val( data.value );
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][custom]"]').val( data.custom ? 1 : 0 );
	cell.find('[name="params[tpl][sub_fields]['+ data.name+ '][mandatory]"]').val( data.mandatory ? 1 : 0 );
	// Fill in options data
	cell.find('[name^="params[tpl][sub_fields]['+ data.name+ '][options]"]').remove();
	for(var key in data) {
		if(data[ key ] && data[ key ] != '') {
			if(key.indexOf('options[') !== -1) {
				var nameArr = key.split('][');
				if(nameArr.length == 2) {
					var optIter = parseInt(nameArr[0].replace(/\D/g, ''));
					if(!isNaN(optIter)) {
						var optParam = nameArr[1].substr(0, nameArr[1].length - 1);
						if(optParam && optParam != '') {
							cell.append('<input type="hidden" name="params[tpl][sub_fields]['+ data.name+ '][options]['+ optIter+ ']['+ optParam+ ']" value="'+ data[ key ]+ '" />');
						}
					}
				}
			}
		}
	}
	cell.find('.ppsSubFieldLabel').html( data.label );
}
function _ppsSfInitFieldToolbar(cell) {
	if(parseInt(cell.find('input[name*="custom"]').val())) {
		var toolbarHtml = jQuery('#ppsSfFieldToolbarExl').clone().removeAttr('id');
		cell.append( toolbarHtml );
		toolbarHtml.find('.ppsSfFieldRemoveBtn').click(function(){
			if(confirm(toeLangPps('Are you sure want to remove "'+ cell.find('.ppsSubFieldLabel').html()+ '" field?'))) {
				cell.slideUp(g_ppsAnimationSpeed, function(){
					cell.remove();
					ppsSavePopupChanges();
				});
			}
			return false;
		});
		toolbarHtml.find('.ppsSfFieldSettingsBtn').click(function(){
			_ppsSfClearEditForm();
			var $wnd = jQuery('#ppsSfEditFieldsWnd')
			,	name = cell.attr('data-name');	// cell.data('name') didn't worked correctly here

			$wnd.find('[name="name"]').val( name );
			$wnd.find('[name="html"]').val( cell.find('input[name*="html"]').val() ).trigger('change');
			$wnd.find('[name="label"]').val( cell.find('input[name*="label"]').val() );
			$wnd.find('[name="value"]').val( cell.find('input[name*="value"]').val() );
			parseInt(cell.find('input[name*="mandatory"]').val())
				? $wnd.find('[name="mandatory"]').attr('checked', 'checked')
				: $wnd.find('[name="mandatory"]').removeAttr('checked');

			var optionsInputs = cell.find('[name^="params[tpl][sub_fields]['+ name+ '][options]"]');
			if(optionsInputs && optionsInputs.size()) {
				var selectOptsData = [];
				optionsInputs.each(function(){
					var nameArr = jQuery(this).attr('name').split('][');
					if(nameArr.length == 6) {
						var optIter = parseInt(nameArr[4]);
						if(!isNaN(optIter)) {
							var optParam = nameArr[5].substr(0, nameArr[5].length - 1);
							if(!selectOptsData[ optIter ]) selectOptsData[ optIter ] = {};
							selectOptsData[ optIter ][ optParam ] = jQuery(this).val();
						}
					}
					
				});
				if(selectOptsData.length) {
					for(var i = 0; i < selectOptsData.length; i++) {
						_ppsSfAddSelectOptRow(selectOptsData[ i ]);
					}
				}
			}
			ppsCheckUpdateArea( $wnd );
			g_ppsSfCurrEditCell = cell;
			$wnd.dialog('open');
			return false;
		});
	}
}
