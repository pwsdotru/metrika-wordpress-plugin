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

	if ( array_key_exists( 'REQUEST_METHOD', $_SERVER ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
		if ( array_key_exists( 'metrika_nonce_field', $_POST ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['metrika_nonce_field'] ) ), 'metrika_action_save' )
		) {
			$update = 0;
			if ( array_key_exists( 'metrika', $_POST ) && 'savesettings' === sanitize_text_field( wp_unslash( $_POST['metrika'] ) ) ) {
				if ( array_key_exists( 'metrika_script', $_POST ) ) {
					update_option( METRIKA_SCRIPT, sanitize_text_field( wp_unslash( $_POST['metrika_script'] ) ) );
				}
				$update = 1;
			}
			if ( $update ) {
				printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Settings has been updated', 'metrika' ) );
			}
		} else {
			printf( '<div id="message">%s</div>', esc_html__( 'Sorry, your nonce did not verify.</div>' ) );
		}
	}
	$metrika_code = get_option( METRIKA_SCRIPT, '' );
	?>
	<div class="wrap">
	<h2><?php esc_html_e( 'Metrika Options', 'metrika' ); ?></h2>
	<br class="clear" />
	<form method="post">
		<p><label for="metrika_sript"><?php esc_html_e( 'Insert code', 'metrika' ); ?>:</label></p>
		<p><textarea name="metrika_script" id="metrika_sript" cols="90" rows="10"><?php esc_html( $metrika_code ); ?></textarea></p>
		<p><input type="submit" value="<?php esc_html_e( 'Save', 'metrika' ); ?>" class="button"></p>
		<input type="hidden" name="metrika" value="savesettings">
		<?php wp_nonce_field( 'metrika_action_save', 'metrika_nonce_field' ); ?>
	</form>
	</div>
	<?php
}

/**
 * Output code
 */
function metrika_printcode() {
	$metrika_code = get_option( METRIKA_SCRIPT, '' );
	esc_html( $metrika_code );
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
