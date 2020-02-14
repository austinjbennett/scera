<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<?php $queried_object = get_queried_object(); ?>
		<h1 class="page-top-title"><?php echo $queried_object->name;?></h1>
		<?php do_action('scera_breadcrumbs'); ?>
	</div>
	<div class="page-bg event-bg">

			<div id="primary" class="site-content">
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>


		<?php if(get_field( 'description_title', $queried_object )) : ?>

		<div class="category-description">

		<?php if(get_field( 'description_image', $queried_object )) : ?>
			<img src="<?php the_field( 'description_image', $queried_object ); ?>" />
		<?php endif; ?>

			<div class="category-description-wrap">

			<h1><?php the_field( 'description_title', $queried_object ); ?></h1>

			</div>

		</div>

		<?php endif; ?>

		<?php $high = get_field( 'highlighted_product', 'options'); ?>
		<?php if($high): ?>

			<div class="highlighted-product">

			<?php foreach($high as $p): ?>

				<?php $pro = get_product($p->ID); ?>

				<a class="category-image" href="<?php echo get_the_permalink($p->ID); ?>">

					<?php echo get_the_post_thumbnail($p->ID, 'shop_catalog'); ?>

				</a>

				<div class="category-content">

					<h3><?php echo get_the_title($p->ID); ?></h3>
					<?php global $product; ?>

					<?php if ( $price_html = $pro->get_price_html() ) : ?>
						<span class="price"><?php echo $price_html; ?></span>
					<?php endif; ?>

					<?php if ( ! $p->post_excerpt ) return; ?>
						<div itemprop="description">
							<?php echo apply_filters( 'woocommerce_short_description', $p->post_excerpt ) ?>
						</div>

					<?php echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
						esc_url( $pro->add_to_cart_url() ),
						esc_attr( $pro->id ),
						esc_attr( $pro->get_sku() ),
						$pro->is_purchasable() ? 'add_to_cart_button' : '',
						esc_attr( $pro->product_type ),
						esc_html( $pro->add_to_cart_text() )
					),
					$pro ); ?>
				</div>

			<?php endforeach; ?>

			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
		<div class="product-list-wrapper">
			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>
		</div>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>
		</div><!-- #primary -->
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
	</div><!-- .page-bg -->
<img class="page-bottom" src="/wp-content/themes/scera/images/page-bottom.png">
<?php get_footer( 'shop' ); ?>