<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title">Oh no! Something is missing!</p>
	</div>
	<div class="page-bg">

		<div id="primary" class="site-content">
			<div id="content" role="main">

				<div class="entry-content event-content">
				<h1 style="text-align: center;">This page needs a little TLC...</h1>
				<p style="font-weight: bold; color: #8D171C; font-size: 18px; text-align: center; text-transform: uppercase;">Don't worry, Adam is fixing it.</p>
				<img style="border-bottom: 1px solid #F5AE40;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/404.jpg" />
				<div style="text-align: center; padding: 40px 0;">
				<a class="button"style="background-color: #8d0207; color: #fff; font-weight: bold; text-transform: uppercase; display: inline-block; margin-right: 30px !important;" href="/about-us/our-team/contact-us/">Report This Page</a>
				<a class="button post-link" style="background-color: #173880; color: #fff; font-weight: bold; text-transform: uppercase; display: inline-block;" href="/">Take Me Back!</a>
				</div>

				</div>
			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
<?php get_footer(); ?>
