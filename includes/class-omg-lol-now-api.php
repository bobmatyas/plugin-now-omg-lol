<?php
/**
 * The API functionality of the plugin.
 *
 * @link       https://omg.lol/now
 * @package    OMG_LOL_Now
 * @since      1.0.0
 * @subpackage OMG_LOL_Now/includes
 */

declare(strict_types=1);

/**
 * The API functionality of the plugin.
 *
 * @package OMG_LOL_Now
 */
class OMG_LOL_Now_API {
	/**
	 * The base URL for the OMG.lol API.
	 *
	 * @var string
	 */
	private $api_base_url = 'https://api.omg.lol/address/';

	/**
	 * Get the now page content for a username.
	 *
	 * @param string $username The OMG.lol username.
	 * @return string|WP_Error The now page content or WP_Error on failure.
	 */
	public function get_now_page( $username ) {
		// Check cache first.
		$cache_key      = 'omg_lol_now_' . sanitize_key( $username );
		$cached_content = get_transient( $cache_key );

		if ( false !== $cached_content ) {
			return $cached_content;
		}

		// Make API request.
		$response = wp_remote_get( $this->api_base_url . rawurlencode( $username ) . '/now' );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( empty( $data ) || ! isset( $data['response']['now']['content'] ) ) {
			return new WP_Error(
				'invalid_response',
				__( 'Invalid response from OMG.lol API.', 'now-page-via-omg-lol-connector' )
			);
		}

		// Process the content.
		$content = $this->process_content( $data['response']['now']['content'] );

		// Cache the content for 1 hour.
		set_transient( $cache_key, $content, HOUR_IN_SECONDS );

		return $content;
	}

	/**
	 * Process the content from the API.
	 *
	 * @param string $content The raw content from the API.
	 * @return string The processed content.
	 */
	private function process_content( $content ) {
		// Remove the "Back to my omg.lol page!" link.
		$content = preg_replace( '/\[Back to my omg\.lol page!\]\(https:\/\/\{address\}\.omg\.lol\)/', '', $content );

		// Remove the profile picture placeholder.
		$content = preg_replace( '/\{profile-picture\}/', '', $content );

		// Convert icon aliases to Font Awesome references.
		$content = preg_replace_callback(
			'/\{([a-z-]+)\}/',
			function ( $matches ) {
				$icon_name = $matches[1];
				// Skip special placeholders like {last-updated}.
				if ( in_array( $icon_name, array( 'last-updated' ), true ) ) {
					return $matches[0];
				}
				return '<i class="fa-solid fa-' . esc_attr( $icon_name ) . '"></i>';
			},
			$content
		);

		// Convert markdown to HTML using namespaced Parsedown.
		if ( ! class_exists( 'OMG_LOL_Now_Vendor_Parsedown' ) ) {
			error_log('OMG_LOL_Now_Vendor_Parsedown class does NOT exist in frontend!');
		} else {
			error_log('OMG_LOL_Now_Vendor_Parsedown class exists in frontend.');
		}
		if ( class_exists( 'OMG_LOL_Now_Vendor_Parsedown' ) ) {
			$parsedown = new OMG_LOL_Now_Vendor_Parsedown();
			$content   = $parsedown->text( $content );
		}

		// Process any special placeholders.
		$content = str_replace(
			array(
				'{profile-picture}',
				'{last-updated}',
			),
			array(
				'',
				'<span class="omg-lol-last-updated">' . esc_html__( 'Last updated:', 'now-page-via-omg-lol-connector' ) . ' ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) . '</span>',
			),
			$content
		);

		return $content;
	}
}
