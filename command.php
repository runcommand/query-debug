<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

require_once dirname( __FILE__ ) . '/inc/class-query-debug-command.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'query-debug', 'Query_Debug_Command' );
}
