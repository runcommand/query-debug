<?php

class Query_Debug_Command {

	/**
	 * Find the query causing your performance issues.
	 *
	 * Executes a request to WordPress to identify which queries are run, and
	 * how long they took. Useful for taking a peek into which pages might be
	 * having performance issues.
	 *
	 * ```
	 * $ wp query-debug --url=http://wordpress-develop.dev/2016/04/14/hello-world/ --format=summary
	 * Loading http://wordpress-develop.dev/2016/04/14/hello-world/ executed 28 queries in 0.006749 seconds.
	 * ```
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render results in a specific format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - summary # Summary including number of queries and total time.
	 *   - json
	 *   - yaml
	 *   - count # Total number of queries.
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
		} else if ( 'summary' === $assoc_args['format'] ) {
			$query_count = count( $wpdb->queries );
			$query_total_time = 0;
			$query_total_time = 0;
			foreach( $wpdb->queries as $query ) {
				$query_total_time += $query[1];
			}
			$query_total_time = round( $query_total_time, 6 );
			$uri = ! empty( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
			$url = home_url( $uri );
			WP_CLI::log( "Loading {$url} executed {$query_count} queries in {$query_total_time} seconds." );
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
