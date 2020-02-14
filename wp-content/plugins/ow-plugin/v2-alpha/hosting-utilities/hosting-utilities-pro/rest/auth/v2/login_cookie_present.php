<?php
/* DOES NOT VALIDATE THE USER
This endpoint simplt returns 'true' if the user has a logged in cookie otherwise it returns 'false'.
Further validation will then need to be performed to validate if it is a valid cookie.
This is useful info to know if the user needs to be logged in or if there credentials need to be validated
Having a separate endpoint for this prevents needing to resend login credentials with each request
*/

function has_logged_in_cookie(){
    foreach ($_COOKIE as $cookie => $v){
        if (strpos($cookie, 'wordpress_logged_in_', 0) === 0)
            return true;
    }
    return false;
}

echo has_logged_in_cookie() ? 'true' : 'false';
