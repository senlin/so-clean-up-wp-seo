<?php

if ( ! defined( 'ABSPATH' ) ) exit;

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
	 *
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '2.5.3' ) {
		$this->_version = $version;
		$this->_token = 'cuws';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'css';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/css/', $this->file ) ) );

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load admin CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Handle localisation
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 0 );

		/*** PLUGIN FUNCTIONS ***/

		// @since 1.5.0
		add_action( 'wp_dashboard_setup', array( $this, 'so_cuws_remove_dashboard_widget' ) );
		// @since 2.0.0
		add_action( 'admin_head', array( $this, 'so_cuws_hide_visibility_css' ) );


		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new CUWS_Admin_API();
		}

		$this->options = $this->get_settings_as_array();

	} // End __construct ()

	/**
	 * Cleanup functions depending on each checkbox returned value in admin
	 *
	 * @since    v2.0.0
	 */

	/**
	 * Since Yoast SEO 3.6 it is possible to disable the adminbar menu within Dashboard > Features, therefore this setting has become redundant
	 *
	 * @since v2.5.0
	 */

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
	 * CSS needed to hide the various options ticked with checkboxes
	 *
	 * @since    v2.0.0
	 * @modified v2.1.0 remove options for nags that have been temporarily disabled in v3.1 of Yoast SEO plugin
	 */
	// CSS needed to hide the various options ticked with checkboxes
	public function so_cuws_hide_visibility_css() {

		echo '<style media="screen" id="so-hide-seo-bloat" type="text/css">';

		// sidebar ads
		if ( ! empty( $this->options['hide_ads'] ) ) {
			echo '#sidebar-container.wpseo_content_cell{visibility:hidden;}'; // @since v1.0.0
		}

		// about nag
		if ( ! empty( $this->options['hide_about_nag'] ) ) {
			echo '#wpseo-dismiss-about{display:none;}'; // @since v1.4.0 hide updated nag (introduced with Yoast SEO version 2.2.1)
		}

		// robots nag
		if ( ! empty( $this->options['hide_robots_nag'] ) ) {
			echo '#yoast-alerts-dismissed, #yoast-warnings-dismissed, #wpseo_advanced .error-message{display:none;}'; // @since v2.0.0 hide robots nag; @modified v2.5.4 to add styling via the options and not globally.
		}

		// image warning nag
		if ( ! empty( $this->options['hide_imgwarning_nag'] ) ) {
			echo '#yst_opengraph_image_warning{display:none;}#postimagediv.postbox{border:1px solid #e5e5e5!important;}'; // @since v1.7.0 hide yst opengraph image warning nag
		}

		// add keyword button
		if ( ! empty( $this->options['hide_addkw_button'] ) ) {
			echo '.wpseo-tab-add-keyword,.wpseo-add-keyword.button{display:none;}'; // @since v1.7.3 hide add-keyword-button in UI which only serves ad in overlay
		}

		// hide issue counter
		if ( ! empty( $this->options['hide_issue_counter'] ) ) {
			echo '#wpadminbar .yoast-issue-counter,#toplevel_page_wpseo_dashboard .update-plugins .plugin-count{display:none;}'; // @since v2.3.0 hide issue counter from adminbar and plugin menu sidebar
		}

		// hide red star "Go Premium" submenu
		if ( ! empty( $this->options['hide_gopremium_star'] ) ) {
			echo '#adminmenu .wpseo-premium-indicator,.wpseo-metabox-buy-premium,#wp-admin-bar-wpseo-licenses{display:none;}'; // @since v2.5.0 hide star of "Go Premium" submenu
		}

		// content analysis
		if ( ! empty( $this->options['hide_wpseoanalysis'] ) ) {
			echo '.wpseoanalysis{display:none;}.wpseo-score-icon{display:none!important;}'; // @since v2.0.0 hide_wpseoanalysis; @modified v2.3.0 to remove the colored ball from the metabox tab too.
		}

		// keyword/content score
		if ( 'both' == $this->options['hide_content_keyword_score'] ) {
			echo '.yoast-seo-score.content-score,.yoast-seo-score.keyword-score{display:none;}'; // @since v2.3.0 hide both Keyword and Content Score from edit Post/Page screens
		}
		if ( 'keyword_score' == $this->options['hide_content_keyword_score'] ) {
			echo '.yoast-seo-score.keyword-score{display:none;}'; // @since v2.3.0 hide both Keyword and Content Score from edit Post/Page screens
		}
		if ( 'content_score' == $this->options['hide_content_keyword_score'] ) {
			echo '.yoast-seo-score.content-score{display:none;}'; // @since v2.3.0 hide both Keyword and Content Score from edit Post/Page screens
		}

		// admin columns
		// @since v2.0.0 remove seo columns one by one
		// @modified 2.0.2 add empty array as default to avoid warnings form subsequent in_array checks - credits [Ronny Myhre Njaastad](https://github.com/ronnymn)
		// @modified 2.1 simplyfy the CSS rules and add the rule to hide the seo-score column on taxonomies (added to v3.1 of Yoast SEO plugin)

		// all columns
		if ( in_array( 'all', $this->options['hide_admin_columns'] ) ) {
			echo '.column-wpseo-score,.column-wpseo_score,.column-wpseo-title,.column-wpseo-metadesc,.column-wpseo-focuskw{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// seo score column
		if ( in_array( 'seoscore', $this->options['hide_admin_columns'] ) ) {
			echo '.column-wpseo-score,.column-wpseo_score{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// title column
		if ( in_array( 'title', $this->options['hide_admin_columns'] ) ) {
			echo '.column-wpseo-title{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// meta description column
		if ( in_array( 'metadescr', $this->options['hide_admin_columns'] ) ) {
			echo '.column-wpseo-metadesc{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// focus keyword column
		if ( in_array( 'focuskw', $this->options['hide_admin_columns'] ) ) {
			echo '.column-wpseo-focuskw{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// help center
		if ( 'ad' == $this->options['hide_helpcenter'] ) {
			echo '.wpseo-tab-video__panel.wpseo-tab-video__panel--text{display:none;}'; // @since v2.2.0 hide help center ad for premium version or help center entirely
		}
		if ( 'helpcenter' == $this->options['hide_helpcenter'] ) {
			echo '.wpseo-tab-video-container{display:none;}'; // @since v2.2.0 hide help center ad for premium version or help center entirely
		}

		// hide upsell notice in Yoast SEO Dashboard
		if ( ! empty( $this->options['hide_upsell_notice'] ) ) {
			echo '#wpseo-upsell-notice{display:none;}'; // @since v2.5.3 hide upsell notice in Yoast SEO Dashboard
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
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Loads the translation file.
	 *
	 * @since v1.0.0
	 */
	function i18n() {

		/* Load the translation of the plugin. */
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
	public static function instance( $file = '', $version = '2.5.3' ) {
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
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'No Access' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'No Access' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @since   v2.0.0
	 * @return  void
	 */
	public function install () {
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
	 * Set default values on activation.
	 *
	 * @access private
	 * @return void
	 */
	private function _set_defaults() {
		update_site_option( 'cuws_hide_ads', 'on' );
		update_site_option( 'cuws_hide_about_nag', 'on' );
		update_site_option( 'cuws_hide_robots_nag', 'on' );
		update_site_option( 'cuws_hide_imgwarning_nag', 'on' );
		update_site_option( 'cuws_hide_addkw_button', 'on' );
		update_site_option( 'cuws_hide_trafficlight', 'on' );
		update_site_option( 'cuws_hide_issue_counter', 'on' );
		update_site_option( 'cuws_hide_gopremium_star', 'on' );
		update_site_option( 'cuws_hide_wpseoanalysis', 'on' );
		update_site_option( 'cuws_hide_content_keyword_score', 'both' );
		update_site_option( 'cuws_hide_helpcenter', 'ad' );
		update_site_option( 'cuws_hide_admin_columns', array( 'seoscore', 'title', 'metadescr' ) );
		update_site_option( 'cuws_remove_dbwidget', 'on' );
		update_site_option( 'cuws_hide_upsell_notice', 'on' );
	} // End _set_defaults ()

	/**
	 * Get plugin settings as an array.
	 *
	 * @access public
	 * @return array
	 */
	public function get_settings_as_array() {
		$settings = array();
		$options  = array(
			'hide_ads',
			'hide_about_nag',
			'hide_robots_nag',
			'hide_imgwarning_nag',
			'hide_addkw_button',
			'hide_trafficlight',
			'hide_wpseoanalysis',
			'hide_issue_counter',
			'hide_gopremium_star',
			'hide_content_keyword_score',
			'hide_helpcenter',
			'hide_admin_columns',
			'remove_dbwidget',
			'hide_upsell_notice',
		);

		foreach ( $options as $option ) {
			$settings[ $option ] = get_site_option( $this->_token . '_' . $option );
		}
		$settings['version'] = $this->_version;

		return $settings;
	}

}
