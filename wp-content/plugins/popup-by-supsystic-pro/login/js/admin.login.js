var g_ppsRfCurrEditCell = null;	// Rf - Registration fields
jQuery(document).ready(function(){
	ppsInitRegFieldsPopup();
	jQuery('#ppoPopupRegFields .ppsRegFieldShell').each(function(){
		_ppsRfInitFieldToolbar(jQuery(this));
	});
	jQuery('.ppsRfFieldSelectOptAddBtn').click(function(){
		_ppsRfAddSelectOptRow();
		return false;
	});
	jQuery('#ppsRfEditFieldsWnd select[name="html"]').change(function(){
		if(jQuery(this).val() == 'selectbox') {
			jQuery('.ppsRfFieldSelectOptsRow').slideDown( g_ppsAnimationSpeed );
		} else {
			jQuery('.ppsRfFieldSelectOptsRow').slideUp( g_ppsAnimationSpeed );
		}
	});
	jQuery('#ppsRfFieldSelectOptsShell').sortable({
		revert: true
	,	placeholder: 'ui-state-highlight-reg-field-select-opt'
	,	axis: 'y'
	,	items: '.ppsRfFieldSelectOptShell'
	});
	jQuery('#ppoPopupRegFields').sortable({
		revert: true
	,	placeholder: 'ui-state-highlight-reg-fields'
	,	axis: 'x'
	,	items: '.ppsRegFieldShell'
	,	update: function() {
			ppsSavePopupChanges();
		}
    });
});
function ppsInitRegFieldsPopup() {
	var $wnd = jQuery('#ppsRfEditFieldsWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 540
	,	height: jQuery(window).height() - 60
	,	buttons: {
			'Ok': function() {
				if(_ppsRfAddRegField($wnd.serializeAnythingPps(false, true), $wnd)) {
					$wnd.dialog('close');
				}
			}
		,	'Cancel': function() {
				$wnd.dialog('close');
			}
		}
	});
	jQuery('#ppsRegAddFieldBtn').click(function(){
		g_ppsRfCurrEditCell = null;
		_ppsRfClearEditForm();
		$wnd.dialog('open');
		return false;
	});
}
function _ppsRfClearEditForm() {
	jQuery('#ppsRfEditFieldsWnd').find('input:not([type="checkbox"])').val('');
	jQuery('#ppsRfEditFieldsWnd').find('select').each(function(){
		this.selectedIndex = 0;
	}).trigger('change');
	ppsCheckUpdate(jQuery('#ppsRfEditFieldsWnd').find('input[type=checkbox]').removeAttr('checked'));
	jQuery('#ppsRfEditFieldsWnd').find('.ppsRfFieldSelectOptShell:not(#ppsRfFieldSelectOptShellExl)').remove();
}
function _ppsRfAddRegField(data, $wnd) {
	var errors = {};
	data.name = data.name ? data.name : false;
	if(data.name) {
		data.name = data.name.replace(/[^0-9a-z\-_+ ]/ig, '');
	}
	if(data.name && data.name != '') {
		if(data.label) {
			var cell = g_ppsRfCurrEditCell ? g_ppsRfCurrEditCell : null
			,	isNewCell = false;
			if(!cell) {
				isNewCell = true;
				var copyFromCell = jQuery('#ppoPopupRegFields .ppsRegFieldShell:not(.ppsRegFieldEmailShell):first');
				ppsCheckDestroyArea( copyFromCell );
				cell = copyFromCell.clone();
			}
			data.custom = 1;
			_ppsRfFillInRegFieldCell(cell, data);
			if(isNewCell) {
				cell.insertBefore( jQuery('#ppoPopupRegFields').find('#ppsRegAddFieldShell') );
				cell.find('input[type=checkbox][name*="enb"]')
					.attr('checked', 'checked')
					.change(function(){
						ppsSavePopupChanges();
					});
				ppsInitCustomCheckRadio( copyFromCell );
				ppsInitCustomCheckRadio( cell );
				_ppsRfInitFieldToolbar( cell );
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
function _ppsRfAddSelectOptRow(data) {
	var newCell = jQuery('#ppsRfFieldSelectOptShellExl').clone().removeAttr('id');
	newCell.hide();
	jQuery('#ppsRfFieldSelectOptsShell').append( newCell );
	newCell.slideDown( g_ppsAnimationSpeed );
	newCell.find('.ppsRfFieldSelectOptRemoveBtn').click(function(){
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
	jQuery('#ppsRfFieldSelectOptsShell input').each(function(){
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
function _ppsRfFillInRegFieldCell(cell, data) {
	cell.find('input').each(function(){
		//params[tpl][reg_fields][name][html] === ["params[tpl", "reg_fields", "name", "html]"] for example
		var nameArr = jQuery(this).attr('name').split('][');
		if(nameArr.length == 4) {
			nameArr[ 2 ] = data.name;
			jQuery(this).attr('name', nameArr.join(']['));
		}
	});
	cell.attr('data-name', data.name);
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][name]"]').val( data.name );
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][html]"]').val( data.html );
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][label]"]').val( data.label );
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][value]"]').val( data.value );
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][custom]"]').val( data.custom ? 1 : 0 );
	cell.find('[name="params[tpl][reg_fields]['+ data.name+ '][mandatory]"]').val( data.mandatory ? 1 : 0 );
	// Fill in options data
	cell.find('[name^="params[tpl][reg_fields]['+ data.name+ '][options]"]').remove();
	for(var key in data) {
		if(data[ key ] && data[ key ] != '') {
			if(key.indexOf('options[') !== -1) {
				var nameArr = key.split('][');
				if(nameArr.length == 2) {
					var optIter = parseInt(nameArr[0].substr(-1));
					if(!isNaN(optIter)) {
						var optParam = nameArr[1].substr(0, nameArr[1].length - 1);
						if(optParam && optParam != '') {
							cell.append('<input type="hidden" name="params[tpl][reg_fields]['+ data.name+ '][options]['+ optIter+ ']['+ optParam+ ']" value="'+ data[ key ]+ '" />');
						}
					}
				}
			}
		}
	}
	cell.find('.ppsRegFieldLabel').html( data.label );
}
function _ppsRfInitFieldToolbar(cell) {
	if(parseInt(cell.find('input[name*="custom"]').val())) {
		var toolbarHtml = jQuery('#ppsRfFieldToolbarExl').clone().removeAttr('id');
		cell.append( toolbarHtml );
		toolbarHtml.find('.ppsRfFieldRemoveBtn').click(function(){
			if(confirm(toeLangPps('Are you sure want to remove "'+ cell.find('.ppsRegFieldLabel').html()+ '" field?'))) {
				cell.slideUp(g_ppsAnimationSpeed, function(){
					cell.remove();
					ppsSavePopupChanges();
				});
			}
			return false;
		});
		toolbarHtml.find('.ppsRfFieldSettingsBtn').click(function(){
			_ppsRfClearEditForm();
			var $wnd = jQuery('#ppsRfEditFieldsWnd')
			,	name = cell.attr('data-name');	// cell.data('name') didn't worked correctly here

			$wnd.find('[name="name"]').val( name );
			$wnd.find('[name="html"]').val( cell.find('input[name*="html"]').val() ).trigger('change');
			$wnd.find('[name="label"]').val( cell.find('input[name*="label"]').val() );
			$wnd.find('[name="value"]').val( cell.find('input[name*="value"]').val() );
			parseInt(cell.find('input[name*="mandatory"]').val())
				? $wnd.find('[name="mandatory"]').attr('checked', 'checked')
				: $wnd.find('[name="mandatory"]').removeAttr('checked');

			var optionsInputs = cell.find('[name^="params[tpl][reg_fields]['+ name+ '][options]"]');
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
						_ppsRfAddSelectOptRow(selectOptsData[ i ]);
					}
				}
			}
			ppsCheckUpdateArea( $wnd );
			g_ppsRfCurrEditCell = cell;
			$wnd.dialog('open');
			return false;
		});
	}
}
