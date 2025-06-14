<?php
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
    public function get_now_page($username) {
        // Check cache first
        $cache_key = 'omg_lol_now_' . sanitize_key($username);
        $cached_content = get_transient($cache_key);

        if (false !== $cached_content) {
            return $cached_content;
        }

        // Make API request
        $response = wp_remote_get($this->api_base_url . urlencode($username) . '/now');

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data) || !isset($data['response']['now']['content'])) {
            return new WP_Error(
                'invalid_response',
                __('Invalid response from OMG.lol API.', 'omg-lol-now')
            );
        }

        // Process the content
        $content = $this->process_content($data['response']['now']['content']);

        // Cache the content for 1 hour
        set_transient($cache_key, $content, HOUR_IN_SECONDS);

        return $content;
    }

    /**
     * Process the content from the API.
     *
     * @param string $content The raw content from the API.
     * @return string The processed content.
     */
    private function process_content($content) {
        // Remove emoji-style placeholders while preserving {last-updated}
        $content = preg_replace('/\{[a-z-]+(?<!last-updated)\}/', '', $content);

        // Convert markdown to HTML
        if (class_exists('Parsedown')) {
            $parsedown = new Parsedown();
            $content = $parsedown->text($content);
        }

        // Process any special placeholders
        $content = str_replace(
            array(
                '{profile-picture}',
                '{last-updated}',
            ),
            array(
                '',
                '<span class="omg-lol-last-updated">' . esc_html__('Last updated:', 'omg-lol-now') . ' ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . '</span>',
            ),
            $content
        );

        return $content;
    }
} 