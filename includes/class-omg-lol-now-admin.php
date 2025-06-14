<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package OMG_LOL_Now
 */

class OMG_LOL_Now_Admin {
    /**
     * Add menu items to the admin menu.
     */
    public function add_plugin_admin_menu() {
        add_options_page(
            __('OMG.lol Now Page Settings', 'omg-lol-now'),
            __('OMG.lol Now', 'omg-lol-now'),
            'manage_options',
            'omg-lol-now',
            array($this, 'display_plugin_admin_page')
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
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            )
        );
    }

    /**
     * Render the settings page.
     */
    public function display_plugin_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('omg_lol_now_settings');
                do_settings_sections('omg_lol_now_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="omg_lol_now_username"><?php esc_html_e('OMG.lol Username', 'omg-lol-now'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="omg_lol_now_username" name="omg_lol_now_username" 
                                   value="<?php echo esc_attr(get_option('omg_lol_now_username')); ?>" class="regular-text">
                            <p class="description">
                                <?php esc_html_e('Enter your OMG.lol username (without the @ symbol).', 'omg-lol-now'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <div class="usage-instructions">
                <h2><?php esc_html_e('Usage Instructions', 'omg-lol-now'); ?></h2>
                <h3><?php esc_html_e('Shortcode', 'omg-lol-now'); ?></h3>
                <p><?php esc_html_e('Use the following shortcode to display your now page:', 'omg-lol-now'); ?></p>
                <code>[omg_lol_now]</code>
                <p><?php esc_html_e('Or specify a different username:', 'omg-lol-now'); ?></p>
                <code>[omg_lol_now username="foobar"]</code>

                <h3><?php esc_html_e('Block', 'omg-lol-now'); ?></h3>
                <p><?php esc_html_e('Search for "OMG.lol Now Page" in the block inserter.', 'omg-lol-now'); ?></p>
            </div>
        </div>
        <?php
    }
} 