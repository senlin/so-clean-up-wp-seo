<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class CUWS_Settings {

	/**
	 * The single instance of CUWS_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	v2.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	v2.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   v2.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   v2.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'cuws_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Initialise settings
	 * @return void
	 * @since   v2.0.0
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 * @since   v2.0.0
	 */
	public function add_menu_item () {
		$page =  add_submenu_page(
			'wpseo_dashboard', 
			__( 'SO Hide SEO Bloat Settings', 'so-clean-up-wp-seo' ),
			__( 'Hide Bloat', 'so-clean-up-wp-seo' ),
			'manage_options',
			$this->parent->_token . '_settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 * @since   v2.0.0
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'so-clean-up-wp-seo' ) . '</a>';
  		array_unshift( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 * @since   v2.0.0
	 * @modified v2.1.0 simplyfy the options to reflect changes to v3.1 of Yoast SEO plugin (temporarily removing non-vital notifications)
	 */
	private function settings_fields () {

		$settings['standard'] = array(
			'title'					=> __( 'Without further ado: Hide the bloat', 'so-clean-up-wp-seo' ),
			//'description'			=> __( 'description' ),
			'fields'				=> array(
				array(
					'id' 			=> 'hide_ads',
					'label'			=> __( 'Sidebar Ads', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide the cartoon-style sidebar ads on almost all settings pages of the Yoast SEO plugin.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_about_nag',
					'label'			=> __( 'About nag', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide about nag that shows on every update of the plugin.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_robots_nag',
					'label'			=> __( 'Robots nag', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide robots nag that shows a warning in the advanced tab of Yoast SEO UI in edit Post/Page screen.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_imgwarning_nag',
					'label'			=> __( 'Featured image nag', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide image warning nag that shows in edit Post/Page screen when featured image is smaller than 200x200 pixels.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_addkw_button',
					'label'			=> __( 'Add keyword button', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide add keyword button that shows in edit Post/Page and only serves to show an ad for the premium version.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_trafficlight',
					'label'			=> __( 'Trafficlight', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide seoscore traffic light in publish/update box on edit Post/Page.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_wpseoanalysis',
					'label'			=> __( 'Content analysis', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Hide content analysis that adds colored balls to the edit Post/Page screens', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'hide_admin_columns',
					'label'			=> __( 'Admin columns', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'The Yoast SEO plugin adds 4 admin columns on the Posts/Pages screen and the SEO Score admin column to taxonomies (since Yoast SEO 3.1), choose here which ones to hide (possible to select multiple, ticking minimum one box is <strong>required</strong>).', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'all' => __( 'Hide all columns', 'so-clean-up-wp-seo' ), 'seoscore' => __( 'Hide SEO score column', 'so-clean-up-wp-seo' ), 'title' => __( 'Hide title column', 'so-clean-up-wp-seo' ), 'metadescr' => __( 'Hide meta description column', 'so-clean-up-wp-seo' ), 'focuskw' => __( 'Hide focus keyword column', 'so-clean-up-wp-seo' ), 'none' => __( 'Show all columns', 'so-clean-up-wp-seo' ) ),
					'default'		=> array( 'seoscore', 'title', 'metadescr'  )
				),
				array(
					'id' 			=> 'remove_adminbar',
					'label'			=> __( 'SEO menu admin bar', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'The adminbar contains an SEO menu with 2 options; select here what to remove.', 'so-clean-up-wp-seo' ),
					'type'			=> 'radio',
					'options'		=> array( 'seo' => __( 'SEO Settings', 'so-clean-up-wp-seo' ), 'keyword' => __( 'Keyword Research', 'so-clean-up-wp-seo' ), 'both' => __( 'Both', 'so-clean-up-wp-seo' ), 'none' => __( 'None', 'so-clean-up-wp-seo' ) ),
					'default'		=> 'seo'
				),
				array(
					'id' 			=> 'remove_dbwidget',
					'label'			=> __( 'Dashboard widget', 'so-clean-up-wp-seo' ),
					'description'	=> __( 'Remove the Yoast SEO widget from the WordPress Dashboard.', 'so-clean-up-wp-seo' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				)
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 * @since   v2.0.0
	 */
	public function register_settings () {
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

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 * @since   v2.0.0
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . esc_attr( __( 'SO Hide SEO Bloat Settings' , 'so-clean-up-wp-seo' ) ) . '</h2>' . "\n";
									
			$html .= '<p>' . esc_attr( __( 'With version 2.0.0 we have added this settings page, so you can adjust things here and there to your liking.', 'so-clean-up-wp-seo' ) ) . '</p>' .  "\n";
			
			$html .= '<p>' . esc_attr( __( 'The default setting, when you activate the plugin, is that almost all boxes have been ticked; why else would you install our plugin?', 'so-clean-up-wp-seo' ) ) . '</p>' .  "\n";
			
			$html .= '<p>' . esc_attr( __( 'The intro tour pop up balloon is set to "seen" by default and there is no setting to show it as you can find it in the Yoast SEO Settings.', 'so-clean-up-wp-seo' ) ) . '</p>' .  "\n";
			
			$html .= '<p>' . esc_attr( __( 'If you ever want to remove the SO Hide SEO Bloat plugin, then you can rest assured that it cleans up after itself:', 'so-clean-up-wp-seo' ) ) . '<br />' . esc_attr( __( 'upon deletion it removes all options automatically.', 'so-clean-up-wp-seo' ) ) . '</p>' .  "\n";

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";

					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'so-clean-up-wp-seo' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";


			// see //codex.wordpress.org/I18n_for_WordPress_Developers#HTML for instructions on i18n of $html
			$rateurl = 'https://wordpress.org/support/view/plugin-reviews/so-clean-up-wp-seo?rate=5#postform';
			$html .= '<p class="rate-this-plugin">' . sprintf( wp_kses( __( 'If you have found this plugin at all useful, please give it a favourable rating in the <a href="%s" title="Rate this plugin!">WordPress Plugin Repository</a>.', 'so-clean-up-wp-seo' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $rateurl ) ) . '</p>' . "\n";

			$translateurl = 'https://translate.wordpress.org/projects/wp-plugins/so-clean-up-wp-seo';
			$html .= '<p class="translate">' . sprintf( wp_kses( __( 'You can also help a great deal by <a href="%s" title="translate the plugin into your own language">translating the plugin</a> into your own language.', 'so-clean-up-wp-seo' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $translateurl ) ) . '</p>' . "\n";

			$supporturl = 'https://github.com/senlin/so-clean-up-wp-seo/issues';
			$html .= '<p class="support">' . sprintf( wp_kses( __( 'If you have an issue with this plugin or want to leave a feature request, please note that we give <a href="%s" title="Support or Feature Requests via Github">support via Github</a> only.', 'so-clean-up-wp-seo' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $supporturl ) ) . '</p>' . "\n";

			$html .= '<div class="author postbox">' . "\n";

			$html .= '<h3 class="hndle"><span>' . esc_attr( __( 'About the Author', 'so-clean-up-wp-seo' ) ) . '</span></h3>' . "\n";

			$html .= '<div class="inside">' . "\n";
			$html .= '<div class="top">' . "\n";

			$html .= '<img class="author-image" src="' . esc_url( plugins_url( 'so-clean-up-wp-seo/images/pietbos-80x80.jpg' ) ) . '" alt="plugin author Piet Bos" width="80" height="80" />' . "\n";			

			$sowpurl = 'http://so-wp.com';
			$html .= '<p>' . sprintf( wp_kses( __( 'Hi, my name is Piet Bos, I hope you like this plugin! Please check out any of my other plugins on <a href="%s" title="SO WP Plugins">SO WP Plugins</a>. You can find out more information about me via the following links:', 'so-clean-up-wp-seo' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $sowpurl ) ) . '</p>' . "\n";

			$html .= '</div>' . "\n"; // end .top

			$html .= '<ul>' . "\n";
			$html .= '<li><a href="http://senlinonline.com/" target="_blank" title="Senlin Online">' . esc_attr( __('Senlin Online', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
			$html .= '<li><a href="http://wpti.ps/" target="_blank" title="WP TIPS">' . esc_attr( __('WP Tips', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
			$html .= '<li><a href="https://www.linkedin.com/in/pietbos" target="_blank" title="LinkedIn profile">' . esc_attr( __( 'LinkedIn', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
			$html .= '<li><a href="https://github.com/senlin" title="on Github">' . esc_attr( __( 'Github', 'so-clean-up-wp-seo' ) ) . '</a></li>' . "\n";
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
	 * @see CUWS()
	 * @return Main CUWS_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Access denied' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since v2.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Access denied' ), $this->parent->_version );
	} // End __wakeup()

}
