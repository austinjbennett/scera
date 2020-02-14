=== Permalink Finder Plugin ===
Tags: permalinks, move, migrate, 301, 404, redirect, PageRank, seo,sitemap, robots.txt, crossdomain.xml, apple-touch-icon.png, favicon.ico
Requires at least: 3.0  
Stable tag: 2.8  
Tested up to: 5.1
Contributors: Keith Graham       
Donate link: https://www.facebook.com/BlogsEye/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Never get a 404 page not found again. If you have restructured or moved your blog, this plugin will find the right post or page every time.

== Description ==

The Permalink Finder Plugin detects when Wordpress cannot find a permalink. Before it generates the 404 error the plugin tries to locate any posts with similar words. It does this by searching through the database trying to find any of the word values from the bad link. It takes the best match and then, rather than issuing a 404 error, it sends back a redirect to the correct page.
Users will see the page that they are looking for, and search engine spiders will see the 301 redirect and update their databases so that the page appears correctly in searches.

This is especially useful where Wordpress removes words like "the" and "a" from the permalink during conversions from Blogger.com accounts. It is also useful for migrations that formerly used extensions such as html and shtml, when Wordpress does not.

The configuration panel allows a user to select how the plugin finds a missing page. The plugin counts the number of words that match to a post. By default, a two word match is sufficient to cause a redirect to the found page. False positives are possible, especially if the user selects a one word match. Increasing the number of words, however makes it unlikely that the plugin will ever find a match. You may eliminate numbers from the search. You may specify that a list of common English words like "the", "and", "who", "you", etc., not be considered in finding the correct permalink.

Optionally, the plugin will redirect hits on index.html, index.htm, and index.shtml to the blog home page. This is useful when a website previously used a non-php home page.

If WordPress detects a 404 error on robots.txt, sitemap.xml, crossdomain.xml, favicon.ico, or apple-touch-icon.png it will provide a default version.

The plugin will also optionally keep track of the last few 404's or redirects. This is useful to find out what pages are missing or named badly that keep causing 404 errors or forcing redirects.


== Installation ==

1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. Change any options in the Permalink Finder settings.
The plugin can be tested by adding or deleting words from a working permalink in your browser address area. Even if you mangle the permalink it should find a valid link and almost always it will find the correct link. 


== Changelog ==

= 2.8 =
* Changed the name of a function that used the mysql_ string so that it would not trigger compatibility testers.

= 2.7 =
* Minor bug where it was not finding yyyy/mm archives when missing, but file was located somewhere else.

= 2.6 =
* Fixed mysqli_escape_string error.

= 2.5 =
* Escaping sql strings in PHP has changed in PHP7. The old MySQL escape fails. I added checks for the existence of the correct functions in case PHP is misconfigured or the default changes again. Many installs fail to install all the libraries needed, which makes this hard to debug.

= 2.4 =
* re-released the plugin so that instances of esc_sql can be updated to mysql_real_escape_string. It appears that not everyone got the change.

= 2.3 =
* ignore search queries - s=search - as this is not a 404 and it caused looping.
* Ignore feed requests as there is no permalink for things starting with /feed/
* fixed load order and deleted an early an unecessary call to get_options
* changed the way MU blog options are loaded.
* fixed the links in the options page
* Put in code to avoid recursive redirects. If redirect equals current page then let wordpress 404 it.
= 2.0 =
* Rewrote entire plugin to be more compatible with new versions of WordPress. Simplified the code and added extra steps to sanitize data and increase security. Added support for default robots.txt, sitemap.xml, crossdomain.xml, favicon.ico, or apple-touch-icon.png files. Added metaphone search. Ignores 404 errors on wp-login and wp-signup from trolls. Sanitizes data so there is less chance of options and logs being reset.

= 2.2 =
* 10/2/2012 Fixed many small but annoying bugs.
* search for exacts matches on categories
* changed the way MU functions work so that MU options can only be set on the Network Admin Dashboard.
* Under MU users cannot see the Permalink options unless the admin sets the MU switch in the permalink finder options.
* Keeps a grand total of the permalinks fixed since the plugin was installed (or version 2.2).
* converts underscores to hyphens.
* added option to load the actual page and change the "404 not found" to a "200 found". This would be useful in SEO when a redirect would not help. It essentially keeps the old permalink structure intact and makes no effort to inform requestors of the change. Creates the ability to type any keyword as a permalink and get a related page without a redirect.
* thanks to siddkb1986 who posted at the Wordpress plugin support page on Wordpress.org about query strings being lost. I incorporated the changes suggested.
* Delayed loading of 404 processing in order to conserve memory resources. Only loads the redirection functions after a 404 has been detected.

= 2.1 =
* 07/20/2012 Fixed issue with error logging. System now displays crash logs so that they can be checked. Changed the way certain Server variables are accessed. The REQUEST_URI was not being set on some hosts. Fixed an error with redirects that had only one token on the original URL. Remove /archive/ from links before checking. Added a reason to the reports in order to get a sense of how a permalink is redirected. Added options to control exactly how the plugin searches for a permalink. Ignores (but logs) many types of files that are normally not things that WordPress controls (images, js, css, pdf, etc). Removed the index/default redirect option, as the plugin now does this as side effect of cleaning the slug.

== Support ==
This plugin is free and I expect nothing in return. Please rate the plugin at: http://wordpress.org/extend/plugins/permalink-finder/
If you wish to support my programming, You can donate or buy my book: 
https://www.facebook.com/BlogsEye
Error Message Eyes: A Programmer's Guide to the Digital Soul

