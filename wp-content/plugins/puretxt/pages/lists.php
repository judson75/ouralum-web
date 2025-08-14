<?php
/*
 * Routing for the Lists page
 *
 * J. Cooper 01/16/2020
 */

switch($_GET['act']) {
	default:
		include_once (PT_PLUGIN_PATH . 'pages/lists-index.php');
	break;
	case 'create_list':
		include_once (PT_PLUGIN_PATH . 'pages/lists-create.php');
	break;
	case 'import_list':
		include_once (PT_PLUGIN_PATH . 'pages/lists-import.php');
	break;
}

?>