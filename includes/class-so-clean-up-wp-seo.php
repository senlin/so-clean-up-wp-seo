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
	public $styles_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $styles_url;

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
	public function __construct( $file = '', $version = '3.14.3' ) {
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
		// @since 3.13.0
		add_action( 'current_screen', array( $this, 'remove_advanced_menu_metabox' ) );


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
	 *
	 * @since v3.11.0
	 * @modified v3.11.1
	 */
	public function so_cuws_remove_frontend_html_comments() {

		if ( ! empty( $this->options['remove_html_comments'] ) ) {

			if ( defined( 'WPSEO_VERSION' ) ) {
				add_action( 'get_header', function () { ob_start( function ( $o ) {
					return preg_replace( '/\n?<.*?Yoast SEO plugin.*?>/mi', '', $o ); } ); } );
				add_action('wp_head',function (){ ob_end_flush(); }, 999);
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
		if ( ! empty( $this->options['hide_seo_scores_dropdown_filters'] ) ) {
			global $wpseo_meta_columns ;
			if ( $wpseo_meta_columns  ) {
				remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns , 'posts_filter_dropdown' ) );
				remove_action( 'restrict_manage_posts', array( $wpseo_meta_columns , 'posts_filter_dropdown_readability' ) );
			}
		}
	}


	/**
	 * Remove Advanced accordion menu of SEO metabox on Post and Custom Post Type edit screens
	 *
	 * credits [Dibbyo456](https://github.com/Dibbyo456)
	 */
	public function remove_advanced_menu_metabox() {

		if ( ! empty( $this->options['remove_advanced'] ) ) {

			// create array of default post types.
			// do not include page for now as the advanced menu can come in handy there
			$default_post_types = array( 'post' );
			// get the custom post types if available.
			$custom_post_types = get_post_types( array( '_builtin' => false ) );
			// merge them. no errors if no cpt found.
			$all_post_types = array_merge( $default_post_types, $custom_post_types );

			// if current edit screen belongs to post types, then change capability.
			if ( in_array( get_current_screen()->id, $all_post_types ) ) {
				add_filter( 'user_has_cap', 'wpseo_master_filter', 10, 3 );
				function wpseo_master_filter( $allcaps, $cap, $args ) {
					$allcaps['wpseo_manage_options'] = false;
					return $allcaps;
				}
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

		// sidebar ads
		if ( ! empty( $this->options['hide_ads'] ) ) {
			echo '#sidebar-container.wpseo_content_cell{display:none!important;}'; // @since v1.0.0; @modified v3.4.1
		}

		// tagline nag
		if ( ! empty( $this->options['hide_tagline_nag'] ) ) {
			echo '#wpseo-dismiss-tagline-notice{display:none;}'; // @since v2.6.0 hide tagline nag
		}

		// robots nag
		if ( ! empty( $this->options['hide_robots_nag'] ) ) {
			echo '#wpseo-dismiss-blog-public-notice,#wpseo_advanced .error-message{display:none;}'; // @since v2.0.0 hide robots nag; @modified v2.5.4 to add styling via the options and not globally.
		}

		// hide upsell notice in Yoast SEO Dashboard
		if ( ! empty( $this->options['hide_upsell_notice'] ) ) {
			echo '#yoast-warnings #wpseo-upsell-notice,#yoast-additional-keyphrase-collapsible-metabox,.wpseo-keyword-synonyms,.wpseo-multiple-keywords{display:none !important;}'; // @since v2.5.3 hide upsell notice in Yoast SEO Dashboard; @modified v2.5.4 improved to remove entire Notification box in the main Dashboard; @modified v2.6.0 only hide this notice; @modified 3.13.4 hide additional keyphrase "option" from metabox as it is ad for premium too.
		}

		// hide premium upsell admin block
		if ( ! empty( $this->options['hide_upsell_admin_block'] ) ) {
			echo '.yoast_premium_upsell,.yoast_premium_upsell_admin_block,#wpseo-local-seo-upsell,div[class^="SocialUpsell__PremiumInfoText"]{display:none}'; // @since v3.1.0; @modified v3.11.1; @modified v3.13.2; @modified v3.14.1
		}

		// hide "Premium" submenu in its entirety
		if ( ! empty( $this->options['hide_premium_submenu'] ) ) {
			echo 'li#toplevel_page_wpseo_dashboard>ul>li:nth-child(6){display:none;}'; // @since v3.6.0 hide "Premium" submenu in its entirety; @modified v3.12.0 decrease with 1, due to Search Console submenu being removed
		}

		// hide Post/Page/Taxonomy Deletion Premium Ad
		if ( ! empty( $this->options['hide_post_deletion_premium_ad'] ) ) {
			echo 'body.edit-php .yoast-alert.notice.notice-warning,body.edit-tags-php .yoast-alert.notice.notice-warning{display:none;}'; // @since v3.8.0
		}

		// Problems/Notification boxes
		if ( ! empty( $this->options['hide_dashboard_problems_notifications'] ) ) {
			if ( in_array( 'problems', $this->options['hide_dashboard_problems_notifications'] ) ) {
				echo '.yoast-container.yoast-container__error{display:none;}'; // @since v2.6.0 hide both Problems/Notifications boxes from Yoast SEO Dashboard; @modified v3.13.1
			}
			if ( in_array( 'notifications', $this->options['hide_dashboard_problems_notifications'] ) ) {
				echo '.yoast-container.yoast-container__warning{display:none;}'; // @since v2.6.0 hide both Problems/Notifications boxes from Yoast SEO Dashboard
			}
		}

		// image warning nag
		if ( ! empty( $this->options['hide_imgwarning_nag'] ) ) {
			echo '#yst_opengraph_image_warning{display:none;}#postimagediv.postbox{border:1px solid #e5e5e5!important;}'; // @since v1.7.0 hide yst opengraph image warning nag
		}

		// hide issue counter
		if ( ! empty( $this->options['hide_issue_counter'] ) ) {
			echo '#wpadminbar .yoast-issue-counter,#toplevel_page_wpseo_dashboard .wp-menu-name .update-plugins{display:none;}'; // @since v2.3.0 hide issue counter from adminbar and plugin menu sidebar; @modified v3.2.1 to remove orange background that shows again; @modified v3.13.5 fix issue 81
		}

		// hide Configuration Wizard on every screen in the Yoast admin
		if ( ! empty( $this->options['hide_config_wizard'] ) ) {
			echo '.yoast-alerts .yoast-container__configuration-wizard{display:none;}'; // @since v3.6.0 hide Configuration Wizard
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

		// help center
		if ( ! empty( $this->options['hide_helpcenter'] ) ) {
			if ( in_array( 'ad', $this->options['hide_helpcenter'] ) ) {
				echo '.wpseo-tab-video__panel.wpseo-tab-video__panel--text,#tab-link-dashboard_dashboard__contact-support,#tab-link-dashboard_general__contact-support,#tab-link-dashboard_features__contact-support,#tab-link-dashboard_knowledge-graph__contact-support,#tab-link-dashboard_webmaster-tools__contact-support,#tab-link-dashboard_security__contact-support,#tab-link-metabox_metabox__contact-support,.yoast-video-tutorial__description:first-child,.react-tabs__tab:last-child,#yoast-helpscout-beacon{display:none;}'; // @since v2.2.0 hide help center ad for premium version or help center entirely; @modified v2.5.5 hide email support/ad as it is a premium only feature; @modified v2.6.0 different tabs gave different classes; @modified v3.3.0 due to Yoast 5.6 update this has all changed; @modified v3.6.0; @modified v3.13.3
			}
			if ( in_array( 'helpcenter', $this->options['hide_helpcenter'] ) ) {
				echo '.yoast-help-center__button{display:none !important;}'; // @since v2.2.0 hide help center ad for premium version or help center entirely; @modified v3.3.0 due to Yoast 5.6 update this has all changed
			}
		}

		// seo settings profile page
		if ( ! empty( $this->options['hide_seo_settings_profile_page'] ) ) {
			echo '.profile-php .yoast.yoast-settings{display:none;}'; // @since v3.6.0
		}

		// hide content/keyword score on Publish/Update Post metabox
		if ( ! empty( $this->options['hide_content_keyword_score'] ) ) {
			echo '
				#misc-publishing-actions #content-score,
				#misc-publishing-actions #keyword-score
				{display:none;}
			'; // @since v3.10.0 hide "Content / Keyword Score" from  Publish/Update metabox
		}

		// hide Premium ad after deleting content (post, page, wc product, cpt)
		if ( ! empty( $this->options['hide_ad_after_trashing_content'] ) ) {
			echo '
				body.edit-php .yoast-notification.notice.notice-warning.is-dismissible,
				body[class*="taxonomy-"] .yoast-notification.notice.notice-warning.is-dismissible
				{display:none;}
			'; // @since v3.14.0; @modified v3.14.2
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
	public static function instance( $file = '', $version = '3.14.3' ) {
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
			'hide_ads'                             => 'on',
			'hide_tagline_nag'                     => 'on',
			'hide_robots_nag'                      => 'on',
			'hide_upsell_notice'                   => 'on',
			'hide_upsell_admin_block'				=> 'on',
			'hide_premium_submenu'					=> 'on',
			'hide_post_deletion_premium_ad'			=> 'on',
			'hide_dashboard_problems_notifications' => array(
				'problems',
				'notifications'
			),
			'hide_config_wizard'					=> 'on',
			'hide_imgwarning_nag'					=> 'on',
			'hide_issue_counter'                   => 'on',
			'hide_helpcenter'                      => array(
				'ad'
			),
			'hide_seo_scores_dropdown_filters'		=> 'on',
			'hide_admincolumns'                    => array(
				'seoscore',
				'readability',
				'title',
				'metadescr',
				'outgoing_internal_links'
			),
			'hide_seo_settings_profile_page'		=> 'on',
			'remove_primarycatfeat'					=> 'on',
			'remove_dbwidget'                      => 'on',
			'remove_adminbar'                      => 'on',
			'hide_content_keyword_score'			=> 'on',
			'remove_html_comments'					=> 'on',
			'remove_permalinks_warning'				=> 'on',
			'remove_advanced'						=> 'on',
			'hide_ad_after_trashing_content'		=> 'on'
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
