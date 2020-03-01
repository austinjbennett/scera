<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
<link rel="stylesheet" href="/wp-content/themes/scera/woocommerce-FlexSlider/flexslider.css" />

<?php date_default_timezone_set('US/Mountain'); ?>

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>


<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '283932068677653');
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=283932068677653&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->


</head>

<body <?php body_class(); ?>>
<div class="top-curtain"></div>
<div class="left-curtain">
<div class="right-curtain">
<div id="page" class="hfeed site">
<div class="page-inner">
<!--	<div class="totop">-->
<!--		<a href="#">-->
<!--			<img src="--><?php //bloginfo('template_directory'); ?><!--/images/backtotop.png" />-->
<!--		</a>-->
<!--	</div>-->
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<a href="<?php bloginfo( 'url' ); ?>"><img class="logo" src="<?php bloginfo( 'template_directory' ); ?>/images/logo.png" /></a>
		</hgroup>

		<div class="social-icons">
			<a class="facebook" href="<?php the_field( 'facebook', 'options' ); ?>"></a>
			<a class="pinterest" href="<?php the_field( 'pinterest', 'options' ); ?>"></a>
			<a class="twitter" href="<?php the_field( 'twitter', 'options' ); ?>"></a>
			<a class="instagram" href="<?php the_field( 'instagram', 'options' ); ?>"></a>
		</div>

		<div class="top-header">
			<?php get_search_form(); ?>
			<div class="lights">
				<img class="h-call" src="<?php bloginfo( 'template_directory' ); ?>/images/h-info.png">
				<a href="<?php the_field( 'header_tickets', 'options' ); ?>"><img class="h-buy" src="<?php bloginfo( 'template_directory' ); ?>/images/h-buy.png" alt="" /></a>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'nav-menu',
				)
			);
			?>
		</nav><!-- #site-navigation -->
		<script>
		jQuery(document).ready(function(){
			jQuery('ul.sub-menu').wrap('<div class="sub-menu-wrapper" />');
		});
		</script>
		<?php
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		<?php endif; ?>
	</header><!-- #masthead -->

	<div id="main" class="wrapper">
