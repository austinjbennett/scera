<?php

/**
* Template Name: Date
*/

// setup $_GET default to today
if (!isset($_GET['date'])) {
    $date = date('Ymd', strtotime('today'));
} else {
    $date = $_GET['date'];
}

$day = strtolower(date('D', strtotime($date)));

if($day === 'tue') {
  $day = 'tues';
} elseif($day === 'thu') {
  $day = 'thurs';
}

function scera_events_where( $where ) {

  $search = array("meta_key = 'event_dates__event_end_date'", "meta_key = 'event_dates__event_start_date'");
  $replace = array("meta_key LIKE 'event_dates_%_event_end_date'", "meta_key LIKE 'event_dates_%_event_start_date'");
  $where = str_replace($search, $replace, $where);
//var_dump($replace);exit;
  return $where;
}

add_filter('posts_where', 'scera_events_where');

$meta_query = array(
    array(
      'key' => 'event_dates__event_start_date',
      'compare' => '<=',
      'value' => $date,
    ),
    array(
      'key' => 'event_dates__event_end_date',
      'compare' => '>=',
      'value' => $date,
    // ),
    // array(
    //   'key' => 'days_of_the_week',
    //   'value' => $day,
    //   'compare' => 'IN'
    )
);


$args = array(
  'post_type' => array('events', 'movies'),
  'tax_query' => array(
    array(
        'taxonomy' => 'event-category',
        'field' => 'slug',
        'terms' => 'education',
        'operator' => 'NOT IN'
    ),
  ),
  'orderby' => 'meta_value_num',
  'order' => 'ASC',
  'meta_query' => $meta_query
);

$query = new WP_Query($args);

get_header(); ?>
  <div class="page-top-wrap">
    <img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
    <p class="page-top-title">Events for <?php echo date('F j, Y', strtotime($date)); ?></p>
  </div>
  <div class="page-bg event-bg">

    <div id="primary" class="site-content">
      <div id="content" role="main">

        <div class="entry-content event-content">

          <div class="events-list-wrapper">
            <div class="events-list">
            <h2 style="margin: 0;"><a style="padding: 25px 0 0; margin: 0 25px; text-decoration: none; color: #8D171C; display: block;" href="/"><< Back to Calendar</a></h2>
            <?php if($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>

                  <?php

                  $day_of_the_week = get_field('days_of_the_week'); ?>
                  
		<?php
                  if ((is_array($day_of_the_week) && in_array($day, $day_of_the_week)) || $day == $day_of_the_week || (get_post_type()==="movies" && $day!=="sun")) {

                      if ( 'movies' == get_post_type()){
                        get_template_part( 'list', 'movies');
                      } else {
                        get_template_part( 'list', 'events');
                      }

                  }

                  ?>

                <?php endwhile; // end of the loop. ?>
            <?php else: ?>

                <h2>Sorry no events today</h2>

            <?php endif; ?>

            </div>
          </div>

        </div><!-- .entry-content -->

      </div><!-- #content -->
    </div><!-- #primary -->

    <?php get_sidebar(); ?>
    <div style="clear:both;"></div>
  </div>
  <img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
  <script src="<?php bloginfo('template_directory'); ?>/js/tabs.js"></script>
<?php $GLOBALS["do_not_display_sponsors"] = true; ?>
<?php get_footer(); ?>
