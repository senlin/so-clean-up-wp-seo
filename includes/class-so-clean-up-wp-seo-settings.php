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
	 */
	private function settings_fields() {
		$cuws    = CUWS::instance();
		$options = $cuws->get_defaults();

		$settings['section_1'] = array(
			'title'  => __( 'Yoast SEO Settings pages', 'so-clean-up-wp-seo' ),
			//'description'			=> __( 'description' ),
			'fields' => array(
				array(
					'id'          => 'hide_dashboard_problems_notifications',
					'label'       => __( 'General > Dashboard tab > Problems/Notifications boxes', 'so-clean-up-wp-seo' ),
					'description' => '<br>' . __( 'Hide entire Problems/Notifications boxes from the Dashboard tab under General Settings.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox_multi',
					'options'     => array(
						'problems'      => __( 'Hide entire Problems box', 'so-clean-up-wp-seo' ),
						'notifications' => __( 'Hide entire Notifications box', 'so-clean-up-wp-seo' ),
					),
					'default'     => $options['hide_dashboard_problems_notifications'],
				),
				array(
					'id'          => 'hide_ads',
					'label'       => __( 'Settings page > Yoast Premium', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide as many as possible ads, premium features or upsells from the Yoast Settings pages.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_ads'],
				),
				array(
					'id'          => 'hide_premium_submenu',
					'label'       => __( 'Premium submenus and issue counter', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hides the "Premium", "Workouts" and "Redirects" submenus as well as the issue counter from the admin sidebar.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_premium_submenu'],
				),
			)
		);
		
		$settings['section_2'] = array(
			'title'	=> __( 'Posts, Pages, Custom post type, Taxonomy pages', 'so-clean-up-wp-seo' ),
			'fields' => array(
				array(
					'id'          => 'hide_admincolumns',
					'label'       => __( 'Admin columns', 'so-clean-up-wp-seo' ),
					'description' => '<br>' . __( 'There are so many admin columns added to Posts/Pages/taxonomies that it is impossible to see the things that matter, such as the Title. Multiple selections are allowed.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox_multi',
					'options'     => array(
						'seoscore'    => __( 'Remove SEO score column', 'so-clean-up-wp-seo' ),
						'readability' => __( 'Remove Readability score column', 'so-clean-up-wp-seo' ),
						'title'       => __( 'Remove SEO title column', 'so-clean-up-wp-seo' ),
						'metadescr'   => __( 'Remove Meta Desc. column', 'so-clean-up-wp-seo' ),
						'focuskw'     => __( 'Remove keyphrase column', 'so-clean-up-wp-seo' ),
						'outgoing_internal_links' => __( 'Remove outgoing/received internal links column', 'so-clean-up-wp-seo' ),
					),
					'default'     => $options['hide_admincolumns'],
				),
				array(
					'id'          => 'remove_seo_scores_dropdown_filters',
					'label'       => __( 'SEO/Readability Scores Dropdown Filters', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove SEO Scores and Readability Scores Dropdown Filters on the Edit Posts/Pages screen', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_seo_scores_dropdown_filters'],
				),
				array(
					'id'          => 'hide_imgwarning_nag',
					'label'       => __( 'Featured image nag', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide image warning nag that shows in edit Post/Page screen when featured image is smaller than 200x200 pixels.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_imgwarning_nag'],
				),
				array(
					'id'          => 'hide_content_keyword_score',
					'label'       => __( 'Keyword/Content Score', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide the Keyword/Content Score from the Publish/Update Metabox on the Edit Post/Page/CPT screen.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_content_keyword_score'],
				),
				array(
					'id'          => 'hide_premium_features_yoast_metabox',
					'label'       => __( 'Hide Premium features on new/edit post-type screens', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide Premium features in the Yoast SEO metabox when publishing or editing content.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_premium_features_yoast_metabox'],
				),
				array(
					'id'          => 'hide_ad_after_trashing_content',
					'label'       => __( 'Hide Ad after trashing content', 'so-clean-up-wp-seo' ),
					'description' => __( 'When deleting content (Post, Page, Product and other Custom Post Type) a new notice appears on the edit screen that is an upsell ad for the premium version of Yoast SEO. This setting hides that notice.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_ad_after_trashing_content'],
				),
			)
		);

		$settings['section_3'] = array(
			'title'	=> __( 'Miscellaneous', 'so-clean-up-wp-seo' ),
			'fields' => array(
				array(
					'id'          => 'remove_adminbar',
					'label'       => __( 'SEO menu admin bar', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove Yoast SEO icon and drop-down menu with more premium buttons from the admin bar.', 'so-clean-up-wp-seo' ),
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
				array(
					'id'          => 'remove_permalinks_warning',
					'label'       => __( 'Remove Permalinks Warning Notice', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove the notice that shows when changing permalinks informing the user that it is not a good idea', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_permalinks_warning'],
				),
				array(
					'id'          => 'hide_seo_settings_profile_page',
					'label'       => __( 'Profile page', 'so-clean-up-wp-seo' ),
					'description' => __( 'Hide SEO Settings on individual profile page.', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['hide_seo_settings_profile_page'],
				),
				array(
					'id'          => 'remove_html_comments',
					'label'       => __( 'Remove HTML Comments', 'so-clean-up-wp-seo' ),
					'description' => __( 'Remove the HTML Comments from the source code (frontend) of the site', 'so-clean-up-wp-seo' ),
					'type'        => 'checkbox',
					'default'     => $options['remove_html_comments'],
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
		$settings = $this->settings_fields();
	
		if ( is_array( $settings ) ) {
			foreach ( $settings as $section => $data ) {
				register_setting( $this->parent->_token . '_settings', 'settings' );
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );
	
				foreach ( $data['fields'] as $field ) {
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
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

		$html .= '<p>' . esc_attr( __( 'The default settings, when you activate the plugin, are that almost all boxes have been ticked; why else would you install this plugin?', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";

		$html .= '<p>' . esc_attr( __( 'If you ever want to remove the Hide SEO Bloat plugin, then you can rest assured that it cleans up after itself:', 'so-clean-up-wp-seo' ) ) . '<br />' . esc_attr( __( 'upon deletion it removes all options automatically.', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		
		$html .= '<p><strong>' . esc_attr( __( 'Without further ado: Hide the bloat', 'so-clean-up-wp-seo' ) ) . '</strong></p><hr>' . "\n";

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

		$html .= '<img class="author-image" src="' . esc_url( plugins_url( 'so-clean-up-wp-seo/images/pieterbos.jpeg' ) ) . '" alt="plugin author Pieter Bos" width="80" height="80" />' . "\n";

		$sowpurl = 'https://so-wp.com';
		$html    .= '<p>' . sprintf( wp_kses( __( 'Hi, my name is Pieter Bos, I hope you like this plugin! Please check out any of my other plugins on <a href="%s" target="_blank" title="SO WP">SO WP</a>. You can find out more information about me via the following links:', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'title' => array() ) ) ), esc_url( $sowpurl ) ) . '</p>' . "\n";

		$html .= '</div>' . "\n"; // end .top

		$html .= '<ul>' . "\n";
		$html .= '<li><a href="https://www.bhi-localization.com" target="_blank" title="BHI Localization for Websites">' . esc_attr( __( 'BHI Localization for Websites', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://www.linkedin.com/in/pieterbos83" target="_blank" title="LinkedIn profile">' . esc_attr( __( 'LinkedIn', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://so-wp.com" target="_blank" title="SO WP">' . esc_attr( __( 'SO WP', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://github.com/senlin" target="_blank" title="on Github">' . esc_attr( __( 'Github', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '<li><a href="https://profiles.wordpress.org/senlin/" target="_blank" title="on WordPress.org">' . esc_attr( __( 'WordPress.org Profile', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
		$html .= '</ul>' . "\n";

		$html .= '</div>' . "\n"; // end .inside

		$html .= '</div>' . "\n"; // end .postbox

		$html .= '<h3>' . esc_attr( __( 'Mission statement:', 'so-clean-up-wp-seo' ) ) . '</h3>' . "\n";
		$html .= '<p>' . esc_attr( __( 'The first version of the Hide SEO Bloat plugin was released in April 2015 and ever since team Yoast and I have been playing a game of cat and mouse.', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		$html .= '<p>' . esc_attr( __( 'Since version 20.0 of Yoast SEO however, the Settings page has received a complete overhaul, which made the Hide SEO Bloat plugin almost obsolete!', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		$html .= '<p>' . esc_attr( __( 'Things have become much, much more trickier to remove/hide now and some things simply can no longer be hidden (believe me, I have tried).', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		$html .= '<p>' . esc_attr( __( 'Why are there still people using Yoast SEO one might ask? There are so many great alternatives that come without screaming ads and hiding features behind a paywall!', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		$ceaurl = 'https://wordpress.org/plugins/classic-editor-addon/';
		$html .= '<p>' . sprintf( wp_kses( __( 'And the only reason that I have to keep Yoast SEO installed (on a sandbox that is) is because of the mere 10K installs where Hide SEO Bloat is running. Compare that with my popular <a href="%s" target="_blank" title="Classic Editor + plugin">Classic Editor + plugin</a>, which has more than 30,000 active installs!', 'so-clean-up-wp-seo' ), array( 'a' => array( 'href' => array(), 'target' => array(), 'title' => array() ) ) ), esc_url( $ceaurl ) ) . '</p>' . "\n";
		$html .= '<p>' . esc_attr( __( 'For everyone to become much more productive and happier, my proposal therefore is to hang the Yoast SEO plugin from a tree and switch to SEOPress, The SEO Framework, Rankmath, or any other SEO plugin out there! Did you know that most SEO plugins come with easy one-click migration tools?', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		$html .= '<p>' . esc_attr( __( 'The goal is to bring the active installs of Hide SEO Bloat to zero, so I can finally quit this nonsense and focus on work that actually pays my bills!', 'so-clean-up-wp-seo' ) ) . '</p>' . "\n";
		
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
