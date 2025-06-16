<?php
/**
 * The main plugin file.
 *
 * @package OMG_LOL_Now
 */

/**
 * The main plugin class.
 *
 * @package OMG_LOL_Now
 */
class OMG_LOL_Now {
	/**
	 * The loader that's responsible for maintaining and registering all hooks.
	 *
	 * @var OMG_LOL_Now_Loader
	 */
	protected $loader;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		// Load admin class.
		require_once OMG_LOL_NOW_PLUGIN_DIR . 'includes/class-omg-lol-now-admin.php';
		// Load API class.
		require_once OMG_LOL_NOW_PLUGIN_DIR . 'includes/class-omg-lol-now-api.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new OMG_LOL_Now_Admin();
		add_action( 'admin_menu', array( $plugin_admin, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 */
	private function define_public_hooks() {
		// Register shortcode.
		add_shortcode( 'omg_lol_now', array( $this, 'render_shortcode' ) );
		// Register block.
		add_action( 'init', array( $this, 'register_block' ) );
		// Register REST API endpoint.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register the block.
	 */
	public function register_block() {
		register_block_type(
			'omg-lol-now/now-page',
			array(
				'editor_script'    => 'omg-lol-now-editor',
				'editor_style'     => 'omg-lol-now-editor',
				'style'            => 'omg-lol-now',
				'render_callback'  => array( $this, 'render_block' ),
			)
		);
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'omg-lol-now/v1',
			'/now/(?P<username>[a-zA-Z0-9_-]+)',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'get_now_page_rest' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args' => array(
					'username' => array(
						'required' => true,
						'validate_callback' => function ( $param ) {
							return is_string( $param ) && ! empty( $param );
						},
					),
				),
			)
		);
	}

	/**
	 * REST API callback for getting now page content.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_now_page_rest( $request ) {
		$username = $request->get_param( 'username' );
		$content = $this->get_now_page_content( $username );

		if ( is_wp_error( $content ) ) {
			return $content;
		}

		return rest_ensure_response(
			array(
				'content' => $content,
			)
		);
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'username' => get_option( 'omg_lol_now_username', '' ),
			),
			$atts,
			'omg_lol_now'
		);

		return $this->get_now_page_content( $atts['username'] );
	}

	/**
	 * Render the block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render_block( $attributes ) {
		$username = isset( $attributes['username'] ) ? $attributes['username'] : get_option( 'omg_lol_now_username', '' );
		// Get the content.
			// Build the style string.
			$style = '';
			if ( isset( $attributes['backgroundColor'] ) ) {
				$style .= 'background-color: ' . esc_attr( $attributes['backgroundColor'] ) . ';';
			}
			if ( isset( $attributes['margin'] ) ) {
				$style .= 'margin: ' . esc_attr( $attributes['margin'] ) . 'px;';
			}
			if ( isset( $attributes['padding'] ) ) {
				$style .= 'padding: ' . esc_attr( $attributes['padding'] ) . 'px;';
			}
			if ( isset( $attributes['borderRadius'] ) ) {
				$style .= 'border-radius: ' . esc_attr( $attributes['borderRadius'] ) . 'px;';
			}
		$content = $this->get_now_page_content( $username, $style );
		// Build the style string.
		// Wrap the content in a div with the styles.
		return sprintf(
			'<div class="markdown-body wp-block-omg-lol-now" style="%s">%s</div>',
			'',
			$content
		);
	}

	/**
	 * Get the now page content.
	 *
	 * @param string $username The OMG.lol username.
	 * @param string $style The style string.
	 * @return string
	 */
	private function get_now_page_content( $username, $style = '' ) {
		if ( empty( $username ) ) {
			return '<p>' . esc_html__( 'Please configure the OMG.lol username in the plugin settings.', 'now-omg-lol' ) . '</p>';
		}

		$api     = new OMG_LOL_Now_API();
		$content = $api->get_now_page( $username );

		if ( is_wp_error( $content ) ) {
			return '<p>' . esc_html( $content->get_error_message() ) . '</p>';
		}

		return '<div class="omg-lol-now-content" style="' . esc_attr( $style ) . '">' . wp_kses_post( $content ) . '</div>';
	}

	/**
	 * Run the plugin.
	 */
	public function run() {
		// Plugin is running.
	}
}
