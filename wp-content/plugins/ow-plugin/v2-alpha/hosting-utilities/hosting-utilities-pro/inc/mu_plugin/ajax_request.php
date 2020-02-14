<?php

/* Stop all plugins from loading */
add_filter( 'option_active_plugins', function(){ return []; } );
