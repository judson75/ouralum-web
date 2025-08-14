<?php
//error_reporting(E_ALL);
//error_reporting(-1);
//ini_set('error_reporting', E_ALL);
global $wpdb, $alum;
//Prepare Table of elements
$wp_list_table = new Alum_List_Table();
$wp_list_table->prepare_items();

//echo "P";
//exit;
echo '<h1 class="wp-heading-inline">Alum List</h1>';
echo '<div>';
echo '<form method="GET">';
echo ' <input type="hidden" name="page" value="alum-menu-page" />';
echo ' <input type="hidden" name="pg" value="' . $_GET['pg'] . '" />';
echo ' <input type="hidden" name="orderby" value="' . $_GET['orderby'] . '" />';
echo ' <input type="hidden" name="order" value="' . $_GET['order'] . '" />';
$wp_list_table->alum_search_box('search', 'search_id');
echo '</form>';
$wp_list_table->display();
echo '</div>';


?>