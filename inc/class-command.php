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

		$wordpress = array();
		$wordpress['core_version'] = $GLOBALS['wp_version'];
		$wordpress['core_type'] = is_multisite() ? 'multisite' : 'standard';

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$wordpress['plugins'] = array();
		foreach( get_plugins() as $file => $details ) {
			$name = Utils\get_plugin_name( $file );
			$status = $this->get_plugin_status( $file );
			if ( 'table' === $assoc_args['format'] ) {
				$wordpress['plugins'][] = $name . ':' . PHP_EOL . '  status: ' . $status;
			} else {
				$wordpress['plugins'][ $name ] = array(
					'status'     => $status,
					'version'    => $details['Version'],
				);
			}
		}
		
		if ( 'table' === $assoc_args['format'] ) {
			$wordpress['plugins'] = implode( PHP_EOL, $wordpress['plugins'] );
		}

		if ( ! isset( $assoc_args['fields'] ) ) {
			$assoc_args['fields'] = array_keys( $wordpress );
		}

		$formatter = new \WP_CLI\Formatter( $assoc_args );
		$formatter->display_item( $wordpress );
	}

	protected function get_plugin_status( $file ) {
		if ( is_plugin_active_for_network( $file ) )
			return 'active-network';

		if ( is_plugin_active( $file ) )
			return 'active';

		return 'inactive';
	}

}
