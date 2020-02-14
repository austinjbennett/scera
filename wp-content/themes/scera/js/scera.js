jQuery(document).ready(function($){
    var top = 100;
    $(window).scroll(function (event){
        var y = $(this).scrollTop();
        if (y >= top)
            $('.totop').css({"top": "0"});
        else
            $('.totop').css({"top": "-300px"});
    });
    var eventBG = $('.event-bg').height();
    var catDesc = $('.category-description').height();
    var highEvent = $('.highlighted-event').height();
    var tall = eventBG - catDesc - highEvent;
    $('.events-list').css("min-height", tall);
});