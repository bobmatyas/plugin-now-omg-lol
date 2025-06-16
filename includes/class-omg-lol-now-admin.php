<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package OMG_LOL_Now
 * @since   1.0.0
 */
class OMG_LOL_Now_Admin {
	/**
	 * Add menu items to the admin menu.
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			__( 'Now via OMG.lol Settings', 'now-omg-lol' ),
			__( 'Now via OMG.lol', 'now-omg-lol' ),
			'manage_options',
			'omg-lol-now',
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Register the settings.
	 */
	public function register_settings() {
		register_setting(
			'omg_lol_now_settings',
			'omg_lol_now_username',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);
	}

	/**
	 * Handle clearing the transient cache.
	 */
	public function handle_clear_cache() {
		if ( ! isset( $_POST['omg_lol_now_clear_cache'] ) || ! check_admin_referer( 'omg_lol_now_clear_cache' ) ) {
			return;
		}

		$username = get_option( 'omg_lol_now_username' );
		if ( ! empty( $username ) ) {
			delete_transient( 'omg_lol_now_' . sanitize_key( $username ) );
		}

		add_settings_error(
			'omg_lol_now_messages',
			'omg_lol_now_message',
			__( 'Cache cleared successfully.', 'now-omg-lol' ),
			'updated'
		);
	}

	/**
	 * Render the settings page.
	 */
	public function display_plugin_admin_page() {
		// Handle cache clearing.
		$this->handle_clear_cache();

		// Display any messages.
		settings_errors( 'omg_lol_now_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'omg_lol_now_settings' );
				do_settings_sections( 'omg_lol_now_settings' );
				?>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="omg_lol_now_username"><?php esc_html_e( 'OMG.lol Username', 'now-omg-lol' ); ?></label>
						</th>
						<td>
							<input type="text" id="omg_lol_now_username" name="omg_lol_now_username" 
								value="<?php echo esc_attr( get_option( 'omg_lol_now_username' ) ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter your OMG.lol username (without the @ symbol).', 'now-omg-lol' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<form method="post" action="">
				<?php wp_nonce_field( 'omg_lol_now_clear_cache' ); ?>
				<input type="submit" name="omg_lol_now_clear_cache" class="button button-secondary" value="<?php esc_attr_e( 'Clear Cache', 'now-omg-lol' ); ?>">
			</form>

			<div class="usage-instructions">
				<h2><?php esc_html_e( 'Usage Instructions', 'now-omg-lol' ); ?></h2>
				<h3><?php esc_html_e( 'Shortcode', 'now-omg-lol' ); ?></h3>
				<p><?php esc_html_e( 'Use the following shortcode to display your now page:', 'now-omg-lol' ); ?></p>
				<code>[omg_lol_now]</code>
				<p><?php esc_html_e( 'Or specify a different username:', 'now-omg-lol' ); ?></p>
				<code>[omg_lol_now username="foobar"]</code>

				<h3><?php esc_html_e( 'Block', 'now-omg-lol' ); ?></h3>
				<p><?php esc_html_e( 'Search for "OMG.lol Now Page" in the block inserter.', 'now-omg-lol' ); ?></p>
			</div>
		</div>
		<?php
	}
}