<?php
// ini_set('display_errors','On');
// error_reporting(E_ALL | E_STRICT);

    /********************* PROPERTY ********************/  
    // $dayLabels = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    // private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    /********************* PUBLIC **********************/ 
        // include 'evCalFunctions.php';
    /**
    * print out the calendar
    */
    // function show($currentYear,$currentMonth,$currentDay,$currentDate,$daysInMonth) {  
    function show() {      
    	
		// global $calArr;
        // $calArr = loopPosts();
        // foreach($calArr as $day=>$events){
        //     // echo $day;
        //     foreach($events as $ev){
        //         // echo $ev;
        //     }
        // }

    	$currentYear=0;
	    $currentMonth=0;	     
	    $currentDay=0;	     
	    $currentDate=null;	     
	    $daysInMonth=0;	     
	    $naviHref= null;
 		
        $year = $_POST['year'];
        $month = $_POST['month']+1; 
        $calArr = $_POST['calArr'];
        // echo $calArr;
        // foreach($calArr as $day=>$events){
        //     echo $day;
        //     foreach($events as $ev){
        //         echo $ev;
        //     }
        // }
        // echo $month.' '.$year;
         
        $currentYear=$year;         
        $currentMonth=$month;
        // return $currentYear;
         
        $daysInMonth=daysInMonth($month,$year);  
        // echo $daysInMonth;
         

         $content='<ul class="label">'.createLabels().'</ul>';   
            $content.='<div class="clear"></div>';     
            $content.='<ul class="dates">';               
            $weeksInMonth = weeksInMonth($month,$year);
            // echo $weeksInMonth;
            // Create weeks in a month
            for( $i=0; $i<$weeksInMonth; $i++ ){
                 
                //Create days in a week
                for($j=0;$j<=6;$j++){
                    $content.=showDay($i*7+$j, $daysInMonth, $currentDay, $currentMonth, $currentYear,$calArr);
                    // echo $currentDay;
                    // $content.=showDay($i*7+$j, $daysInMonth);

                }
            }
            $content.='</ul>';                                 
            $content.='<div class="clear"></div>';
        return $content;   
    }
     
    /********************* PRIVATE **********************/ 
    /**
    * create the li element for ul
    */
    function showDay($cellNumber, $daysInMonth, $currentDay, $currentMonth, $currentYear,$calArr){
    // function showDay($cellNumber, $daysInMonth){
    // function showDay($cellNumber){

        global $currentDay; 
         
        if($currentDay==0){
             
            $firstDayOfTheWeek = date('w',strtotime($currentYear.'-'.$currentMonth.'-01'));
            // echo intval($cellNumber);
            // echo intval($firstDayOfTheWeek);
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                $currentDay=1;
                // echo $currentDay;
                 
            }
        }
         // echo $currentDay;
        if( ($currentDay!=0)&&($currentDay<=$daysInMonth) ){
             
            $currentDate = date('Y-m-d',strtotime($currentYear.'-'.$currentMonth.'-'.($currentDay)));
             
            $cellContent = $currentDay;
             
            $currentDay++;   
             
        }else{
             
            $currentDate =null;
 
            $cellContent=null;
        }
             
          // CALENDER EVENTS   
        if($calArr[$currentDate]){
            $cellEv;
            $evArr = $calArr[$currentDate];
            foreach($evArr as $ev=>$cat){
                // echo $ev;
                $cellEv.='<span class="'.$cat.'">'.$ev.'</span>';
            }
        }else{
            $cellEv = null;
        }
             
        // $today=date('Y-m-d');        

        return '<li id="li-'.$currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').'"><span class="cellContent">'.$cellContent.'</span><p class="evTxt">'.$cellEv.'</p>'.'</li>';

    }
     
    /**
    * create navigation
    */
    function createNavi(){
         
        $nextMonth = $currentMonth==12?1:intval($currentMonth)+1;
         
        $nextYear = $currentMonth==12?intval($currentYear)+1:$currentYear;
         
        $preMonth = $currentMonth==1?12:intval($currentMonth)-1;
         
        $preYear = $currentMonth==1?intval($currentYear)-1:$currentYear;
         
        return
            '<div class="header">'.
                '<a class="prev" id="prevMonth">Prev</a>'.
                    '<span id="curDateTxt" class="title">'.date('M Y',strtotime($currentYear.'-'.$currentMonth.'-1')).'</span>'.
                // '<a class="next" href="'.$naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
                '<a class="next" id="nextMonth">Next</a>'.

            '</div>';
    }
         
    /**
    * create calendar week labels
    */
    function createLabels(){  

    	$dayLabels = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
                 
        $labContent='';
         
        foreach($dayLabels as $index=>$label){
             
            $labContent.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $labContent;
        // return 'here '.$dayLabels;

    }
     
     
     
    /**
    * calculate number of weeks in a particular month
    */
    function weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('w',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('w',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
            // echo 'yeaaa';
         
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    function daysInMonth($month=null,$year=null){
         
        if(null==$year)
            $year =  date("Y",time()); 
 
        if(null==$month)
            $month = date("m",time());

        // echo date('t',strtotime($year.'-'.$month.'-01'));
             
        return date('t',strtotime($year.'-'.$month.'-01'));

    }     

    // echo "this: ".show();
    // global $calArr;
    // $calArr = loopPosts();
    echo show();
    // echo "this: ".$_POST['year'].' '.$_POST['month'];
    // echo $dayLabels;

?>