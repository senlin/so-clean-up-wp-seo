<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CUWS
 */
class CUWS {

	/**
	 * The single instance of CUWS.
	 *
	 * @var    object
	 * @access   private
	 * @since    v2.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   v2.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $dir;

	/**
	 * The plugin styles directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $assets_url;

	/**
	 * The admin API instance.
	 *
	 * @var     object
	 * @access  public
	 * @since   v2.0.0
	 */
	public $admin = null;

	/**
	 * Holds an array of plugin options.
	 *
	 * @var array
	 * @access public
	 * @since  2.x
	 */
	public $options = array();

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   v2.0.0
	 *
	 * @param string $file
	 * @param string $version Version number.
	 */
	public function __construct( $file = '', $version = '4.0.2' ) {
		$this->_version = $version;
		$this->_token   = 'cuws';

		// Load plugin environment variables
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'css';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/css/', $this->file ) ) );

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load admin CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Handle localisation
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 0 );

		/*** PLUGIN FUNCTIONS ***/

		// @since v1.3.0
		add_action( 'admin_bar_menu', array( $this, 'so_cuws_remove_adminbar_settings' ), 999 );
		// @since 1.5.0
		add_action( 'wp_dashboard_setup', array( $this, 'so_cuws_remove_dashboard_widget' ) );
		// @since 2.0.0
		add_action( 'admin_head', array( $this, 'so_cuws_hide_visibility_css' ) );
		// @since 3.10.0
		add_action( 'admin_menu', array( $this, 'so_cuws_remove_menu_item'), 999 );
		// @since 3.11.0
		add_action( 'plugins_loaded', array( $this, 'so_cuws_remove_frontend_html_comments' ), 999 );
		// @since 3.13.0
		add_action( 'admin_init', array( $this, 'so_cuws_remove_class_hook' ) );
		// @since 3.13.0
		add_action( 'admin_menu', array( $this, 'so_cuws_remove_admin_columns_init' ), 11 );
		// @since 3.13.0
		add_action( 'admin_init', array( $this, 'so_cuws_remove_seo_scores_dropdown_filters' ), 20 );


		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new CUWS_Admin_API();
		}

		$this->options = $this->_get_options();
	} // End __construct ()

	/**
	 * Remove Settings submenu in admin bar
	 * Since Yoast SEO 3.6 it is possible to disable the adminbar menu within
	 * Dashboard > Features but only in individual sites, not network admin
	 *
	 * inspired by [Lee Rickler](https://profiles.wordpress.org/lee-rickler/)
	 *
	 * @since v1.3.0
	 */
	public function so_cuws_remove_adminbar_settings() {
		if ( empty( $this->options['remove_adminbar'] ) ) {
			return;
		}
		global $wp_admin_bar;
		$nodes = array_keys( $wp_admin_bar->get_nodes() );
		foreach ( $nodes as $node ) {
			if ( false !== strpos( $node, 'wpseo' ) ) {
				$wp_admin_bar->remove_node( $node );
			}
		}
	}

	/**
	 * Version 2.3 of Yoast SEO introduced a dashboard widget
	 * This function removes this widget
	 *
	 * @since v1.5.0
	 */
	public function so_cuws_remove_dashboard_widget() {

		if ( ! empty( $this->options['remove_dbwidget'] ) ) {

			remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );

		}
	}

	/**
	 * at some point Yoast SEO has introduced "Primary Category Feature"
	 * This function removes this "feature"
	 *
	 * @since v3.6.0
	 */
	public function so_cuws_remove_primary_category_feature() {

		if ( ! empty( $this->options['remove_primarycatfeat'] ) ) {

			add_filter( 'wpseo_primary_term_taxonomies', '__return_empty_array' );

		}
	}

	/**
	 * Remove Search Console
	 *
	 * @since v3.10.0
	 */
	public function so_cuws_remove_menu_item() {

		// Google has discontinued its Crawl Errors API so the Search Console page in Yoast is useless now; thanks [@Dibbyo456](https://github.com/senlin/so-clean-up-wp-seo/issues/69); @since v3.12.0
		remove_submenu_page( 'wpseo_dashboard', 'wpseo_search_console' );

	}

	/**
	 * Upon request by many the plugin now also removes the frontend HTML comments left by Yoast
	 * improvements of v3.11.1 via [Robert Went](https://gist.github.com/robwent/f36e97fdd648a40775379a86bd97b332)
	 * v3.14.6: added conditional for new filter tip from [Emanuel-23](https://github.com/senlin/so-clean-up-wp-seo/issues/95)
	 *
	 * @since v3.11.0
	 * @modified v3.11.1
	 * @modified v3.14.6
	 */
	public function so_cuws_remove_frontend_html_comments() {

		if ( ! empty( $this->options['remove_html_comments'] ) ) {

			if ( defined( 'WPSEO_VERSION' ) ) {

				$wpseo_version = constant( 'WPSEO_VERSION' );

				// the wpseo_debug_markers() filter was added in WP SEO version 14.1
				if ( version_compare ( $wpseo_version , '14.1', '<' ) ) {

					add_action( 'get_header', function () { ob_start( function ( $o ) {
						return preg_replace( '/\n?<.*?Yoast SEO plugin.*?>/mi', '', $o ); } ); } );
					add_action( 'wp_head',function (){ ob_end_flush(); }, 999 );

				} else {

					add_filter( 'wpseo_debug_markers', '__return_false' );

				}

			}

		}
	}

    /**
	 * Remove warning notice when changing permalinks
	 *
	 * Removes the permalink notice action (see includes/remove-class.php)
	 * Uses @remove_class_hook.
	 *
	 * @since	v3.13.0
	 */
	public function so_cuws_remove_class_hook() {

		if ( ! empty( $this->options['remove_permalinks_warning'] ) ) {

			remove_class_hook( 'admin_notices', 'WPSEO_Admin_Init', 'permalink_settings_notice' );

		}
	}

	/*
	 * Remove admin columns
	 * @since v2.0.0 remove seo columns one by one
	 * @modified 2.0.2 add empty array as default to avoid warnings form subsequent
	 *  in_array checks - credits [Ronny Myhre Njaastad](https://github.com/ronnymn)
	 * @modified 2.1 simplify the CSS rules and add the rule to hide the seo-score
	 *  column on taxonomies (added to v3.1 of Yoast SEO plugin)
	 * @modified 2.6.0 only 2 columns left change from checkboxes to radio
	 * @modified 2.6.1 revert radio to checkboxes and removing the options
	 *  for focus keyword, title and meta-description
	 * @modified 3.10.1 add checkbox to hide outgoing internal links column
	 * @modified 3.13.0 recode the function to **remove** columns instead of hiding them - credits [Dibbyo456](https://github.com/Dibbyo456)
	 */
	public function so_cuws_remove_admin_columns_init() {

		// post, page and custom post types
		$all_post_types = array_merge( array( 'post', 'page' ), get_post_types( array( '_builtin' => false ) ) );

		foreach( $all_post_types as $post_type ) {
			add_filter( 'manage_edit-'. $post_type .'_columns', array( $this, 'so_cuws_remove_admin_columns' ), 10, 1  );
		}

	}

	public function so_cuws_remove_admin_columns( $columns ) {

		// if empty return columns right away.
		if ( empty( $this->options['hide_admincolumns'] ) ) {
			return $columns;
		}

		// seo score column
		if ( in_array( 'seoscore', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-score'] );
		}

		// readability column
		if ( in_array( 'readability', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-score-readability'] );
		}

		// title column
		if ( in_array( 'title', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-title'] );
		}

		// meta description column
		if ( in_array( 'metadescr', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-metadesc'] );
		}

		// focus keyword column
		if ( in_array( 'focuskw', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-focuskw'] );
		}

		// outgoing internal links column
		if ( in_array( 'outgoing_internal_links', $this->options['hide_admincolumns'] ) ) {
			unset( $columns['wpseo-links'] );
			unset( $columns['wpseo-linked'] );
		}

		return $columns;

	}


	/**
	 * Remove (as opposed to hide) SEO/readability Scores dropdown filters on edit posts screens
	 *
	 * credits [Dibbyo456](https://github.com/Dibbyo456)
	 */
	public function so_cuws_remove_seo_scores_dropdown_filters() {
		if ( ! empty( $this->options['remove_seo_scores_dropdown_filters'] ) ) {
			global $wpseo_meta_columns ;
			if ( $wpseo_meta_columns  ) {
				remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns , 'posts_filter_dropdown' ) );
				remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns , 'posts_filter_dropdown_readability' ) );
			}
		}
	}


	/**
	 * CSS needed to hide the various options ticked with checkboxes
	 *
	 * @since    v2.0.0
	 * @modified v2.1.0 remove options for nags that have been temporarily
	 * disabled in v3.1 of Yoast SEO plugin
	 */
	public function so_cuws_hide_visibility_css() {

		echo '<style media="screen" id="so-hide-seo-bloat" type="text/css">';

		// Problems/Notification boxes
		if ( ! empty( $this->options['hide_dashboard_problems_notifications'] ) ) {
			if ( in_array( 'problems', $this->options['hide_dashboard_problems_notifications'] ) ) {
				echo '.yoast-container.yoast-container__error{display:none;}'; // @since v2.6.0 hide both Problems/Notifications boxes from Yoast SEO Dashboard; @modified v3.13.1
			}
			if ( in_array( 'notifications', $this->options['hide_dashboard_problems_notifications'] ) ) {
				echo '.yoast-container.yoast-container__warning{display:none;}'; // @since v2.6.0 hide both Problems/Notifications boxes from Yoast SEO Dashboard
			}
		}

		// Hide ads for premium version across Yoast SEO Settings pages
		if ( ! empty( $this->options['hide_ads'] ) ) {
			echo '
			/* hide sidebar ad */
			@media (min-width: 1280px){
				.seo_page_wpseo_page_settings .xl\:yst-right-8{
					display:none!important;
				}
			}
			/* hide sidebar ad General page */
			.toplevel_page_wpseo_dashboard #sidebar-container {
				display:none;
			}
			/* hide ad for premium at bottom of Settings screen */
			.seo_page_wpseo_page_settings .yst-grow.yst-space-y-6.yst-mb-8.xl\:yst-mb-0 .yst-p-6.xl\:yst-max-w-3xl.yst-rounded-lg.yst-bg-white.yst-shadow {
				display:none;
			}
			/* hide premium features from settings page */
			.seo_page_wpseo_page_settings div#card-wpseo-enable_link_suggestions,
			.seo_page_wpseo_page_settings div#card-wpseo-enable_index_now,
			.seo_page_wpseo_page_settings div#card-wpseo-enable_metabox_insights {
				display:none !important;
			}

			/* hide upsells */
			.seo_page_wpseo_page_settings .yst-feature-upsell.yst-feature-upsell--card {
				display:none;
			}

			/* hide premium upsell admin block that shows throughout Yoast backend */
			.yoast_premium_upsell,
			.yoast_premium_upsell_admin_block,
			#wpseo-local-seo-upsell,
			div[class^="SocialUpsell__PremiumInfoText"] {
				display:none;
			}

			/* hide upsell notice in Yoast SEO Dashboard */
			#yoast-warnings #wpseo-upsell-notice,
			#yoast-additional-keyphrase-collapsible-metabox,
			.wpseo-keyword-synonyms,.wpseo-multiple-keywords,
			.switch-container.premium-upsell,
			.yoast-settings-section-upsell,
			.yoast-settings-section-disabled{
				display:none !important;
			}

			/* hide help center */
			div#yoast-helpscout-beacon,
			.yoast-help-center__button {
				display:none !important;
			}
			';
		}

		// Yoast sidebar menu
		if ( ! empty( $this->options['hide_premium_submenu'] ) ) {
			echo '
				/* hide "Academy", "Premium", "Workouts" and "Redirects" submenus */
				li#toplevel_page_wpseo_dashboard>ul>li:nth-child(6),
				li#toplevel_page_wpseo_dashboard>ul>li:nth-child(7),
				li#toplevel_page_wpseo_dashboard>ul>li:nth-child(8),
				li#toplevel_page_wpseo_dashboard>ul>li:nth-child(9) {
					display:none;
				}

				/* hide issue counter */
				#wpadminbar .yoast-issue-counter,#toplevel_page_wpseo_dashboard .wp-menu-name .update-plugins{
					display:none;
				}
			';
		}

		/*
 		* admin columns
 		* @since v2.0.0 remove seo columns one by one
 		* @modified 2.0.2 add empty array as default to avoid warnings form subsequent
 		*  in_array checks - credits [Ronny Myhre Njaastad](https://github.com/ronnymn)
 		* @modified 2.1 simplify the CSS rules and add the rule to hide the seo-score
 		*  column on taxonomies (added to v3.1 of Yoast SEO plugin)
 		* @modified 2.6.0 only 2 columns left change from checkboxes to radio
 		* @modified 2.6.1 revert radio to checkboxes and removing the options
 		*  for focus keyword, title and meta-description
 		* @modified 3.10.1 add checkbox to hide outgoing internal links column
 		* @modified 3.13.2 put CSS rules back to fix bug when using quick edit function (issue #75)
 		*/
		// all columns
		if ( ! empty( $this->options['hide_admincolumns'] ) ) {
			// seo score column
			if ( in_array( 'seoscore', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-score,.column-wpseo_score{display:none;}'; // @since v2.0.0 remove seo columns one by one
			}
			// readability column
			if ( in_array( 'readability', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-score-readability,.column-wpseo_score_readability{display:none;}'; // @since v2.6.0 remove added readibility column
			}
			// title column
			if ( in_array( 'title', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-title{display:none;}'; // @since v2.0.0 remove seo columns one by one
			}
			// meta description column
			if ( in_array( 'metadescr', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-metadesc{display:none;}'; // @since v2.0.0 remove seo columns one by one
			}
			// focus keyword column
			if ( in_array( 'focuskw', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-focuskw{display:none;}'; // @since v2.0.0 remove seo columns one by one
			}
			// outgoing internal links column
			if ( in_array( 'outgoing_internal_links', $this->options['hide_admincolumns'] ) ) {
				echo '.column-wpseo-links{display:none;}'; // @since v3.10.1 add checkbox to hide outgoing internal links column
			}
		}

		// image warning nag
		if ( ! empty( $this->options['hide_imgwarning_nag'] ) ) {
			echo '#yst_opengraph_image_warning{display:none;}#postimagediv.postbox{border:1px solid #e5e5e5!important;}'; // @since v1.7.0 hide yst opengraph image warning nag
		}

		// hide content/keyword score on Publish/Update Post metabox
		// hide Premium SEO Analysis button on Publish/Update Post metabox
		if ( ! empty( $this->options['hide_content_keyword_score'] ) ) {
			echo '
				#misc-publishing-actions #content-score,
				#misc-publishing-actions #keyword-score,
				#misc-publishing-actions #inclusive-language-score,
				#misc-publishing-actions .yoast-zapier-text
				{display:none;}
			';
		}

		// hide Premium features on new/edit post-type screens
		if ( ! empty( $this->options['hide_premium_features_yoast_metabox'] ) ) {
			echo '
				button#yoast-premium-seo-analysis-metabox-modal-open-button,
				#wpseo-metabox-root button.wpseo-keyword-synonyms,
				#wpseo-metabox-root button.wpseo-multiple-keywords,
				button#yoast-additional-keyphrase-collapsible-metabox {
					display: none;
				}
			';
		}

		// hide Premium ad after deleting content (post, page, wc product, cpt)
		if ( ! empty( $this->options['hide_ad_after_trashing_content'] ) ) {
			echo '
				body.edit-php .yoast-notification.notice.notice-warning.is-dismissible,
				body[class*="taxonomy-"] .yoast-notification.notice.notice-warning.is-dismissible
				{display:none;}
			'; // @since v3.14.0; @modified v3.14.2
		}


		// seo settings profile page
		if ( ! empty( $this->options['hide_seo_settings_profile_page'] ) ) {
			echo '.profile-php .yoast.yoast-settings{display:none;}'; // @since v3.6.0
		}

		echo '</style>';
	}


	/**
	 * Load admin CSS.
	 *
	 * @access  public
	 * @since   v2.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Loads the translation file.
	 *
	 * @since v1.0.0
	 */
	function i18n() {
		load_plugin_textdomain( 'so-clean-up-wp-seo', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Main CUWS Instance
	 *
	 * Ensures only one instance of CUWS is loaded or can be loaded.
	 *
	 * @since v2.0.0
	 * @static
	 * @see   CUWS()
	 *
	 * @param string $file
	 * @param string $version Version number.
	 *
	 * @return CUWS $_instance
	 */
	public static function instance( $file = '', $version = '4.0.0' ) {
		if ( null === self::$_instance ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'No Access' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'No Access' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @since   v2.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
		$this->_set_defaults();
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access  private
	 * @since   v2.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		update_site_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	/**
	 * Array containing the default values.
	 * Use `array_keys()` to return the key names.
	 *
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'hide_dashboard_problems_notifications' => array(
				'problems',
				'notifications'
			),
			'hide_ads'                             => 'on',
			'hide_premium_submenu'				   => 'on',
			'hide_admincolumns'                    => array(
				'seoscore',
				'readability',
				'title',
				'metadescr',
				'outgoing_internal_links'
			),
			'remove_seo_scores_dropdown_filters'	=> 'on',
			'hide_imgwarning_nag'					=> 'on',
			'hide_content_keyword_score'			=> 'on',
			'hide_premium_features_yoast_metabox'	=> 'on',
			'hide_ad_after_trashing_content'		=> 'on',
			'remove_adminbar'                       => 'on',
			'remove_dbwidget'                       => 'on',
			'remove_permalinks_warning'				=> 'on',
			'hide_seo_settings_profile_page'		=> 'on',
			'remove_html_comments'					=> 'on'
		);

		return $defaults;
	}

	/**
	 * Set default values on activation.
	 *
	 * @access private
	 * @return void
	 */
	private function _set_defaults() {
		$defaults = $this->get_defaults();
		update_site_option( $this->_token . '_settings', $defaults );
	} // End _set_defaults ()

	/**
	 * Get plugin options.
	 * Add new default options if missing from saved options.
	 *
	 * @return array $options Plugin options.
	 * @since 3.8.1
	 */
	private function _get_options() {
		$options  = get_site_option( $this->_token . '_settings', array() );
		$defaults = $this->get_defaults();
		$diff     = array_diff_key( $defaults, $options );

		if ( ! empty( $diff ) ) {
			$options = array_merge( $options, $diff );
			update_site_option( $this->_token . '_settings', $options );
		}

		return $options;
	}

}
