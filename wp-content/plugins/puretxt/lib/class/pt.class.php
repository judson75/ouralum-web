<?php

/*
 * Core class for PureTxt Plugin
 *
 * @ J. Cooper 01/15/2020
 *
 */

class pureTxt {
	
	
	/*
	 * function verifyAPIKey($api_key)
	 *
	 * Send a request to the PureTXT API Server to verify API. This will return Company Name only
	 * Second verification step verifies User Hash to API Key
	 * 
	 */
	
	public function verifyAPIKey($api_key) {
		$resp = file_get_contents('https://puretxt.com/api/verify-key?key=' . $api_key );
		return $resp;
	}
	

	/*
	 * function verifyUserHash($api_key)
	 *
	 * Send a request to the PureTXT API Server to verify User Hash. This will return user ID and Hash
	 * If verified will set the option key
	 * 
	 */

	public function verifyUserHash($api_key, $user_hash) {
		$resp = file_get_contents('https://puretxt.com/api/verify-key?key=' . $api_key . '&hash=' . $user_hash );
		return $resp;
	}
	
	/*
	 * function getSourceOrigins($source)
	 *
	 * get the source origin, this is for creating list, the source will either be a post type, or database table
	 * J. Cooper 01/15/202
	 * 
	 */

	public function getSourceOrigins($source) {
		global $wpdb;
		switch($_POST['source']) {
			case 'post_type':
				//Get All Post types
				$args = array(
					'public'   => true
				 );
				 $output = 'names'; 
				 $operator = 'and';
				 $post_types = get_post_types( $args, $output, $operator );
			break;
			case 'database_table':
				//Get all tables
				$sql = "SHOW TABLES LIKE '%'";
				$results = $wpdb->get_results($sql);
				foreach($results as $index => $value) {
					foreach($value as $tableName) {
						$data[] = $tableName;
					}
				}
			break;
		}
		$response['type'] = 'success';
		$response['data'] = $data;
		return $response;
	}
	
	
	public function getTableColumns($table_name) {
		global $wpdb;
		$table = $table_name;
		foreach ( $wpdb->get_col( "DESC " . $table, 0 ) as $column_name ) {
			$data[] = $column_name;
		}
		$response['type'] = 'success';
		$response['data'] = $data;
		return $response;
	}


	public function generateSubscribersList($request) {
		global $wpdb;
		$list_meta = ''; //serialized/json of remaining data
		$date = date("Y-m-d H:i:s");
		$sql = "INSERT INTO `puretxt_list` 
				(`list_name`, `source`, `list_meta`, `last_run_date`, `last_updated`, `creation_date`) VALUES 
				('" . $_POST['list_name'] . "', '" . $_POST['source'] . "', '" .  $list_meta . "', '', '', '" .  $date . "')";
		$wpdb->query($sql);
		$list_id = $wpdb->insert_id;
		
		$response['id'] = $list_id;

		//Enter Subscribers
		if($_POST['source'] == 'database_table') {
			$table = $_POST['db_table'];
			$fields[] = $_POST['phone_field'] . ' AS phone';
			if($_POST['first_name_field'] != '') {
				$fields[] = $_POST['first_name_field'] . ' AS first_name';
			}
			if($_POST['last_name_field'] != '') {
				$fields[] = $_POST['last_name_field'] . ' AS last_name';
			}
			$cols = implode(', ', $fields);
			$sql = "SELECT $cols FROM $table";
			$subscribers = $wpdb->get_results($sql);
			$sa = 0;
			$sk = 0;
			foreach($subscribers as $subscriber) {
				if($subscriber->phone != '') {
					$fname = ($subscriber->first_name != '') ? $subscriber->first_name : '';
					$lname = ($subscriber->last_name != '') ? $subscriber->last_name : '';
					$sql2 = "INSERT INTO `puretxt_list_subscribers` 
					(`list_id`, `first_name`, `last_name`, `phone`, `status`, `last_sent_date`) VALUES 
					('" . $list_id . "', '" . $fname . "', '" .  $lname . "', '" .  $subscriber->phone . "', 1, '')";
					$wpdb->query($sql2);
					$response['sql2'] = $sql2;
					$subscriber_id = $wpdb->insert_id;
					$sa++;
				}
				else {
					$sk++;
				}
			}
			$response['added'] = $sa;
			$response['skipped'] = $sk;
		}
		elseif($_POST['source'] == 'post_type') {

		}
		$response['type'] = 'success';
		return $response;
	}
	
	
	public function getSubscribersLists() {
		global $wpdb;
		$sql = "SELECT * FROM puretxt_list";
		$results = $wpdb->get_results($sql);
		$data = $results;
		foreach($results as $i => $result) {
			//Get Subscriber count
			$sql2 = "SELECT COUNT(id) AS sub_count FROM puretxt_list_subscribers WHERE list_id = '" . $result->id . "'";
			$results2 = $wpdb->get_row($sql2);
			$data[$i]->subscriber_count = $results2->sub_count;
		}
		
		$response['data'] = $data;
		$response['type'] = 'success';
		return $response;
	}
	
	private function sendPOSTRequest($action) {
		
	}
	
	private function sendGETRequest($action) {
		
		
		$url = PT_API_ENDPOINT;
		
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'PureTxt Wordpress Plugin'
		]);
		$resp = curl_exec($curl);
		print_r($resp);
		curl_close($curl);
		//
	}
	
	private function sendDELETERequest($action) {
		
	}
}
?>