jQuery(document).bind('ppsResize', function(e, params) {
	params = params || {};
	if(parseInt(params.popup.params.tpl.enb_layered)) {
		// For case if it was not defined in admin area for now
		params.popup.params.tpl.layered_pos = params.popup.params.tpl.layered_pos ? params.popup.params.tpl.layered_pos : 'top';
		_ppsLayeredPositionPopup( params );
		params.shell.positioned_outside = true;
	}
});
jQuery(document).bind('ppsBeforePopupsInit', function(e, popups){
	for(var i = 0; i < ppsPopups.length; i++) {
		if(parseInt(ppsPopups[ i ].params.tpl.enb_layered)) {
			ppsPopups[ i ].ignore_background = true;
		}
	}
});
function _ppsLayeredPositionPopup(params) {
	params = params || {};
	var shell = params.shell ? params.shell : ppsGetPopupShell( params.popup );
	if(shell) {
		var shellWidth = shell.width()
		,	shellHeight = shell.height()
		,	wndWidth = params.wndWidth ? params.wndWidth : jQuery(window).width()
		,	wndHeight = params.wndHeight ? params.wndHeight : jQuery(window).height()
		,	newPos = {}
		,	d = 0;	// Delta
		switch(params.popup.params.tpl.layered_pos) {
			// Top line
			case 'top_left':
				newPos.top = d;
				newPos.left = d;
				break;
			case 'top':
				newPos.top = d;
				newPos.left = (wndWidth - shellWidth) / 2;
				break;
			case 'top_right':
				newPos.top = d;
				newPos.right = d;
				break;
			// Center line
			case 'center_left':
				newPos.top = (wndHeight - shellHeight) / 2;;
				newPos.left = d;
				break;
			case 'center':
				newPos.top = (wndHeight - shellHeight) / 2;;
				newPos.left = (wndWidth - shellWidth) / 2;
				break;
			case 'center_right':
				newPos.top = (wndHeight - shellHeight) / 2;;
				newPos.right = d;
				break;
			// Bottom line
			case 'bottom_left':
				newPos.bottom = d;
				newPos.left = d;
				break;
			case 'bottom':
				newPos.bottom = d;
				newPos.left = (wndWidth - shellWidth) / 2;
				break;
			case 'bottom_right':
				newPos.bottom = d;
				newPos.right = d;
				break;
		}
		shell
			.css( newPos )
			.addClass('ppsLayered_'+ params.popup.params.tpl.layered_pos);
	} else {
		console.log('CAN NOT FIND POPUP SHELL TO REPOSITION!');
	}
}