
<!-- OG HEADER W/ ICONS AND SEPARATE NAV BAR -->
<!-- <header class="headerBG clearfix">
	<div class="container">
		<div class="row justify-content-between clearfix">
			<div class="column small-2">
				<div class="logoWrap">
					<a href="/">
						<img class="logoImg" src="https://scera.org/wp-content/themes/scera/images/logo.png">
					</a>
				</div>						
			</div>
			<div class="column small-2">
				<ul class="icons">
					<li class="icon search">			
						<form id="searchForm" class="form-inline searchArea" method="get" action="/">
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
			</div>
		</div>				
	</div> 
</header> -->
<!-- <nav class="navbar navbar-expand-sm justify-content-between myNavbar">
	<div class="hbMenu" id="hbMenu" onclick="menuClick(this)">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
	</div>
	<?php wp_nav_menu(array('menu_id' => 'myTopNav','menu_class' => 'topNav'));?>	
</nav> -->

<!-- HARDCODED CAROUSEL -- BOOTSTRAP -->
<!-- <div id="homeCaro" class="carousel slide homeCaro" data-ride="carousel">
	<ol class="carousel-indicators">
	 	<li style="list-style-type: none;">
	<ol class="carousel-indicators">
	 	<li class="active" data-target="#homeCaro" data-slide-to="0"></li>
	</ol>
	</li>
	</ol>

	<ol class="carousel-indicators">
	 	<li style="list-style-type: none;">
	<ol class="carousel-indicators">
	 	<li data-target="#homeCaro" data-slide-to="1"></li>
	</ol>
	</li>
	</ol>

	<ol class="carousel-indicators">
	 	<li data-target="#homeCaro" data-slide-to="2"></li>
	</ol>
	<div class="carousel-inner">
		<div class="carousel-item active caro1"><a href="stage.php">
		<img class="d-block w-100" src="[wbcr_php_snippet id='29'][/wbcr_php_snippet]/img/stageCurtain.jpg" alt="First slide"></a>
		<div class="caroOverlay"></div>
		<div class="carousel-caption">
		<h3>Stage</h3>
		</div>


		</div>
		<div class="carousel-item caro2"><a href="screen.php">
		<img class="d-block w-100" src="[wbcr_php_snippet id='29'][/wbcr_php_snippet]/img/screenTheatre.jpg" alt="Second slide"></a>
		<div class="caroOverlay"></div>
		<div class="carousel-caption">
		<h3>Screen</h3>
		</div>


		</div>
		<div class="carousel-item caro3"><a href="education.php">
		<img class="d-block w-100" src="[wbcr_php_snippet id='29'][/wbcr_php_snippet]/img/educationPaint.jpg" alt="Third slide"></a>
		<div class="caroOverlay"></div>
		<div class="carousel-caption">
		<h3>Education</h3>
		</div>
		</div>
	</div>
</div> -->


<!-- *********** FOOTER CONTACT FORM, ABOUT, NEWSLETTER ************** -->
<!-- <div class="container">
		<div class="row">
			<div itemscope itemtype="http://schema.org/LocalBusiness" class="col-xs-12 col-sm-6 col-md-4">
				<h1>Contact Us</h1>
				<img itemprop="image" class="footLogo" src="<?php echo get_template_directory_uri(); ?>/img/logo.png">
				<p itemprop="priceRange" style="display:none">10 to 999</p>
				<h2 itemprop="name">SCERA</h2>
				<h3>Address:</h3>
				<a href="https://goo.gl/maps/rgqkWTqw1qH2" itemprop="address">SCERA Park, 600 S 400 E, Orem, UT 84058</a>
				<h3>Phone:</h3>
				<a href="tel:1-801-225-2787" itemprop="telephone" content="+18005551234">1-801-225-2787</a>
				<h3>Hours of Operation:</h3>
				<p itemprop="openingHours" datetime="Mo-Fr 9:00-16:00">Monday – Friday 9am – 4pm</p>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<h1>NewsLetter</h1>				
				<p>Subscribe to our email list and stay up-to-date with our hottest offers and latest specials.</p>
				<h3>Email Address:</h3>				
				<form class="form-inline newsLetterForm" method="post" action="#">
					<div class="input-group emailInputG">
						<input type="Email" name="email" placeholder="example@mail.com" required>
						<div class="input-group-append">
							<button class="inputBtn" type="submit">
								<p>Sign Me Up!</p>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-xs-12 col-md-4">
				<h1>Why Scera</h1>
				<p>Our theatre has been a staple of local life and culture for decades. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			</div>
		</div>
	</div>  -->

	<!-- *********** BOOTSTRAP ETC CDN LINKS FOR BOTTOM OF FOOTER  ************** -->
		<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/core.js" integrity="sha256-36hEPcG8mKookfvUVvEqRkpdYV1unP6wvxIqbgdeVhk=" crossorigin="anonymous"></script> -->
	<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
		<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->

<!-- *********** HOME PAGE WITH COLUMNS MAY REMOVE BOOTSTRAP CLASSES  ************** -->
<!-- <div class="row">
<div class="col-xs-12 col-md-4 column small-4 medium-5">
	<h2>Calendar</h2>
	<div class="calWrap">
		<?php echo do_shortcode('[myEventCalendar]'); ?>
	</div>
</div>

<div class="col-xs-12 col-sm-6 col-md-4 column small-4 ">
	<h2>UPCOMING EVENTS</h2>
	<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
</div>

<div class="col-xs-12 col-sm-6 col-md-4 column small-4 ">
	<h2>FEATURE SECTION</h2>
	<p>This section can be used to highlight classes, gift cards, merchandise, or donation requests. It even comes with a handy call to action button.</p>
	<button>CTA</button>
</div>

<div class="col-xs-12 col-sm-6 col-md-4 column small-4 medium-3">
	<img class="d-block w-100 colTopImg homeImg" src="<?php echo get_template_directory_uri(); ?>/img/shell.jpg" alt="SCERA Shell Theatre">
	<h2>EXPERIENCE OUR VENUES</h2>
	<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
</div>

<div class="col-xs-12 col-sm-6 col-md-4 column small-4 medium-3">
	<h2>THANKS TO OUR SPONSORS</h2>
	<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti.</p>
</div>

</div> -->