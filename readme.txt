=== SO Hide SEO Bloat ===
Contributors: senlin, afragen
Donate link: https://so-wp.com/plugins/donations
Tags: hide, seo, bloat, remove, ads, cartoon, wordpress seo addon, admin columns, nags, traffic light, dashboard widget, hide premium 
Requires at least: 4.7.2
Tested up to: 4.8
Stable tag: 3.0.0
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Free addon for the Yoast SEO plugin to hide the bloat it adds to the WordPress backend.

== Description ==

Almost anyone who uses the Yoast SEO plugin will agree that it is a good SEO plugin, but the developers are adding more and more unwanted things to the WordPress backend.

The purpose of the SO Hide SEO Bloat plugin, a free addon for the Yoast SEO plugin, is to clean up all those unwanted things.

Since version 3.0.0 we have _simplified_ the settings page. The radio buttons with all/none choices are replaced with checkboxes for everything. Ticking the box means hiding (or removing) that particular "feature".

It is a good idea to have a look at the Settings page if only to see what you can fine-tune. The link to the page has been added to the Yoast SEO menu and of course there is also a link to it from the Plugins page.

The **Default Settings** of the current release are as follows:

* hide the cartoon-style sidebar ads on almost all settings pages of the Yoast SEO plugin
* hide the tagline nag showing as a Problem in the Yoast SEO Dashboard
* hide the robots nag showing as a Problem in the Yoast SEO Dashboard and in the advanced tab of Yoast SEO UI in edit Post/Page screen when your site is blocking access to robots
* hide the Upsell Notice in the Notification box that shows in the Yoast SEO Dashboard
* hide Problems box from Yoast SEO Dashboard
* hide Notifications box from Yoast SEO Dashboard
* hide image warning nag that shows in edit Post/Page screen when featured image is smaller than 200x200 pixels
* hide add keyword button that shows in edit Post/Page and only serves to show an ad for the premium version
* hide content analysis that adds colored balls to the edit Post/Page screens as well as Readability tab that contains the analysis
* hide issue counter from adminbar and sidebar
* hides red star of "Go Premium" submenu as well as of metabox in edit Post/Page screens
* hide Keyword (SEO) Score 
* hide Content (Readability) Score
* hide ad for premium version in help center
* hide the SEO Score, Readability, Title and Meta Description admin columns on the Posts/Pages screens; Focus keyword column can be hidden too
* hide the SEO Score and Readability admin columns on taxonomies
* remove the Yoast SEO admin bar menu
* remove the Yoast SEO widget from the WordPress Dashboard

We support this plugin exclusively through [Github](https://github.com/senlin/so-clean-up-wp-seo/issues). Therefore, if you have any questions, need help and/or want to make a feature request, please open an issue over at Github. You can also browse through open and closed issues to find what you are looking for and perhaps even help others.

**PLEASE DO NOT POST YOUR ISSUES VIA THE WORDPRESS FORUMS**

Thanks for your understanding and cooperation.

If you like the SO Hide SEO Bloat plugin, please consider leaving a [review](https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform). You can also help a great deal by [translating the plugin](https://translate.wordpress.org/projects/wp-plugins/so-clean-up-wp-seo) into your own language.
Alternatively you are welcome to make a [donation](https://so-wp.com/plugins/donations/). Thanks!

== Frequently Asked Questions ==

= Where is the settings page? =

The link to the page has been added to the Yoast SEO menu and of course there is also a link to it from the Plugins page.

= I have updated the plugin and a new setting has been added, but I still can see that particular item. =

That indeed can happen when we add a new setting. The plugin's settings then need to be re-saved. So all you need to do is go to the Settings page of the SO Hide SEO Bloat plugin and save them. Then all should be good.

= Can I use SO Hide SEO Bloat on Multisite? =

Yes, you can.
For version 2.4.0 [Andy Fragen](https://github.com/afragen) has refactored that part of the plugin to make it fully Multisite compatible. The Settings screen only shows in Network Admin as we don't think it makes sense that individual sites override the Network Settings.

= The name of the plugin is confusing, it hides bloat of which SEO plugin? =

Yes, you are right, the name is a bit vague (see Changelog v1.8.0). On the other hand there is only one SEO plugin that adds a lot of bloat to the WordPress Dashboard and that is the Yoast SEO plugin. 

= The plugin doesn't do anything! =

Do you have the Yoast SEO plugin installed? It hides the bloat from that plugin only. 
If you have and the plugin still doesn't do anything, then please open a [support ticket](https://github.com/senlin/so-clean-up-wp-seo/issues).

= With a settings page comes additional entries in the database; what happens on uninstall? =

Great question!
Indeed the SO Hide SEO Bloat plugin writes its settings to the database. The included `uninstall.php` file removes all the plugin-related entries from the database once you remove the plugin via the WordPress Plugins page (not on deactivation).

= I have an issue with this plugin, where can I get support? =

Please open an issue on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

== Screenshots ==

1. settings page with default settings
2. typical nags that are hidden with the plugin activated
3. bloat on edit Post/Page screen that is hidden with the plugin activated
4. SEO menu in admin bar that is partly hidden with the plugin activated
5. dashboard widget that is removed with the plugin activated

== Changelog ==

= 3.0.0 = 

* release date 2017.4.6
* IMPORTANT: as this is a major release, most likely you will need to save the settings again; apologies for any inconvenience.
* another massive rewrite by [Andy Fragen](https://github.com/afragen); big thank you Andy who now also has become an _official_ contributor to the plugin!
	* rewrite of settings to single array, making it the same for single site and multisite installs
	* use better capabilities for both single site and mulitsite
	* update for WordPress Coding Standards (DocBlock fixes)
	* fix uninstall to use `$wpdb->prepare()`, necessary for potential security issues
	* add remove admin bar menu option back (removed since v2.5.0) to make it easier for Superadmin in Multisite setup to remove it globally
	* set defaults from defaults listed in `get_defaults()`
	* change radio to checkbox_multi
	* fix for errors on update
	* fix checkbox_multi options to use in_array
	* fix need to check empty not in_array
* add plugin's location on Github to make plugin suitable for use with Github Updater by @afragen
* update settings page text
* update admin styling
* update screenshot-1
* update readme.txt

= 2.6.1 = 

* release date 2017.3.31
* revert admin columns settings that were changed in v2.6.0 (last week): checkboxes return to select multiple options, title, meta description and focus keyword can be selected again for "removal" and additional checkbox for readability column
* remove [forgotten about nag setting](https://wordpress.org/support/topic/i-have-updated-to-2-6-0-but-still-see-setting-for-about-nag/)
* update readme.txt

= 2.6.0 = 

* release date 2017.3.24
* add option to hide Readability column on Posts/Pages screens
* update hide email support (see 2.5.5), all tabs have a different class, so need more CSS rules to hide them
* update robots nag
* reintroduce the setting to hide the tagline nag, which was removed in 2.1.1
* modify hide add keyword button in edit Post/Page screens to give remaining tab enough width to show entire text "Enter your focus keyword"
* clarify text Content (Readability)/Keyword (SEO) Score
* edit setting admin columns from 4 to 2 (SEO score and Readability score) and make it radio button option instead of checkboxes; thanks to [Curtis](https://github.com/soaro77) for [bringing it to our attention](https://github.com/senlin/so-clean-up-wp-seo/issues/23)
* remove hide about nag as it no longer is a site-wide nag
* update readme.txt

= 2.5.5 = 

* release date 2017.2.28 (triggered by release of Yoast SEO 4.4)
* hide the email support of the help center as it is a premium-only feature and therefore an "ad in disguise"

= 2.5.4 = 

* Happy Holidays!
* release date 2016.12.22 (triggered by release of Yoast SEO 4.0)
* fix: change robots nag hiding via settings instead of globally
* improvement: content analysis - hide readability tab
* improvement: upsell notice: hide entire notifications box
* add FAQ

= 2.5.3 = 

* release date 2016.11.29 (triggered by release of Yoast SEO 3.9)
* hide "Go Premium" text from adminbar dropdown
* hide dismissed notices and warnings in Yoast SEO Dashboard
* new setting: globally hide upsell notice in Yoast SEO Dashboard

= 2.5.2 = 

* release date 2016.10.11 (triggered by release of Yoast SEO 3.7)
* once again hide red premium star, this time from the opposite side of the metabox on the Edit Post/Page screen

= 2.5.1 =

* release date 2016.10.09
* add rule to hide additional star tab of Yoast SEO metabox

= 2.5.0 =

* release date 2016.10.06
* remove tour setting (redundant since v3.6 of Yoast SEO)
* remove adminbar setting (redundant since v3.6 of Yoast SEO)
* add new setting that hides the red star behind the "Go Premium" submenu that was added in v3.6 of Yoast SEO (it is probably necessary to save the settings page for this change to take effect). Thanks to Jake Jackson for reporting [this issue](https://github.com/senlin/so-clean-up-wp-seo/issues/19).

= 2.4.0 =

* release date 2016.08.13
* with a BIG THANK YOU to [Andy Fragen](https://github.com/afragen) who made the plugin fully Multisite compatible and therewith we have been able to finally close [this issue](https://github.com/senlin/so-clean-up-wp-seo/issues/4).
* Andy also cleaned up some misc spacing and refactored part of the code for it to work smoother. People interested can see the full [PR](https://github.com/senlin/so-clean-up-wp-seo/pull/16).
* tested with WP version 4.6

= 2.3.0 =

* release date 2016.06.20 triggered by changes made with version 3.3 of the Yoast SEO plugin
* hide coloured ball of content analysis also from metabox tab (edit Post/Page screens)
* substitute hide wpseo-score traffic light (v1.7.4) with hide content and SEO score (Yoast SEO 3.3.0), thanks to [Andrea Balzarini](https://github.com/andrebalza) [issue 15](https://github.com/senlin/so-clean-up-wp-seo/pull/15), because "a SEO plugin telling you that your content is poor while saving a new post, is not just nagging, but offensive too"
* hide yoast-issue-counter "enhancement" introduced in Yoast SEO 3.3 from adminbar and sidebar
* move minimum WordPress version up to 4.3

= 2.2.0 =

* release date 2016.04.21 triggered by changes made with version 3.2 of the Yoast SEO plugin
* hide the ad for the premium version in the help center or hide the whole help center (added to v3.2 of Yoast SEO plugin)
* tested up to WP 4.5

= 2.1.0 =

* release date 2016.03.02 triggered by changes made with version 3.1 of the Yoast SEO plugin
* simplify the CSS rules and add the rule to hide the seo-score column on taxonomies (added to v3.1.0 of Yoast SEO plugin)
* remove option to hide tagline nag (temporarily disabled in v3.1 of Yoast SEO plugin)
* partly remove option to hide robots nag (partly temporarily disabled in v3.1 of Yoast SEO plugin)
* remove option to hide GSC nag (temporarily disabled in v3.1 of Yoast SEO plugin)
* remove option to hide recalculate nag (temporarily disabled in v3.1 of Yoast SEO plugin)
* adjust readme file


= 2.0.2 =

* release date 2016.02.26
* add translator details Dutch language file
* update readme files (text and tags)
* PR [#11](https://github.com/senlin/so-clean-up-wp-seo/pull/11) add empty array as default for `get_option cuws_hide_admin_columns` to avoid warnings form subsequent `in_array` checks - credits [Ronny Myhre Njaastad](https://github.com/ronnymn)
* remove whitespace

= 2.0.1 =

* release date 2016.02.05
* include text-domain in plugin header which I forgot to do in the 2.0.0 release; apologies

= 2.0.0 =

* release date 2016.02.04
* complete rewrite of the plugin
* new Settings page to fine tune what is hidden/removed to your liking
* new screenshots
* tested up to WP 4.4.2

= 1.8.0 =

* release date 2016.01.28
* name change to avoid "Yoast" trademark violation

= 1.7.5 =

* release date 2015.12.26
* remove SEO score algorithm recalculate nag

= 1.7.4 =

* release date 2015.11.19
* remove wpseo-score traffic light next to Move to trash on Edit Post/Page screen

= 1.7.3 =

* release date 2015.11.19
* version 3.0 of Yoast SEO has introduced a cool new UI for the Edit screens. This also shows a + icon and when clicking that, you'll have a big fat ad in your face. This is a premium feature and the only function of the + icon therefore is to irritate you with an ad. We have therefore made it invisible. 
* tested up to WP 4.4
* adjust readme files

= 1.7.2 =

* release date 2015.09.30
* [BUG FIX] fix bug that slipped in (forgot to remove) 1.7.1 release, thanks for the [report](https://wordpress.org/support/topic/171-update-problem) [@stansbury](https://wordpress.org/support/profile/stansbury)

= 1.7.1 =

* release date 2015.09.30
* remove function that checks whether Yoast SEO has been installed; reason is to simplify things a bit.
* adjust readme files

= 1.7.0 =

* release date 2015.09.16
* remove yst_opengraph_image_warning nag that was added to Yoast SEO 2.1, but we never noticed it before. In the changelog it has been described as "validation error", which of course is nonsense, because the world is larger than social media. The nag manifests itself by placing thick red borders around your Featured Image as well as a red-bordered warning message when your Featured Image is smaller than 200x200 pixels.
* change function name
* add screenshot of before/after yst_opengraph_image_warning nag
* adjust readme files

= 1.6.0 =

* release date 2015.08.07
* remove GSC (Google Search Console) nag that was introduced in Yoast SEO 2.3.3

= 1.5.0 =

* release date 2015.07.22
* remove overview dashboard widget that was introduced in Yoast SEO 2.3
* change plugin name to reflect the name-change of the plugin it cleans up for ([WordPress SEO became Yoast SEO](https://yoast.com/yoast-seo-2-3/)) 

= 1.4.0 =

* release date 2015.06.17
* remove updated nag (introduced with Yoast SEO version 2.2.1)
* remove previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards; replaced with with so_cuws_ignore_tour() function

= 1.3.2.1 =

* release date 2015.05.15
* Clean up white space

= 1.3.2 =

* release date 2015.05.14
* Fix issue that WP SEO columns were still showing on Edit Posts/Pages pages 

= 1.3.1 =

* release date 2015.05.01
* Added styling to remove Tour Intro and button to start tour
* Added screenshots
* Removed redundant dashboard widget function 

= 1.3.0 =

* release date 2015.04.30
* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on [Google+](https://plus.google.com/u/0/+PietBos/posts/AUfs8ZdwLP3)
* put code actions/filters in order

= 1.2.0 =

* release date 2015.04.30
* Release on wordpress.org Repo

= 1.1.0 =

* release date 2015.04.27
* Release version 
* banner image (in assets folder) by [Leigh Kendell](https://unsplash.com/leighkendell)

= 1.0.0 =

* release date 2015.04.24
* Initial plugin [code snippet](https://github.com/senlin/Code-Snippets/blob/0ae24e6fc069efe26e52007c05c7375012ee688a/Functions/Admin-Dashboard/remove-yoast-crap.php)

== Upgrade Notice ==

= 2.6.0 =

* As some settings have changed, it is probably necessary to save the settings page for these changes to take effect

= 2.5.0 =

* To hide the red star behind the new "Go Premium" submenu, it is probably necessary to save the settings page for this change to take effect

= 2.0.0 =

* Version 2.0.0 is a complete rewrite of the SO Hide SEO Bloat plugin. Please visit the Settings page after you have updated to this version, so you can fine tune what is hidden/removed.

= 1.8.0 =

* name change to avoid "Yoast" trademark violation

= 1.5.0 =

* We have changed the name of our plugin to reflect the name change of the plugin it cleans up after

= 1.4.0 =

* Version 2.2.1 of the Yoast SEO plugin changes a lot of things around. The automatic redirect to the plugin's About page is no longer, so we have removed the function that disables it. The new version introduced an updated nag that doesn't let itself be dismissed easily, so we have simply hidden it altogether. The super irritating balloon to follow the intro tour was back again too, we have countered that with a function that sets the user_meta of that intro tour to true, which means "seen".

