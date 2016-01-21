=== SO Clean Up Yoast SEO ===
Contributors: senlin
Donate link: http://so-wp.com/donations
Tags: clean up, disable, remove, sidebar ads, cartoon, ads, keyword column, description column, nags, tour, yoast seo, traffic light  
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.7.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Clean up several things that the Yoast SEO plugin adds to your WordPress Dashboard

== Description ==

Almost anyone that uses Yoast SEO by Team Yoast will agree that it is a great SEO plugin, but the developers are adding more and more unwanted things to the WordPress Dashboard.

The purpose of the SO Clean Up Yoast SEO plugin is to clean up all those unwanted things.

As per the current release, the plugin removes and/or disables the following unwanted items:

* removes sidebar ads on all Yoast SEO settings pages
* removes the Yoast SEO settings from the Admin Bar
* removes updated nag
* sets plugin-intro-tour user_meta to true (means done)
* removes the keyword/description columns on edit Posts/Pages pages
* removes the overview dashboard widget that was introduced in version 2.3 of the Yoast SEO plugin
* remove GSC (Google Search Console) nag that was introduced in Yoast SEO 2.3.3
* remove yst_opengraph_image_warning nag that was added to Yoast SEO 2.1, but we never noticed it before. In the changelog it has been described as "validation error", which of course is nonsense, because the world is larger than social media. The nag manifests itself by placing thick red borders around your Featured Image as well as a red-bordered warning message when your Featured Image is smaller than 200x200 pixels.
* remove + icon from new Edit screen UI as it serves only to show an ad for the premium version of Yoast SEO
* remove wpseo-score traffic light next to Move to trash on Edit Post/Page screen
* remove SEO score algorithm recalculate nag

We support this plugin exclusively through [Github](https://github.com/senlin/so-clean-up-wp-seo/issues). Therefore, if you have any questions, need help and/or want to make a feature request, please open an issue over at Github. You can also browse through open and closed issues to find what you are looking for and perhaps even help others.

**PLEASE DO NOT POST YOUR ISSUES VIA THE WORDPRESS FORUMS**

Thanks for your understanding and cooperation.

If you like the SO Clean Up Yoast SEO plugin, please consider leaving a [review](https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform).
Alternatively you are welcome to make a [donation](http://so-wp.com/donations/). Thanks!


== Installation ==

= WordPress =

Search for "SO Clean Up Yoast SEO" and install with the **Plugins > Add New** back-end page.

 &hellip; OR &hellip;

Follow these steps:

 1. Download zip file.

 2. Upload the zip file via the Plugins > Add New > Upload page &hellip; OR &hellip; unpack and upload with your favourite FTP client to the /plugins/ folder.

 3. Activate the plugin on the Plugins page.

Done!


== Frequently Asked Questions ==

= Where is the settings page? =

For [v2.0.0](https://github.com/senlin/so-clean-up-wp-seo/milestones/version%202.0.0) we have planned a complete rewrite of the SO Clean Up Yoast SEO plugin which includes a Settings page to enable the user to clean up individual items, instead of the current blanket rule.

For now however you can stop looking, there is none yet. Activate the plugin to clean up your WordPress dashboard.

= Can I use SO Clean Up Yoast SEO on Multisite? =

You can, but until further notice you need to activate the plugin on a per-site basis ([see this Github issue](https://github.com/senlin/so-clean-up-wp-seo/issues/4))

= You have only five ratings/reviews; why should I install this plugin? =

Yes, you are correct and many people say that it is important for a plugin to have a good rating.
Fortunately there are already quite a few people that have downloaded the plugin, installed it, activated it and keep it activated, as you can see from the Active Installs in the sidebar. There must be something good that the plugin does then, right?

So if you decide to install it and you're happy about the plugin, then please help the next person (and me) by [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform). 

Thank you very much!

= I have an issue with this plugin, where can I get support? =

Please open an issue on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

== Screenshots ==

1. before/after sidebar ads
2. before/after intro tour
3. before/after admin bar
4. before/after yst_opengraph_image_warning nag
5. before/after wpseo-score trafficlight in Publish metabox (Yoast SEO 3.0+)

== Changelog ==

= 1.7.5 (2015.12.26) =

* remove SEO score algorithm recalculate nag

= 1.7.4 (2015.11.19) =

* remove wpseo-score traffic light next to Move to trash on Edit Post/Page screen

= 1.7.3 (2015.11.19) =

* version 3.0 of Yoast SEO has introduced a cool new UI for the Edit screens. This also shows a + icon and when clicking that, you'll have a big fat ad in your face. This is a premium feature and the only function of the + icon therefore is to irritate you with an ad. We have therefore made it invisible. 
* tested up to WP 4.4
* adjust readme files

= 1.7.2 (2015.09.30) =

* [BUG FIX] fix bug that slipped in (forgot to remove) 1.7.1 release, thanks for the [report](https://wordpress.org/support/topic/171-update-problem) [@stansbury](https://wordpress.org/support/profile/stansbury)

= 1.7.1 (2015.09.30) =

* remove function that checks whether Yoast SEO has been installed; reason is to simplify things a bit.
* adjust readme files

= 1.7 (2015.09.16) =

* remove yst_opengraph_image_warning nag that was added to Yoast SEO 2.1, but we never noticed it before. In the changelog it has been described as "validation error", which of course is nonsense, because the world is larger than social media. The nag manifests itself by placing thick red borders around your Featured Image as well as a red-bordered warning message when your Featured Image is smaller than 200x200 pixels.
* change function name
* add screenshot of before/after yst_opengraph_image_warning nag
* adjust readme files

= 1.6 (2015.08.07) =

* remove GSC (Google Search Console) nag that was introduced in Yoast SEO 2.3.3

= 1.5 (2015.07.22) =

* remove overview dashboard widget that was introduced in Yoast SEO 2.3
* change plugin name to reflect the name-change of the plugin it cleans up for ([WordPress SEO became Yoast SEO](https://yoast.com/yoast-seo-2-3/)) 

= 1.4 (2015.06.17) =

* remove updated nag (introduced with Yoast SEO version 2.2.1)
* remove previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards; replaced with with so_cuws_ignore_tour() function

= 1.3.2.1 (2015.05.15) =

* Clean up white space

= 1.3.2 (2015.05.14) =

* Fix issue that WP SEO columns were still showing on Edit Posts/Pages pages 

= 1.3.1 (2015.05.01) =

* Added styling to remove Tour Intro and button to start tour
* Added screenshots
* Removed redundant dashboard widget function 

= 1.3 (2015.04.30) =

* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on [Google+](https://plus.google.com/u/0/+PietBos/posts/AUfs8ZdwLP3)
* put code actions/filters in order

= 1.2 (2015.04.30) =

* Release on wordpress.org Repo

= 1.1 (2015.04.27) =

* Release version 
* banner image (in assets folder) by [Leigh Kendell](https://unsplash.com/leighkendell)

= 1.0 (2015.04.24) =

* Initial plugin [code snippet](https://github.com/senlin/Code-Snippets/blob/0ae24e6fc069efe26e52007c05c7375012ee688a/Functions/Admin-Dashboard/remove-yoast-crap.php)

== Upgrade Notice ==

= 1.5 =

* We have changed the name of our plugin to reflect the name change of the plugin it cleans up after

= 1.4 =

* Version 2.2.1 of the Yoast SEO plugin changes a lot of things around. The automatic redirect to the plugin's About page is no longer, so we have removed the function that disables it. The new version introduced an updated nag that doesn't let itself be dismissed easily, so we have simply hidden it altogether. The super irritating balloon to follow the intro tour was back again too, we have countered that with a functiobn that sets the user_meta of that intro tour to true, which means "seen".

