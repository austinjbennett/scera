<?php

$root = $_SERVER['DOCUMENT_ROOT'];

require_once($root.'/wp-load.php');

function php_calendar($month, $year)
{

    // First and last day in month
    $firstday = date('Ymd', mktime(1, 1, 1, $month, 1, $year));
    $lastday = date('Ymd', mktime(1, 1, 1, $month+1, 0, $year));

    $args = array(
        'post_type' => array('events', 'movies'),
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'event-category',
                'field' => 'slug',
                'terms' => 'education',
                'operator' => 'NOT IN'
            ),
        )
    );

    $query = new WP_Query($args);
    $post_range = array();
    // $all_the_dates = array();

    /**
     * Loop through all of the events posts and build an array of dates on which events occur.
     */
    while ($query->have_posts()) : $query->the_post();

        // $all_the_dates[] = get_the_title();
        if( have_rows('event_dates')) :
            while( have_rows('event_dates')) : the_row();

                $start = get_sub_field('event_start_date');
                $end = get_sub_field('event_end_date');
                $day_of_the_week = get_field('days_of_the_week');
                $lala_title = get_the_title();

                $current = $start;

                    while($current <= $end) {

                        //* compare the current day of the week with the days selected in the events' custom field.
                        $current_dotw = strtolower(date('D', strtotime($current)));

                        //* make some magic for backwards compatibility
                        if($current_dotw === 'tue') {
                          $current_dotw = 'tues';
                        } elseif($current_dotw === 'thu') {
                          $current_dotw = 'thurs';
                        }

                        //* Check to see if the field is not an array (so we can include single day events)
                        //* Check to see if the current day of the week is in the array
                        if(!is_array($day_of_the_week) || in_array($current_dotw, $day_of_the_week )) {

                            $all_the_dates[] = $current;

                        }

                        // $all_the_dates[] = $current;
                        $current = date("Ymd", strtotime("+1 day", strtotime($current)));
                    }

            endwhile;
        endif;

    endwhile;

    /* Set up $calendar variable */
    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

    /* Set up days */
    $headings = array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' );
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">' .implode('</td><td class="calendar-day-head">', $headings).'</td></tr>';

    $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();

    /* row for week one */
    $calendar.= '<tr class="calendar-row">';

    /* print "blank" days until the first of the current week */
    for($x = 0; $x < $running_day; $x++):
        $calendar.= '<td width="35" height="36" class="calendar-day-np"> </td>';
        $days_in_this_week++;
    endfor;

    /* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++):
        $today = date('Ymd', mktime(0, 0, 0, $month, $list_day, $year));
        $weekday = date('N', mktime(0, 0, 0, $month, $list_day, $year));

        $calendar.= '<td width="35" height="36" class="calendar-day">';
            /* add in the day number */
        if (!in_array($today, $all_the_dates)) {
            $calendar.= '<div class="day-number">'.$list_day.'</div>';
        } else {
            $calendar.= '<div class="day-number"><a class="calendar-link" href="/events-date/?date=' . $today . '">'.$list_day.'</a></div>';
        }

        $calendar.= '</td>';
        if($running_day == 6):
            $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month):
                $calendar.= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++;
        $running_day++;
        $day_counter++;
    endfor;

    /* finish the rest of the days in the week */
    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td width="35" height="36" class="calendar-day-np"> </td>';
        endfor;
    endif;

    /* final row */
    $calendar.= '</tr>';

    /* end the table */
    $calendar.= '</table>';

    return $calendar;
}

if (!isset($_POST['month'])) {
    $month = date('n');
} else {
    $month = $_POST['month'] + 1;
}
if (!isset($_POST['year'])) {
    $year = date('Y');
} else {
    $year = $_POST['year'];
}

echo php_calendar($month, $year);
