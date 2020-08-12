<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Scera Theatre <?php wp_title(); ?></title>

    <!-- FONT AWESOME ICONS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- LATEST VERSION OF JQUERY -->
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->

    <!-- META TAG FOR DO NOT LIE -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><!--', maximum-scale=1' prevents users zooming on mobile-->

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <!-- STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/main.css">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="body">
    <header class="main-header bg-blue">
        <div class="header-logo">
            <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/img/scera-logo-gold.png" alt="Scera"/></a>
        </div>
        <div class="shortstack" id="nav-toggle">
            <span></span>
	        <span></span>
            <span></span>
        </div>
        <div id="nav-wrapper" class="bg-blue">
            <div class='searchWrap'>
                <form id="searchForm" class="form-inline searchArea" method="get" action="/">
                    <input class="sfield" type="search" name="s" placeholder="Search SCERA.org">
                    <i class="fas fa-search fa-lg"></i>
                </form>
            </div>
            <?php
            $menu_parameters = array(
                'menu_id'        => 'menu-main-header-navigation',
                'menu_class'     => '',
                'theme_location' => 'headerNav',
                'items_wrap'     => '<nav id="%1$s" class="%2$s">%3$s</nav>',
                'echo'           => false,
            );
            echo strip_tags(wp_nav_menu($menu_parameters), '<a>, <nav>');
            ?>
        </div>

    </header>