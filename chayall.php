<?php
/*
Plugin Name: ChayAll
Plugin URI: https://chayall.com/
Description: Boost your sales and your customer relationship thanks to messaging. Integrate WhatsApp Business, Messenger, Apple Business Chat and Google's Business Messages on your site
Author: Greenbureau
Version: 1.0
Author URI: https://corp.greenbureau.com/
Text Domain: chayall
*/

// i18n
function chayall_load_i18n() {
  $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages/';
  load_plugin_textdomain( 'chayall', false, $plugin_rel_path );
}
add_action('plugins_loaded', 'chayall_load_i18n');

// FRONTEND
// Adding configured frontend script(s)
function chayall_scripts(){
  $options = get_option('chayall_options');
  $api_key  = $options['api_key'];

  if (!!$api_key) {
    echo '<script defer async
      data-chayall-account="' . $api_key . '"
      src="https://widgets.chayall.fr/js/chayall.js">
    </script>';
  }
}
add_action( 'wp_footer', 'chayall_scripts' );

// BACKEND
// Add backend page/menu
function chayall_add_settings_page() {
  add_options_page( 'ChayAll', 'ChayAll', 'manage_options', 'chayall', 'chayall_render_settings_page' );
}
add_action( 'admin_menu', 'chayall_add_settings_page' );

// Set up backend page content
function chayall_render_settings_page() {
  ?>
  <div class="wrap">
    <h1>ChayAll</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'chayall_options' );
        do_settings_sections( 'chayall' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
  </div>
  <?php
}

// Set up backend page form content
function chayall_register_settings() {
  register_setting( 'chayall_options', 'chayall_options' );
  add_settings_section( 'api_settings', '', 'chayall_api_section_text', 'chayall' );
  add_settings_field( 'chayall_setting_api_key', __('API Key', 'chayall'), 'chayall_setting_api_key', 'chayall', 'api_settings' );

}
add_action( 'admin_init', 'chayall_register_settings' );

function chayall_api_section_text() {
  $url = esc_html__('https://app.chayall.fr', 'chayall');
  $link = sprintf( wp_kses( __( 'To get started, log on to <a href="%s" target="_blank">ChayAll</a>:', 'chayall' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
  echo '<p>' . $link . '</p>';
  echo '<ol>';
  echo '  <li>' . esc_html__('Go to the Messaging page', 'chayall') . '</li>';
  echo '  <li>' . esc_html__('Go to the "Embed on your website" and choose WordPress as the embed method', 'chayall') . '</li>';
  echo '  <li>' . esc_html__('Copy the API key and paste it below', 'chayall') . '</li>';
  echo '</ol>';
}

function chayall_setting_api_key() {
  $options = get_option( 'chayall_options' );
  echo '<input id="chayall_setting_api_key" name="chayall_options[api_key]" type="text" value="' . esc_attr( $options["api_key"] ) . '" />';
}