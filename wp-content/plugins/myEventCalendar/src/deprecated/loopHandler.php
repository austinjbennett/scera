<?php
ini_set('display_errors','On');
error_reporting(E_ALL | E_STRICT);
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// if(isset($_POST['month'])){
// 	$month = $_POST['month'];
// 	$year = $_POST['year'];
// 	echo $month.$year;
// }

function loopPosts($month,$year){
	function whereEvDate($where){
		global $wpdb;
		$where = str_replace("meta_key = 'event_dates_","meta_key LIKE 'event_dates_",$wpdb->remove_placeholder_escape($where));
		return $where;
	}
	// global $calArr;
	$calArr = array();
	// $month = '07'; 
	// $month = date('m'); 	
	// $year = '2019';
	$month_start_date = date($year.'-'.$month.'-01');	
	$month_end_date = date($year.'-'.$month.'-t');
	// echo $start_date;	
	// echo $end_date;
	$args=[
		'post_type'=>'event',
		'posts_per_page'=>50,
		// 'tax_query'=>array(array(
		// 		'taxonomy'=>'event_category',
		// 		'field'=>'slug',
		// 		'terms'=>'screen'
		// 	)),		
		'meta_query'=>array(
			'relation'=>'OR',
			array(				
				'key'=>'event_dates_%_event_start_date',
				'value'=>array($month_start_date, $month_end_date),	
				'compare'=>'BETWEEN',
				'type'=>'DATE'				
			),
			array(				
				'key'=>'event_dates_%_event_end_date',
				'value'=>array($month_start_date, $month_end_date),
				'compare'=>'BETWEEN',
				'type'=>'DATE'				
			),
			array(
				'relation'=>'AND',
				array(
					'key'=>'event_dates_%_event_start_date',
					'value'=>$month_start_date,
					'compare'=>'<=',
					'type'=>'DATE'
				),
				array(
					'key'=>'event_dates_%_event_end_date',
					'value'=>$month_end_date,
					'compare'=>'>=',
					'type'=>'DATE'	
				)
			)
		)
	]; 

	add_filter('posts_where','whereEvDate');
	$query= new WP_Query($args);
	if($query->have_posts()){ 
		$begin = new DateTime($month_start_date);
		$interval = new DateInterval('P1D');
		$end = new DateTime($month_end_date);
		// $end = $end->modify('+1 day');
		$period = new DatePeriod($begin,$interval,$end);

		while($query->have_posts()):	$query->the_post();
			// echo '<h1 class="title">'.get_the_title().'</h1>';			
			$evDates=get_field('event_dates');
			if($evDates){
				// echo '<p class="showTimesTitle">Event Dates</p>';
				if(have_rows('event_dates')){
					while(have_rows('event_dates')):the_row();
						$evStart=get_sub_field('event_start_date');
						$evEnd=get_sub_field('event_end_date');
						// the_sub_field('event_start_date');
						// the_sub_field('event_end_date');
						$begin = new DateTime($evStart);
						$interval = new DateInterval('P1D');
						$end = new DateTime($evEnd);
						$end = $end->modify('+1 day');
						$period = new DatePeriod($begin,$interval,$end);
						echo '<br>';
						foreach($period as $date){
							// $date = date_format($date, 'Y-m-d');
							// print_r($date);
							// echo $date;
							if($date->format('Y-m-d')>=$month_start_date && $date->format('Y-m-d')<=$month_end_date){		
								$dow = get_field('days_of_the_week');
								if($dow){
									// echo strtolower($date->format('D'));
									if(in_array(strtolower($date->format('D')),$dow)){
										$calArr[$date->format('Y-m-d')][]=get_the_title();		
									}
								}
							}				
						}
					endwhile;
				}
			}else{
				// echo 'No Dates Available';
			}
			

		endwhile;
		// print_r($calArr);
		return $calArr;


		// wp_reset_postdata();
	}

}
	$month = $_POST['month'];
	$year = $_POST['year'];
	echo loopPosts($month,$year);



?>