<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Mock Theatre <?php wp_title(); ?></title>

	<!-- LOAD GOOGLE FONT -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Raleway:300,600" rel="stylesheet">  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600|Oswald" rel="stylesheet">

	<!-- LATEST VERSION OF JQUERY -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 

	<!-- META TAG FOR DO NOT LIE -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><!--', maximum-scale=1' prevents users zooming on mobile-->

<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<!-- STYLE SHEETS -->
<!-- RESET -->
<!-- <link href="<?php echo get_template_directory_uri(); ?>/css/reset.css" rel="stylesheet"> -->
<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- MAIN CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/scss/main.css">
<!-- FONT AWESOME ICONS -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<!-- <script src="jquery-3.3.1.min.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/> -->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- <body> -->
	<header class="headerBG">
		<!-- <div class="container-fluid">		 -->
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-5">
						<div class="logoWrap">
							<!-- <div class="logoDiv"></div> -->
							<a href="/">
								<img class="logoImg" src="https://scera.org/wp-content/themes/scera/images/logo.png">
							</a>
						</div>						
					</div>
					<div class="col-7">
						<!-- <div class="icons">
							<a class="icon search" href="#">
								<i class="fas fa-search"></i>
							</a>
							<a class="icon account" href="#">
								<i class="fas fa-user"></i>
							</a>
							<a class="icon cart" href="#">
								<i class="fas fa-shopping-cart"></i>				
							</a>
							<a class="icon tickets" href="#">
								<i class="fas fa-ticket-alt"></i>
							</a>							
						</div> -->
						<ul class="icons">
							<li class="icon search">			
								<form class="form-inline searchArea" method="get" action="/">
									<input class="sfield" type="search" name="s" placeholder="Search">
									<i class="fas fa-search"></i>
								</form>	
							</li>
							<li class="icon account">
								<i class="fas fa-user"></i>		
								<ul class="accDD hide">
									<?php
										if(is_user_logged_in()){
											echo 
											'<li>
												<a href="/my-account">Account</a>
											</li>
											<li>
												<a href="/logout">Logout</a>
											</li>';
										}else{
											echo 
											'<li>
												<a href="/login">Login</a>
											</li>';
										}
									?>
									
								</ul>				
							</li>							
							<li class="icon cart">
								<i class="fas fa-shopping-cart"></i>
							</li>
							<li class="icon tickets">
								<i class="fas fa-ticket-alt"></i>
							</li>						
						</ul>
						<!-- <div class="row">
							<a class="phoneNum" href="tel:1-801-225-2787">
								<i class="fas fa-phone"></i>
								1-801-225-2787
							</a>
						</div> -->
					</div>
				</div>				
			</div> <!-- end container for header -->
		</header>
		<nav class="navbar navbar-expand-sm justify-content-between myNavbar">
			<div class="container">
				<button class="navbar-toggler hbMenuWrap" type="button" data-toggle="collapse" data-target="#myTopNav">
					<div class="hbMenu" id="hbMenu" onclick="menuClick(this)">
						<div class="bar1"></div>
						<div class="bar2"></div>
						<div class="bar3"></div>
					</div>
				</button>		
				<!-- <form class="form-inline searchArea d-block d-sm-none" method="get" action="/03WordPress">
					<div class="input-group">
						<input class="form-control searchBar sfield" type="search" name="s" placeholder="Search...">
						<div class="input-group-append">
							<button type="submit" class="btn sbtn searchBtn">
								<i class="fas fa-search fa-lg"></i>
							</button>		
						</div>			
					</div>
				</form>	 -->	
				<?php 
					bootstrap_nav(); 
					// wp_nav_menu(array('menu_id' => 'myTopNav','menu_class' => 'topNav'));
				?>	
				<!-- <form class="form-inline d-none d-sm-block searchArea" method="get" action="/03WordPress">
					<div class="input-group">
						<input class="form-control searchBar sfield" type="search" name="s" placeholder="Search...">
						<div class="input-group-append">
							<button type="submit" class="btn sbtn searchBtn">
								<i class="fas fa-search fa-lg"></i>
							</button>		
						</div>			
					</div>
				</form>	 -->	
			</div>
		</nav>
