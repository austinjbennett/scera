<?php

// You can override these during development, and this will take effect inside all the php files in this directory.
// Example:
//   $domain = 'zionssecurity.com';
//   $notpassUser = 'scotty';
//   $notpassKey = file_get_contents(__dir__.'/_nocommit key.txt');

$domain = parse_url( home_url() )['host'];
$notpassUser = 'https://' . $domain;
$notpassKey = get_hu_api_key($domain);
