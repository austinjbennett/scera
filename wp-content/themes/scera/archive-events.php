<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
 */

get_header(); ?>
  <div class="page-top-wrap">
    <img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
    <p class="page-top-title">Events</p>
  </div>
  <div class="page-bg event-bg">

    <div id="primary" class="site-content">
      <div id="content" role="main">

        <div class="entry-content event-content">

        <?php $high = get_field( 'events_highlighted', 'options'); ?>
        <?php if($high): ?>

          <div class="highlighted-event">

              <?php foreach($high as $p): ?>
                <?php $terms = wp_get_post_terms($p->ID, 'event-category', array("fields" => "all"));?>
                <?php $post_terms = array();?>
                <?php foreach ($terms as $term) {
                    $post_terms[] = $term->slug;
                }?>
                <?php if(in_array('education', $post_terms)) { ?>
                    <div class="title-info <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
              <?php if (get_field( 'age_group' )) :?>
              <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ages-bg.png" />
              <p>+<?php the_field('age_group'); ?><span>AGES</span></p>
              <?php endif; ?>
              <h1><?php the_title(); ?></h1>
            </div>

            <div class="event-info" >
              <div class="event-date <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
                <?php

                if( get_field('semester') ) {
                  the_field('semester');
                  echo' - ';
                }

                if(get_field('event_dates')):
                  $count = 1;
                  while(has_sub_field('event_dates')):
                    // the_sub_field('event_start_date');
                    if($count > 1 ) {echo ' | ';}
                      $date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
                      $date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));
                      if($date1 != $date2):
                        if ( $date1->format('Y') == $date2->format('Y') ){
                          echo $date1->format('F d - ');
                        } else {
                          echo $date1->format('F d, Y - ');
                        }
                        echo $date2->format('F d, Y');
                      else:
                        echo $date1->format('F d, Y');
                      endif;
                      $count ++;
                  endwhile;
                endif;
                ?>
              </div>

              <?php if( get_field('poster_image', $p->ID) ):
              $attachment_id = get_field('poster_image', $p->ID);
              $size = "list-edu";
              $image = wp_get_attachment_image_src( $attachment_id, $size );
              ?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
              endif; ?>

              <?php // Long Description
              if( get_field('long_description') ): ?>
                <div class="long-description">
                  <?php the_field('long_description'); ?>
              <a class="button" style="background-color:#8d0207; color:#fff;" href="">Register</a>
              <a class="button post-link" style="background-color: #173880; color: #fff;" href="<?php echo get_permalink( $p->ID ); ?>">Details</a>
                </div><?php
              endif; ?>

            </div>

            <div class="further-info">

              <?php // Instructor
              if( get_field('instructor') ): ?>
                <div class="instructor">
                  <h4>Instructor:</h4>
                  <div><?php the_field('instructor'); ?></div>
                </div><?php
              endif; ?>

              <?php // Times
              if(get_field('times')): ?>
                <div class="times">
                  <h4>Times:</h4><?php
                  while(has_sub_field('times'))
                  {
                    echo'<div>';
                    the_sub_field('time');
                    echo'</div>';
                  } ?>
                </div>
              <?php endif; ?>

            <?php // Location
            if( get_field('location') == "Scera Center for the Arts" ): ?>
              <div class="location">
                <h4>Location:</h4>
                <?php the_field('location'); ?>
                <a href="https://maps.google.com?saddr=Current+Location&daddr=745+South+State+Street+Orem+UT+84058">Get Directions >></a>
              </div>
            <?php
            elseif( get_field('location') == "Scera Shell Outdoor Theater" ): ?>
              <div class="location">
                <h4>Location:</h4>
                <?php the_field('location'); ?>
                <a href="https://maps.google.com?saddr=Current+Location&daddr=669+South+State+Street+Orem+UT+84058">Get Directions >></a>
              </div>
            <?php
            elseif( get_field('location') == "Scera Art Studio" ): ?>
              <div class="location">
                <h4>Location:</h4>
                <?php the_field('location'); ?>
                <a href="https://maps.google.com?saddr=Current+Location&daddr=340+East+720+South+Orem+UT+84058">Get Directions >></a>
              </div>
            <?php
            elseif( get_field('location') == "Scera Shop" ): ?>
              <div class="location">
                <h4>Location:</h4>
                <?php the_field('location'); ?>
                <a href="https://maps.google.com?saddr=Current+Location&daddr=345+East+800+South+Orem+UT+84058">Get Directions >></a>
              </div>
            <?php
            else: ?>
              <div class="location">
                <h4>Location:</h4>
                <?php the_field('location'); ?>
                <a href="<?php the_field('directions'); ?>">Get Directions HERE >></a>
              </div>

            <?php
            endif; ?>

            </div><?php
                } else {
                    if( get_field('poster_image', $p->ID) ):
                      $attachment_id = get_field('poster_image', $p->ID);
                      $size = "full";
                      $image = wp_get_attachment_image_src( $attachment_id, $size );
                      ?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
                    endif; ?>

                    <div class="title-info">
                      <h1><?php echo get_the_title( $p->ID ); ?></h1>
                    </div>

                    <div class="event-info" >
                      <div class="event-date">
                        <?php
                        if(get_field('event_dates')):
                          $count = 1;
                          while(has_sub_field('event_dates')):
                            // the_sub_field('event_start_date');
                            if($count > 1 ) {echo ' | ';}
                              $date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
                              $date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));
                              if(get_sub_field('event_end_date')):
                                if ( $date1->format('Y') == $date2->format('Y') ){
                                  echo $date1->format('F d - ');
                                } else {
                                  echo $date1->format('F d, Y - ');
                                }
                                echo $date2->format('F d, Y');
                              else:
                                echo $date1->format('F d, Y');
                              endif;
                              $count ++;
                          endwhile;
                        endif;
                        ?>
                      </div>

                      <?php // Short Description
                      if( get_field('short_description', $p->ID) ):?>
                        <div class="short-description">
                          <?php the_field('short_description', $p->ID); ?>
                        </div><?php
                      endif; ?>
                    </div>

                    <div class="further-info">

                      <?php // Times
                      if(get_field('times', $p->ID)): ?>
                        <div class="times">
                          <h4>Times:</h4>
                          <div><?php
                          while(has_sub_field('times', $p->ID))
                          {
                            the_sub_field('time', $p->ID);
                          } ?>
                          </div>
                        </div>
                      <?php endif; ?>

                      <?php $buy_link = get_field('buy_online_link'); ?>
                      <?php if (! empty($buy_link)): ?>
                          <a class="button" style="background-color:#8d0207; color:#fff;" href="<?php echo $buy_link; ?>">BUY TICKETS</a>
                      <?php endif; ?>

                      <a class="post-link" href="<?php echo get_permalink( $p->ID ); ?>">See Details >></a>

                    </div><?php
                }?>

            <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="events-list-wrapper">
            <div class="events-list">

            <?php //Get latest start date

                function scera_events_where( $where ) {

                  $search = array("meta_key = 'event_dates__event_end_date'", "meta_key = 'event_dates__event_start_date'");
                  $replace = array("meta_key LIKE 'event_dates_%_event_end_date'", "meta_key LIKE 'event_dates_%_event_start_date'");
                  $where = str_replace($search, $replace, $where);
//var_dump($where); exit;
                  return $where;
                }

                add_filter('posts_where', 'scera_events_where');
                $date = date('Ymd');

                $args = array(
                  'post_type' => 'events',
                  'meta_key' => 'event_dates__event_start_date', //where filter adds a wildcard
                  'orderby' => 'meta_value_num',
                  'order' => 'ASC',
                  'posts_per_page' => -1,
                  'meta_query' => array(
                    array(
                      'key' => 'event_dates__event_end_date', //where filter adds a wildcard
                      'compare' => '>=',
                      'value' => $date,
                    )
                  )
                );

                $query = new WP_Query($args);
            ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>

              <?php if( has_term( 'education', 'event-category' )) {
                get_template_part( 'list', 'education');
              } else {
                get_template_part( 'list', 'events');
              } ?>

            <?php endwhile; // end of the loop. ?>

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
<?php get_footer(); ?>
