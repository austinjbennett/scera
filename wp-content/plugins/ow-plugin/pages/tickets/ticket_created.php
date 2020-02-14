<html class='ticket-received'>
<head>
<?php
  $name = isset($_GET['name']) ? ucfirst(htmlentities($_GET['name'])) : null;

  if ( isset($_GET['style_url']) ){
    $url = filter_var($_GET['style_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
    if ($url)
      echo '<link rel="stylesheet" type="text/css" href="'.$url.'">';
  }
?>
</head>
<body>

  <main id="wrap">
  <div id="wrap-inner">

    <h1>Ticket Received</h1>
    <p> <?php echo $name ? $name.", thank" : 'Thank' ?> you for notifying us about your problem. </p>
    <?php if (isset($_GET['redirect_uri']) && $_GET['redirect_uri']): ?>
      <a href='<?php echo $_GET['redirect_uri']; ?>'>Continue ⤑</a>
    <?php endif; ?>

  </div>
  </main>

</body>
</html>
