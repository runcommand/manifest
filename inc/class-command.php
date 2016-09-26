<?php

namespace runcommand\Manifest;

use WP_CLI;
use WP_CLI\Utils;

class Command {

	/**
	 * See what's going on inside of WordPress.
	 *
	 * Produces an overview of WordPress' configuration for a high-level
	 * understanding of what's going on inside of WordPress.
	 *
	 * ```
	 * $ wp manifest
	 * +--------------+-----------+
	 * | Field        | Value     |
	 * +--------------+-----------+
	 * | core_version | 4.6       |
	 * | core_type    | standard  |
	 * | database_size | 3 MB     |
	 * | uploads_size  | 140 B    |
	 * +--------------+-----------+
	 * ```
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 *   - csv
	 * ---
	 */
	public function __invoke( $_, $assoc_args ) {
		$wordpress = array();
		$wordpress['core_version'] = $GLOBALS['wp_version'];
		$wordpress['core_type'] = is_multisite() ? 'multisite' : 'standard';
		$wordpress['database_size'] = size_format( $GLOBALS['wpdb']->get_var( $GLOBALS['wpdb']->prepare( "SELECT SUM(data_length + index_length) FROM information_schema.TABLES where table_schema = '%s' GROUP BY table_schema;", DB_NAME ) ) );
		$upload_dir = wp_upload_dir();
		$wordpress['uploads_size'] = size_format( shell_exec( 'du -sk ' . escapeshellarg( $upload_dir['basedir'] ) ) );

		if ( ! isset( $assoc_args['fields'] ) ) {
			$assoc_args['fields'] = array_keys( $wordpress );
		}

		$formatter = new \WP_CLI\Formatter( $assoc_args );
		$formatter->display_item( $wordpress );
	}

}
