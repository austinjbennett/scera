<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ow_modify_admin_menu() {
  ?>
	<script>
	document.addEventListener("DOMContentLoaded", function(e) {
	  let owMoreInner = document.getElementById('ow-more-inner')
	  let owMore = owMoreInner.parentElement.parentElement
	  owMore.classList.add('ow-more')
	  owMore.parentElement.classList.add('ow-more-added')

	  owMore.addEventListener('click', function(){
		  owMore.classList.toggle('ow-more')
		  if ( owMore.classList.contains('ow-more') ){
			  owMoreInner.innerHTML = 'More <span class="dashicons dashicons-arrow-down-alt2"></span>'
		  } else {
			  owMoreInner.innerHTML = 'Less &nbsp;<span class="dashicons dashicons-arrow-up-alt2"></span>'
		  }
	  })
	  <?php if (is_wpoverwatch_user()): ?>
		  owMore.click();
	  <?php endif; ?>

	  // Remove Ewww
	  let eww = document.querySelector('#adminmenu li:not(.toplevel_page_wpoverwatch) a[href="options-general.php?page=ewww-image-optimizer/ewww-image-optimizer.php"]')
	  if (eww)
	  	eww.parentElement.outerHTML = "";

  	  // Remove Breeze
	  let breeze = document.querySelector('#adminmenu li:not(.toplevel_page_wpoverwatch) a[href="options-general.php?page=breeze"]')
	  if (breeze)
	  	  breeze.parentElement.outerHTML = "";

	  // Remove WordFence
	  let wordfence = document.querySelector('#adminmenu > li:not(.toplevel_page_wpoverwatch) a[href="admin.php?page=Wordfence"]')
	  if (wordfence)
	  	  wordfence.parentElement.outerHTML = "";

	  // Remove WP Mail SMTP
	  let smtp = document.querySelector('#adminmenu > li:not(.toplevel_page_wpoverwatch) a[href="options-general.php?page=wp-mail-smtp"]')
	  if (smtp)
	  	  smtp.parentElement.outerHTML = "";

	});
	</script>
  <?php
}
add_action('admin_head', 'ow_modify_admin_menu');

/* On the mission control dashboard going back could take you back to a different site, so let's make the wp_die function a little more user friendly */
// based off _default_wp_die_handler
function add_dashboard_button_to_wp_die($message, $title = '', $args = array('back_link'=>true) ){
	if ( ! in_array('back_link', $args) ){
		$args['back_link'] = true;
	}
    list( $message, $title, $r ) = _wp_die_process_input( $message, $title, $args );

    if ( is_string( $message ) ) {
        if ( ! empty( $r['additional_errors'] ) ) {
            $message = array_merge(
                array( $message ),
                wp_list_pluck( $r['additional_errors'], 'message' )
            );
            $message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $message ) . "</li>\n\t</ul>";
        } else {
            $message = "<p>$message</p>";
        }
    }

    $have_gettext = function_exists( '__' );

    if ( ! empty( $r['link_url'] ) && ! empty( $r['link_text'] ) ) {
        $link_url = $r['link_url'];
        if ( function_exists( 'esc_url' ) ) {
                $link_url = esc_url( $link_url );
        }
        $link_text = $r['link_text'];
        $message  .= "\n<p><a href='{$link_url}'>{$link_text}</a></p>";
    }

	if (strpos($_SERVER['REQUEST_URI'],'/wp-admin/') !== false){
		$message .= "<a href='". admin_url() ."' class=button style='float:right;'>Go to dashboard</a>";
	}

    if ( isset( $r['back_link'] ) && $r['back_link'] ) {
        $back_text = $have_gettext ? __( '&laquo; Back' ) : '&laquo; Back';
        $message  .= "\n<p><a href='javascript:history.back()'>$back_text</a></p>";
    }

    if ( ! did_action( 'admin_head' ) ) :
        if ( ! headers_sent() ) {
            status_header( $r['response'] );
            nocache_headers();
            header( 'Content-Type: text/html; charset=utf-8' );
        }

        $text_direction = $r['text_direction'];
        if ( function_exists( 'language_attributes' ) && function_exists( 'is_rtl' ) ) {
            $dir_attr = get_language_attributes();
        } else {
            $dir_attr = "dir='$text_direction'";
        }
        ?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php echo $dir_attr; ?>>
		<head>
		        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		        <meta name="viewport" content="width=device-width">
		                <?php
		                if ( function_exists( 'wp_no_robots' ) ) {
		                        wp_no_robots();
		                }
		                ?>
		        <title><?php echo $title; ?></title>
		        <style type="text/css">
		                html {
		                        background: #f1f1f1;
		                }
		                body {
		                        background: #fff;
		                        color: #444;
		                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		                        margin: 2em auto;
		                        padding: 1em 2em;
		                        max-width: 700px;
		                        -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
		                        box-shadow: 0 1px 3px rgba(0,0,0,0.13);
		                }
		                h1 {
		                        border-bottom: 1px solid #dadada;
		                        clear: both;
		                        color: #666;
		                        font-size: 24px;
		                        margin: 30px 0 0 0;
		                        padding: 0;
		                        padding-bottom: 7px;
		                }
		                #error-page {
		                        margin-top: 50px;
		                }
		                #error-page p {
		                        font-size: 14px;
		                        line-height: 1.5;
		                        margin: 25px 0 20px;
		                }
		                #error-page code {
		                        font-family: Consolas, Monaco, monospace;
		                }
		                ul li {
		                        margin-bottom: 10px;
		                        font-size: 14px ;
		                }
		                a {
		                        color: #0073aa;
		                }
		                a:hover,
		                a:active {
		                        color: #00a0d2;
		                }
		                a:focus {
		                        color: #124964;
		                        -webkit-box-shadow:
		                                0 0 0 1px #5b9dd9,
		                                0 0 2px 1px rgba(30, 140, 190, .8);
		                        box-shadow:
		                                0 0 0 1px #5b9dd9,
		                                0 0 2px 1px rgba(30, 140, 190, .8);
		                        outline: none;
		                }
		                .button {
							background: #f7f7f7;
	                        border: 1px solid #ccc;
	                        color: #555;
	                        display: inline-block;
	                        text-decoration: none;
	                        font-size: 13px;
	                        line-height: 26px;
	                        height: 28px;
	                        margin: 0;
	                        padding: 0 10px 1px;
	                        cursor: pointer;
	                        -webkit-border-radius: 3px;
	                        -webkit-appearance: none;
	                        border-radius: 3px;
	                        white-space: nowrap;
	                        -webkit-box-sizing: border-box;
	                        -moz-box-sizing:    border-box;
	                        box-sizing:         border-box;

	                        -webkit-box-shadow: 0 1px 0 #ccc;
	                        box-shadow: 0 1px 0 #ccc;
	                         vertical-align: top;
	                }

	                .button.button-large {
	                        height: 30px;
	                        line-height: 28px;
	                        padding: 0 12px 2px;
	                }

	                .button:hover,
	                .button:focus {
	                        background: #fafafa;
	                        border-color: #999;
	                        color: #23282d;
	                }

	                .button:focus  {
	                        border-color: #5b9dd9;
	                        -webkit-box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );
	                        box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );
	                        outline: none;
	                }

	                .button:active {
	                        background: #eee;
	                        border-color: #999;
	                         -webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
	                         box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
	                         -webkit-transform: translateY(1px);
	                         -ms-transform: translateY(1px);
	                         transform: translateY(1px);
	                }

	                <?php
	                if ( 'rtl' == $text_direction ) {
	                        echo 'body { font-family: Tahoma, Arial; }';
	                }
	                ?>
			</style>
		</head>
		<body id="error-page">
		<?php endif; // ! did_action( 'admin_head' ) ?>

				<!-- override WordPress styles here -->
			<style>
				html{
					background: #f1f1f1;
					display: flex;
					align-items: center;
					height: 100%;
				}
				body{
					width: 700px;
					box-shadow: 0 1px 4px -2px rgba(0,0,0,0.15);
				}
				#error-page{
					margin-top: 0; /* 32px would center vertically, making the content sleightly higher than that */
				}
			</style>

	        	<?php echo $message; ?>
		</body>
		</html>
	        <?php
	        if ( $r['exit'] ) {
	                die();
	        }
}
function get_custom_wp_die_handler(){ return 'add_dashboard_button_to_wp_die'; }
add_filter( 'wp_die_handler', 'get_custom_wp_die_handler' );
