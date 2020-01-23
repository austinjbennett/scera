<?php
// ini_set('display_errors','On');
// error_reporting(E_ALL | E_STRICT);
// defined( 'ABSPATH' ) || define('ABSPATH',__DIR__.'/');

// if(isset($_POST['month'])){
// 	$month = $_POST['month'];
// 	$year = $_POST['year'];
// 	// echo $month.$year;
// 	loopPosts($month,$year);
// }

// MAIN FUNCTION
function getMyCalendar(){
	// return 'Hello there testo'; 
	// loopPosts();
	date_default_timezone_set('America/Denver');

	$calendar=new Calendar();
	echo $calendar->show();    
    // echo $calEvs;
}

function loopPosts($month,$year){
	// print_r('here');
	function whereEvDate($where){
		global $wpdb;
		$where = str_replace("meta_key = 'event_dates_","meta_key LIKE 'event_dates_",$wpdb->remove_placeholder_escape($where));
		return $where;
	}
	// global $calArr;
	$calArr = array();
	$evCats = array();
	// $month = '07'; 
	// $month = date('m'); 	
	// $year = '2019';
	$month_start_date = date($year.'-'.$month.'-01');	
	$month_end_date = date($year.'-'.$month.'-t');
	// echo $start_date;	
	// echo $end_date;
	$args=[
		'post_type'=>'event',
		'posts_per_page'=>500,
		// 'tax_query'=>array(array(
		// 		'taxonomy'=>'event_category',
		// 		'field'=>'slug',
		// 		'terms'=>'screen'
		// 	)),		
		// 'meta_query'=>array(
		// 	'relation'=>'OR',
		// 	array(
		// 		'key'=>'event_dates_%_event_start_date',
		// 		'value'=>array($month_start_date, $month_end_date),
		// 		'compare'=>'BETWEEN',
		// 		'type'=>'DATE'
		// 	),
		// 	array(				
		// 		'key'=>'event_dates_%_event_end_date',
		// 		'value'=>array($month_start_date, $month_end_date),
		// 		'compare'=>'BETWEEN',
		// 		'type'=>'DATE'				
		// 	),
		// 	array(
		// 		'relation'=>'AND',
		// 		array(
		// 			'key'=>'event_dates_%_event_start_date',
		// 			'value'=>$month_start_date,
		// 			'compare'=>'<=',
		// 			'type'=>'DATE'
		// 		),
		// 		array(
		// 			'key'=>'event_dates_%_event_end_date',
		// 			'value'=>$month_end_date,
		// 			'compare'=>'>=',
		// 			'type'=>'DATE'	
		// 		)
		// 	)
		// )
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

			// GET POST PARENT CATEGORY AND PUSH TO ARR
			$terms=get_the_terms($post,'event_category');
			// // $tempArr=array();
			// foreach($terms as $term){
			// 	if($term->parent == 0){
			// 		// array_push($tempArr,$term->name);
			// 		$evCats[get_the_title()]=$term->name;
			// 	}
			// }
			// // $evCats[get_the_title()]=$tempArr;

			$evDates=get_field('event_dates');
			if($evDates){
				// echo '<p class="showTimesTitle">Event Dates</p>';
				if(have_rows('event_dates')){				
					while(have_rows('event_dates')):the_row();
						// echo '<p>'.the_row().'</p>';
						$evStart=get_sub_field('event_start_date');
						$evEnd=get_sub_field('event_end_date');
						// the_sub_field('event_start_date');
						// the_sub_field('event_end_date');
						$begin = new DateTime($evStart);
						$interval = new DateInterval('P1D');
						$end = new DateTime($evEnd);
						$end = $end->modify('+1 day');
						$period = new DatePeriod($begin,$interval,$end);
						// echo '<br>';
						foreach($period as $date){
							// $date = date_format($date, 'Y-m-d');
							// print_r($date);
							// echo $date;
							// if($date->format('Y-m-d')>=$month_start_date && $date->format('Y-m-d')<=$month_end_date){		
								$dow = get_field('days_of_the_week');
								if($dow){
									// echo strtolower($date->format('D'));
									if(in_array(strtolower($date->format('D')),$dow)){
										// $calArr[$date->format('Y-m-d')][]=get_the_title();
										foreach($terms as $term){
											if($term->parent == 0){
												$calArr[$date->format('Y-m-d')][get_the_title()]=$term->name;
											}
										}		
									}
								}
							// }				
						}
					endwhile;
				}
			}else{
				// echo 'No Dates Available';
			}
			

		endwhile;
		// print_r($calArr);
		// print_r($evCats);
		return $calArr;


		wp_reset_postdata();
	}

}
// add_action('init','loopPosts');


	// $fields = get_fields();
			// if($fields){
			// 	echo '<ul>';
			// 	foreach($fields as $name=>$value){
			// 		echo '<li>'.$name.': '.$value.'</li>';	
			// 		if(is_array($value)){
			// 			echo '<ul>';
			// 			foreach($value as $key=>$value2){
			// 				echo '<li>'.$key.': '.$value2.'</li>';
			// 				if(is_array($value2)){
			// 					echo '<ul>';
			// 					foreach ($value2 as $key3 => $value3) {
			// 						echo '<li>'.$key3.': '.$value3.'</li>';
			// 					}
			// 					echo '</ul>';
			// 				}
			// 			}
			// 			echo '</ul>';
			// 		}
			// 	}
			// 	echo '</ul>';
			// }
	

	// 'meta_query'=>array(
	// 		'relation'=>'OR',
	// 		array(
	// 			'relation'=>'AND',
	// 			array(
	// 				'meta_key'=>'event_dates_%_event_start_date',
	// 				'meta_value'=>date('m/d/Y H:i:s',strtotime('06/01/2019')),
	// 				'compare'=>'>=',
	// 				'meta_type'=>'DATETIME'
	// 			),
	// 			array(
	// 				'meta_key'=>'event_dates_%_event_start_date',
	// 				'meta_value'=>date('m/d/Y H:i:s',strtotime('06/30/2019')),
	// 				'compare'=>'<=',
	// 				'meta_type'=>'DATETIME'
	// 			)
	// 		),
	// 		array(
	// 			'relation'=>'AND',
	// 			array(
	// 				'meta_key'=>'event_dates_%_event_end_date',
	// 				'meta_value'=>date('m/d/Y H:i:s',strtotime('06/01/2019')),
	// 				'compare'=>'>=',
	// 				'meta_type'=>'DATETIME'
	// 			),
	// 			array(
	// 				'meta_key'=>'event_dates_%_event_end_date',
	// 				'meta_value'=>date('m/d/Y H:i:s',strtotime('06/30/2019')),
	// 				'compare'=>'<=',
	// 				'meta_type'=>'DATETIME'
	// 			),				
	// 		)
	// 	),

?>