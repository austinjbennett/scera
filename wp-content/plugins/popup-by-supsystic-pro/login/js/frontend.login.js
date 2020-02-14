jQuery(document).bind('ppsAfterPopupsInit', function(e, popups){
	for(var i = 0; i < ppsPopups.length; i++) {
		if(ppsPopups[ i ].params.tpl.enb_login) {
			_ppsBindPopupLogin( ppsPopups[ i ] );
		}
		if(ppsPopups[ i ].params.tpl.enb_reg) {
			_ppsBindPopupRegistration( ppsPopups[ i ] );
		}
	}
});
function _ppsBindPopupLogin( popup ) {
	var $shell = ppsGetPopupShell( popup );
	$shell.find('.ppsLoginForm').submit(function(){
		var $submitBtn = jQuery(this).find('input[type=submit]')
		,	self = this
		,	$msgEl = jQuery(this).find('.ppsLoginMsg');
		$submitBtn.attr('disabled', 'disabled');
		jQuery(this).sendFormPps({
			msgElID: $msgEl
		,	onSuccess: function(res){
				jQuery(self).find('input[type=submit]').removeAttr('disabled');
				if(!res.error) {
					var $parentShell = jQuery(self).parents('.ppsSubscribeShell')
					,	$closeInsideBtn = jQuery(self).find('.ppsPopupClose');	// Close button can be inside form - we can't remove it, because in this case user will not be able to close PopUp
					if($closeInsideBtn && $closeInsideBtn.size()) {
						$closeInsideBtn.appendTo( $parentShell );
					}
					$msgEl.appendTo( $parentShell );
					jQuery(self).animateRemovePps( 300 );
					_ppsPopupSetActionDone(popup, 'login');
					if(res.data && res.data.redirect) {
						toeRedirect(res.data.redirect, parseInt(popup.params.tpl.login_redirect_new_wnd));
					} else {
						toeReload();
					}
				}
			}
		});
		return false;
	});
}
function _ppsBindPopupRegistration( popup ) {
	var $shell = ppsGetPopupShell( popup );
	$shell.find('.ppsRegForm').submit(function(){
		var $submitBtn = jQuery(this).find('input[type=submit]')
		,	self = this
		,	$msgEl = jQuery(this).find('.ppsRegMsg');
		$submitBtn.attr('disabled', 'disabled');
		jQuery(this).sendFormPps({
			msgElID: $msgEl
		,	onSuccess: function(res){
				jQuery(self).find('input[type=submit]').removeAttr('disabled');
				if(!res.error) {
					var $parentShell = jQuery(self).parents('.ppsSubscribeShell')
					,	$closeInsideBtn = jQuery(self).find('.ppsPopupClose');	// Close button can be inside form - we can't remove it, because in this case user will not be able to close PopUp
					if($closeInsideBtn && $closeInsideBtn.size()) {
						$closeInsideBtn.appendTo( $parentShell );
					}
					$msgEl.appendTo( $parentShell );
					jQuery(self).animateRemovePps( 300 );
					_ppsPopupSetActionDone(popup, 'registration');
					if(res.data && res.data.redirect) {
						toeRedirect(res.data.redirect, parseInt(popup.params.tpl.reg_redirect_new_wnd));
					}
				}
			}
		});
		return false;
	});
}
