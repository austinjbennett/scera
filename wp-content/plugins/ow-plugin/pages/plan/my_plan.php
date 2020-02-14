<div id="wrap">
<div id="wrap-inner">

<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';
}
//get_token() has the side effect of loading a leightweight version of the WordPress environment, if WordPress hasn't already been loaded
$api_key = get_token(null, $wpDir="../../../../../wp-load.php");

// // Example of how to update data, when it comes time to do that
// $id = 74;
// $json = wp_remote_post("https://clients.wordpressoverwatch.com/wp-json/acf/v3/websites/$id", array(
//   'method' => 'POST',
//   'blocking' => true,
//   'headers' => array("Authorization" => " Basic YXBpOjdIcVcgbG9xMSBiYUExIFRLYjUgZFVJbSBjaHlv"),
//   'body' => array('token' => $api_key, 'fields[notes][public_notes]'=>'test')
//   //'body' => array('token' => $api_key, 'fields[features][security_price]'=>'10')
// ))['body'];
//
// echo '<pre>';
// echo $json;
// var_export(json_decode($json, JSON_PRETTY_PRINT));
// echo '</pre>';
// exit;

$resp = wp_remote_get("https://info.wordpressoverwatch.com/wp-json/acf/v3/websites/?token=$api_key");
if ( ! $resp || is_wp_error($resp) ){
  echo '<h1>Unable to fetch plan details. <a href="https://servers.wp-overwatch.com/">The server might be down</a></h1>';
} else{
  $json = $resp['body'];
  $data = json_decode($json, true);
  if ( $data === null || isset($data['data']['status']) && $data['data']['status'] >= 400 ) {
    echo '<h1>Failed to fetch plan details</h1>';
  } else if ( empty($data) ) {
    echo '<h1>We have not yet entered your plan details into our system</h1>';
  } else{
    ?>
      <h1>My Plan</h1>
      <i><p>We have new plans that you might be interested in. <a href="<?php echo ow_branding('pricing url'); ?>">More info</a>.</p></i><br/>
    <?php


    /* Get the current features associated with their plan */
    if (!isset($data[0]['acf']['plan']['all_features'])){
      echo '<h1>Received Invalid Data</h1>';
      return;
    }
    $features = $data[0]['acf']['plan']['all_features'];

    /* Fetch descriptions for the different plan features */
    $description_fields = get_plan_descriptions($api_key);
    if ( ! $description_fields || ! isset($description_fields['acf']['feature_descriptions']) ){
      echo '<h1>Failed to get plan descriptions</h1>';
      return;
    }
    $description_fields = $description_fields['acf']['feature_descriptions'];
    $descriptions = array();
    foreach($description_fields as $field){
      $descriptions[$field['feature_field']] = $field['description'];
    }

    /* combine the plan features with the descriptions, and output what they've signed up for */
    $out = array();
    foreach($features as $feature){
      $out[$feature] = $descriptions[$feature] ?? '';
    }
    echo '<div class="plan">';
    display_data($out);
    echo '</div>';

  }
}

function display_data($data, $depth=1){
  echo '<ul style="list-style-type: none;">';
  _display_data($data, $depth);
  echo '</ul>';
}
function _display_data($data, $depth=1){
  foreach($data as $name => $item){
    if (is_array($item)){
      if ($depth !=1)
        echo '<br/><li class="plan-depth-'.$depth.' plan-header" style="text-transform:uppercase;"><b>=='.$name.'==</b></li>';
      display_data($item, ++$depth);
    } else{
      $name = str_replace('_', ' ', $name);
      echo '<li class="plan-depth-'.$depth.'"><b style="text-transform:capitalize">'.$name.':</b> '.$item.'</li>';
    }
  }

}

function get_plan_descriptions($api_key){
  #TODO cache response in WP cache

  $resp = wp_remote_get("https://info.wordpressoverwatch.com/wp-json/acf/v3/options/plan-descriptions?token=$api_key");
  if ( ! $resp || is_wp_error($resp) )
    return false;

  $json = $resp['body'];
  $data = json_decode($json, true);
  if ( empty($data) || isset($data['data']['status']) && $data['data']['status'] >= 400 )
    return false;
  return $data;

}

?>

</div>
</div>
