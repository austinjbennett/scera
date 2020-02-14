<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?>
    <span class="price-wrap" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

        <p class="price"><?php echo $product->get_price_html(); ?></p>

        <meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
        <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
        <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

    </span>
</h1>