<?php

namespace Elementor\App\Modules\ImportExport;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ImportElementorKIT {
	public function __construct() {
		// Hook into WordPress
		add_action( 'init', [ $this, 'import_ekit_fun' ] );
	}

	public function import_ekit( $file_path ) {
		// Ensure Elementor plugin is active
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return new \WP_Error( 'elementor_not_active', 'Elementor is not active.' );
		}

		// Verify that the user has administrator privileges
		if ( ! current_user_can( 'administrator' ) ) {
			return new \WP_Error( 'permission_denied', 'You do not have permission to perform this action.' );
		}

		// Check if the provided file exists
		if ( empty( $file_path ) || ! file_exists( $file_path ) ) {
			return new \WP_Error( 'file_not_found', 'Please specify a valid file path to import.' );
		}

		// Prepare settings for the import
		$import_settings             = [];
		$import_settings['referrer'] = Module::REFERRER_LOCAL;

		// Initialize the Import/Export module
		$import_export_module = \Elementor\Plugin::$instance->app->get_component( 'import-export' );
		if ( ! $import_export_module ) {
			return new \WP_Error( 'module_not_found', 'Import Export module is not available.' );
		}

		// Run the import process
		try {
			$import = $import_export_module->import_kit( $file_path, $import_settings );

			// Fetch the kit's manifest data
			$manifest_data = $import_export_module->import->get_manifest();

			// Apply any potential filters to the manifest data
			$manifest_data = apply_filters( 'elementor/import-export/wp-cli/manifest_data', $manifest_data );

			return 'Kit imported successfully';
		} catch ( \Exception $error ) {
			\Elementor\Plugin::$instance->logger->get_logger()->error( $error->getMessage(), [
				'meta' => [
					'trace' => $error->getTraceAsString(),
				],
			] );

			return new \WP_Error( 'import_failed', $error->getMessage() );
		}
	}

	public function import_ekit_fun() {
		// Provide the actual file path where your Elementor kit is located
		$file_path = get_template_directory() . '/includes/demo/content/elementor-kit.zip';

		// Call the import function with the file path
		$result = $this->import_ekit( $file_path );

		if ( is_wp_error( $result ) ) {
			error_log( 'Error: ' . $result->get_error_message() );
		} else {
			error_log( $result );
		}
	}
}

// Initialize the class to hook into WordPress
//new ImportElementorKIT();

