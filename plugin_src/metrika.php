<?php
/**
 * Plugin Name: Metrika
 * Text Domain: metrika
 * Plugin URI: http://pwsdotru.com/wordpress/metrika
 * Description: Plugin allow insert to blog code for counter from Yandex Metrika: http://metrika.yandex.ru/
 * Author: Aleksander Novikov
 * Version: 1.3
 * Author URI: http://pwsdotru.com
 *
 * @package metrika
 */

define( 'METRIKA_SCRIPT', 'metrika_script' );

/**
 * Init
 */
function members_listing_load_textdomain() {
	load_plugin_textdomain( 'metrika', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'init', 'members_listing_load_textdomain' );


/**
 * Menu
 */
function metrika_menu() {
	add_options_page( __( 'Metrika Options', 'metrika' ), __( 'Metrika', 'metrika' ), 8, __FILE__, 'metrika_options' );
}
add_action( 'admin_menu', 'metrika_menu' );

/**
 * Edit form
 */
function metrika_options() {

	if ( array_key_exists( 'REQUEST_METHOD', $_SERVER ) && 'POST' === $_SERVER['REQUEST_METHOD'] &&
		! empty( $_POST['metrika'] ) && 'savesettings' === $_POST['metrika'] ) {

		update_option( METRIKA_SCRIPT, trim( StripSlashes( $_POST['metrika_script'] ) ) );
		$update = 1;
	} else {
		$update = 0;
	}
	if ( $update ) {
		printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Settings has been updated', 'metrika' ) );
	}
	?>
	<div class="wrap">
	<h2><?php esc_html_e( 'Metrika Options', 'metrika' ); ?></h2>
	<br class="clear" />
	<form method="post">
		<p><label for="metrika_sript"><?php esc_html_e( 'Insert code', 'metrika' ); ?>:</label></p>
	<textarea name="metrika_script" id="metrika_sript" cols="90" rows="10"><?php echo( StripSlashes( get_option( METRIKA_SCRIPT, '' ) ) ); ?></textarea>
	<p>
	<input type="submit" value="<?php esc_html_e( 'Save', 'metrika' ); ?>" class="button">
	<input type="hidden" name="metrika" value="savesettings">
	</p>
	</form>
	</div>
	<?php
}

/**
 * Output code
 */
function metrika_printcode() {
	printf( "\n%s\n", get_option( 'metrika_script' ) );
}
add_action( 'wp_footer', 'metrika_printcode' );


/**
 * Notice
 */
function metrika_admin_notice() {
	if ( null === get_option( METRIKA_SCRIPT, null ) ) {
		printf( '<div class="update-nag"><p>%s</p></div>', esc_html__( 'Metrika: Need configure', 'metrika' ) );
	}
}
add_action( 'admin_notices', 'metrika_admin_notice' );
