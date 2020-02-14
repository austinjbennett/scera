jQuery(document).ready(function(){
	var tblId = 'ppsAbPopupTbl';
	jQuery('#'+ tblId).jqGrid({ 
		url: ppsAbTblDataUrl
	,	datatype: 'json'
	,	autowidth: true
	,	shrinkToFit: true
	,	colNames:[toeLangPps('ID'), toeLangPps('Label'), toeLangPps('Views'), toeLangPps('Unique Views'), toeLangPps('Actions'), toeLangPps('Conversion'), toeLangPps('Active')]
	,	colModel:[
			{name: 'id', index: 'id', searchoptions: {sopt: ['eq']}, width: '30', align: 'center'}
		,	{name: 'label', index: 'label', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'views', index: 'views', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'unique_views', index: 'unique_views', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'actions', index: 'actions', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'conversion', index: 'conversion', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'active', index: 'active', searchoptions: {sopt: ['eq']}, align: 'center'}
		]
	,	rowNum: 10
	,	rowList: [10, 20, 30, 1000]
	,	pager: '#'+ tblId+ 'Nav'
	,	sortname: 'id'
	,	viewrecords: true
	,	sortorder: 'desc'
	,	jsonReader: { repeatitems : false, id: '0' }
	,	caption: toeLangPps('Current PopUp')
	,	height: '100%' 
	,	emptyrecords: toeLangPps('You have no PopUps for now.')
	,	multiselect: true
	,	onSelectRow: function(rowid, e) {
			var tblId = jQuery(this).attr('id')
			,	selectedRowIds = jQuery('#'+ tblId).jqGrid ('getGridParam', 'selarrrow')
			,	totalRows = jQuery('#'+ tblId).getGridParam('reccount')
			,	totalRowsSelected = selectedRowIds.length;
			if(totalRowsSelected) {
				jQuery('#ppsAbPopupRemoveGroupBtn').removeAttr('disabled');
				if(totalRowsSelected == totalRows) {
					jQuery('#cb_'+ tblId).prop('indeterminate', false);
					jQuery('#cb_'+ tblId).attr('checked', 'checked');
				} else {
					jQuery('#cb_'+ tblId).prop('indeterminate', true);
				}
			} else {
				jQuery('#ppsAbPopupRemoveGroupBtn').attr('disabled', 'disabled');
				jQuery('#cb_'+ tblId).prop('indeterminate', false);
				jQuery('#cb_'+ tblId).removeAttr('checked');
			}
			ppsCheckUpdateArea('#gview_'+ tblId);
		}
	,	gridComplete: function(a, b, c) {
			var tblId = jQuery(this).attr('id');
			jQuery('#ppsAbPopupRemoveGroupBtn').attr('disabled', 'disabled');
			jQuery('#cb_'+ tblId).prop('indeterminate', false);
			jQuery('#cb_'+ tblId).removeAttr('checked');
			if(jQuery('#'+ tblId).jqGrid('getGridParam', 'records'))	// If we have at least one row - allow to clear whole list
				jQuery('#ppsAbClearBtn').removeAttr('disabled');
			else
				jQuery('#ppsAbClearBtn').attr('disabled', 'disabled');
			// Custom checkbox manipulation
			ppsInitCustomCheckRadio('#gview_'+ tblId);
			ppsCheckUpdateArea('#gview_'+ tblId);
		}
	,	loadComplete: function() {
			var tblId = jQuery(this).attr('id');
			if (this.p.reccount === 0) {
				jQuery('#gbox_'+ tblId).hide();
				jQuery('.'+ tblId+ 'Btn').hide();
				jQuery('#'+ tblId+ 'EmptyMsg').show();
			} else {
				jQuery('#gbox_'+ tblId).show();
				jQuery('.'+ tblId+ 'Btn').show();
				jQuery('#'+ tblId+ 'EmptyMsg').hide();
			}
		}
	});
	jQuery('#'+ tblId+ 'NavShell').append( jQuery('#'+ tblId+ 'Nav') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-table td:first').remove();
	jQuery('#'+ tblId+ '').jqGrid('navGrid', '#'+ tblId+ 'Nav', {edit: false, add: false, del: false});
	// Disabled sticky for this table header
	jQuery('#gview_'+ tblId).find('.ui-jqgrid-hdiv').addClass('sticky-ignore');
	jQuery('#cb_'+ tblId+ '').change(function(){
		jQuery(this).attr('checked') 
			? jQuery('#ppsAbPopupRemoveGroupBtn').removeAttr('disabled')
			: jQuery('#ppsAbPopupRemoveGroupBtn').attr('disabled', 'disabled');
		ppsCheckUpdateArea('#gview_'+ tblId);
	});
	jQuery('#ppsAbPopupRemoveGroupBtn').click(function(){
		if(jQuery(this).attr('disabled')) return false;
		var selectedRowIds = jQuery('#ppsAbPopupTbl').jqGrid ('getGridParam', 'selarrrow')
		,	listIds = [];
		for(var i in selectedRowIds) {
			var rowData = jQuery('#ppsAbPopupTbl').jqGrid('getRowData', selectedRowIds[ i ]);
			listIds.push( rowData.id );
		}
		var confirmMsg = listIds.length > 1
			? toeLangPps('Are you sur want to remove '+ listIds.length+ ' Test PopUps?')
			: toeLangPps('Are you sure want to remove "'+ ppsGetGridColDataById(listIds[0], 'label', 'ppsAbPopupTbl')+ '" Test PopUp?')
		if(confirm(confirmMsg)) {
			jQuery.sendFormPps({
				btn: this
			,	data: {mod: 'popup', action: 'removeGroup', listIds: listIds}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#ppsAbPopupTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	jQuery('#ppsAbClearBtn').click(function(){
		if(confirm(toeLangPps('Clear whole Test list?'))) {
			jQuery.sendFormPps({
				btn: this
			,	data: {mod: 'ab_testing', action: 'clear', base_id: ppsPopup.id}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#ppsAbPopupTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	ppsAbInitAddNewDialog();
	jQuery(document).bind('ppsPopupAbTesting_tabSwitch', function(){
		jQuery('#ppsAbPopupTbl').setGridWidth( jQuery('#gbox_ppsAbPopupTbl').parent().width(), true ); //Back to original width
	});
});
function ppsAbInitAddNewDialog() {
	var $container = jQuery('#ppsAbAddNewWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 460
	,	height: 180
	,	buttons:  {
			OK: function() {
				jQuery('#ppsAbAddNewForm').submit();
			}
		,	Cancel: function() {
				$container.dialog('close');
			}
		}
	});
	jQuery('.ppsAbAddNew').click(function(){
		$container.dialog('open');
		return false;
	});
	jQuery('#ppsAbAddNewForm').submit(function(){
		jQuery(this).sendFormPps({
			msgElID: 'ppsAbAddNewMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsAbAddNewForm [name=label]').val('');
					jQuery('#ppsAbPopupTbl').trigger( 'reloadGrid' );
					$container.dialog('close');
				}
			}
		});
		return false;
	});
}
