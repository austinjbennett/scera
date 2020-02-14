# README #

Authenticates the current visitor is logged in to WordPress with permissions to view the dashboard. Also provides an endpoint for the reporting server to update the API token the dashboard uses to authenticate with the reporting server after a user is deemed to be able to view the dashboard.

### Verify a user can view the dashboard through the rest API ###
~~~~
Send a post request here to authenticate the current user and retrieve the required token for communicating with the reporting server
@end-point
 /wp-authenticator/index.php
@args
 website = (optional) if passed in, we will validate the wordpress installation we are communicating with is for this website
 wordpress-dir = (optional) the directory to the wp-load.php file if different from the default "../wp-load.php"
@returns
 returns either the token or the string "Error: " followed by the error message
~~~~

### Verify a user can view the dashboard from PHP ###


```php
require "wp-authenticator/auth.php";

//If $website is passed in, an additional check will be done to verify we are using the correct website. This check isn't really necessary.
//Some websites have WordPress installed in a sub-directory, in which case $wpDir needs to be set appropriately.
$token = get_token($website="website.com", $wpDir="../wp-load.php");
```

### Redirecting the user to the login page ###

~~~~
 Use the url: wp-authenticator/login.php?wordpress_login="URL_OF_WP-LOGIN.PHP"&return_url="WHERE_TO_REDIRECT_AFTER_LOGGING_IN"
 (Note: The provided login page is buggy. It redirects somewhere else if a bad login is given. It's recommended to use wordpress's default login page)
~~~~

### Update the token used for the dashboard <==> reporting server authentication ###

~~~~
 Recieves a post request to update the token used to communicate with the reporting server
 @end-point
  /wp-authenticator/save-dashboard-token.php
 @post-data args
  api_token = the password for using this API
  token_to_save = The new token the dashboard will need to use for communicating with the reporting server
 @returns
  returns either the string "success" or a failure message
~~~~

### The login form ###
~~~~
 There is a login form at `/wp-authenticator/login.html` that can be shown when authentication fails
~~~~

#

![Company Logo][1]
[1]: https://wordpressoverwatch.com/logo-small.png "WP OverWatch Logo"
