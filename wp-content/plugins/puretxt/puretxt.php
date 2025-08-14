<?php

/*
Plugin Name: PureTxt
Plugin URI: http://puretxt.com/wordpress-plugin
Description: Use the PureTxt platform to send text messages
Version: 1.0
Author: PureTxt
Author URI: http://puretxt.com
*/

//error_reporting(0);
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('PT_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('PT_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('PT_API_ENDPOINT', 'https://puretxt.com/api/v1');

/* Do this when initiating plugin */
function puretxt_activate() {
    global $wpdb;
    register_uninstall_hook( __FILE__, 'puretxt_uninstall' );
    /* Tables */
    $sql = "CREATE TABLE `puretxt_list` (
            `id` int(10) UNSIGNED NOT NULL,
            `list_name` varchar(255) NOT NULL,
            `source` varchar(55) NOT NULL,
            `list_meta` text NOT NULL,
            `last_run_date` datetime NOT NULL,
            `last_updated` datetime NOT NULL,
            `creation_date` datetime NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
    $wpdb->query($sql);

    $sql = "ALTER TABLE `puretxt_list` ADD PRIMARY KEY (`id`)";
    $wpdb->query($sql);

    $sql = "ALTER TABLE `puretxt_list` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMIT";
    $wpdb->query($sql);

    /*
    CREATE TABLE `puretxt_list_subscribers` (
        `id` int(10) UNSIGNED NOT NULL,
        `list_id` int(10) UNSIGNED NOT NULL,
        `first_name` varchar(55) DEFAULT NULL,
        `last_name` varchar(55) DEFAULT NULL,
        `phone` varchar(25) NOT NULL,
        `status` int(1) NOT NULL COMMENT '1 = active, 0 = inactive',
        `last_sent_date` datetime NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
      
      --
      -- Indexes for dumped tables
      --
      
      --
      -- Indexes for table `puretxt_list_subscribers`
      --
      ALTER TABLE `puretxt_list_subscribers`
        ADD PRIMARY KEY (`id`),
        ADD KEY `list_id` (`list_id`);
      COMMIT;

      $sql = "ALTER TABLE `puretxt_list_subscribers` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMIT";
        $wpdb->query($sql);
      */
}

register_activation_hook( __FILE__, 'puretxt_activate' );

/* Do this when deactiviating plugin */
function puretxt_deactivate() {

}
register_deactivation_hook( __FILE__, 'puretxt_deactivate' );

/* Do this when uninstalling plugin */
function puretxt_uninstall( ) {
    
}

/* initialization */
function ptInitialize() {
    if(!session_id()) {
        session_start();
    }
    /* PureTxt API KEY */
    if(isset($_GET['set_key'])) {
        update_option('_pt_api_key', $_GET['set_key']);
        define('PT_API_KEY', $_GET['set_key']);
        // redirect to admin page
        wp_redirect(get_bloginfo('url') . '/wp-admin/admin.php?page=puretxt-menu-page&pg=overview');
    }
}

add_action('init', 'ptInitialize', 1);



/* Classes */
include_once PT_PLUGIN_PATH . 'lib/class/pt.class.php';
$pt = new pureTxt();

/* Include Files */
//include_once PT_PLUGIN_PATH . '/lib/inc/shortcodes.inc.php';
//include_once PT_PLUGIN_PATH . '/lib/inc/functions.inc.php';
include_once PT_PLUGIN_PATH . 'lib/inc/ajax.inc.php';



//bcad21330404fc2882fe33020319f63b
/* Enqueue styles */
function puretxt_admin_style() {
	wp_register_style( 'puretxt_admin_css', plugins_url('lib/css/admin-style.css', __FILE__), false, '1.0.0' );
	wp_enqueue_style( 'puretxt_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'puretxt_admin_style' );

/* Enqueue Scripts */
function puretxt_admin_script() {
    wp_enqueue_script('fa_admin_script', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array('jquery'), '4.7.0', true );
    wp_enqueue_script('puretxt_admin_script', plugins_url('lib/js/admin-script.js', __FILE__), array('jquery'), '1.0.0', true );
	wp_localize_script('puretxt_admin_script', 'ptAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
add_action( 'admin_enqueue_scripts', 'puretxt_admin_script' );


/* Admin Messages */
function pt_admin_messages() {
    global $pt_api_key;
    if(PT_API_KEY == '') {
        add_settings_error( 'puretxt-notices', 'puretxt-api-key-required', __('You must register an API Key with PureTxt to use the plugin. <a href="/wp-admin/admin.php?page=puretxt-menu-page&pg=overview">Click here to enter your api key</a>', 'puretxt'), 'error' );
    }
    settings_errors( 'puretxt-notices' );
}
add_action('admin_notices', 'pt_admin_messages');


/* Admin Menu */
function pt_plugin_menu() {
	add_menu_page('PureTxt', 'PureTxt', 'manage_options', 'puretxt-menu-page', 'puretxt_admin_page', 'dashicons-format-chat', 10 );
}
add_action( 'admin_menu', 'pt_plugin_menu' );

function puretxt_admin_page() {
    global $pt_api_key;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    settings_errors( 'your_setting_key' );
	echo '<div class="wrap">';
	//echo '<h1 class="wp-heading-inline"><span class="dashicons dashicons-format-chat"></span> PureTxt</h1>';
	echo '<img src="https://puretxt.com/app/lib/images/logo_sm.png" alt="">';
	echo '<p></p>';
	$admin_tabs = array(
		'puretxt-menu-page&pg=overview' => 'Overview'
	);

    if(PT_API_KEY != '') {
        $admin_tabs['puretxt-menu-page&pg=settings'] = 'Settings';
        $admin_tabs['puretxt-menu-page&pg=text'] = 'Text Messages';
        $admin_tabs['puretxt-menu-page&pg=settings'] = 'Settings';
        $admin_tabs['puretxt-menu-page&pg=lists'] = 'Subscribers Lists';
    }
	
    if(is_null($current)){
        if(isset($_GET['pg'])){
            $current = $_GET['pg'];
        }
		else {
			$current = 'overview';
		}
    }
    
    echo '<h2 class="nav-tab-wrapper">';
    foreach($admin_tabs as $location => $tabname){
        if($current == $location){
            $class = ' nav-tab-active';
        } 
		else {
            $class = '';    
        }
        echo '<a class="nav-tab'.$class.'" href="?page='.$location.'">'.$tabname.'</a>';
    }
    echo '</h2>';

    echo '<div class="pt-admin-page">';
	switch($_GET['pg']) {
		default:
		case 'overview':
			include('pages/index.php');
		break;
		case 'settings':
			include('pages/settings.php');
		break;

		case 'text':
			include('pages/text.php');
        break;
        case 'lists':
			include('pages/lists.php');
		break;
	}
	echo '</div>';
	echo '</div>';
}
?>