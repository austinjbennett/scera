// Close popup - after any action was done - main option
jQuery(document).bind('ppsAfterPopupsActionDone', function(e, params){
	if(params.popup.params.main.close_on == 'after_action') {
		var closeAfterTime = parseInt(params.popup.params.main.close_on_after_action_time);
		if(isNaN(closeAfterTime) || closeAfterTime < 0)
			closeAfterTime = 1;
		setTimeout(function(){
			ppsClosePopup( params.popup );
		}, closeAfterTime * 1000);	// Seconds to miliseconds
	}
});
// Close popup - after some time it was opened - main option
jQuery(document).bind('ppsAfterPopupsActionShow', function(e, popup){
	if(popup.params.main.close_on == 'after_time') {
		var closeAfterTimeValue = parseInt( popup.params.main.close_on_after_time_value );
		if(!isNaN(closeAfterTimeValue)) {
			setTimeout(function(){
				ppsClosePopup( popup );
			}, closeAfterTimeValue * 1000);
		}
	}
});
// Opend popup - after user scrolled down to the bottom or, if there are no scroll - show immediately - main option
var g_ppsOnPageBottomPopups = []
,	g_ppsOnInactivePopups = {}
,	g_ppsOnInactiveIdleTime = 0;
jQuery(document).bind('ppsAfterPopupsInit', function(e, popups){
	var bindInactivePopups = false;
	for(var i = 0; i < ppsPopups.length; i++) {
		if(ppsPopups[ i ].params.main.show_on == 'page_bottom') {
			g_ppsOnPageBottomPopups.push( ppsPopups[ i ] );
		}
		if(ppsPopups[ i ].params.main.show_on == 'after_inactive') {
			var inactiveTimeValue = parseInt( ppsPopups[ i ].params.main.show_on_after_inactive_value );
			if(inactiveTimeValue) {
				if(!g_ppsOnInactivePopups[ inactiveTimeValue ]) {
					g_ppsOnInactivePopups[ inactiveTimeValue ] = [];
				}
				g_ppsOnInactivePopups[ inactiveTimeValue ].push( ppsPopups[ i ] );
				bindInactivePopups = true;
			}
		}
		if(ppsPopups[ i ].params.main.show_on == 'after_comment') {
			ppsCheckShowPopup( ppsPopups[ i ] );
		}
		if(ppsPopups[ i ].params.main.show_on == 'link_follow') {
			var hashParams = toeGetHashParams();
			if(hashParams && hashParams.length && toeInArray('ppsShowPopUp_'+ ppsPopups[ i ].id, hashParams) !== -1) {
				if(!_ppsPopupBindDelay(ppsPopups[ i ], 'show_on_link_follow_delay', 'show_on_link_follow_enb_delay')) {
					ppsCheckShowPopup( ppsPopups[ i ] );
				}
			}
		}
		// Build-in page content
		if(ppsPopups[ i ].params.main.show_on == 'build_in_page') {
			_ppsShowBuildInPopup( ppsPopups[ i ] );
		}
		// AdBlock detection
		if(ppsPopups[ i ].params.main.show_on == 'adblock_detected') {
			if(typeof(g_ppsAdBlockDisabled) === 'undefined') {
				ppsCheckShowPopup( ppsPopups[ i ] );
			}
		}
	}
	if(g_ppsOnPageBottomPopups.length) {
		_ppsBindPageBottomEvent();
	}
	if(bindInactivePopups) {
		_ppsBindInactivePopups();
	}
});
function _ppsBindPageBottomEvent() {
	var docHt = jQuery(document).height()
	,	wndHt = jQuery(window).height()
	,	renderClb = function(){
		
		for(var i = 0; i < g_ppsOnPageBottomPopups.length; i++) {
			if(!g_ppsOnPageBottomPopups[ i ].is_visible && !g_ppsOnPageBottomPopups[ i ].is_rendered) {
				ppsCheckShowPopup( g_ppsOnPageBottomPopups[ i ] );
			}
		}
	},	checkScrollCheckClb = function(){
		
		if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
			renderClb();
		}
	};
	if(docHt > wndHt) {
		jQuery(window).bind('scroll', checkScrollCheckClb);
		jQuery(window).bind('resize', checkScrollCheckClb);
		jQuery(window).bind('orientationchange', checkScrollCheckClb);
	} else {
		renderClb();
	}
}
function _ppsBindInactivePopups() {
	jQuery(this).mousemove(function(){
        g_ppsOnInactiveIdleTime = 0;
    });
    jQuery(this).keypress(function(){
        g_ppsOnInactiveIdleTime = 0;
    });
	setInterval(timerIncrement, 1000); // 1 second
}
function timerIncrement() {
    g_ppsOnInactiveIdleTime += 1;	// Increment for 1 second
	if(g_ppsOnInactivePopups 
		&& g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ] 
		&& g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ].length
	) {
		for(var i = 0; i < g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ].length; i++) {
			if(!g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ][ i ].is_visible && !g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ][ i ].is_rendered) {
				ppsCheckShowPopup( g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ][ i ] );
			}
		}
		delete g_ppsOnInactivePopups[ g_ppsOnInactiveIdleTime ];
	}
}
function _ppsShowBuildInPopup( popup ) {
	var shell = ppsGetPopupShell( popup )
	,	buildInShell = jQuery('.ppsBuildInPopup[data-id="'+ popup.id+ '"]');
	popup._notResizeHeight = true;
	buildInShell.html('').append( shell );
	shell.css({
		'top': 0
	,	'left': 0
	,	'position': 'static'
	,	'z-index': 1
	});
	buildInShell.css({
		'position': 'relative'
	,	'padding': '0'
	,	'margin': '0'
	,	'display': 'inline-block'
	});
	ppsCheckShowPopup(popup, {
		ignoreBgOverlay: true
	,	ignorePosition: true
	});
}
