<?php

/**
* Template Name: Multi Event
*/

add_filter('body_class','add_multi_class');
function add_multi_class($classes) {
  $classes[] = 'multi-event';
  return $classes;
}

get_header(); ?>
  <div class="page-top-wrap">
    <img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
    <p class="page-top-title"><?php the_title(); ?></p>
  </div>
  <div class="page-bg">

    <div id="primary" class="site-content">
      <div id="content" role="main">

        <?php while ( have_posts() ) : the_post(); ?>
        <div class="entry-content">
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if(get_field('page_heading')){ ?><h1><?php the_field('page_heading'); ?></h1><?php } ?>
            <?php if(get_field('subheading')){ ?><h2><?php the_field('subheading'); ?></h2><?php } ?>
            <?php the_content(); ?>
          </article><!-- #post -->
        </div>
        <?php endwhile; // end of the loop. ?>

        <?php $events = get_field('multi_event');
          if($events):
        ?>
          <ul>
          <?php foreach( $events as $e) : ?>
            <li><a href="<?php echo get_permalink($e->ID); ?>"><?php echo get_the_title($e->ID); ?></a></li>
          <?php endforeach; ?>
          </ul>
        <?php endif; ?>

      </div><!-- #content -->
    </div><!-- #primary -->

    <?php get_sidebar(); ?>
    <div style="clear:both;"></div>
  </div>
  <img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
<?php get_footer(); ?>