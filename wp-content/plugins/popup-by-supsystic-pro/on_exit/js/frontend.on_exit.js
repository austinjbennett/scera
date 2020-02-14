var g_ppsOnExitPopups = []
,	g_ppsOnExitInited = false;
jQuery(document).bind('ppsAfterPopupsInit', function(e, popups){
	_ppsInitOnExitPopups();
});
jQuery(document).ready(function(){
	setTimeout(function(){
		_ppsInitOnExitPopups();	// Additonal check - some jquery libraries rewrite custom "ppsAfterPopupsInit" event somehow
	}, 2000);
});
function _ppsInitOnExitPopups() {
	if(!g_ppsOnExitInited) {
		for(var i = 0; i < ppsPopups.length; i++) {
			if(ppsPopups[ i ].params.main.show_on == 'on_exit') {
				g_ppsOnExitPopups.push( ppsPopups[ i ] );
			}
		}
		if(g_ppsOnExitPopups.length) {
			_ppsBindOnExitEvent();
		}
		g_ppsOnExitInited = true;
	}
}
function _ppsBindOnExitEvent() {
	jQuery(document).mouseout(function(e){
		e = e ? e : window.event;
        var from = e.relatedTarget || e.toElement;
        if (!from || from.nodeName == "HTML") {
			if(e.clientY <= 0 && g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
        }
	});
	if(isMobilePps()) {
		// Bad, bad code, I know.........
		jQuery(window).on('beforeunload', function() {
			var havePopUps = false;
			if(g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						havePopUps = true;
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
			if(havePopUps) {
				return "Do you really want to close?";
			}
		});
		window.onbeforeunload = function() {
			var havePopUps = false;
			if(g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						havePopUps = true;
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
			if(havePopUps) {
				return "Do you really want to close?";
			}
		};
		jQuery(document).on('pagehide', function() {
			var havePopUps = false;
			if(g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						havePopUps = true;
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
			if(havePopUps) {
				return "Do you really want to close?";
			}
		});
		document.unload = function() {
			var havePopUps = false;
			if(g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						havePopUps = true;
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
			if(havePopUps) {
				return "Do you really want to close?";
			}
		};
		document.pagehide = function() {
			var havePopUps = false;
			if(g_ppsOnExitPopups && g_ppsOnExitPopups.length) {
				for(var i = 0; i < g_ppsOnExitPopups.length; i++) {
					if(!g_ppsOnExitPopups[ i ].is_visible && !g_ppsOnExitPopups[ i ].is_rendered) {
						havePopUps = true;
						ppsCheckShowPopup( g_ppsOnExitPopups[ i ] );
					}
				}
			}
			if(havePopUps) {
				return "Do you really want to close?";
			}
		};
	}
}