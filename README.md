# SO Clean Up Yoast SEO

[![plugin version](https://img.shields.io/wordpress/plugin/v/so-clean-up-wp-seo.svg)](https://wordpress.org/plugins/so-clean-up-wp-seo)

###### Last updated on 2015.07.22
###### requires at least WordPress 4.0
###### tested up to WordPress 4.3-beta
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

## Frequently Asked Questions

### Where are the Settings?

You can stop looking, there is none. Activate the plugin to clean up your WordPress dashboard.

### I have an issue with this plugin, where can I get support?

Please open an issue here on [Github](https://github.com/senlin/so-clean-up-wp-seo/issues)

## Contributions

This repo is open to _any_ kind of contributions.

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

### 1.5

* date: 2015.07.22
* remove overview dashboard widget that was introduced in Yoast SEO 2.3
* change plugin name to reflect the name-change of the plugin it cleans up for ([WordPress SEO became Yoast SEO](https://yoast.com/yoast-seo-2-3/)) 

### 1.4

* date: 2015.06.17
* remove updated nag (introduced with Yoast SEO version 2.2.1)
* remove previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards; replaced with with so_cuws_ignore_tour() function

### 1.3.2.1

* date: 2015.05.15
* Clean up white space

### 1.3.2

* date: 2015.05.14
* Fix issue that WP SEO columns were still showing on Edit Posts/Pages pages 

### 1.3.1

* date: 2015.05.01
* Added styling to remove Tour Intro and button to start tour
* Added screenshots (wp.org version)
* Removed redundant dashboard widget function 

### 1.3

* date: 2015.04.30
* Added function to remove Yoast SEO Settings from Admin Bar, inspired by comment of [Lee Rickler](https://profiles.wordpress.org/lee-rickler/) in discussion on [Google+](https://plus.google.com/u/0/+PietBos/posts/AUfs8ZdwLP3) [issue #1](https://github.com/senlin/so-clean-up-wp-seo/issues/1)
* put code actions/filters in order

### 1.2

* Release on WP.org Repo (2015.04.30)

### 1.1

* Release version (2015.04.27)
* banner image (in assets folder) by [Leigh Kendell](https://unsplash.com/leighkendell)

### 1.0

* Initial plugin [code snippet](https://github.com/senlin/Code-Snippets/blob/0ae24e6fc069efe26e52007c05c7375012ee688a/Functions/Admin-Dashboard/remove-yoast-crap.php) (2015.04.24)

## Update Notice

### 1.5

* We have changed the name of our plugin to reflect the name change of the plugin it cleans up after

### 1.4

* Version 2.2.1 of the Yoast SEO plugin changes a lot of things around. The automatic redirect to the plugin's About page is no longer, so we have removed the function that disables it. The new version introduced an updated nag that doesn't let itself be dismissed easily, so we have simply hidden it altogether. The super irritating balloon to follow the intro tour was back again too, we have countered that with a functiobn that sets the user_meta of that intro tour to true, which means "seen".
