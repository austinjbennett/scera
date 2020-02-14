<html><head><style>
    body{
      font-family: "Roboto", "Open Sans", "sans-serif";
    }
    h1{
      padding: 1em;
      margin-left: .5em;
      margin-right: .5em;
      border-bottom: 1px solid black;

      font-family: "Open Sans", sans-serif;
      font-size: 1.8em;
      letter-spacing: -.01rem;
    }
    h2{
      text-transform: uppercase;
    }
    #form-container{
      max-width: 400px;
      margin: auto;
    }
    img{
      margin-top: 1em auto;
      display: block;
    }
    input{
      float: right;
    }
    input[type=submit]{
      background: #eee;
      border: none;
      padding: .5em .8em;
      background-image: linear-gradient(
        to right top,
        #eee 33%,
        #ddd 33%,
        #ddd 66%,
        #eee 66%
      );
      background-size: 3px 3px;
    }
    input[type=submit]:hover{
      cursor: pointer;
      background-image: linear-gradient(
        to right top,
        #eee 33%,
        #ccc 33%,
        #ccc 66%,
        #eee 66%
      );
    }

    input[type="checkbox"] {
     -webkit-appearance:none;/* Hides the default checkbox style */
     height: 20px;
     width: 20px;
     cursor:pointer;
     position:relative;
     -webkit-transition: .15s;
     border-radius:2em;
     background-color:#900;
    }

    input[type="checkbox"]:checked {
     background-color:green;
    }

    input[type="checkbox"]:not(:checked){
      width: 20px;
      height: 20px;
      background: #eee;
      border: 2px solid #ccc;
    }

    input[type="checkbox"]:before, input[type="checkbox"]:checked:before {
     position:absolute;
     top:0;
     left:0;
     width:100%;
     height:100%;
     line-height: 21px;
     text-align:center;
     color:#fff;
     /*content: '✘';*/
    }

    input[type="checkbox"]:checked:before {
     content: '✔';
    }

    input[type="checkbox"]:hover:before {
     background: rgba(255,255,255,0.3);
    }

    input[type="text"], input[type="password"]{
      border: 2px solid #ccc;
      padding-left: 3px;
    }

  </style>
  </head><body><h1>WP-Overwatch Dashboard</h1>
    <div id="form-container">
    <h2>Log In</h2>

		<form name="loginform" id="loginform" action=<?php echo urldecode($_GET['wordpress_login']); ?> method="post">

			<p class="login-username">
				<label for="user_login">Username or Email Address</label>
				<input type="text" name="log" id="user_login" class="input" value="" size="20">
			</p>
			<p class="login-password">
				<label for="user_pass">Password</label>
				<input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
			</p>

			<p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label></p>
			<p class="login-submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In">
				<input type="hidden" name="redirect_to" value=<?php echo urldecode($_GET['return_url']); ?>>
			</p>

		</form>    <img src="http://wordpressoverwatch.com/logo-small.png">
  </div>
  </body></html>
