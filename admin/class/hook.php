<?php
register_activation_hook( __FILE__, 'wli_popular_posts_default_option_value' );
register_uninstall_hook(__FILE__,'wli_popular_posts_delete_option_value');

if ( ! class_exists( 'Walker_Category_Checklist_Widget' ) ) {
	require_once( 'walker.php' );
}
/**
 *  wli_popular_posts_default_option_value() is called when the plugin is activated.
 *
 *  @return             void
 *  @var                No arguments passed
 *  @author             Weblineindia
 *
 */
function wli_popular_posts_default_option_value() {
	$default_values=array(
			'version'=>WLIPOPULARPOSTS_VERSION,
	);
	add_option('wli_popular_posts_settings',$default_values);

}

/**
 *   wli_popular_posts_delete_option_value() is called when when the plugin is deleted.
 *
 *  @return             void
 *  @var                No arguments passed
 *  @author             Weblineindia
 *
 */
function wli_popular_posts_delete_option_value() {
	delete_option('wli_popular_posts_settings');
}
?>
