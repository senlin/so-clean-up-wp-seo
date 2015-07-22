=== SO Clean Up Yoast SEO ===
Contributors: senlin
Donate link: http://so-wp.com/donations
Tags: yoast seo, wordpress seo, yoast, seo, remove, disable, about, tour, sidebar, ads, columns 
Requires at least: 4.0
Tested up to: 4.3-beta
Stable tag: 1.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Clean up several things that the Yoast SEO plugin adds to your WordPress Dashboard

== Description ==

Almost anyone that uses Yoast SEO by Team Yoast will agree that it is *the* SEO plugin, but the developers are adding more and more unwanted things to the WordPress Dashboard.

The purpose of the SO Clean Up Yoast SEO is to clean up all those unwanted things.

As per the current release, the plugin removes and/or disables the following unwanted items:

* removes sidebar ads on all Yoast SEO settings pages
* removes the Yoast SEO settings from the Admin Bar
* removes updated nag
* sets plugin-intro-tour user_meta to true (means done)
* removes the keyword/description columns on edit Posts/Pages pages
* removes the overview dashboard widget that was introduced in version 2.3 of the Yoast SEO plugin 

We support this plugin exclusively through [Github](https://github.com/senlin/so-clean-up-wp-seo/issues). Therefore, if you have any questions, need help and/or want to make a feature request, please open an issue over at Github. You can also browse through open and closed issues to find what you are looking for and perhaps even help others.

**PLEASE DO NOT POST YOUR ISSUES VIA THE WORDPRESS FORUMS**

Thanks for your understanding and cooperation.

If you like the SO Clean Up Yoast SEO plugin, please consider leaving a [review](http://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo#postform) or making a [donation](http://so-wp.com/donations/). Thanks!


== Installation ==

= Wordpress =

Search for "SO Clean Up Yoast SEO" and install with the **Plugins > Add New** back-end page.

 &hellip; OR &hellip;

Follow these steps:

 1. Download zip file.

 2. Upload the zip file via the Plugins > Add New > Upload page &hellip; OR &hellip; unpack and upload with your favourite FTP client to the /plugins/ folder.

 3. Activate the plugin on the Plugins page.

Done!


== Frequently Asked Questions ==

= Where is the settings page? =

You can stop looking, there is none. Activate the plugin to clean up your WordPress dashboard.

= I have an issue with this plugin, where can I get support? =

Please open an issue on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

== Screenshots ==

1. before/after sidebar ads
2. before/after intro tour
3. before/after admin bar

== Changelog ==

= 1.5 =

* date: 2015.07.22
* remove overview dashboard widget that was introduced in Yoast SEO 2.3
* change plugin name to reflect the name-change of the plugin it cleans up for ([WordPress SEO became Yoast SEO](https://yoast.com/yoast-seo-2-3/)) 

= 1.4 =

* date: 2015.06.17
* remove updated nag (introduced with Yoast SEO version 2.2.1)
* remove previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards; replaced with with so_cuws_ignore_tour() function

= 1.3.2.1 =

* date: 2015.05.15
* Clean up white space

= 1.3.2 =

* date: 2015.05.14
* Fix issue that WP SEO columns were still showing on Edit Posts/Pages pages 

= 1.3.1 =

* date: 2015.05.01
* Added styling to remove Tour Intro and button to start tour
* Added screenshots
* Removed redundant dashboard widget function 

= 1.3 =

* date: 2015.04.30
* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on [Google+](https://plus.google.com/u/0/+PietBos/posts/AUfs8ZdwLP3)
* put code actions/filters in order

= 1.2 =

* Release on WP.org Repo (2015.04.30)

= 1.1 =

* Release version (2015.04.27)
* banner image (in assets folder) by [Leigh Kendell](https://unsplash.com/leighkendell)

= 1.0 =

* Initial plugin [code snippet](https://github.com/senlin/Code-Snippets/blob/0ae24e6fc069efe26e52007c05c7375012ee688a/Functions/Admin-Dashboard/remove-yoast-crap.php) (2015.04.24)

== Upgrade Notice ==

= 1.5 =

* We have changed the name of our plugin to reflect the name change of the plugin it cleans up after

= 1.4 =

* Version 2.2.1 of the Yoast SEO plugin changes a lot of things around. The automatic redirect to the plugin's About page is no longer, so we have removed the function that disables it. The new version introduced an updated nag that doesn't let itself be dismissed easily, so we have simply hidden it altogether. The super irritating balloon to follow the intro tour was back again too, we have countered that with a functiobn that sets the user_meta of that intro tour to true, which means "seen".

