=== Hide SEO Bloat ===
Contributors: senlin, afragen
Donate link: https://so-wp.com/donations
Tags: hide, seo, bloat, remove, ads, cartoon, wordpress seo addon, admin columns, nags, dashboard widget, hide premium
Requires at least: 4.9
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: 4.0.2
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Free addon for the Yoast SEO plugin to hide the bloat it adds to the WordPress backend. See [changelog](https://wordpress.org/plugins/so-clean-up-wp-seo/#developers) for what's new in this release.

== Description ==

Hides (sidebar) ads and premium version buttons of Yoast SEO from their settings pages and your website's dashboard (and frontend).

<hr>

The first version of the Hide SEO Bloat plugin was released in April 2015 and ever since team Yoast and I have been playing a game of cat and mouse.

Since version 20.0 of Yoast SEO however, the Settings page has received a complete overhaul, which made the Hide SEO Bloat plugin almost obsolete!

Things have become much, much more trickier to remove/hide now and some things simply can no longer be hidden (believe me, I have tried).

Why are there still people using Yoast SEO one might ask? There are so many great alternatives that come without screaming ads and hiding features behind a paywall!

And the only reason that I have to keep Yoast SEO installed (on a sandbox that is) is because of the mere 10K installs where Hide SEO Bloat is running. Compare that with my popular [Classic Editor + plugin](https://wordpress.org/plugins/classic-editor-addon/), which has more than 30,000 active installs!

For everyone to become much more productive and happier, my proposal therefore is to switch to any of the other SEO plugins, such as SEOPress, The SEO Framework, Rankmath, or any other one out there! Did you know that most SEO plugins come with easy one-click migration tools?

<hr>

I support this plugin exclusively through [Github](https://github.com/senlin/so-clean-up-wp-seo/issues). Therefore, if you have any questions, need help and/or want to make a feature request, please open an issue over at Github. You can also browse through open and closed issues to find what you are looking for and perhaps even help others.

Thanks for your understanding and cooperation.

If you like the Hide SEO Bloat plugin, please consider leaving a [review](https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform). You can also help a great deal by [translating the plugin](https://translate.wordpress.org/projects/wp-plugins/so-clean-up-wp-seo) into your own language.
Alternatively you are welcome to make a [donation](https://so-wp.com/donations). Thanks!

[Hide SEO Bloat](https://so-wp.com/plugin/hide-seo-bloat) by SO WP.

== Frequently Asked Questions ==

= Where is the settings page? =

The link to the page has been added to the Yoast SEO menu and of course there is also a link to it from the Plugins page.

= Can I use Hide SEO Bloat on Multisite? =

Yes, you can.
For version 2.4.0 [Andy Fragen](https://github.com/afragen) has refactored that part of the plugin to make it fully Multisite compatible. The Settings screen only shows in Network Admin as it doesn't make sense that individual sites override the Network Settings.

= The name of the plugin is confusing, it hides bloat of which SEO plugin? =

Yes, you are right, the name is a bit vague (see Changelog v1.8.0). On the other hand there is only one SEO plugin that adds a lot of bloat to the WordPress Dashboard and that is the Yoast SEO plugin.

= The plugin doesn't do anything! =

Do you have the Yoast SEO plugin installed? It hides the bloat from that plugin only.
If you have and the plugin still doesn't do anything, then please open a [support ticket](https://github.com/senlin/so-clean-up-wp-seo/issues).

= With a settings page comes additional entries in the database; what happens on uninstall? =

Great question!
Indeed the Hide SEO Bloat plugin writes its settings to the database. The included `uninstall.php` file removes all the plugin-related entries from the database once you remove the plugin via the WordPress Plugins page (not on deactivation).

= I have an issue with this plugin, where can I get support? =

Please open an issue on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

== Changelog ==

= 4.0.2 =

* release date January 15, 2024
* remove rule that hides crawl optimisation menu in Yoast SEO settings thanks for reporting [@melcarthus](https://wordpress.org/support/topic/non-bloatware-link-removed-by-plugin/)
* hide premium Redirects submenu from left admin bar, which had become visible again after Yoast added the Academy submenu

= 4.0.1 =

* release date July 30, 2023
* Fix PHP 8.2 deprecation notices ([PR #106](https://github.com/senlin/so-clean-up-wp-seo/pull/106)) (thanks Brandon)
* compatible up to WP 6.3

= 4.0.0 =

* release date February 10, 2023
* redo many styles that hide features, due to dashboard makeover release of Yoast SEO version 20.0
* merge many settings (from 23 to 14)
* new sections on the settings page with assistance of [ChatGPT](https://chat.openai.com/)
* simplify register_settings() function with assistance of [ChatGPT](https://chat.openai.com/)

= 3.14.13 =

* release date January 31, 2023
* fix issue where Hide SEO Bloat link to settings page was made invisible due to Yoast SEO switching around menu items in admin sidebar
* hide Premium SEO Analysis button on Publish/Update Post metabox

= 3.14.12 =

* release date December 2, 2022
* hide "Unlock with Premium" button and options in Yoast dashboard (Features tab)
* hide "Social Settings" box and "Unlock with Premium" button in Yoast dashboard (Search Appearance, Content types and Taxonomies tabs)

= 3.14.11 =

* release date November 7, 2022
* slight correction on hiding the submenu items of 3.14.10

= 3.14.10 =

* release date November 7, 2022
* hide ["Redirects"-submenu](https://github.com/senlin/so-clean-up-wp-seo/pull/103) and hide ["Crawl Settings"-tab](https://github.com/senlin/so-clean-up-wp-seo/pull/104), both courtesy of [Chris Johnson](https://github.com/workeffortwaste)

= 3.14.9 =

* release date November 7, 2022
* hide "Connect Yoast SEO with Zapier"-text in post publish sidebar
* remove link to blog from plugin's settings page

= 3.14.8 =

* release date August 16, 2021
* include hiding "Workouts" submenu (https://github.com/senlin/so-clean-up-wp-seo/issues/102)
* adjust admin CSS to not affect other plugins (https://wordpress.org/support/topic/css-affect-other-plugin/)

= 3.14.7 =

* release date February 23, 2021
* use version_compare for wpseo_debug_markers() filter; see [this comment](https://github.com/senlin/so-clean-up-wp-seo/commit/441613bd83ee59882cfff4859b0b2c4f8fdb209a#r47454525)

= 3.14.6 =

* release date February 4, 2021
* add condition to use new-ish `wpseo_debug_markers` filter to remove frontend comments in a backward compatible way; fixes [issue #95](https://github.com/senlin/so-clean-up-wp-seo/issues/95)

= 3.14.5 =

* release date February 3, 2021
* fix React hide tabs conflict ([issue #94](https://github.com/senlin/so-clean-up-wp-seo/issues/94))

= 3.14.4 =

* release date December 18, 2020
* hides ad for premium version on post type analysis dropdown
* delete function added in 3.13.0 that removes advanced accordion menu at bottom of SEO metabox for post- and custom post types, since it no longer functions properly ([issue #67](https://github.com/senlin/so-clean-up-wp-seo/issues/67))

= 3.14.3 =

* release date August 11, 2020
* fixed bug that occurred with some themes due to introduction of `is_object()` in WP 5.5. Thanks for the patch @andreiglingeanu!

= 3.14.2 =

* release date August 1, 2020
* improved hiding notice (added to v3.14.0) that shows after content deletion as it also shows after deleting taxonomies (thanks @KoolPal)

= 3.14.1 =

* release date August 1, 2020
* hide premium upsell ad from social tab of Yoast SEO Metabox (thanks @allanrehhoff)
* tested up to WP 5.5

= 3.14.0 =

* release date May 20, 2020
* hide new notice that shows after deleting content (post, page, product, other CPT), address issue #83, thanks [@Kagan Akbas](https://github.com/remaindeer)

= 3.13.6 =

* release date March 7, 2019
* previous hide readability features setting (our v3.9.0) was blocking the icons of SEO and Readability tab of Yoast metabox; now not anymore. Thanks for reporting [@koolpad](https://wordpress.org/support/topic/non-critical-seo-readability-icons-disappear/)
* cleanup

= 3.13.5 =

* release date February 25, 2020
* fix issue where updates from other plugins are hidden in admin sidebar

= 3.13.4 =

* release date February 5, 2020
* hide additional keyphrase "option" from metabox as it is ad for premium too

= 3.13.3 =

* release date January 9, 2020
* update help center classes
* hide help beacon (issue #77 - thanks for the fix [@allanrehhoff](https://github.com/allanrehhoff))

= 3.13.2 =

* release date November 19, 2019
* put CSS rules back to fix bug when using quick edit function (issue #75)
* adjust CSS to fix bug (issue #76 - thanks for the fix [@allanrehhoff](https://github.com/allanrehhoff))

= 3.13.1 =

* release date October 1, 2019
* adjust the hiding of the Problems box (General settings Yoast) which received new class name

= 3.13.0 =

* release date August 29, 2019
* remove notice on permalinks page that warns the user of the implications of changing them, fixes [issue #58](https://github.com/senlin/so-clean-up-wp-seo/issues/58)
* recode hiding of the admin columns into actually removing them, fixes [issue #65](https://github.com/senlin/so-clean-up-wp-seo/issues/65)
* recode hiding of the seo score/readability score filters into actually removing them, fixes [issue #65](https://github.com/senlin/so-clean-up-wp-seo/issues/65)
* remove advanced accordion menu at bottom of SEO metabox for post- and custom post types, fixes [issue #67](https://github.com/senlin/so-clean-up-wp-seo/issues/67)

Credits of all the above improvements go to [@Dibbyo456](https://github.com/Dibbyo456); many thanks Harry!

= 3.12.0 =

* release date August 17, 2019
* remove Search Console submenu, redundant since Google has discontinued its Crawl Errors API; thanks [@Dibbyo456](https://github.com/senlin/so-clean-up-wp-seo/issues/69)

= 3.11.1 =

* release date July 27, 2019
* refactor remove HTML comments from source code (frontend) with thanks to [Robert Went](https://www.robertwent.com/blog/remove-yoast-html-comments-in-version-11-0/)
* hide upsell ad for local seo; addresses [issue #57](https://github.com/senlin/so-clean-up-wp-seo/issues/57)

= 3.11.0 =

* release date June 17, 2019
* remove HTML comments from source code (frontend)

= 3.10.1 =

* release date April 27, 2019
* hide outgoing internal links columns on edit Posts/Pages/CPTs screens; addresses [issue #55](https://github.com/senlin/so-clean-up-wp-seo/issues/55)

= 3.10.0 =

* release date April 22, 2019
* add function that removes course sub menu, addresses [issue #54](https://github.com/senlin/so-clean-up-wp-seo/issues/54); thanks [Igor](https://github.com/artifex404)
* hide SEO Scores dropdown filters as well as Readability Scores filter dropdown from edit Posts/Pages/CPTs; addresses [issue #43](https://github.com/senlin/so-clean-up-wp-seo/issues/43)
* hide content/keyword score from Publish/Update Metabox on Edit Post/Page/CPT screen

= 3.9.2 =

* release date November 19, 2018
* fix issue where Go Premium metabox option makes snippet preview box blank (https://wordpress.org/support/topic/go-premium-metabox-option-makes-snippet-preview-box-blank/); thanks for reporting @pxlar8

= 3.9.1 =

* release date October 29, 2018
* fix hiding "Add related keyphrase" in metabox as it only serves as an ad for premium version

= 3.9.0 =

* release date October 28, 2018
* hide new readability "features" of Post/Page metabox incl. "Add related keyphrase" menu as it is nothing more than an ad for premium version
* update hide Go Premium rules
* compatible with ClassicPress 1.0.0

= 3.8.1 =

* release date September 25, 2018
* refactor options to ensure that additional settings will have default values. Fixes [issue #44](https://github.com/senlin/so-clean-up-wp-seo/issues/44)

= 3.8.0 =

* release date September 20, 2018
* add option to hide the Premium ad that shows after a Post, Page or Taxonomy is deleted (see [issue #44](https://github.com/senlin/so-clean-up-wp-seo/issues/44))

= 3.7.0 =

* release date September 2, 2018
* add option to hide the check Configuration wizard box that shows on top of most admin screens
* removed option to hide Post/Page content analysis incl. content/keyword score, because it can now be turned off in the Features tab of the General Settings of Yoast SEO
* removed option to hide "add keyword box" (which is an ad for Premium), because it can now be turned off from within Yoast SEO Features tab
* make flow of Setting page a little more clear

= 3.6.0 =

* release date June 11, 2018
* address issues [#40](https://github.com/senlin/so-clean-up-wp-seo/issues/40)
* NEW: remove primary category feature
* NEW: hide SEO settings on individual profile page
* remove option to hide red star for Premium submenu as there doesn't seem to be a red star anymore
* NEW: hide Premium submenu in its entirety
* NEW: hide "Go Premium" metabox on edit Post/Page screens
* update hide help center ads

= 3.5.0 =

* release date January 2, 2018
* fix issue #37
* remove Github Branch in header info
* change link to personal LI profile

= 3.4.1 =

* release date October 25, 2017
* add `!important` to CSS rule to enforce it

= 3.4.0 =

* release date October 25, 2017
* change hiding of sidebar ads from `visibility: hidden` to `display: none` to avoid conflict with screen width

= 3.3.0 =

* release date October 10, 2017
* adjust the settings of Help Center that received overhaul in Yoast SEO 5.6
* updated links due to renewal of SO WP website

= 3.2.1 =

* release date September 25, 2017
* modified issue counter of sidebar which was showing orange background again

= 3.2.0 =

* release date August 25, 2017
* hide Upsell Notice in social tab of Yoast Post/Page metabox
* styling adjustments settings page

= 3.1.0 =

* release date July 25, 2017
* hide the Premium Upsell Admin Block that was introduced in version 5.1 (of Yoast SEO) and shows in the entire Yoast SEO backend

= 3.0.0 =

* release date April 6, 2017
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
* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on Google+ (no longer available)
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

= 3.8.0 =

* new option that hides Post/Page/Taxonomy Deletion Premium Ad has been added, check settings of Hide SEO Bloat if you like it hidden

= 3.7.0 =

* new option that hides "check configuration wizard"-box has been added, check settings of Hide SEO Bloat if you like it hidden

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

