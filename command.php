<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

require_once dirname( __FILE__ ) . '/inc/class-command.php';
WP_CLI::add_command( 'manifest', 'runcommand\Manifest\Command' );
