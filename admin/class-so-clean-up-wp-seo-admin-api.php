<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CUWS_Admin_API
 */
class CUWS_Admin_API {

	/**
	 * Generate HTML for displaying fields
	 *
	 * @param  array      $data Field data
	 * @param object|bool $post WP_Post
	 * @param  boolean    $echo Whether to echo the field HTML or return it
	 *
	 * @return string
	 * @source  : //github.com/hlashbrooke/WordPress-Plugin-Template/
	 * @since   v2.0.0
	 */
	public function display_field( $data = array(), $post = false, $echo = true ) {

		// Get plugin settings
		$cuws    = CUWS::instance();
		$options = get_site_option( $cuws->_token . '_settings' );

		// Get field info
		//$field = isset( $data['field'] ) ? $data['field'] : $data;
		if ( isset( $data['field'] ) ) {
			$field = $data['field'];
		} else {
			$field = $data;
		}

		// Check for prefix on option name
		//$prefix = isset( $data['prefix'] ) ? $data['prefix'] : '';
		if ( isset( $data['prefix'] ) ) {
			$prefix = $data['prefix'];
		} else {
			$prefix = '';
		}

		// Get saved data
		$data        = '';
		$option_name = $field['id'];
		if ( $post ) {
			// Get saved field data
			$option = get_post_meta( $post->ID, $field['id'], true );

			// Get data to display in field
			if ( isset( $option ) ) {
				$data = $option;
			}
		} else {
			// Get saved option
			$option = $options[ $option_name ];

			// Get data to display in field
			if ( isset( $option ) ) {
				$data = $option;
			}
		}

		// Show default data if no option saved and default is supplied
		if ( $data === false && isset( $field['default'] ) ) {
			$data = $field['default'];
		} elseif ( $data === false ) {
			$data = '';
		}

		$html = '';

		switch ( $field['type'] ) {

			case 'checkbox':
				$checked = '';
				if ( $data && 'on' == $data ) {
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $prefix . $option_name ) . '" ' . $checked . '/>' . "\n";
				break;

			case 'checkbox_multi':
				if ( empty( $data ) ) {
					$data = array();
				}
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( in_array( $k, $data ) ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $prefix . $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
				break;

			case 'radio':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $prefix . $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
				break;

		}

		switch ( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
				break;

			default:
				if ( ! $post ) {
					$html .= '<label for="' . esc_attr( $field['id'] ) . '">' . "\n";
				}

				$html .= '<span class="description">' . $field['description'] . '</span>' . "\n";

				if ( ! $post ) {
					$html .= '</label>' . "\n";
				}
				break;
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

}
