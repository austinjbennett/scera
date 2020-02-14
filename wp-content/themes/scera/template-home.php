<?php
/**
 * Template Name: Home
 */

get_header(); ?>


    <div id="primary" class="home-content">
        <div id="content" role="main">

            <?php while (have_posts()) : the_post(); ?>

            <?php echo do_shortcode('[accorss]'); ?>

            <div class="pre-block-group">
                <div class="home-donate">
                    <span class="donate-title">Donate now!</span>
                    <p><?php the_field('donate_text', 'options'); ?></p>
                    <a class="home-donate-button" href="<?php the_field('donate_link', 'options'); ?>">donate</a>
                </div>
                <div class="home-update">
                <span class="update-title">Want updates?</span>
                <span class="upate-text"></span>
                <?php echo do_shortcode('[formidable id=3]'); ?>
                </div>
            </div>

            <div class="first-block-group">
                <?php
                if( have_rows('event_blocks', 'options')):
                    while ( have_rows('event_blocks','options') ) : the_row();
                        ?><div class="first-block"><?php
                            if(get_sub_field('image','options')) {
                                ?> <img class="main-image" src="<?php the_sub_field('image','options'); ?>" /><?php
                            }
                            if(get_sub_field('logo','options')) {
                                ?> <img class="logo-image" src="<?php the_sub_field('logo','options'); ?>" /><?php
                            }
                            ?><div class="first-text"><?php
                                if(get_sub_field('text','options')) {?><p><?php the_sub_field('text','options');?></p><?php }
                                if(get_sub_field('button_text','options')) {
                                    ?><a class="button" style="background-color:#8d0207; color:#fff;" href="<?php the_sub_field('button_link','options'); ?>">
                                        <?php the_sub_field('button_text','options'); ?>
                                    </a><?php
                                }
                            ?></div><?php
                        ?></div><?php
                    endwhile;
                endif; ?>
                <div class="first-block home-calendar">
                    <h4 class="calendar-title">Event Calendar</h4>
                    <div id="calendar"><?php include_once('php-calendar.php'); ?></div>
                    <div class="calendar-nav"><a id="month-dec" href="#"></a><span id="month-name"><?php echo date('M Y'); ?></span><a id="month-inc" href="#"></a></div>
                    <script>
                    jQuery(document).ready(function($){

                        var m = new Date();
                        var n = m.getMonth();
                        var y = m.getFullYear();
                        var months = new Array(12);
                        var date = '20140208';
                        months[0] = 'Jan';
                        months[1] = 'Feb';
                        months[2] = 'Mar';
                        months[3] = 'Apr';
                        months[4] = 'May';
                        months[5] = 'Jun';
                        months[6] = 'Jul';
                        months[7] = 'Aug';
                        months[8] = 'Sept';
                        months[9] = 'Oct';
                        months[10] = 'Nov';
                        months[11] = 'Dec';

                        function doAJAX() {
                            $('#month-name').text(months[n] + ' ' + y);

                            $.ajax({
                                url: '<?php echo get_stylesheet_directory_uri(); ?>/php-calendar.php',
                                type: 'post',
                                data: {'month': n, 'year': y },
                                success: function(text) {
                                    response = text;
                                    $('#calendar').html(response);
                                }
                            });
                        }

                        $('#month-dec').on('click', function(e) {
                            e.preventDefault();
                            n--;
                            if(n < 0) {
                                n = 11;
                                y--;
                            }
                            doAJAX();

                        });
                        $('#month-inc').on('click', function(e) {
                            e.preventDefault();
                            n++;
                            if(n > 11) {
                                n = 0;
                                y++;
                            }
                            doAJAX();
                        });
                    });
                    </script>
                </div>
            </div>

            <div class="second-block-group">
                <?php
                if( have_rows('lower_blocks','options') ):
                    while ( have_rows('lower_blocks','options') ) : the_row();
                        ?>

                        <div class="sb-wrap-two">
                         <div class="sb-wrap-one">
                          <div class="second-block">
                           <?php
                            if(get_sub_field('title','options')) {?><h2><?php the_sub_field('title','options');?></h2><?php }
                            if(get_sub_field('image', 'options')) {?><div class="image-wrap"><img src="<?php the_sub_field('image', 'options'); ?>" /></div><?php }
                            if(get_sub_field('text','options')) {?><p><?php the_sub_field('text','options');?></p><?php }
                            if(get_sub_field('button_text','options')) {
                                ?><a class="lower-button" href="<?php the_sub_field('button_link','options'); ?>">
                                    <?php the_sub_field('button_text','options'); ?>
                                </a><?php
                            }
                           ?>
                          </div>
                         </div>
                        </div>
                        <?php
                    endwhile;
                endif; ?>
            </div>


            <?php endwhile; // end of the loop. ?>

        </div><!-- #content -->
    </div><!-- #primary -->
<?php get_footer(); ?>
