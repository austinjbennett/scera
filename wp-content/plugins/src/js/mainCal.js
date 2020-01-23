// MAIN JS

jQuery(document).ready(function($) {
    // mainFunc();
});

// function mainFunc(){

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

    var daysow= new Array(7);
    daysow[0] = 'Sun';
    daysow[1] = 'Mon';
    daysow[2] = 'Tues';
    daysow[3] = 'Weds';
    daysow[4] = 'Thurs';
    daysow[5] = 'Fri';
    daysow[6] = 'Sat';


    calArr = JSON.parse(calArr);
    console.log(calArr);
    // calArr.forEach(ev=>{
    // 	console.log(ev);
    // })

    function getNewCal(){
        var newCal;
        $.ajax({
            // url: myScript.pluginsUrl+'evCalFunctions.php',
            // url: myScript.pluginsUrl+'loopHandler.php',          
            url: myScript.pluginsUrl+'calAjaxHandler.php',     
            type: 'POST',
            async: false,
            data: {
                // action: 'nextMonth',
                // next: 'Thank U, Next!',
                month: n,
                year: y,
                calArr: calArr
            },
            success: function(response) {
                newCal = response;
                // console.log(newCal);
                // $('#calContent').html(response);
            }               
        });
        return newCal;
    };

    function displayNewCal(newCal){     
        $('#curDateTxt').text(months[n] + ' ' + y);
        $('#calContent').html(newCal);
    }

    function getNextMonth(){
        n++;
        if(n > 11) {
            n = 0;
            y++;
        }
        // getNewCal();
        var newCal = getNewCal();
        return newCal;
    };

    function getPrevMonth(){
        n--;
        if(n < 0) {
            n = 11;
            y--;
        }
        // getNewCal();
        var newCal = getNewCal();
        return newCal;
    };

    $('#nextMonth').click(function(e){
        e.preventDefault();
        var newCal = getNextMonth();
        // console.log(newCal);
        displayNewCal(newCal);
    });
    $('#prevMonth').click(function(e){
        e.preventDefault();
        var newCal = getPrevMonth();
        // console.log(newCal);        
        displayNewCal(newCal);
    });


    // DISPLAYING DAILY MOBILE
    function formatDate(day){
        var dd = String(day.getDate()).padStart(2, '0');
        var mm = String(day.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = day.getFullYear();
        var dowNum=day.getDay();
        var dateVar={
            dd: dd,
            mm: mm,
            yyyy: yyyy,
            dow: daysow[dowNum],
            month: months[day.getMonth()],
            ymd: yyyy+'-'+mm+'-'+dd
        };
        return dateVar;
    };

    function removeListeners(callback){
        // console.log('removing');
        var oldE = document.querySelector('.datesMobile');
        var newE = oldE.cloneNode(true);
        oldE.parentNode.replaceChild(newE,oldE);
        callback();
    }

    function addListeners(){       
        // console.log('adding');
        var datesMobile = document.querySelector('.datesMobile');
        var dayDivs = document.querySelectorAll('.dayDiv');
        for(var i=0;i<dayDivs.length;i++){
            if(i == Math.floor(dayDivs.length/2)+1){
                var newDayBlock=dayDivs[i];
                newDayBlock.classList.add('toL');
                newDayBlock.onclick=function(){mobileNextDay();}; 
                function mobileNextDay(){ 
                    var newDayYmd = document.querySelector('.toL .ymdDate').innerText;                        
                    // console.log(dayDivs);
                    datesMobile.classList.add('slideL');
                    dayDivs[2].classList.remove('today');                
                    dayDivs[3].classList.add('today');
                    datesMobile.style.marginLeft=ogMarg+'px';
                    setTimeout(function(){
                        // dayDivs[3].classList.add('today');
                        console.log(newDayYmd);
                        datesMobile.classList.remove('slideL');                        
                        getSibs(newDayYmd);
                    },300);
                };
            }else if(i == Math.floor(dayDivs.length/2)-1){
                var newDayBlock=dayDivs[i];
                newDayBlock.classList.add('toR');
                newDayBlock.onclick=function(){mobilePrevDay();}
                function mobilePrevDay(){   
                    var newDayYmd = document.querySelector('.toR .ymdDate').innerText;
                    // console.log(dayDivs);
                    datesMobile.classList.add('slideR');
                    dayDivs[2].classList.remove('today');                
                    dayDivs[1].classList.add('today');
                    datesMobile.style.marginLeft=ogMarg+'px';
                    setTimeout(function(){
                        // dayDivs[1].classList.add('today');
                        console.log(newDayYmd);
                        datesMobile.classList.remove('slideR');                        
                        getSibs(newDayYmd);
                    },300);
                };
            }
        }
        // var el=document.querySelector('.datesMobile');
        var el = datesMobile;
        swipedetect(el, function(swipedir){
            // swipedir contains either "none", "left", "right", "top", or "down"
            if(swipedir=='right'){
                mobilePrevDay();
            }else if(swipedir=='left'){
                mobileNextDay();             
            }
        });
    };

    function getSibs(selDay){
        var selDayVar;
        var datesMobile = document.querySelector('.datesMobile');
        datesMobile.innerHTML='';
        var mobileEvsDiv = document.querySelector('.mobileEvsDiv');    
        mobileEvsDiv.innerHTML='';

        if(typeof selDay === 'string'){
            var dayParts = selDay.split('-');
            selDay = new Date(Number(dayParts[0]),Number(dayParts[1])-1,Number(dayParts[2]));
            // console.log(selDay); 
        }

        var sibIncs=[-2,-1,0,1,2];
        var sibsCount=0;
        sibIncs.forEach(inc=>{
            var sibDate = new Date(selDay);
            sibDate.setDate(selDay.getDate()+inc);    
            var dateVar = formatDate(sibDate);
            // console.log(dateVar);

            var newDayBlock = document.createElement('div');
            newDayBlock.classList.add('dayDiv','show');
            if(inc==0){
                newDayBlock.classList.add('today');
                selDayVar=dateVar;
            }
            newDayBlock.innerHTML='<span class="ymdDate">'+dateVar.ymd+'</span><p class="cellDow">'+dateVar.dow+'</p><p class="cellDayNum">'+dateVar.dd+'</p><p class="cellMonth">'+dateVar.month+'</p>';
            $('.datesMobile').append(newDayBlock);

            sibsCount++;
            if(sibsCount==sibIncs.length){
                sibsCount=0;
                // addListeners();
                removeListeners(addListeners);
            }           
        });

        // ADD EVENTS TO EVENT DIV
        var todayEvs=calArr[selDayVar.ymd];
        for(var ev in todayEvs){
            if(todayEvs.hasOwnProperty(ev)){
                var ico;
                var cat = todayEvs[ev];
                // console.log(ev+' '+cat);
                switch(cat){
                    case 'Screen':
                    ico='fas fa-film';
                    break;
                    case 'Education':
                    ico='fas fa-graduation-cap';
                    break;
                    default:
                    ico='fas fa-star';
                    break;
                };
                var newEvDiv=document.createElement('div');
                newEvDiv.classList.add('newEvDiv');
                newEvDiv.innerHTML='<div class="icoWrap"><i class="'+ico+' fa-lg"></i></div><p class="eventTitle">'+ev+'</p>';
                newEvDiv.addEventListener('click',function(){
                    var evTxt = this.querySelector('.eventTitle').innerText;
                    console.log(evTxt);
                    var evLink = evTxt.replace(/\s+/g, '-').toLowerCase();
                    window.location='/event/'+evLink;
                });
                $('.mobileEvsDiv').append(newEvDiv);
                setTimeout(function(){
                newEvDiv.classList.add('evTransform');                

                },500);
            }
        }
    };


    var today = new Date();  
    // var today = new Date('August 31, 2019');
    var dateVar = formatDate(today);    
    var datesMobileC = document.createElement('div');
    // datesMobileC.style.display='none';
    datesMobileC.classList.add('datesMobile');
    $('.box-content').append(datesMobileC);    
    var datesMobile = document.querySelector('.datesMobile');
    var style = datesMobile.currentStyle || window.getComputedStyle(datesMobile);        
    var ogMarg = parseInt(style.marginLeft);
    // console.log(ogMarg);
    // MAKE EVENTS SECTION ON MOBILE
    var mobileEvsDiv = document.createElement('div');
    mobileEvsDiv.classList.add('mobileEvsDiv');
    $('.homeSection.calendar').append(mobileEvsDiv);
    getSibs(today);




// };
//}); // END JQUERY



