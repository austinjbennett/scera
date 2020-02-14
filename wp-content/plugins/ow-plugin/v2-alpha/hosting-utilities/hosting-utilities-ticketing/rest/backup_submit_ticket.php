<?php
/* If the ticket service isn't working, this can be used as a backup to send in a ticket directly to teamworks via email */

http_response_code(500);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('A POST request must be used');
}

if ( ! isset($_POST['nonce'])
     && ! (isset($_POST['title']) || $_POST['message'] )
   ){
    http_response_code(422);
    exit('Please supply a nonce and supply a title or message');
}

require '../common/rest_init.php';
rest_init([
    'wp_load_mode' => 'no_plugins',
    'verify_nonce' => [ 'nonce_name'=>'email_hu_ticket', 'nonce'=>$_POST['nonce'] ],
    'approved_permissions' => ['administrator', 'hu_submit_tickets']
]);

$to = hu_branding('ticketing email') ?: hu_branding('email');
if ( ! filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
    echo 'Invalid email address';
    exit;
}
// The subject and body don't need sanitization. If they contain a bad payload,
// that same email could be delivered to us without having to go through this API
wp_mail($to, $_POST['title'], $_POST['message']);

http_response_code(200);
echo 'email sent successfully';
