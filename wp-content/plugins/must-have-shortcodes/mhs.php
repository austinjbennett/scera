<?php
/*
	Plugin Name: The must have shortcode collection
	Description: A collection of shortcodes that are useful for every wordpress site.
	Version: 1.0.1
	Author: Evan Bradham
	Copyright 2013 evanbradham.org

*/
	define( 'MHS_PATH', plugin_dir_path(__FILE__) );
	
	add_action('admin_menu', 'mhs_admin_actions'); 
	function mhs_admin_actions() {  
		add_options_page("Shortcode Documentation", "Shortcode Documentation", 'read', "mhs-docs", "mhs_admin");
	}  
	
	function mhs_admin() {
		require MHS_PATH . 'admin.php';
	} 

	remove_filter( 'the_content', 'wpautop' );
	add_filter( 'the_content', 'wpautop' , 12);
	
		/* [testimonial]   *//*
		function testimonial_shortcode( $atts, $content = null ) {
			extract( shortcode_atts( array(
			  'name' => '',
			  'title' => '',
			  ), $atts ) );

			return '<div class="testimonial"><h3>"'.$title.'"</h3>'.$content.'<div style="clear:both;"></div><span>'.$name.	'</span></div>';
		}
		add_shortcode( 'testimonial', 'testimonial_shortcode' );
*/

		/*  [toggle_init]  */
		function toggle_init_shortcode( $atts, $content = null ){
			
			return do_shortcode("<div class='toggle-init'>".$content."</div>
			<script>
				jQuery('.toggle-title').click(function() {
					jQuery(this).next('.toggle-content').toggle('800');
					if( jQuery(this).hasClass('closed_tog') ){
						var showOrHide = 'closed';
					}else{
						var showOrHide = 'open';
					}
					if ( showOrHide == 'closed') {
						jQuery(this).next('.toggle-content').show();
						jQuery(this).addClass('opened_tog');
						jQuery(this).removeClass('closed_tog');
					} else if ( showOrHide == 'open' ) {
						jQuery(this).next('.toggle-content').hide();
						jQuery(this).addClass('closed_tog');
						jQuery(this).removeClass('opened_tog');
					}
				});</script>");
		}

		add_shortcode('toggle_init','toggle_init_shortcode');



		/*  [toggle]  */
		function toggle_shortcode( $atts, $content = null ){
			extract( shortcode_atts( array(
				'title' => '',
			), $atts ) );
			
			return do_shortcode('<div class="toggle-wrap"><div class="toggle-title closed_tog">'.$title.'</div><div class="toggle-content" style="display: none;">'.$content.'</div></div>');
		}

		add_shortcode('toggle','toggle_shortcode');
		
		/*  [clear]  */
		function clear_shortcode( $atts, $content = null ){
			extract( shortcode_atts( array(
				'class' => ''
			), $atts ) );

			return '<div class="'.$class.'" style="clear:both;"></div>';
		}
		add_shortcode('clear','clear_shortcode');		

		/*  [pdf_googledrive]  */
		function pdf_shortcode($attr, $content) {  
			extract( shortcode_atts( array(
				'class' => '',
				'link' => ''
			), $atts ) );
			
			return '<a class="pdf_google" href="http://docs.google.com/viewer?url=' . $link . '">'.$content.'</a>';   
		}  
		add_shortcode('pdf_googledrive', 'pdf_shortcode'); 

		/*  [email]  */
		function email_shortcode( $atts , $content=null ) {  
   
			for ($i = 0; $i < strlen($content); $i++) $encodedmail .= "&#" . ord($content[$i]) . ';';   
		   
			return '<a target="_blank" href="mailto:'.$encodedmail.'">'.$encodedmail.'</a>';  
		   
		}  
		add_shortcode('email', 'email_shortcode'); 

		/*  [googlemap]  */
		function googlemap_shortcode($atts, $content = null) {  
		   extract(shortcode_atts(array(  
			  "width" => '640',  
			  "height" => '480',  
			  "src" => ''  
		   ), $atts));  
		   return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'"></iframe>';  
		}  
		add_shortcode("googlemap", "googlemap_shortcode"); 
	
		/*  [rss]  */
		include_once(ABSPATH.WPINC.'/class-simplepie.php');  
		function rss_shortcode($atts) {  
			extract(shortcode_atts(array(  
			"feed" => 'http://',  
			  "num" => '1',  
			), $atts));  
		  
			return wp_rss($feed, $num);  
		}  
		  
		add_shortcode('rss', 'rss_shortcode'); 
		
		/*  [twitter]  */
		function twitter_shortcode( $atts, $content=null ){  
		/* Author: Nicholas P. Iler 
		 * URL: http://www.ilertech.com/2011/07/add-twitter-share-button-to-wordpress-3-0-with-a-simple-shortcode/ 
		 Modified by Evan Bradham to make it actually work.
		 */  
			extract(shortcode_atts(array(  
				'url' => get_permalink(),  
				'counturl' => null,  
				'via' => '',  
				'text' => '',  
				'related' => '',  
				'countbox' => 'none', // none, horizontal, vertical  
			), $atts));  
			// Check for count url and set to $url if not provided  
			if($counturl == null) $counturl = $url;  
		   
			$twitter_code = '
				<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
				<a href="http://twitter.com/share" class="twitter-share-button"
				data-url="'.$url.'"
				data-counturl="'.$counturl.'"
				data-via="'.$via.'"
				data-text="'.$text.'"
				data-related="'.$related.'"
				data-count="'.$countbox.'"></a>';  
		   
			return $twitter_code;  
		   
		}  
		add_shortcode('twitter', 'twitter_shortcode');
		
		/*  [related_posts]  */
		function related_posts_shortcode( $atts ) {  
			extract(shortcode_atts(array(  
				'limit' => '5',  
			), $atts));  
		   
			global $wpdb, $post, $table_prefix;  
		   
			if ($post->ID) {  
				$retval = '<ul class="related_posts_sc">';  
				// Get tags  
				$tags = wp_get_post_tags($post->ID);  
				$tagsarray = array();  
				foreach ($tags as $tag) {  
					$tagsarray[] = $tag->term_id;  
				}  
				$tagslist = implode(',', $tagsarray);  
		   
				// Do the query  
				$q = "SELECT p.*, count(tr.object_id) as count 
					FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p WHERE tt.taxonomy ='post_tag' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tr.object_id  = p.ID AND tt.term_id IN ($tagslist) AND p.ID != $post->ID 
						AND p.post_status = 'publish' 
						AND p.post_date_gmt < NOW() 
					GROUP BY tr.object_id 
					ORDER BY count DESC, p.post_date_gmt DESC 
					LIMIT $limit;";  
		   
				$related = $wpdb->get_results($q);  
				if ( $related ) {  
					foreach($related as $r) {  
						$retval .= '<li><a title="'.wptexturize($r->post_title).'" href="'.get_permalink($r->ID).'">'.wptexturize($r->post_title).'</a></li>';  
					}  
				} else {  
					$retval .= ' 
			<li>No related posts found</li>';  
				}  
				$retval .= '</ul>';  
				return $retval;  
			}  
			return;  
		} 
		add_shortcode('related_posts', 'related_posts_shortcode');
		
		/*  [bloginfo]  */
		function bloginfo_shortcode( $atts ) {
			extract(shortcode_atts(array(
				'show' => '',
			), $atts));
			return get_bloginfo($show);
		}
		add_shortcode('bloginfo', 'bloginfo_shortcode');
		
		
		/*  [columns]  */
		function column_shortcode( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'class' => '',
			), $atts));
			
			$random = rand(0, 999);
			
			return do_shortcode("<div id='column-".$random."' class='".$class."'>".$content."</div>
			<script>
				var colCount = jQuery('#column-".$random." .col').length;
				var columnWidth = 100-((colCount-1) * 5);
				var colWidth = columnWidth / colCount;
				jQuery('#column-".$random." > .col').css('width', colWidth+'%');
				jQuery('#column-".$random." > .col').css('margin-right', '5%');
				jQuery('#column-".$random." > .col').css('float', 'left');
				jQuery('#column-".$random." > .col:last-child').css('margin', '0');
			</script>
			");
		}
		add_shortcode('columns', 'column_shortcode');	
		
		
		/*  [col]  */
		function col_shortcode( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'class' => '',
			), $atts));
			return do_shortcode('<div class="col">'.$content.'</div>');
		}
		add_shortcode('col', 'col_shortcode');			
		

		
	//This creates the table and field shortcodes
	class test_table_shortcode 
	{
		static $add_script;
		static $back_final;
		static $text_final;
		static $column_sizes;
		static $count;
		static $column_count;
		
		static function init() 
		{
			add_shortcode('table', array(__CLASS__, 'handle_shortcode'));
			add_shortcode('field', array(__CLASS__, 'handle_field_shortcode'));

			add_action('init', array(__CLASS__, 'register_script'));
			add_action('wp_footer', array(__CLASS__, 'print_script'));
		}

		static function handle_shortcode($atts, $content = null ) 
		{
			self::$add_script = true;

			extract(shortcode_atts(array(  
				"class" => '',  
				"columns" => '',  
				"sizes" => '',  
				"background_colors" => '',
				"text_colors" => '' 
			), $atts));  
			
			if($class){ $class = ' '.$class; }
			
			if($background_colors)
			{
				$background_colors = str_replace('#', '', $background_colors);
				self::$back_final = explode(',', $background_colors);
			}else{ self::$back_final = ''; }
			
			if($text_colors)
			{
				$text_colors = str_replace('#', '', $text_colors);
				self::$text_final = explode(',', $text_colors);
			}else{ self::$text_final = ''; }
						
			if($sizes)
			{
				$sizes = str_replace('%', '', $sizes);
				self::$column_sizes = explode(',', $sizes);
			}else{ self::$column_sizes = ''; }
				
			self::$column_count = self::$count = $columns;

			if(is_numeric($columns))
			{
				return '<div class="mhs-content-table'.$class.'">'.do_shortcode($content).'</div>';
			}
			else
			{
				return 'In your table shortcode, please set the number of columns. Must be a numerical.(ex. columns="3")';
			}
			
		}
		
		static function handle_field_shortcode($atts, $content = null ) 
		{
			extract(shortcode_atts(array(  
				"class" => '' 
			), $atts));  
			
			if($class){ $class = ' '.$class; }

			$which_col = self::$count % self::$column_count;
			
			$style = 'style="';
			if(self::$back_final[$which_col])
			{
				$style .= 'background-color:#'.self::$back_final[$which_col].';';
			}
			if(self::$text_final[$which_col])
			{
				$style .= 'color:#'.self::$text_final[$which_col].';';
			}
			if(self::$column_sizes[$which_col])
			{
				$style .= 'width:'.(self::$column_sizes[$which_col]-5).'%;';
			}
			else
			{
				$dividend = 100 - array_sum(self::$column_sizes);
				$divisor = self::$column_count - $which_col;
				$style .= 'width:'.(($dividend / $divisor)-5).'%;';
			}
			if( ($which_col+1) == self::$column_count )
			{
				$style .= 'border-right-width:1px;border-right-style:solid;';
			}
			if( ($which_col) == 0)
			{
				$style .= 'font-weight:bold;';
				if(self::$count >= 6 )
				{
					$style .= 'clear:both;';
				}
			}
			
			if( (self::$count - self::$column_count) < self::$column_count  )
			{
				$style .= 'border-top-width:1px;border-top-style:solid;';
			}
			$style .= '"';
			
			self::$count++;
			
			return '<div class="mhs-table-field'.$class.'" '.$style.'>'.do_shortcode($content).'</div>'; 
		}

		static function register_script() 
		{
			wp_register_script('table_script', plugins_url('js/table.js', __FILE__), array('jquery'), '1.0', true);
			wp_register_style( 'table_style', plugins_url('css/table.css', __FILE__) );
		}

		static function print_script() 
		{
			if ( ! self::$add_script )
				return;

			wp_print_scripts('table_script');
			wp_enqueue_style('table_style');
		}
	}

	test_table_shortcode::init();
	
	
	
	
	
		
		
