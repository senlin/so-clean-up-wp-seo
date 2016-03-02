<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class CUWS {

	/**
	 * The single instance of CUWS.
	 * @var 	object
	 * @access  private
	 * @since 	v2.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   v2.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $dir;

	/**
	 * The plugin styles directory.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $styles_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $styles_url;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   v2.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '2.1.0' ) {
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

		// @since v1.3.0
		add_action( 'admin_bar_menu', array( $this, 'so_cuws_remove_adminbar_settings' ), 999 );
		// @since 1.4.0
		add_action( 'admin_init', array( $this, 'so_cuws_ignore_tour' ), 999 );
		// @since 1.5.0
		add_action( 'wp_dashboard_setup', array( $this, 'so_cuws_remove_dashboard_widget' ) );
		// @since 2.0.0
		add_action( 'admin_head', array( $this, 'so_cuws_hide_visibility_css' ) );


		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new CUWS_Admin_API();
		}


	} // End __construct ()

	/**
	 * Cleanup functions depending on each checkbox returned value in admin
	 *
	 * @since    v2.0.0
	 */

	/**
	 * Remove Settings submenu in admin bar
	 *
	 * inspired by [Lee Rickler](https://profiles.wordpress.org/lee-rickler/)
	 * @since v1.3.0
	 */

	public function so_cuws_remove_adminbar_settings() {

		$adminbar = get_option( 'cuws_remove_adminbar' );

		global $wp_admin_bar;

		if ( 'seo' == $adminbar ) {

			$wp_admin_bar->remove_node( 'wpseo-settings' );

		}

		if ( 'keyword' == $adminbar ) {

			$wp_admin_bar->remove_node( 'wpseo-kwresearch' );

		}

		if ( 'both' == $adminbar ) {

			$wp_admin_bar->remove_node( 'wpseo-menu' );

		}

	}

	/**
	 * Replaces previous so_cuws_remove_about_tour() function that has become redundant from Yoast SEO 2.2.1 onwards
	 *
	 * @since v1.4.0
	 */
	public function so_cuws_ignore_tour() {

		update_user_meta( get_current_user_id(), 'wpseo_ignore_tour', true );

	}

	/**
	 * Version 2.3 of Yoast SEO introduced a dashboard widget
	 * This function removes this widget
	 *
	 * @since v1.5.0
	 */
	public function so_cuws_remove_dashboard_widget() {

		$remove_dbwidget = get_option( 'cuws_remove_dbwidget' );

		if ( !empty( $remove_dbwidget ) ) {

			remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );

		}
	}

	/**
	 * CSS needed to hide the various options ticked with checkboxes
	 *
	 * @since v2.0.0
	 * @modified v2.1.0 remove options for nags that have been temporarily disabled in v3.1 of Yoast SEO plugin
	 */
	// CSS needed to hide the various options ticked with checkboxes
	public function so_cuws_hide_visibility_css() {

		echo '<style media="screen" id="so-hide-seo-bloat" type="text/css">';

		// sidebar ads
		$hide_ads = get_option( 'cuws_hide_ads' );
		if ( !empty( $hide_ads ) ) {
			echo '#sidebar-container.wpseo_content_cell{visibility:hidden;}'; // @since v1.0.0
		}

		// about nag
		$hide_about_nag = get_option( 'cuws_hide_about_nag' );
		if ( !empty( $hide_about_nag ) ) {
			echo '#wpseo-dismiss-about{display:none;}'; // @since v1.4.0 hide updated nag (introduced with Yoast SEO version 2.2.1)
		}

		// robots nag
		$hide_robots_nag = get_option( 'cuws_hide_robots_nag' );
		if ( !empty( $hide_robots_nag ) ) {
			echo '#wpseo_advanced .error-message{display:none;}'; // @since v2.0.0 hide robots nag
		}

		// image warning nag
		$hide_imgwarning_nag = get_option( 'cuws_hide_imgwarning_nag' );
		if ( !empty( $hide_imgwarning_nag ) ) {
			echo '#yst_opengraph_image_warning{display:none;}#postimagediv.postbox{border:1px solid #e5e5e5!important;}'; // @since v1.7.0 hide yst opengraph image warning nag
		}

		// add keyword button
		$hide_addkw_button = get_option( 'cuws_hide_addkw_button' );
		if ( !empty( $hide_addkw_button ) ) {
			echo '.wpseo-add-keyword{display:none;}'; // @since v1.7.3 hide add-keyword-button in UI which only serves ad in overlay
		}

		// trafficlight
		$hide_trafficlight = get_option( 'cuws_hide_trafficlight' );
		if ( !empty( $hide_trafficlight ) ) {
			echo '.submitbox #wpseo-score{display:none;}'; // @since v1.7.4 hide wpseo-score traffic light in publish box
		}

		// content analysis
		$hide_wpseoanalysis = get_option( 'cuws_hide_wpseoanalysis' );
		if ( !empty( $hide_wpseoanalysis ) ) {
			echo '.wpseoanalysis{display:none;}'; // @since v2.0.0 hide_wpseoanalysis
		}

		// admin columns
		// @since v2.0.0 remove seo columns one by one
		// @modified 2.0.2 add empty array as default to avoid warnings form subsequent in_array checks - credits [Ronny Myhre Njaastad](https://github.com/ronnymn)
		// @modified 2.1 simplyfy the CSS rules and add the rule to hide the seo-score column on taxonomies (added to v3.1 of Yoast SEO plugin)
		$admincolumns = get_option( 'cuws_hide_admin_columns', array() );

		// all columns
		if ( in_array( 'all', $admincolumns ) ) {
		    echo '.column-wpseo-score,.column-wpseo_score,.column-wpseo-title,.column-wpseo-metadesc,.column-wpseo-focuskw{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// seo score column
		if ( in_array( 'seoscore', $admincolumns ) ) {
		    echo '.column-wpseo-score,.column-wpseo_score{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// title column
		if ( in_array( 'title', $admincolumns ) ) {
			echo '.column-wpseo-title{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// meta description column
		if ( in_array( 'metadescr', $admincolumns ) ) {
			echo '.column-wpseo-metadesc{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		// focus keyword column
		if ( in_array( 'focuskw', $admincolumns ) ) {
			echo '.column-wpseo-focuskw{display:none;}'; // @since v2.0.0 remove seo columns one by one
		}

		echo '</style>';

	}


	/**
	 * Load admin CSS.
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
	 * @see CUWS()
	 * @return Main CUWS instance
	 */
	public static function instance ( $file = '', $version = '2.0.2' ) {
		if ( is_null( self::$_instance ) ) {
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
	 * @access  public
	 * @since   v2.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	private function _set_defaults() {
		update_option( 'cuws_hide_ads', 'on', true );
		update_option( 'cuws_hide_about_nag', 'on', true );
		update_option( 'cuws_hide_robots_nag', 'on', true );
		update_option( 'cuws_hide_imgwarning_nag', 'on', true );
		update_option( 'cuws_hide_addkw_button', 'on', true );
		update_option( 'cuws_hide_trafficlight', 'on', true );
		update_option( 'cuws_hide_wpseoanalysis', 'on', true );
		update_option( 'cuws_hide_admin_columns', array( 'seoscore', 'title', 'metadescr' ), true );
		update_option( 'cuws_remove_adminbar', 'seo', true );
		update_option( 'cuws_remove_dbwidget', 'on', true );
	} // End _set_defaults ()

}
