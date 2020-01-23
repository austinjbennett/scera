// credit: http://www.javascriptkit.com/javatutors/touchevents2.shtml
function swipedetect(el, callback){
  
    var touchsurface = el,
    swipedir,
    startX,
    startY,
    distX,
    distY,
    threshold = 80, //required min distance traveled to be considered swipe
    restraint = 100, // maximum distance allowed at the same time in perpendicular direction
    allowedTime = 300, // maximum time allowed to travel that distance
    elapsedTime,
    now,
    isSwipe,
    startTime,
    handleswipe = callback || function(swipedir){}
  
    touchsurface.addEventListener('touchstart', function(e){
        var touchobj = e.changedTouches[0]
        touchsurface.style.transition='none';
        swipedir = 'none'
        dist = 0
        startX = touchobj.pageX
        startY = touchobj.pageY
        startTime = new Date().getTime() // record time when finger first makes contact with surface
        // e.preventDefault()
    }, false)
  
    touchsurface.addEventListener('touchmove', function(e){
        // if(touchobj.pageY-startY < 200 && touchobj.pageY-startY > -200){
            e.preventDefault() // prevent scrolling when inside DIV
        // }
        var touchobj = e.changedTouches[0]      
        distX=touchobj.pageX - startX;
        elapsedTime = new Date().getTime() - startTime;
        var speed = Math.abs(distX)/elapsedTime;
        // console.log(distX);
        // console.log(elapsedTime);
        // if((distX < 30 && distX > -30) || elapsedTime >= allowedTime){
        // if(speed < 1 || Math.abs(distX)>threshold || (elapsedTime>50 && Math.abs(distX)<-70)){
        if(speed < 1){
            touchsurface.style.marginLeft=ogMarg + distX+'px';            
        }
        if(elapsedTime>100){      
            console.log('touchmove');
            // touchsurface.style.marginLeft=ogMarg + distX+'px';
            var nextElm = document.querySelector('.toL');
            var nextDate = document.querySelector('.toL .ymdDate').innerText;
            var prevElm = document.querySelector('.toR');
            var prevDate = document.querySelector('.toR .ymdDate').innerText;  
            var curElm = document.querySelector('.today');
            if(distX < -120){
                curElm.classList.remove('today');
                nextElm.classList.add('today');
                touchsurface.classList.add('slideL');
                setTimeout(function(){
                    touchsurface.classList.remove('slideL');
                    touchsurface.style.marginLeft=ogMarg+'px';
                    getSibs(nextDate);
                },300);
            }else if(distX > 120){
                curElm.classList.remove('today');
                prevElm.classList.add('today');
                touchsurface.classList.add('slideR');
                setTimeout(function(){
                    touchsurface.classList.remove('slideR');
                    touchsurface.style.marginLeft=ogMarg+'px';
                    getSibs(prevDate);
                },300);
            }
        }

    }, false)
  
    touchsurface.addEventListener('touchend', function(e){
        // console.log('here');
        console.log('touchend');
        touchsurface.style.transition='.2s';
        var touchobj = e.changedTouches[0]
        distX = touchobj.pageX - startX // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.pageY - startY // get vertical dist traveled by finger while in contact with surface
        elapsedTime = new Date().getTime() - startTime // get time elapsed
        var speed = Math.abs(distX)/elapsedTime;
        if (elapsedTime <= allowedTime && speed>1){ // first condition for awipe met
            // touchsurface.style.marginLeft=ogMarg+'px';
            if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint){ // 2nd condition for horizontal swipe met
                swipedir = (distX < 0)? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
            }
            else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint){ // 2nd condition for vertical swipe met
                swipedir = (distY < 0)? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
            }
        }else{
            touchsurface.style.marginLeft=ogMarg+'px';           
        }
        handleswipe(swipedir)
        // e.preventDefault()
    }, false)
}
  
//USAGE:
// var el = document.getElementById('calendar');
// swipedetect(el, function(swipedir){
//     // swipedir contains either "none", "left", "right", "top", or "down"
//     console.log(swipedir);
//     if(swipedir=='left'){
//         mobilePrevDay();
//     }else if(swipedir=='right'){
//         mobileNextDay();
//     }
//     // el.innerHTML = 'Swiped <span style="color:yellow;margin: 0 5px;">' + swipedir +'</span>';
// });
