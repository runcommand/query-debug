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
	 * $ wp query-debug --url=http://wp.dev/2016/04/14/hello-world/ --format=summary
	 * Loading http://wp.dev/2016/04/14/hello-world/ executed 28 queries in 0.006749 seconds.
	 * ```
	 *
	 * Use the `--debug` flag to inspect the main query and rendered theme template:
	 *
	 * ```
	 * $ wp query-debug --url=http://wp.dev/2016/04/14/hello-world/ --debug
	 * Debug (query-debug): Main WP_Query: is_single, is_singular
	 * Debug (query-debug): Theme template: twentyfifteen/single.php
	 * ```
	 *
	 * ## OPTIONS
	 *
	 * [--url=<url>]
	 * : Execute a request against a specified URL. Default to the home URL.
	 *
	 * [--format=<format>]
	 * : Render results in a specific format.
	 * ---
	 * default: summary
	 * options:
	 *   - summary # Summary including number of queries and total time.
	 *   - table # List of all queries.
	 *   - json
	 *   - yaml
	 *   - count # Total number of queries.
	 * ---
	 *
	 * @when before_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb, $wp, $wp_filter;

		if ( ! defined( 'SAVEQUERIES' ) ) {
			define( 'SAVEQUERIES', true );
		}

		if ( ! isset( \WP_CLI::get_runner()->config['url'] ) ) {
			if ( ! isset( $wp_filter['muplugins_loaded'] ) ) {
				$wp_filter['muplugins_loaded'] = array();
			}
			if ( ! isset( $wp_filter['muplugins_loaded'][0] ) ) {
				$wp_filter['muplugins_loaded'][0] = array();
			}
			$wp_filter['muplugins_loaded'][0][] = array(
				'function'      => function() {
					WP_CLI::set_url( home_url( '/' ) );
				},
				'accepted_args' => 1,
			);
		}
		$this->load_wordpress_with_template();

		if ( 'count' === $assoc_args['format'] ) {
			WP_CLI::log( count( $wpdb->queries ) );
		} else if ( 'summary' === $assoc_args['format'] ) {
			$query_count = count( $wpdb->queries );
			$query_total_time = 0;
			foreach( $wpdb->queries as $query ) {
				$query_total_time += $query[1];
			}
			$query_total_time = round( $query_total_time, 6 );
			$request = $wp->request ? : '/';
			$request = home_url( $request );
			WP_CLI::log( "Loading {$request} executed {$query_count} queries in {$query_total_time} seconds. Use --format=table to see the full list." );
		} else {
			$items = array_map( function( $query ){
				$backtrace_bits = explode( ', ', $query[2] );
				$settings_key = array_search( 'Query_Debug_Command->load_wordpress_with_template', $backtrace_bits );
				return array(
					'seconds'     => round( $query[1], 6 ),
					'backtrace'   => implode( ', ', array_slice( $backtrace_bits, $settings_key + 1 ) ),
					'query'       => $query[0],
				);

			}, $wpdb->queries );
			WP_CLI\Utils\format_items( $assoc_args['format'], $items, array( 'seconds', 'backtrace', 'query' ) );
		}
	}

	/**
	 * Runs through the entirety of the WP bootstrap process
	 */
	private function load_wordpress_with_template() {
		global $wp_query;

		WP_CLI::get_runner()->load_wordpress();

		// Set up the main WordPress query.
		wp();

		$interpreted = array();
		foreach( $wp_query as $key => $value ) {
			if ( 0 === stripos( $key, 'is_' ) && $value ) {
				$interpreted[] = $key;
			}
		}
		WP_CLI::debug( 'Main WP_Query: ' . implode( ', ', $interpreted ), 'query-debug' );

		define( 'WP_USE_THEMES', true );

		add_filter( 'template_include', function( $template ) {
			$display_template = str_replace( dirname( get_template_directory() ) . '/', '', $template );
			WP_CLI::debug( "Theme template: {$display_template}", 'query-debug' );
			return $template;
		}, 999 );

		// Load the theme template.
		ob_start();
		require_once( ABSPATH . WPINC . '/template-loader.php' );
		ob_get_clean();
	}

}
