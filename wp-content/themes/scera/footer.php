<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php if(is_front_page()) {
    $posts = get_field('sponsors', 'options');
} else {
    $posts = get_field('sponsors');
} ?>

<?php if( $posts && ! (isset($GLOBALS["do_not_display_sponsors"]) && $GLOBALS["do_not_display_sponsors"] === true) ): ?>
    <div class="sponsors">
<!--     <h4>This page sponsored by:
        <?php foreach ( $posts as $post ) : ?>
            <?php setup_postdata($post); ?>
            <?php echo' '; ?>
            <a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
        <?php endforeach; ?>
    </h4> -->
        <ul>
        <?php foreach ( $posts as $post ) : ?>
            <?php setup_postdata($post); ?>
            <li class="sponsored-image"><a href="<?php the_field('sponsor_site'); ?>" target="_blank"><?php the_post_thumbnail( 'full' ); ?></a></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

	</div><!-- #main .wrapper -->
</div>
</div><!-- #page -->
</div>
</div>

<footer id="colophon" role="contentinfo">
	<div class="site-info">
		<?php do_action( 'twentytwelve_credits' ); ?>

	</div><!-- .site-info -->
</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
