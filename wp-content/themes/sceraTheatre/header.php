<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Scera Theatre <?php wp_title(); ?></title>

    <!-- LOAD GOOGLE FONT -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,600" rel="stylesheet">  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Oswald" rel="stylesheet">

    <!-- FONT AWESOME ICONS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- LATEST VERSION OF JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <!-- META TAG FOR DO NOT LIE -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><!--', maximum-scale=1' prevents users zooming on mobile-->

    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

    <!-- STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css">
    <!-- BOOTSTRAP -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <!-- MAIN CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/main.css">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!--     <header class="keepOpen">
            <div class="row justify-content-between clearfix">
                <div class="column small-2">
                    <ul class="icons">
                        <li class="icon search">
                            <form id="searchForm" class="form-inline searchArea" method="get" action="/">
                                <input class="sfield" type="search" name="s" placeholder="Search">
                                <i class="fas fa-search"></i>
                            </form>
                        </li>
                        <li class="icon account">
                            <i class="fas fa-user"></i>
                            <ul class="accDD hide">
                                <?php
                                if (is_user_logged_in()) {
                                        echo
                                        '<li>
                                            <a href="/my-account">Account</a>
                                        </li>
                                        <li>
                                            <a href="/logout">Logout</a>
                                        </li>';
                                } else {
                                        echo
                                        '<li>
                                            <a href="/login">Login</a>
                                        </li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="icon cart">
                            <i class="fas fa-shopping-cart"></i>
                        </li>
                        <li class="icon tickets">
                            <i class="fas fa-ticket-alt"></i>
                        </li>
                    </ul>
                </div>
            </div>
    </header> -->
    <header class="main-header">
        <div class="header-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/img/scera_logo.png" alt="Scera"/>
        </div>
        <div class="shortstack" id="nav-toggle">
            <span></span>
            <span></span>
        </div>
        <div id="nav-wrapper">
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
                'container'      => 'nav',
            );
            echo strip_tags(wp_nav_menu($menu_parameters), '<a>, <nav>');
            ?>
        </div>
<!--        <nav class="right-nav">-->
<!--            <a href="#"><i class="fas fa-ticket-alt fa-lg"></i></a>-->
<!--            <a href="#"><i class="fas fa-shopping-bag fa-lg"></i></a>-->
<!--        </nav>-->
<!--        <div class="navWrap2">-->
<!--            <div class="navCol navL">-->
<!--                <div class="hbMenu" id="hbMenu" onclick="menuClick(this)">-->
<!--                    <div class="bar1"></div>-->
<!--                    <div class="bar2"></div>-->
<!--                    <div class="bar3"></div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="navCol navM">-->
<!--                <div class="navLogo">-->
<!--                    <a href="/" class="templateLink">-->
<!--                        <p>SCERA</p>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="navCol navR">-->
<!--                <ul class="icons">-->
<!--                    <li class="icon tickets">-->
<!--                        <i class="fas fa-ticket-alt fa-lg icoLgPlus"></i>-->
<!--                    </li>-->
<!--                    <li class="icon cart">-->
<!--                        <i class="fas fa-shopping-bag fa-lg icoLgPlus"></i>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->

        <!-- <div class='searchWrap'>
            <form id="searchForm" class="form-inline searchArea" method="get" action="/">
                <input class="sfield" type="search" name="s" placeholder="Search SCERA.org">
                <i class="fas fa-search fa-lg"></i>
            </form>
        </div> -->

    </header>
<!--    <div class='behindNav'></div>-->
    <!-- <div class="container"> -->
