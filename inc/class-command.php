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
		$assoc_args = array_merge( array(
			'fields'    => 'core_version,core_type'
		), $assoc_args );

		$wordpress = array();
		$wordpress['core_version'] = $GLOBALS['wp_version'];
		$wordpress['core_type'] = is_multisite() ? 'multisite' : 'standard';
		$formatter = new \WP_CLI\Formatter( $assoc_args );
		$formatter->display_item( $wordpress );
	}

}
