<?php
/*
 * Routing for the Text page
 *
 * J. Cooper 01/16/2020
 */

switch($_GET['act']) {
	default:
		include_once (PT_PLUGIN_PATH . 'pages/text-index.php');
	break;
	case 'send_text':
		include_once (PT_PLUGIN_PATH . 'pages/text-send.php');
	break;
	case 'setup_campaign':
		include_once (PT_PLUGIN_PATH . 'pages/text-campaign.php');
	break;
}

?>