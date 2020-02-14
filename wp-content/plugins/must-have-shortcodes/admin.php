<?php
/*
	This file contains all the information regarding how to implement the shortcodes as it is fed to the admin settings page for this plugin.
	Unless your a Sebo developer tasked in working on this plugin, you shouldn't be here :)
*/
?>
<div style="width:60%; background:#DDE4EA; padding:10px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); margin-top:10px;">
	<h1>Must Have Shortcode Documentation</h1>
	<h3>Here you will find everything you need to implement these shortcodes.</h3>

	<h2></h2>
	<div style="padding-left:15px;">
		<p></p>
	</div>
	<hr>
	
	<p>All shortcodes added from this plugin have the attribute 'class' which can be applied like this: [shortcode class="YourClass"]. Which add the class to the element.  This feature is to assist in custom styling.</p>

	<h2>Blog Info</h2>
	<div style="padding-left:15px;">
		<p>[bloginfo show=""] This allows you to use the websites url or template path without hardcoding it in.  See <a href="http://codex.wordpress.org/Function_Reference/bloginfo" target="_blank">Bloginfo info</a> for what to put in show.</p>
	</div>
	<hr>
	
	<h2>Clear</h2>
	<div style="padding-left:15px;">
		<p>[clear] This will add the div with clear:both.  This is used to reset the html flow after floats.</p>
	</div>
	<hr>
	
	<h2>Columns</h2>
	<div style="padding-left:15px;">
		<p>[columns][/columns] must wrap around all of your columns.  If you want more then one row of columns, then use more then one [column] tag.  It will automatically fit the number of columns you want into the row.</p>
		<p>Inside of the columns tag you need to put [col][/col] around each columns content.  See example below.</p>
		<p>[columns]<br>
			[col] First Column [/col]<br>
			[col] Second Column [/col]<br>
			[col] Third Column [/col]<br>
		[/columns]
		</p>
	</div>
	<hr>
	
	<h2>Email</h2>
	<div style="padding-left:15px;">
		<p>[email]myemail@example.com[/email]  This will obfuscate the email address and help prevent spam.  It also makes the email address clickable where it loads your browsers default emailer.</p>
	</div>
	<hr>
	
	<h2>Google Drive PDF Print</h2>
	<div style="padding-left:15px;">
		<p>[pdf_googledrive link="www.PDFLINK.com/LINKPATH.pdf"]LINK TEXT[/pdf_googledrive]</p>
		<p>Put a link to the pdf as link and the clickable text to view it inside of the shortcode, and the pdf will open with Google Drive.</p>
	</div>
	<hr>
	
	<h2>Google Maps</h2>
	<div style="padding-left:15px;">
		<p>[googlemap width="600" height"360" src="http://google.com/maps/?ie=..."]  Width and height are optional. This adds an iframe of the google map into your site.</p>
	</div>
	<hr>
	
	<h2>Related Posts</h2>
	<div style="padding-left:15px;">
		<p>[related_posts limit=""] This will show related posts to the current post based on tags.  Some styling may be required.</p>
	</div>
	<hr>
	
	<h2>RSS Feed</h2>
	<div style="padding-left:15px;">
		<p>[rss feed=" " num=" "]  This will embed another websites RSS feed into yours.  Put the link to the feed as 'feed' and the number of posts as 'num'.</p>
	</div>
	<hr>
	
	<h2>Table</h2>
	<div style="padding-left:15px;">
		<p>[table columns="" background_colors="" text_colors="" sizes=""] [field][/field] [/table]  Create a table.  Set the number of columns and if desired override the default background colors, text colors and column widths.</p>
		<p>Here is how you override the colors: [table columns=3 background_colors='ffffff,fbfafb,f0eff0' text_colors="000000,121212,565656"].  It takes the hex codes in order and applies them to the respective column.  If you only want to set a color of single column then just leave the others blank.  Ex. background_colors=" , ,ffffff" this will change just the third column to white, note the commas.</p>
		<p>Here is how you set up the sizes: [table columns=3 sizes='25,50,35'].  They are percents. If you leave any blank, it will divide the unused space by the number of columns left blank.</p>
		<p>The [field][/field] is for each item of your table.  It will automatically organize it based on number of columns.</p>
	</div>
	<hr>
	
	<h2>Toggle</h2>
	<div style="padding-left:15px;">
		<p>There are two shortcodes to get the toggle working.  The first is [toggle_init][/toggle_init], which adds the neccessary jQuery for the toggle to work.</p>
		<p>Each toggle must be wrapped in this shortcode [toggle title=" "][/toggle].  The title is the text that is displayed while the toggle is closed.  The content which shows only when toggled open, should be put between the two tags.</p>
		<p>[toggle_init]<br>[toggle title="Click here to read more Lorem Ipsum"] Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.  [/toggle]<br>[/toggle_init]</p>
	</div>
	<hr>
	
	<h2>Twitter Share</h2>
	<div style="padding-left:15px;">
		<p>[twitter url="" counturl="" via="" text="" related="" countbox=""]  This plugin creates the official twitter share button.  If you leave the url blank then it will automatically grab the current page.  Countbox has three options: none, horizontal and vertical.  Related lets you define which related accounts show up after you’ve tweeted.  The text will show up in the composed tweet.</p>
	</div>
	<hr>













</div>