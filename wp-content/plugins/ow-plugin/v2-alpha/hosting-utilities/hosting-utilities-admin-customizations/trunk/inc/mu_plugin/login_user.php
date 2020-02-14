<?php

require '../init/init.php';

$user_info = verify_user($_POST['username'], $_POST['password']);

if ($user_info){

  $_SESSION['username'] = $_POST['username'];
  $_SESSION['password'] = $_POST['password'];

  $tomorrow = time() + 60*60*24;

  setcookie('username', $user_info['username'], $tomorrow, '/', empty($_SERVER['HTTPS']), true);
  setcookie('firstname', $user_info['firstname'], $tomorrow, '/', empty($_SERVER['HTTPS']), true);
  setcookie('lastname', $user_info['lastname'], $tomorrow, '/', empty($_SERVER['HTTPS']), true);

  header("location: $base_url");
  exit;
  ?>

<?php } else { ?>

  <h1>Login Failed</h1>
  <a href="<?php echo $base_url."login-register"; ?>">Back</a>

<?php } ?>
