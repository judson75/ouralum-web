<?php

/*
Plugin Name: The Alum
*/
error_reporting(0);
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('alum_plugin_path', plugin_dir_path( __FILE__ ));
define('alum_plugin_url', plugin_dir_url( __FILE__ ));

/* Do this when initiating plugin */
function alum_activate() {
	register_uninstall_hook( __FILE__, 'alum_uninstall' );
	//create Login Page
	//Create REgistration Page
}
register_activation_hook( __FILE__, 'alum_activate' );

/* Do this when deactiviating plugin */
function alum_deactivate() {

}
register_deactivation_hook( __FILE__, 'alum_deactivate' );

/* Do this when uninstalling plugin */
function alum_uninstall( ) {
    
}


/* Classes */
include_once alum_plugin_path . '/lib/class/alum.class.php';
$alum = new Alum();
require_once alum_plugin_path . '/lib/class/upload.class.php';

/* Include Files */
include_once alum_plugin_path . '/lib/inc/shortcodes.inc.php';
include_once alum_plugin_path . '/lib/inc/functions.inc.php';

/* Enqueue styles */
function alum_admin_style() {
	wp_register_style( 'alum_admin_css', plugins_url('css/admin-style.css', __FILE__), false, '1.0.0' );
	wp_enqueue_style( 'alum_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'alum_admin_style' );

/* Enqueue Scripts */
function alum_admin_script() {
    wp_enqueue_script('alum_admin_script', plugins_url('js/admin-script.js', __FILE__), array('jquery'), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'alum_admin_script' );

/* Frontend Scripts*/
function alum_style() {
	wp_register_style( 'popup_css', plugins_url('css/magnific-popup.css', __FILE__), false, '1.0.0' );
	wp_enqueue_style( 'popup_css' );
}

function alum_script() {
	
	wp_enqueue_script( 'alum_script', plugins_url('js/user-script.js', __FILE__), array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'popup_script', plugins_url('js/jquery.magnific-popup.min.js', __FILE__), array('jquery'), '1.0.0', true );
	
}

add_action( 'wp_enqueue_scripts', 'alum_style' );
add_action( 'wp_enqueue_scripts', 'alum_script' );

/* Admin Menu */
function my_plugin_menu() {
	//add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
	add_menu_page('Alum', 'Alum', 'manage_options', 'alum-menu-page', 'alum_admin_page', 'dashicons-groups', 10 );
}


add_action( 'admin_menu', 'my_plugin_menu' );


function alum_admin_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h1 class="wp-heading-inline"><span class="dashicons dashicons-groups"></span> The Alum Admin</h1>';
	echo '<p></p>';
	$admin_tabs = array(
		'alum-menu-page&pg=overview' => 'Overview',
		'alum-menu-page&pg=settings' => 'Settings',
		'alum-menu-page&pg=import' => 'Import/Export',
		'alum-menu-page&pg=email' => 'Email'
	);

	
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

	switch($_GET['pg']) {
		default:
		case 'overview':
			include('pages/index.php');
		break;
		case 'settings':
			
		break;
		case 'import':
			include('pages/import.php');
		break;
		case 'email':
			include('pages/email.php');
		break;
	}
	echo '</div>';
}


/*
require_once( alum_plugin_path . 'lib/php/class-wp-list-table.php' );
class Alum_List_Table extends JC_List_Table {
	
	function __construct(){
        global $status, $page;
        $construct_array = array (
			'singular'  => 'Alumni',     //singular name of the listed records
			'plural'    => 'Alum',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		);
		$this->id_column = 'ID';
		$this->title_column = 'title';
		$query = "SELECT * FROM alumni"; 
		if($_GET['s'] != '') {
			$query .= " WHERE LOWER(first_name) LIKE '%" . strtolower($_GET['s']) . "%' OR LOWER(last_name) LIKE '%" . strtolower($_GET['s']) . "%'";
		}
		$this->sql = $query;
		
		$this->columns = array(
						'cb'	=> '<input type="checkbox" />', //Render a checkbox instead of text
						'name'	=> 'Alumi Name',
						'pledge_class'	=> 'Pledge Class',
						'initiation_date'	=> 'Initiation Date',
						'claimed'	=> 'Profile Claimed',
						'last_updated'	=> 'Last Updated',
						'action'	=> 'Actions',
					);
		 $this->sortable_columns = array(
						'name'     => array('name',true),     //true means it's already sorted
						'pledge_class'    => array('pledge_class',false),
						'initiation_date'  => array('initiation_date',false),
			 			'claimed'  => array('user_id',false),
			 			'last_updated'  => array('last_updated',false)
					);

        parent::__construct($construct_array);
        
    }
	
	public function alum_search_box( $text, $input_id ) {
		
    	echo '<p class="search-box">
				  <label class="screen-reader-text" for="' .  $input_id . '">' .  $text. ':</label>
				  <input type="search" id="' . $input_id . '" name="s" value="' . $_GET['s'] . '" />';
		submit_button( $text, 'button', false, false, array('id' => 'search-submit'));
		echo '</p>';

	}

    function column_default($item, $column_name){
//		echo "COL NAME: $column_name<BR>";
		//echo "VALUE: " . $item->$column_name . "<br>";
//		echo "<pre>"; print_r($item); echo "</pre>"; 
		//exit;
		if(!empty($item->$column_name)) {
			if($column_name == 'initiation_date') {
				return date("m/d/Y", strtotime($item->$column_name));
			}
			elseif($column_name == 'last_updated') {
				//$last_updated = ($item->$column_name != NULL) ? date("m/d/Y", strtotime($item->$column_name)) . ' ' . date("g:i a", strtotime($item->$column_name)) : 'Never';
				if($item->user_id == '' || $item->$column_name == NULL) {
					$last_updated = 'Never';
				}
				else {
					$last_updated = date("m/d/Y", strtotime($item->$column_name)) . ' ' . date("g:i a", strtotime($item->$column_name));
				}
				return $last_updated;
			}
			else {
				return ucwords($item->$column_name);
			}
		}
		else {
			if($column_name == 'name') {
				return $item->first_name . ' ' . $item->last_name;
			}
			elseif($column_name == 'claimed') {
				$output = ($item->user_id != '') ? '<center><span class="dashicons dashicons-yes"></span></center>' : '' ;
				return $output;
			}
			elseif($column_name == 'action') {
				$output = '' ;
				return $output;
			}
			elseif($column_name == 'pledge_class') {
				return '';
			}
			elseif($column_name == 'last_updated') {
				$last_updated = ($item->$column_name != NULL) ? date("m/d/Y", strtotime($item->$column_name)) . ' ' . date("g:i a", strtotime($item->$column_name)) : 'Never';
				return $last_updated;
			}
			else {
				return print_r($item,true); //Show the whole array for troubleshooting purposes
			}
		}
    }

    function column_title($item){
		$id_column = $this->id_column;
		$title_column = $this->title_column;
        //Build row actions
        $actions = array(
            'edit'	 => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item->$id_column),
            'delete' => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item->$id_column),
        );
        
        //Return the title contents
        return sprintf('%1$s  %3$s',
            /*$1%s*\/ $item->$title_column,
            /*$2%s*\/ $item->$id_column,
            /*$3%s*\/ $this->row_actions($actions)
        );
    }
	
	
	
    function column_cb($item){
		$id_column = $this->id_column;
		$title_column = $this->title_column;
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*\/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*\/ $item->$id_column                //The value of the checkbox should be the record's id
        );
    }


    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {       
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }

    function prepare_items($type) {
        global $wpdb, $MLS;
        $per_page = 20;
		$sql = $this->sql;
		$columns = $this->columns;
		$sortable = $this->sortable_columns;
        // $columns = $this->get_columns();
        $hidden = array();
        //$sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
		$data = $wpdb->get_results($sql);
     	//echo '<pre>'; print_r($_POST); echo '</pre>'; 
		if(!empty($data)) {
			function usort_reorder($a,$b){
				$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'last_name'; //If no sort, default to title
				$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
				$result = strcmp($a->$orderby, $b->$orderby); //Determine sort order
				return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
			}
			usort($data, 'usort_reorder');
			$current_page = $this->get_pagenum();
			$total_items = count($data);
			$data = array_slice($data,(($current_page-1)*$per_page),$per_page);  
			$this->items = $data;        
			$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
			) );
		}
    }
}
*/
?>