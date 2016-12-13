<?php
/**
 * General
 *
 * This file contains any general functions
 *
 * @package   Core_Functionality
 * @since        1.0.0
 * @link					https://github.com/billerickson/Core-Functionality
 * @author			Matt Ryan [Cap Web Solutions] <matt@mattryan.co>
 * @copyright  Copyright (c) 2016, Cap Web Solutions
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

add_filter( 'http_request_args', 'cws_core_functionality_hidden', 5, 2 );
/**
 * Don't Update Plugin
 * @since 1.0.0
 *
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function cws_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}

// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Replace "Howdy" with "Logged in as" in WordPress bar.
 *
 * @since  2.0.0
 */
add_filter( 'admin_bar_menu', 'replace_howdy',25 );
function replace_howdy( $wp_admin_bar ) {
	$my_account=$wp_admin_bar->get_node('my-account');
	$newtitle = str_replace( 'Howdy,', 'Logged in as', $my_account->title );
	$wp_admin_bar->add_node( array(
    	'id' => 'my-account',
    	'title' => $newtitle,
    ) );
}
/**
 * Remove theme and plugin editor links.
 *
* @author Matt Ryan
 * @since  2.0.0
 */
function cws_hide_editor_and_tools() {
	remove_submenu_page('themes.php','theme-editor.php');
	remove_submenu_page('plugins.php','plugin-editor.php');
}
add_action('admin_init','cws_hide_editor_and_tools');

/**
 *
 * Prevent the Jetpack publicize connections from being auto-selected,
 * so you need to manually select them if you’d like to publicize something.
 *
 * @since  2.0.0
 * @author Matt Ryan
 * @link: http://jetpack.me/2013/10/15/ever-accidentally-publicize-a-post-that-you-didnt/
 */
add_filter( 'publicize_checkbox_default', '__return_false' );

/**
 * Re-enable links manager.
 *
 * @since  2.0.0
 * @author Matt Ryan
 * @link: http://codex.wordpress.org/Links_Manager
 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * Remove Menu Items
 * @since 1.0.0
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 *
 */
function cws_remove_menus () {
	global $menu;
	$restricted = array( );
	// Example: $restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action( 'admin_menu', 'cws_remove_menus' );

/**
 * Customize Admin Bar Items
 * @since 1.0.0
 * @link http://wp-snippets.com/addremove-wp-admin-bar-links/
 */
 function cws_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar -> remove_menu( 'new-link', 'new-content' );
}
add_action( 'wp_before_admin_bar_render', 'cws_admin_bar_items' );

add_filter( 'menu_order', 'cws_custom_menu_order' );
add_filter( 'custom_menu_order', 'cws_custom_menu_order' );
/**
 * Customize Menu Order
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 *
 */
function cws_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', //the page tab
		'edit.php', //the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
    'themes.php', // Appearance
    'plugins.php', // Plugins
    'separator1', // --Space--
    'tools.php', // Tools
    'options-general.php', // Settings
    'users.php', // Users
    'separator2', // --Space--
    'edit-comments.php', // Comments
    );
}

/**
 * Automatically link Twitter names to Twitter URL.
 *
 * @since  2.0.0
 * @author Andrea Whitmer
 * @link: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
 */
function twtreplace($content) {
	$twtreplace = preg_replace('/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/',"$1<a href=\"http://twitter.com/$2\" target=\"_blank\" rel=\"nofollow\">@$2</a>",$content);
	return $twtreplace;
}
add_filter('the_content', 'twtreplace');
add_filter('comment_text', 'twtreplace');

/**
 * Force Stupid IE to NOT use compatibility mode.
 *
 * @since  2.0.0
 * @author Andrea Whitmer
 * @link: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
 */
add_filter( 'wp_headers', 'wsm_keep_ie_modern' );
function wsm_keep_ie_modern( $headers ) {
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
                $headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
        }
        return $headers;
}

/**
 * Customize search form input box text.
 *
 * @since  2.0.0
 * @link: https://my.studiopress.com/snippets/search-form/
 */
add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
	// return esc_attr( 'Search my blog...' );
	return esc_attr( 'Seach the site...' . get_bloginfo( $show, 'display' ));
	get_permalink();
}

/**
 * Enqueue needed scripts.
 *
 * @since  2.0.0
 */
add_action( 'wp_enqueue_scripts', 'cws_enqueue_needed_scripts' );
function cws_enqueue_needed_scripts() {
	// font-awesome
	// Ref: application of these fonts: https://sridharkatakam.com/using-font-awesome-wordpress/
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
}

/**
 * Add custom logo to login page.
 *
 * Add custom logo to login page. Requires a transparent logo file in the theme's images
 * folder named 'login_logo.png'.
 *
 * @since  2.0.0
 */
//add_action('login_head', 'custom_loginlogo');
function custom_loginlogo() {
echo '<style type="text/css">
h1 a {background-image: url('.get_bloginfo('template_directory').'/images/login_logo.png) !important; }
</style>';
}

/**
 * Enable Gravity Forms Visibility Setting.
 *
 * @since  2.0.0
 * @author Matt Ryan
 * @link: https://www.gravityhelp.com/gravity-forms-v1-9-placeholders/
 */
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

/**
 * Move submit button on form & add a litttle following comment.
 *
 * @since  2.0.0
 * @author Matt Ryan
 * @link: https://www.gravityhelp.com/gravity-forms-v1-9-placeholders/
 */
add_filter( 'gform_submit_button_10', 'add_paragraph_below_submit', 10, 2 );
function add_paragraph_below_submit( $button, $form ) {
    return $button .= "<small>By joining the Digital Pro newsletter, you agree to our <a href=\"#\">Privacy Policy</a> and <a href=\"#\">Community Guidelines</a>. Got questions? <a href=\"#\">Check the FAQ</a>.</small>";
}


/*
 * List WooCommerce Products by tags
 *
 * ex: [woo_products_by_tags tags="shoes,socks"]
 */
function woo_products_by_tags_shortcode( $atts, $content = null ) {

	// Get attribuets
	extract(shortcode_atts(array(
		"tags" => ''
	), $atts));

	ob_start();

	// Define Query Arguments
	$args = array(
				'post_type' 	 => 'product',
				'posts_per_page' => 5,
				'product_tag' 	 => $tags
				);

	// Create the new query
	$loop = new WP_Query( $args );

	// Get products number
	$product_count = $loop->post_count;

	// If results
	if( $product_count > 0 ) :

		echo '<ul class="products">';

			// Start the loop
			while ( $loop->have_posts() ) : $loop->the_post(); global $product;

				global $post;

				echo "<p>" . $thePostID = $post->post_title. " </p>";

				if (has_post_thumbnail( $loop->post->ID ))
					echo  get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
				else
					echo '<img src="'.$woocommerce->plugin_url().'/assets/images/placeholder.png" alt="" width="'.$woocommerce->get_image_size('shop_catalog_image_width').'px" height="'.$woocommerce->get_image_size('shop_catalog_image_height').'px" />';

			endwhile;

		echo '</ul><!--/.products-->';

	else :

		_e('No product matching your criteria.');

	endif; // endif $product_count > 0

	return ob_get_clean();

}

add_shortcode("woo_products_by_tags", "woo_products_by_tags_shortcode");
