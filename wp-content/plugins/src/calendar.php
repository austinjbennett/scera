<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// if(isset($_POST['next'])){
//     // $calendar=new Calendar();
//     // echo $calendar->show();
//     getMyCalendar();
// }

class Calendar { 
     
    /**
     * Constructor
     */
    public function __construct(){     
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }
    // public function __destruct(){
    //     echo 'killing';
    // }
     
    /********************* PROPERTY ********************/  
    private $dayLabels = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    // private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;
     
    /********************* PUBLIC **********************/ 
        
    /**
    * print out the calendar
    */
    public function show() {


        $month = date('m');  
        $year = date('Y');
        // print_r($month);
        // print_r($year);
        $calArr = loopPosts($month,$year);
        foreach($calArr as $day=>$events){
            // echo $day;
            foreach($events as $ev){
                // echo $ev;
            }
        }
         ?>
            <script type="text/javascript">
                var calArr = '<?php echo json_encode($calArr); ?>';
            </script>
        <?php

        $year = null;
         
        $month = null;
         
        // echo 'this it';
        if(null==$year&&isset($_POST['year'])){
        // if(null==$year&&$newYear){       
 
            $year = $_POST['year'];
         
        }else if(null==$year){
 
            $year = date("Y",time());  
         
        }          
         
        if(null==$month&&isset($_POST['month'])){
        // if(null==$month&&$newMonth){        

            // echo 'yeah'; 
            $month = $_POST['month'];
         
        }else if(null==$month){
 
            $month = date("m",time());
         
        }                  
         
        $this->currentYear=$year;
         
        $this->currentMonth=$month;
         
        $this->daysInMonth=$this->_daysInMonth($month,$year);  
         
        $content='<div class="calWrap"><div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div id="calContent" class="box-content">'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';   
                                $content.='<div class="clear"></div>';     
                                $content.='<ul class="dates">';    
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                     
                                    //Create days in a week
                                    for($j=0;$j<=6;$j++){
                                        $content.=$this->_showDay($i*7+$j, $calArr);
                                    }
                                }
                                 
                                $content.='</ul>';
                                 
                                $content.='<div class="clear"></div>';     
             
                        $content.='</div>';
                 
        $content.='</div></div>';
        return $content;   
    }
     
    /********************* PRIVATE **********************/ 
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber, $calArr){
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
             
            $cellContent = $this->currentDay;
             
            $this->currentDay++;   
             
        }else{
             
            $this->currentDate =null;
 
            $cellContent=null;
        }

        // CALENDER EVENTS   
        if($calArr[$this->currentDate]){
            $cellEv;
            $evArr = $calArr[$this->currentDate];
            foreach($evArr as $ev=>$cat){
                // foreach()
                // echo $ev;
                $cellEv.='<span class="'.$cat.'">'.$ev.'</span>';
            }
        }else{
            $cellEv = null;
        }
        
        $today=date('Y-m-d');        
        // print_r($today);

        return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').'"><span class="cellContent">'.$cellContent.'</span><p class="evTxt">'.$cellEv.'</p>'.'</li>';

        // return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').'">'.$cellContent.'<p class="evTxt">'.$cellEv.'</p>'.'</li>'.'<p class="evTxtOutside">'.$cellEv.'</p>';

             
        // return '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').($this->currentDate==$today?' today ':'').'">'.$cellContent.'<p class="evTxt">'.$cellEv.'</p>'.'</li>';

    }
     
    /**
    * create navigation
    */
    private function _createNavi(){
         
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
         
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
         
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;

      
         
        return
            '<div class="header">'.
                '<a class="prev" id="prevMonth">Prev</a>'.
                    '<span id="curDateTxt" class="title">'.date('M Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                // '<a class="next" href="/calendar/#calendar?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
                '<a class="next" id="nextMonth">Next</a>'.

            '</div>';
    }
         
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
        }
         
        return $content;
    }
     
     
     
    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
         
        return $numOfweeks;
    }
 
    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());

        // echo $currentMonth.' has '.date('t',strtotime($year.'-'.$month.'-01')).' days.';
             
        return date('t',strtotime($year.'-'.$month.'-01'));

    }     
}