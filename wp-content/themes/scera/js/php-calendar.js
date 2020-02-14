jQuery(document).ready(function($){

    var m = new Date();
    var n = m.getMonth();
    var y = m.getFullYear();
    var months = new Array(12);
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

    function doAJAX() {
        $('#month-name').text(months[n] + ' ' + y);

        $.ajax({
            url: 'php-calendar.php',
            type: 'post',
            data: {'month': n, 'year': y },
            success: function(text) {
                response = text;
                $('#calendar').html(response);
            }
        });
    }

    $('#month-dec').on('click', function(e) {
        e.preventDefault();
        n--;
        if(n < 0) {
            n = 11;
            y--;
        }
        doAJAX();

    });
    $('#month-inc').on('click', function(e) {
        e.preventDefault();
        n++;
        if(n > 11) {
            n = 0;
            y++;
        }
        doAJAX();
    });

});