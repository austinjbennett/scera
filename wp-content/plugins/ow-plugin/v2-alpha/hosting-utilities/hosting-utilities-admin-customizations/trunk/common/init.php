<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined('HU_COMMON_FUNCTIONS_LOADED') && ! defined('HU_ROADMAP_AND_OPTIONS_VERSION')){
    include_once 'helper_functions.php';
    include_once 'menu.php';
    define('HU_COMMON_FUNCTIONS_LOADED', true);
}
