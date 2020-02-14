<div class="arch-event">
  <div class="arch-safelane">
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

    <div class="event-date">
      <?php
        if(get_field('event_dates')):
          $count = 1;
          while(has_sub_field('event_dates')):

            if(get_sub_field('event_start_date')!=''):
              // the_sub_field('event_start_date');
              if($count > 1 ) {echo ' | ';}

              $date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
              $date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));

              if($date1 != $date2):

                if ( $date1->format('Y') == $date2->format('Y') ){
                  echo $date1->format('F d - ');
                } else {
                  echo $date1->format('F d, Y ');
                }
                echo $date2->format('F d, Y');
              else:
                echo $date1->format('F d, Y');
              endif;
              $count ++;

            endif;

          endwhile;
        endif;
      ?>
    </div>

    <?php // Short Description
    if( get_field('short_description') ):?>
        <div class="short-description">
          <?php the_field('short_description'); ?>
        </div><?php
    endif; ?>

    <div class="link-wrapper"><a class="post-link" href="<?php the_permalink(); ?>">See Details >></a></div>
    <?php if(get_field('buy_online_link')) { ?><a class="buy-link" href="<?php the_field('buy_online_link');?>">Buy Tickets >></a><?php } ?>
  </div>

  <div class="arch-offlane">
<?php if(get_field('poster_image')):
      $attachment_id = get_field('poster_image');
      $size = 'thumbnail';
      $image = wp_get_attachment_image_src($attachment_id, $size);
      ?><div class="piw"><a href="<?php the_permalink(); ?>"><img class="poster-image" src="<?php echo $image[0]; ?>" /></a></div><?php
endif; ?>

    <?php // Times
    if(get_field('times')): ?>
        <div class="times">
          <h4>Times:</h4>
          <div><?php
          while(has_sub_field('times'))
          {
            the_sub_field('time');?>&nbsp;<?php
          } ?>
          </div>
        </div>
    <?php endif; ?>
  </div>
</div>
