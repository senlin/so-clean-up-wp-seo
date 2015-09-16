# SO Clean Up Yoast SEO

[![plugin version](https://img.shields.io/wordpress/plugin/v/so-clean-up-wp-seo.svg)](https://wordpress.org/plugins/so-clean-up-wp-seo)

###### Last updated on 2015.09.16
###### requires at least WordPress 4.0
###### tested up to WordPress 4.3
###### Author: [Piet Bos](https://github.com/senlin)

Clean up several unwanted things that the WordPress SEO plugin adds to your WordPress Dashboard.

## Description

Almost anyone that uses Yoast SEO by Team Yoast will agree that it is *the* SEO plugin, but the developers are adding more and more unwanted things to the WordPress Dashboard.

The purpose of the SO Clean Up Yoast SEO is to clean up all those unwanted things.

As per the current release, the plugin removes and/or disables the following unwanted items:

* removes sidebar ads on all Yoast SEO settings pages
* removes the Yoast SEO settings from the Admin Bar
* removes updated nag
* sets plugin-intro-tour user_meta to true (means done)
* removes the keyword/description columns on edit Posts/Pages pages
* removes the overview dashboard widget that was introduced in version 2.3 of the Yoast SEO plugin
* removes GSC (Google Search Console) nag that was introduced in Yoast SEO 2.3.3
* remove yst_opengraph_image_warning nag that was sneakily added to the Yoast SEO plugin without adding anything to the changelog

## Frequently Asked Questions

### Where are the Settings?

For [v2.0.0](https://github.com/senlin/so-clean-up-wp-seo/milestones/version%202.0.0) we have planned a complete rewrite of the SO Clean Up Yoast SEO plugin which includes a Settings page to enable the user to clean up individual items, instead of the current blanket rule.

For now however you can stop looking, there is none yet. Activate the plugin to clean up your WordPress dashboard.

### Can I use SO Clean Up Yoast SEO on Multisite?

You can, but until further notice you need to activate the plugin on a per-site basis ([see this Github issue](https://github.com/senlin/so-clean-up-wp-seo/issues/4))

### I have an issue with this plugin, where can I get support?

Please open an issue here on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

## Contributions

We are looking to release a [new version](https://github.com/senlin/so-clean-up-wp-seo/milestones/version%202.0.0) that has a Settings page to enable the user to clean up individual items. For that version we want a complete rewrite of the plugin, preferably using one of the boilerplates.
**If you're interested in becoming involved, please [let us know](http://so-wp.com/info-contact/) or simply send a PR with your proposed improvement.** 

## License

* License: GNU Version 3 or Any Later Version
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

## Donations

* Donate link: http://so-wp.com/donations

## Connect with us through

[Website](http://senlinonline.com)

[Github](https://github.com/senlin) 

[LinkedIn](https://cn.linkedin.com/in/pietbos) 

[WordPress](https://profiles.wordpress.org/senlin/) 


## Changelog

### 1.7 (2015.09.16)

* remove yst_opengraph_image_warning nag that was sneakily added to the Yoast SEO plugin without adding anything to the changelog. The nag manifests itself by placing thick red borders around your Featured Image as well as a red-bordered warning message when your Featured Image is smaller than 200x200 pixels.
* change function name
* add screenshot of before/after yst_opengraph_image_warning nag

### 1.6 (2015.08.07)

* remove GSC (Google Search Console) nag that was introduced in Yoast SEO 2.3.3

### 1.5 (2015.07.22)

* remove overview dashboard widget that was introduced in Yoast SEO 2.3
* change plugin name to reflect the name-change of the plugin it cleans up for ([WordPress SEO became Yoast SEO](https://yoast.com/yoast-seo-2-3/)) 

### 1.4 (2015.06.17)

* remove updated nag (introduced with Yoast SEO version 2.2.1)
* remove previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards; replaced with with so_cuws_ignore_tour() function

### 1.3.2.1 (2015.05.15)

* Clean up white space

### 1.3.2 (2015.05.14)

* Fix issue that WP SEO columns were still showing on Edit Posts/Pages pages 

### 1.3.1 (2015.05.01)

* Added styling to remove Tour Intro and button to start tour
* Added screenshots
* Removed redundant dashboard widget function 

### 1.3 (2015.04.30)

* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on [Google+](https://plus.google.com/u/0/+PietBos/posts/AUfs8ZdwLP3)
* put code actions/filters in order

### 1.2 (2015.04.30)

* Release on wordpress.org Repo

### 1.1 (2015.04.27)

* Release version 
* banner image (in assets folder) by [Leigh Kendell](https://unsplash.com/leighkendell)

### 1.0 (2015.04.24)

* Initial plugin [code snippet](https://github.com/senlin/Code-Snippets/blob/0ae24e6fc069efe26e52007c05c7375012ee688a/Functions/Admin-Dashboard/remove-yoast-crap.php)

## Update Notice

### 1.5

* We have changed the name of our plugin to reflect the name change of the plugin it cleans up after

### 1.4

* Version 2.2.1 of the Yoast SEO plugin changes a lot of things around. The automatic redirect to the plugin's About page is no longer, so we have removed the function that disables it. The new version introduced an updated nag that doesn't let itself be dismissed easily, so we have simply hidden it altogether. The super irritating balloon to follow the intro tour was back again too, we have countered that with a functiobn that sets the user_meta of that intro tour to true, which means "seen".
