<?php
/**
 * Remove Class Filter Without Access to Class Object
 *
 * In order to use the core WordPress remove_filter() on a filter added with the callback
 * to a class, you either have to have access to that class object, or it has to be a call
 * to a static method.  This method allows you to remove filters with a callback to a class
 * you don't have access to.
 *
 * @sources:
 * - https://iarijit.com/blog/remove-any-action-filter-wordpress/,
 * - https://mekshq.com/remove-wordpress-action-filter-class/,
 * - https://gist.github.com/tripflex/c6518efc1753cf2392559866b4bd1a53#gistcomment-2823505
 *
 * @param string $tag         Filter to remove
 * @param string $class_name  Class name for the filter's callback
 * @param string $method_name Method name for the filter's callback
 * @param int    $priority    Priority of the filter (default 10)
 *
 * @return bool Whether the function is removed.
 */
if ( ! function_exists( 'remove_class_hook' ) ) {
	function remove_class_hook( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;
		$is_hook_removed = false;
		if ( ! empty( $wp_filter[ $tag ]->callbacks[ $priority ] ) ) {

			$methods = array_filter( wp_list_pluck(
				$wp_filter[ $tag ]->callbacks[ $priority ],
				'function'
			), function ( $method ) {
				/**
				 * Allow only array & string notation for hooks, since we're
				 * looking to remove an exact method of a class anyway. And the
				 * method of the class is passed in as a string anyway.
				 */
				return is_string( $method ) || is_array( $method );
			} );

			$found_hooks = ! empty( $methods ) ? wp_list_filter( $methods, array( 1 => $method_name ) ) : array();

			foreach( $found_hooks as $hook_key => $hook ) {
				if ( ! empty( $hook[0] ) && is_object( $hook[0] ) && get_class( $hook[0] ) === $class_name ) {
					$wp_filter[ $tag ]->remove_filter( $tag, $hook, $priority );
					$is_hook_removed = true;
				}
			}
		}
		return $is_hook_removed;
	}
}
