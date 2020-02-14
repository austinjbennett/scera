<?php $maintenance_page = "/home/244555.cloudwaysapps.com/saaxcthxjf/public_html/wp-content/plugins/ow-plugin/v2-alpha/hosting-utilities/hosting-utilities-aaa-options/trunk/inc/lockout_notices/updating_page.php";
if (file_exists($maintenance_page)){
  include $maintenance_page;
} else{
    ?><h2>Maintenance Mode</h2><p>The website will be back up in a few moments</p><?php
}