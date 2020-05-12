<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Thrive_Theme_Cloud_Api_Templates
 */
class Thrive_Theme_Cloud_Api_Templates extends Thrive_Theme_Cloud_Api_Base {

	public $theme_element = 'templates';

	/**
	 * Download template archive and update the exiting from one
	 *
	 * @param string $tag
	 * @param string $version
	 *
	 * @return array
	 * @throws Exception
	 */
	public function download_item( $tag, $version = '' ) {
		$response = [];
		$this->ensure_folders();

		$zip_path = $this->theme_folder_path . 'templates/' . $tag . '.zip';

		/* If the file with the version from cloud was previously downloaded, than we don't need to download it again */
		if ( Thrive_Utils::bypass_transient_cache() || ! file_exists( $zip_path ) ) {
			$zip_path = $this->get_zip( $tag, $zip_path );
		}

		$import   = new Thrive_Transfer_Import( $zip_path );
		$template = $import->import( 'template', [ 'update' => 1 ] );

		if ( ! empty( $template ) ) {
			$response = $template->ID;
		}

		return $response;

	}
}
