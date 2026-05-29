<?php
/*
Plugin Name: Metrika
Text Domain: metrika
Plugin URI: http://pwsdotru.com/wordpress/metrika
Description: Allowed insert to blog code for counter from Yandex Metrika: http://metrika.yandex.ru/
Author: Aleksander Novikov
Version: 1.2
Author URI: http://pwsdotru.com
*/
define('PLUGIN_LANGUAGE_ID', 'metrika');
load_plugin_textdomain(PLUGIN_LANGUAGE_ID, false, basename( dirname( __FILE__ ) ) . "/languages");
//Install function
function metrika_install() {
	add_option("metrika_script", "");
}
register_activation_hook(__FILE__, 'metrika_install');

//Menu
function metrika_menu() {
	add_options_page(__("Metrika Options", PLUGIN_LANGUAGE_ID), __("Metrika", PLUGIN_LANGUAGE_ID), 8, __FILE__, 'metrika_options');
}
add_action('admin_menu', 'metrika_menu');

//Edit form
function metrika_options() {

	if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["metrika"]) && $_POST["metrika"]=="savesettings") {

		update_option("metrika_script", trim(StripSlashes($_POST["metrika_script"])));
		$update=1;
	} else {
		$update=0;
	}
	if($update) {
?>
        <div id="message" class="updated fade">
            <p><strong><?php _e("Settings has been updated", PLUGIN_LANGUAGE_ID)?></strong></p>
        </div>
<?php
	}
?>
	<div class="wrap">
	<h2><?php _e("Metrika Options", PLUGIN_LANGUAGE_ID); ?></h2>
	<br class="clear" />
	<form method="post">
	<p><?php _e("Insert code", PLUGIN_LANGUAGE_ID); ?>:</p>
	<textarea name="metrika_script" cols="90" rows="10"><?php echo(StripSlashes(get_option("metrika_script"))); ?></textarea>
	<p>
	<input type="submit" value="<?php _e("Save", PLUGIN_LANGUAGE_ID); ?>" class="button">
	<input type="hidden" name="metrika" value="savesettings">
	</p>
	</form>
	</div>
<?php
}

//Output code
function metrika_printcode() {
	echo("\n".get_option("metrika_script")."\n");
}
add_action('wp_footer', 'metrika_printcode');


//Notice
function metrika_admin_notice() {
?>
<div class="update-nag">
  <p><?php _e( 'Metrika: Need configure', 'metrika' ); ?></p>
</div>
<?php
}
add_action('admin_notices', 'metrika_admin_notice');
