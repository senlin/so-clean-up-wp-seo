<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CUWS_Settings {

	/**
	 * The single instance of CUWS_Settings.
	 *
	 * @var    object
	 * @access   private
	 * @since    v2.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var    object
	 * @access   public
	 * @since    v2.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var     array
	 * @access  public
	 * @since   v2.0.0
	 */
	public $settings = array();

	/**
	 * CUWS_Settings constructor.
	 *
	 * @param $parent
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		$this->base = 'cuws_';

		$plugin_slug = plugin_basename( $this->parent->file );

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add settings page to menu
		//add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'add_menu_item' ), 15 );
		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array( $this, 'add_menu_item' ), 15 );
		} else {
			add_action( 'admin_menu', array( $this, 'add_menu_item' ), 15 );
		}
		// Add settings link to plugins page
		//add_filter( is_multisite() ? 'network_admin_plugin_action_links_' . $plugin_slug : 'plugin_action_links_' . $plugin_slug, array( $this, 'add_settings_link' ) );
		if ( is_multisite() ) {
			add_filter( 'network_admin_plugin_action_links_' . $plugin_slug, array( $this, 'add_settings_link' ) );
		} else {
			add_filter( 'plugin_action_links_' . $plugin_slug, array( $this, 'add_settings_link' ) );
		}

		// Save setting in Multisite
		add_action( 'network_admin_edit_' . $this->parent->_token . '_settings', array(
			$this,
			'update_settings',
		) );

	}

	/**
	 * Save settings when on single-site or multisite network admin.
	 *
	 * @access public
	 * @since  2.x
	 */
	public function update_settings() {

		// suggestion by @afragen to try and fix [issue #37](https://github.com/senlin/so-clean-up-wp-seo/issues/37)
		if ( ! isset( $_POST['option_page'], $_POST['action'] ) ) {
    			return;
		}

		$cuws          = CUWS::instance();
		$options_list  = array_keys( $cuws->get_defaults() );
		$multi_options = array(
			'hide_admincolumns',
			'hide_dashboard_problems_notifications',
			'hide_helpcenter',
		);

		if ( $this->parent->_token . '_settings' === $_POST['option_page'] &&
		     'update' === $_POST['action']
		) {
			foreach ( $options_list as $option ) {
				if ( ! isset( $_POST[ $this->parent->_token . '_' . $option ] ) ) {
					$_POST[ $this->parent->_token . '_' . $option ] = null;
					if ( in_array( $option, $multi_options ) ) {
						$_POST[ $this->parent->_token . '_' . $option ] = array();
					}
				}
				$options[ $option ] = $_POST[ $this->parent->_token . '_' . $option ];
			}
			update_site_option( $this->parent->_token . '_settings', $options );

			$location = add_query_arg(
				array( 'page' => $this->parent->_token . '_settings', ),
				network_admin_url( 'admin.php' )
			);
			wp_redirect( $location );
			exit;
		}
	}

	/**
	 * Initialise settings
	 *
	 * @return void
	 * @since   v2.0.0
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 *
	 * @return void
	 * @since   v2.0.0
	 */
	public function add_menu_item() {
		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		add_submenu_page(
			'wpseo_dashboard',
			__( 'Hide SEO Bloat Settings', 'so-clean-up-wp-seo' ),
			__( 'Hide Bloat', 'so-clean-up-wp-seo' ),
			$capability,
			$this->parent->_token . '_settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Add settings link to plugin list table
	 *
	 * @param  array $links Existing links
	 *
	 * @return array        Modified links
	 * @since   v2.0.0
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'so-clean-up-wp-seo' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page
	 * @since    v2.0.0
	 * @modified v2.1.0 simplyfy the options to reflect changes to v3.1 of Yoast SEO plugin (temporarily removing
	 *           non-vital notifications)
	 */
	private function settings_fields() {
		$cuws    = CUWS::instance();
		$options = $cuws->get_defaults();

		$settings['standard'] = array(
			'title'  => __( 'Without further ado: Hide the bloat', 'so-clean-up-wp-seo' ),
			//'description'			=> __( 'description' ),
			'fields' => array(
				array(
					'id'          => 'hide_ads',
					'label'       => __( 'Sidebar Ads', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the cartoon-style sidebar ads on almost all settings pages of the Yoast SEO plugin.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_ads'],
				),
				array(
					'id'          => 'hide_tagline_nag',
					'label'       => __( 'General > Dashboard tab > Problems box > <b>Tagline nag</b>', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide Tagline nag that shows in the Problem Box of the Dashboard tab under General Settings.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_tagline_nag'],
				),
				array(
					'id'          => 'hide_robots_nag',
					'label'       => __( 'General > Dashboard tab > Problems box > <b>Robots nag</b>', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide robots nag that shows as a "Huge SEO issue" in the Problem Box of the Dashboard tab under General Settings.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_robots_nag'],
				),
				array(
					'id'          => 'hide_upsell_notice',
					'label'       => __( 'General > Dashboard tab > Notifications box > <b>Upsell Notice</b>', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the Upsell Notice in the Notifications box that shows in the Notifications Box of the Dashboard tab under General Settings.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_upsell_notice'],
				),
				array(
					'id'          => 'hide_dashboard_problems_notifications',
					'label'       => __( 'General > Dashboard tab > <b>Problems/Notifications boxes</b>', 'so-clean-up-wp-seo' ),
					'description' => '<br>' . __( 'Hide entire Problems/Notifications boxes from the Dashboard tab under General Settings.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox_multi',
					'options'     => array(
						'problems'      => __( 'Hide entire Problems box', 'so-clean-up-wp-seo' ),
						'notifications' => __( 'Hide entire Notifications box', 'so-clean-up-wp-seo' ),
					),
					'default'     => $options['hide_dashboard_problems_notifications'],
				),
				array(
					'id'          => 'hide_upsell_metabox_socialtab',
					'label'       => __( 'Upsell Notice social tab Yoast Post/Page metabox', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the Upsell Notice in the social tab of the Yoast Post/Page metabox', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_upsell_metabox_socialtab'],
				),
				array(
					'id'          => 'hide_upsell_admin_block',
					'label'       => __( 'Premium Upsell Admin Block', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the Premium Upsell Admin Block that shows in the entire Yoast SEO backend.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_upsell_admin_block'],
				),
				array(
					'id'          => 'hide_premium_submenu',
					'label'       => __( 'Premium submenu', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hides the "Premium" submenu in its entirety.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_premium_submenu'],
				),
				array(
					'id'          => 'hide_premium_metabox',
					'label'       => __( 'Go Premium metabox', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hides "Go Premium" metabox in edit Post/Page screens.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_premium_metabox'],
				),
				array(
					'id'          => 'hide_post_deletion_premium_ad',
					'label'       => __( 'Post/Page/Taxonomy Deletion Premium Ad', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hides Post Deletion Premium Ad in edit Post/Page/Taxonomy screens.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_post_deletion_premium_ad'],
				),
				array(
					'id'          => 'hide_config_wizard',
					'label'       => __( 'Hide Configuration Wizard check', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the Configuration Wizard check that shows at the top of almost all Yoast SEO Settings screens.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_config_wizard'],
				),
				array(
					'id'          => 'hide_imgwarning_nag',
					'label'       => __( 'Featured image nag', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide image warning nag that shows in edit Post/Page screen when featured image is smaller than 200x200 pixels.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_imgwarning_nag'],
				),
				array(
					'id'          => 'hide_issue_counter',
					'label'       => __( 'Issue Counter', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide issue counter from adminbar and sidebar.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_issue_counter'],
				),
				array(
					'id'          => 'hide_readability_features',
					'label'       => __( 'Readability "Features"', 'so-clean-up-wp-seo' ),
					'description' => __( 'The Readability metabox on Posts/Pages has received some new "features" with growing color bars that indicate the "correct length" of titles and descriptions; this option hides these "features".', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_readability_features'],
				),
				array(
					'id'          => 'hide_helpcenter',
					'label'       => __( 'Help center', 'so-clean-up-wp-seo' ),
					'description' => '<br>' . __( 'The Yoast SEO plugin comes with a help center (since Yoast SEO 3.2) that shows introduction videos and (of course) ads for the premium version of the plugin and now (since Yoast SEO 5.6) also a paid-for course; select here what to hide (if anything).', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox_multi',
					'options'     => array(
						'ad'         => __( 'Hide the ads', 'so-clean-up-wp-seo' ),
						'helpcenter' => __( 'Hide the entire help center', 'so-clean-up-wp-seo' ),
					),
					'default'     => $options['hide_helpcenter'],
				),
				array(
					'id'          => 'hide_admincolumns',
					'label'       => __( 'Admin columns', 'so-clean-up-wp-seo' ),
					'description' => '<br>' . __( 'The Yoast SEO plugin adds 5(!) admin columns on the Posts/Pages screen and the SEO Score and Readability admin columns to taxonomies (since Yoast SEO 3.1). Multiple selections are allowed.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox_multi',
					'options'     => array(
						'seoscore'    => __( 'Hide SEO score column', 'so-clean-up-wp-seo' ),
						'readability' => __( 'Hide Readability score column', 'so-clean-up-wp-seo' ),
						'title'       => __( 'Hide title column', 'so-clean-up-wp-seo' ),
						'metadescr'   => __( 'Hide meta description column', 'so-clean-up-wp-seo' ),
						'focuskw'     => __( 'Hide focus keyword column', 'so-clean-up-wp-seo' ),
					),
					'default'     => $options['hide_admincolumns'],
				),
				array(
					'id'          => 'hide_seo_settings_profile_page',
					'label'       => __( 'Profile page', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide SEO Settings on individual profile page.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_seo_settings_profile_page'],
				),
				array(
					'id'          => 'remove_primarycatfeat',
					'label'       => __( 'Primary category', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove primary category feature.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_primarycatfeat'],
				),
				array(
					'id'          => 'remove_adminbar',
					'label'       => __( 'SEO menu admin bar', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove the admin bar Yoast SEO menu.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_adminbar'],
				),
				array(
					'id'          => 'remove_dbwidget',
					'label'       => __( 'Dashboard widget', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove the Yoast SEO widget from the WordPress Dashboard.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_dbwidget'],
				),
			),
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 * @since   v2.0.0
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) {
					continue;
				}

				register_setting( $this->parent->_token . '_settings', 'settings' );

				// Add section to page
				add_settings_section( $section,
					$data['title'],
					array(
						$this,
						'settings_section',
					),
					$this->parent->_token . '_settings'
				);

				foreach ( $data['fields'] as $field ) {

					// Add field to page
					add_settings_field(
						$field['id'],
						$field['label'],
						array(
							$this->parent->admin,
							'display_field',
						),
						$this->parent->_token . '_settings',
						$section,
						array(
							'field'  => $field,
							'prefix' => $this->base,
						)
					);
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
		if ( isset( $_POST['action'] ) && 'update' === $_POST['action'] ) {
			$this->update_settings();
		}
	}

	/**
	 * @access public
	 *
	 * @param $section
	 */
	public function settings_section( $section ) {
		$html = "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 *
	 * @return void
	 * @since   v2.0.0
	 */
	public function settings_page() {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2>' . esc_attr( __( 'Hide SEO Bloat Settings', 'so-clean-up-wp-seo' ) ) . '</h2>' . "\n";

		$html .= '<p>' . esc_attr( __( 'On this settings page you can adjust things here and there to your liking.', 'so-clean-up-wp-seo' ) ) . '<br>' . esc_attr( __( 'Although some settings are for "features" that can easily be dismissed on a per user basis, hiding or removing them here, has two advantages:', 'so-clean-up-wp-seo' ) ) . '</p><ol><li>' . esc_attr( __( 'the settings here are global, for all users', 'so-clean-up-wp-seo' ) ) . '</li><li>' . esc_attr( __( 'these settings are centralised on one page, no need to keep dismissing stuff all over the site\'s backend', 'so-clean-up-wp-seo' ) ) . '</li></ol></p>' . "\n";

		$html .= '<p>' . esc_attr( __( 'The default settings, when you activate the plugin, are that almost all boxes have been ticked; why else would you install our plugin?', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";

		$html .= '<p>' . esc_attr( __( 'If you ever want to remove the Hide SEO Bloat plugin, then you can rest assured that it cleans up after itself:', 'so-clean-up-wp-seo' ) ) . '<br />' . esc_attr( __( 'upon deletion it removes all options automatically.', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";

		//$action = is_network_admin() ? 'edit.php?action=' . $this->parent->_token . '_settings' : 'options.php';
		if ( is_network_admin() ) {
			$action = 'edit.php?action=' . $this->parent->_token . '_settings';
		} else {
			$action = 'options.php';
		}

		$html .= '<form method="post" action="' . $action . '" enctype="multipart/form-data">' . "\n";

		// Get settings fields
		ob_start();
		settings_fields( $this->parent->_token . '_settings' );
		do_settings_sections( $this->parent->_token . '_settings' );
		$html .= ob_get_clean();

		$html .= '<p class="submit">' . "\n";

		$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings', 'so-clean-up-wp-seo' ) ) . '" />' . "\n";
		$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";


		// see //codex.wordpress.org/I18n_for_WordPress_Developers#HTML for instructions on i18n of $html
		$rateurl = 'https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform';
		$html    .= '<p class="rate-this-plugin">' . sprintf( wp_kses( __( 'If you have found this plugin at all useful, please give it a favourable rating in the <a href="%s" title="Rate this plugin!">WordPress Plugin Repository</a>.', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $rateurl ) ) . '</p>' . "\n";

		$translateurl = 'https://translate.wordpress.org/projects/wp-plugins/so-clean-up-wp-seo';
		$html         .= '<p class="translate">' . sprintf( wp_kses( __( 'You can also help a great deal by <a href="%s" title="translate the plugin into your own language">translating the plugin</a> into your own language.', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $translateurl ) ) . '</p>' . "\n";

		$supporturl = 'https://github.com/senlin/so-clean-up-wp-seo/issues';
		$html       .= '<p class="support">' . sprintf( wp_kses( __( 'If you have an issue with this plugin or want to leave a feature request, please note that we give <a href="%s" title="Support or Feature Requests via Github">support via Github</a> only.', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $supporturl ) ) . '</p>' . "\n";

		$html .= '<div class="author postbox">' . "\n";

		$html .= '<h3 class="hndle"><span>' . esc_attr( __( 'About the Author', 'so-clean-up-wp-seo' ) ) . '</span></h3>' . "\n";

		$html .= '<div class="inside">' . "\n";
		$html .= '<div class="top">' . "\n";

		$html .= '<img class="author-image" src="' . esc_url( plugins_url( 'so-clean-up-wp-seo/images/pietbos-80x80.jpg' ) ) . '" alt="plugin author Pieter Bos" width="80" height="80" />' . "\n";

		$sowpurl = 'https://so-wp.com';
		$html    .= '<p>' . sprintf( wp_kses( __( 'Hi, my name is Pieter Bos, I hope you like this plugin! Please check out any of my other plugins on <a href="%s" title="SO WP">SO WP</a>. You can find out more information about me via the following links:', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $sowpurl ) ) . '</p>' . "\n";

		$html .= '</div>' . "\n"; // end .top

		$html .= '<ul>' . "\n";
		$html .= '<li><a href="https://bohanintl.com" target="_blank" title="BHI Consulting for Websites">' . esc_attr( __( 'BHI Consulting for Websites', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://www.linkedin.com/in/pieterbos83" target="_blank" title="LinkedIn profile">' . esc_attr( __( 'LinkedIn', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://so-wp.com" target="_blank" title="SO WP">' . esc_attr( __( 'SO WP', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://github.com/senlin" title="on Github">' . esc_attr( __( 'Github', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://bohanintl.com/wptips/" title="Useful WordPress Tips for people who like to DIY">' . esc_attr( __( 'WP Tips', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://profiles.wordpress.org/senlin/" title="on WordPress.org">' . esc_attr( __( 'WordPress.org Profile', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '</ul>' . "\n";

		$html .= '</div>' . "\n"; // end .inside

		$html .= '</div>' . "\n"; // end .postbox

		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main CUWS_Settings Instance
	 *
	 * Ensures only one instance of CUWS_Settings is loaded or can be loaded.
	 *
	 * @since v2.0.0
	 * @static
	 * @see   CUWS()
	 *
	 * @param CUWS $parent Instance of main class.
	 *
	 * @return CUWS_Settings $_instance
	 */
	public static function instance( $parent ) {
		if ( null === self::$_instance ) {
			self::$_instance = new self( $parent );
		}

		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Access denied' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Access denied' ), $this->parent->_version );
	} // End __wakeup()

}
