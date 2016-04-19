<?php

class Query_Debug_Command {

	/**
	 * Execute a request to WordPress and identify queries performed.
	 *
	 * Useful for taking a peek into which pages might be having performance
	 * issues.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render results in a specific format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 *   - count
	 * ---
	 *
	 * @when before_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		if ( ! defined( 'SAVEQUERIES' ) ) {
			define( 'SAVEQUERIES', true );
		}

		WP_CLI::get_runner()->load_wordpress();

		// Set up the main WordPress query.
		wp();

		define( 'WP_USE_THEMES', true );

		// Load the theme template.
		ob_start();
		require_once( ABSPATH . WPINC . '/template-loader.php' );
		ob_get_clean();

		if ( 'count' === $assoc_args['format'] ) {
			WP_CLI::log( count( $wpdb->queries ) );
		} else {
			$items = array_map( function( $query ){
				return array(
					'seconds'     => round( $query[1], 6 ),
					'backtrace'   => implode( ', ', array_slice( explode( ', ', $query[2] ), 12 ) ),
					'query'       => $query[0],
				);

			}, $wpdb->queries );
			WP_CLI\Utils\format_items( $assoc_args['format'], $items, array( 'seconds', 'backtrace', 'query' ) );
		}
	}


}
