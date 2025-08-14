<?php
global $wpdb, $alum;
$profile_fields = array(
	'Salutation',
	'First Name',
	'Middle Name',
	'Last Name',
	'Full Name',
	'Suffix',
	'Pledge Class',
	'Initiation Date',
	'Email',
	'Phone Number',
	'Address',
	'City',
	'State',
	'Zip Code'
);

//print_r($_POST);

if($_POST['run_import'] == 1) {
	//print_r($_FILES);
	$target_dir = alum_plugin_path . 'uploads/';
	$file_name = str_replace(array(' '), array('_'), basename($_FILES['alum_import_file']['name']));
	$target_file = $target_dir . $file_name;
	$path_parts = pathinfo($target_file);
	$fn = $path_parts['filename'];
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);	
	// Check if file already exists
	if (file_exists($target_file)) {
		//echo "Sorry, file already exists.";
		echo '<div class="alert alert-error">Sorry, file already exists. <a href="admin.php?page=alum-menu-page&pg=import&delete=' . $target_file . '">Click here to delete file and start over</a></div>';
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["alum_import_file"]["size"] > 5000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($fileType != "xlsx" && $fileType != "xls" && $fileType != "csv") {
		echo "Sorry, only XLS & CSV files are allowed. You uploaded a " . $fileType;
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else {
		if(!move_uploaded_file($_FILES['alum_import_file']['tmp_name'], $target_file)) {
			echo '<div class="alert alert-error">Sorry, there was an error uploading your file.</div>';
		} 
		else {
			//echo "The file ". basename( $_FILES["alum_import_file"]["name"]). " has been uploaded.";
			echo '<h3>Please Complete the form below before proceeding</h3>';
			//Proceed with mapping
			//echo "FILE TYEP: $fileType<br>";
			if($fileType == 'xlsx' || $fileType == 'xls') {
				define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
				date_default_timezone_set('America/Chicago');
				//echo alum_plugin_path . 'lib/php/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php'. "<BR>";
				//echo "TF: " . $target_file . "<BR>";
				//exit;
				require_once alum_plugin_path . 'lib/php/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
				$objPHPExcel = PHPExcel_IOFactory::load($target_file);
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$p = 0;
				foreach ($objWorksheet->getRowIterator() as $i => $row) {				
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false); 
					foreach ($cellIterator as $c => $cell) {
						if($i == 1) {
							$headers[$c] = $cell->getValue();
						}
						elseif($headers[$c] != '') {
							//$datas[$headers[$c]][$p] = $cell->getValue();
							$datas[$headers[$c]][$p] = $cell->getFormattedValue();
						}	
					}
					$p++;
					if($p == 4) {
						break;
					}
				}
			}
			else {
				$p = 0;
				$i = 1;
				if (($handle = fopen($target_file, "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;
						for ($c=0; $c < $num; $c++) {
							if($i == 1) {
								$headers[$c] = $data[$c];
							}
							else {
								$datas[$headers[$c]][$p] = $data[$c];
							}
							//echo $data[$c] . "<br />\n";
						}
						$p++;
						$i++;
						if($p == 4) {
							break;
						}
					}
					fclose($handle);
				}
			}
			$colleges = $alum->getColleges();
			//echo '<pre>'; print_r($colleges); echo '</pre>';
			//Output mapping
			echo '<form method="post" id="runImportFrm">';
			echo '<input type="hidden" name="file_name" value="' . $file_name . '">';
			echo '<input type="hidden" name="import_file" value="1">';
			echo '<div class="alum-frm-grp"><label for="">College:</label>'; //<input type="text" name="college_name" class="alum-input">
			echo '<select class="input" name="college_name" id="college_name">';
			echo '<option value="">Choose College</option>';
			foreach($colleges as $college) {
				echo '<option value="' . $college . '">' . $college . '</option>';
			}
			echo '</select>';
			
			
			echo '<div style="margin: 10px 0;"><label>Group:</label>';
			echo '<select class="input" name="group_id" id="group_id">';
			echo '<option value="">Choose Group</option>';
			$groups = $alum->getGroups();
			foreach($groups as $group) {
				echo '<option value="' . $group->id . '">' . $group->group_name . '</option>';
			}
			echo '</select>';
			echo '</div>';


			echo '</div>';
			echo '<div class="alum-frm-grp"><label for="">Fraternity/Sorority Name:</label><input type="text" name="frat_name" class="alum-input" value="' . $fn  . '"></div>';
			echo '<table class="table">';
			$fc = 0;
			foreach($datas as $caption => $data) {
				echo '<tr><td><b>' . $caption. ':</b></td><td>' . implode(', ', $data) . '</td>';
				echo '<td>';
				echo '<input type="hidden" name="file_field['.$fc.']" value="' . $caption . '">';
				echo '<select name="profile_field['.$fc.']" class="pfSelect">';
				echo '<option value="">Choose Field</option>';
				foreach($profile_fields as $profile_field) {
					echo '<option value="' . $profile_field . '"';
					if($profile_field == $caption) {
						echo ' selected';
					}
					elseif($profile_field == 'Address' && strpos(strtolower($caption), 'street') !== FALSE) {
						echo ' selected';
					}
					elseif($profile_field == 'City' && strpos(strtolower($caption), 'city') !== FALSE) {
						echo ' selected';
					}
					elseif($profile_field == 'State' && strpos(strtolower($caption), 'state') !== FALSE) {
						echo ' selected';
					}
					elseif($profile_field == 'Zip Code' && strpos(strtolower($caption), 'zip') !== FALSE) {
						echo ' selected';
					}
					elseif($profile_field == 'Phone Number' && strpos(strtolower($caption), 'mobile') !== FALSE) {
						echo ' selected';
					}
					elseif($profile_field == 'Pledge Class' && strpos(strtolower($caption), 'pledge') !== FALSE) {
						echo ' selected';
					}
					echo '>' . $profile_field . '</option>';
				}
				echo '</select>';
				echo '</td>';
				echo '</tr>';
				$fc++;
			}
			echo '<tr><td colspan="3"><button class="al-btn al-btn-primary runImport" type="button">Proceed to Import</button></td></tr>';
			echo '</table>';
			echo '</form>';
		}
	}
}
elseif($_POST['run_export'] == 1) {
	//header('Content-type: text/csv');
	//header('Content-Disposition: attachment; filename="demo.csv"');
	// create a file pointer connected to the output stream
	$filename =  time() . '.csv';
	$file = fopen(alum_plugin_path . 'exports/' . $filename, 'w');
	
	print_r($_POST);
	
	$sql_fields = 'a.*';
	$sql_tables = 'alumni a';
	if(isset($_POST['group_id']) && $_POST['group_id'] != '') {
		//$sql .= "  college = '" . $_POST['college_name'] . "'";
		//$sql_fields = 'a.*';
		$sql_tables .= ', alum_groups g';
		$sql_where .= " AND g.group_id = '" . $_POST['group_id'] . "' AND a.id = g.alum_id";
	}
	
	$sql = "SELECT $sql_fields FROM $sql_tables WHERE 1 $sql_where";
	
	
	
	//echo "SQL:  $sql<BR>";
	//exit;
	$data = $wpdb->get_results($sql);
	if(!empty($data)) {	
		fputcsv($file, array('Contact ID','ACA Member Type (A)','ACA Member Role (B)','ACA Member Standing (C)','Salutation','First Name','Middle Name','Last Name','Suffix','Pledge Class','Initiation Date','Email','Preferred Phone Number','Mailing Street','Mailing City', 'Mailing State/Province','Mailing Zip/Postal Code','Last Update'));
		//$CSV = '"Contact ID","ACA Member Type (A)","ACA Member Role (B)","ACA Member Standing (C)","Salutation","First Name","Middle Name","Last Name","Suffix","Pledge Class","Initiation Date","Email","Preferred Phone Number","Mailing Street","Mailing City", "Mailing State/Province","Mailing Zip/Postal Code","Last Update"' . "\r\n";
		foreach($data as $row) {
			$params = json_decode($row->dump_fields,true);
			//$CSV = '"' . $params['Contact ID'] . '","' . $params['ACA Member Type (A)'] . '","' . $params['ACA Member Role (B)'] . '","' . $params['ACA Member Standing (C)'] . '","' . $row->salutation . '","' . $row->first_name . '","' . $row->middle_name . '","' . $row->last_name . '","' . $row->suffix . '","' . $row->pledge_class . '","' . $row->initiation_date . '","' . $row->email . '","' . $row->phone . '","' . $row->address . '","' . $row->city . '", "' . $row->state . '","' . $row->zipcode . '","' . $row->last_updated . '"' . "\r\n";
			$row1 = array($params['Contact ID'],$params['ACA Member Type (A)'],$params['ACA Member Role (B)'],$params['ACA Member Standing (C)'],$row->salutation,$row->first_name,$row->middle_name,$row->last_name,$row->suffix,$row->pledge_class,$row->initiation_date,$row->email,$row->phone,$row->address,$row->city, $row->state,$row->zipcode,$row->last_updated);
			fputcsv($file, $row1);
		}
		echo '<h1 class="wp-heading-inline">Export List</h1>';
		echo '<div>';
		echo '<p><a href="' . alum_plugin_url . 'exports/' . $filename . '">Click Here To Download</a></p>';
		echo '</div>';
	}
	else {
		echo '<h1 class="wp-heading-inline">Export List</h1>';
		echo '<div><p>Sorry, no data found';
		if(isset($_POST['college_name']) && $_POST['college_name'] != '') { 
			echo ' for <b>' . $_POST['college_name'] . '</b>';
		}
		echo '</p></div>';
	}
	
	
}
elseif($_POST['import_file'] == 1) {
	//Import them
	$target_dir = alum_plugin_path . 'uploads/';
	$file_name = $_POST['file_name'];
	$target_file = $target_dir . $file_name;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$inserted_accounts = 0;
	$existing_accounts = 0;
	$updated_accounts = 0;
	//$updated_ids
	if($_POST['group_id'] == '') {
	//Create Category...
		if($_POST['group_name'] == '') {
			$group_name =  $_POST['frat_name'] . ' - ' . $_POST['college_name'];
		}
		else {
			$group_name =  $_POST['group_name'];
		}
		$group_slug = sanitize_title($group_name);
		$group = $wpdb->get_row( "SELECT id FROM groups WHERE group_name = '$group_name'" );
		//echo 'ACCT: <pre>'; print_r($account); echo '</pre>';
	//print_r($_POST);
	//echo '<BR><BR>' . $group_slug . ' - ' . $group_name . '<br>';
		//If not insert it
		if(empty($group)) {
			$cat_id = wp_create_category($group_name, 0);
			//Create group
			$wpdb->insert( 
				'groups', 
				array( 
					'group_name' => $group_name,
					'group_slug' => $group_slug,
					'cat_id' => $cat_id,
					'admin_id' => 30,
				), 
				array( 
					'%s', 
					'%s', 
					'%d',
					'%d'
				) 
			);
			$group_id = $wpdb->insert_id;
		}
		else {
			$group_id = $group->id;
		}
	}
	else {
		$group_id = $_POST['group_id'];
	}
//echo $group_id . "<BR>";
//echo "FT: " . $fileType. "<BR>";
	
	if($fileType == 'xlsx' || $fileType == 'xls') {
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		date_default_timezone_set('Europe/London');
		require_once alum_plugin_path . 'lib/php/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load($target_file);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$p = 0;
		foreach ($objWorksheet->getRowIterator() as $i => $row) {				
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
			$datas = array();
			foreach ($cellIterator as $c => $cell) {
				if($i == 1) {
					//Map headers here...
					foreach($_POST['file_field'] as $fi => $fcap) {
					//echo "CAP: $fcap - I: $fi<br>";
						if($fcap == $cell->getValue()) {
							if($_POST['profile_field'][$fi] != '') {
								$mapped_header = $_POST['profile_field'][$fi];
							}
							else {
								$mapped_header = 'DF_' . $cell->getValue();
							}	
							$headers[$c] = $mapped_header;
							break;
						}
					//	$headers[$c] = $cell->getValue();
					}	
				}
				elseif($headers[$c] != '') {
					//$datas[$headers[$c]][$p] = $cell->getValue();
					$data[$headers[$c]] = $cell->getFormattedValue();
				}	
			}
			//loop it...
			if($i != 1) {
				$account = addAccount($data);
				if($account['existing'] == 1) {
					$existing_accounts++;
					$existing_ids[] = $account['existing_id'];
				}
				elseif($account['inserted'] == 1) {
					$inserted_accounts++;
				}

			}
			$p++;
			//tmp
			//if($p == 4) {
			//	break;
			//}
		}	
	}
	else {
	 //csv
	 	$p = 0;
		$i = 1;
		if (($handle = fopen($target_file, "r")) !== FALSE) {
			while (($d = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($d);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
					if($i == 1) {
						//Map headers here...
						foreach($_POST['file_field'] as $fi => $fcap) {
						//echo "CAP: $fcap - I: $fi<br>";
							if($fcap == $d[$c]) {
								if($_POST['profile_field'][$fi] != '') {
									$mapped_header = $_POST['profile_field'][$fi];
								}
								else {
									$mapped_header = 'DF_' . $d[$c];
								}	
								$headers[$c] = $mapped_header;
								break;
							}
						//	$headers[$c] = $cell->getValue();
						}	
					}
					elseif($headers[$c] != '') {
						//$datas[$headers[$c]][$p] = $cell->getValue();
						$data[$headers[$c]] = $d[$c];
					}
				}
				$p++;
//echo '<pre>'; print_r($data); echo '</pre>';
				//loop it...
				if($i != 1) {
					$account = addAccount($data, $group_id);
					if($account['existing'] == 1) {
						$existing_accounts++;
						$existing_ids[] = $account['existing_id'];
					}
					if($account['updated'] == 1) {
						$updated_accounts++;
						$updated_ids[] = $account['updated_id'];
					}
					elseif($account['inserted'] == 1) {
						$inserted_accounts++;
					}

				}
				$i++;
			}
			fclose($handle);
		}
		else {
			echo "CANNOT OPEN $target_file<BR>";
		}
	}
	

	
	unlink($target_file);
	echo '<div class="alert alert-success">';
	echo $inserted_accounts . ' added<br>';
	echo $updated_accounts . ' updated<br>';
	echo $existing_accounts . ' already in database';
	echo '</div>';
	if(!empty($existing_ids)) {
		
		foreach($existing_ids as $existing_id) {
			$account = $wpdb->get_row( "SELECT last_updated, first_name, last_name FROM alumni WHERE id = '" . $existing_id . "'");
			echo '<div class="">' . $account->first_name . ' ' . $account->last_name . ' (' . $existing_id . ') is already in database</div>';
		}
		
	}
	if(!empty($updated_ids)) {
		
		foreach($updated_ids as $updated_id) {
			$account = $wpdb->get_row( "SELECT last_updated, first_name, last_name FROM alumni WHERE id = '" . $updated_id . "'");
			echo '<div class="">' . $account->first_name . ' ' . $account->last_name . ' (' . $existing_id . ') updated</div>';
		}
		
	}
}
else {
	echo '<h1 class="wp-heading-inline">Import List</h1>';
	if($_GET['delete'] != '') {
		unlink($_GET['delete']);
		$path_parts = pathinfo($_GET['delete']);
		$fn = $path_parts['filename'];
		echo '<div class="alert alert-success">The file ' . $fn . ' has been deleted</div>';
	}
	echo '<div>';
	echo '<form method="post" id="importListFrm" enctype="multipart/form-data" >';
	echo '<input type="hidden" name="run_import" value="1">';
	echo '<p>Click the button below to import your school list, you will need to map the fileds and will be asked for fraternity/school information</p>';
	echo '<input type="file" name="alum_import_file" id="alum_import_file">';
	echo '<button class="al-btn al-btn-primary uploadFile" type="button">Upload File</button>';
	echo '</form>';
	echo '</div>';

	echo '<hr>';
	echo '<h1 class="wp-heading-inline">Export List</h1>';
	echo '<div>';
	echo '<form method="post" id="exportListFrm" enctype="multipart/form-data" >';
	echo '<input type="hidden" name="run_export" value="1">';
	echo '<p>Click the button below to export your school list. Select group to limit to a particular group.</p>';
	echo '<div style="margin: 10px 0;"><label>Group:</label>';
	echo '<select class="input" name="group_id" id="group_id">';
	echo '<option value="">Choose Group</option>';
	$groups = $alum->getGroups();
	foreach($groups as $group) {
		echo '<option value="' . $group->id . '">' . $group->group_name . '</option>';
	}
	echo '</select>';
	echo '</div>';
	echo '<button class="al-btn al-btn-primary exportFile" type="submit">Export File</button>';
	echo '</form>';

	echo '</div>';
}

function addAccount($data, $group_id) {
	global $wpdb;
//echo 'DATA: <pre>'; print_r($data); echo '</pre>';
	//Look for it
	$first_name = $data['First Name'];
	$middle_name = $data['Middle Name'];
	$last_name = $data['Last Name'];
	$initiation_date = ($data['Initiation Date'] != '') ? date("Y-m-d", strtotime($data['Initiation Date'])) : '';
	foreach($data as $d => $v) {
		if(substr($d, 0, 3) == 'DF_') {
			$f = str_replace('DF_', '', $d);
			$dfields[$f] = $v;
		}
	}
	if(!empty($dfields)) {
		$dump_fields = json_encode($dfields);
	}
	$pledge_class = ($data['Pledge Class'] != '') ? date("Y", strtotime($data['Pledge Class'])) : NULL;
	//echo $data['Pledge Class'] . '<br>' . $pledge_class . '<br>';
	//exit;
	$account = $wpdb->get_row( "SELECT id, last_updated, user_id FROM alumni WHERE first_name = '" . esc_sql($first_name) . "' AND last_name = '" . esc_sql($last_name) . "' AND initiation_date = '$initiation_date'" );
//echo 'ACCT: <pre>'; print_r($account); echo '</pre>';
	//If not insert it
	if(empty($account)) {
		$wpdb->insert( 
			'alumni', 
			array( 
				'first_name' => $first_name, 
				'middle_name' => $middle_name,
				'last_name' => $last_name,
				'initiation_date' => $initiation_date,
				'salutation' => $data['Salutation'],
				'suffix' => $data['Suffix'],
				'pledge_class' => $pledge_class,
				'email' => $data['Email'],
				'phone' => $data['Phone Number'],
				'address' => $data['Address'],
				'city' => $data['City'],
				'state' => $data['State'],
				'zipcode' => $data['Zip Code'],
				'college' => $_POST['college_name'],
				'fraternity' => $_POST['frat_name'],
				'dump_fields' => $dump_fields,
				'last_updated' => NULL
			), 
			array( 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			) 
		);
		$alum_id = $wpdb->insert_id;
		$wpdb->insert( 
			'alum_groups', 
			array( 
				'group_id' => $group_id, 
				'alum_id' => $alum_id,
				'status' => 1
			), 
			array( 
				'%d', 
				'%d',
				'%d'
			) 
		);
		//$wpdb->show_errors(); 
		//$wpdb->print_error(); 
		//$inserted_accounts++;
		$response['inserted'] = 1;
		$response['id'] = $alum_id;
	}
	//Else ... Update only if the account has not been updated
	elseif($account->user_id == '' || $account->user_id == NULL) {
		$alum_id = $account->id;
		$last_update = ($account->last_updated != NULL) ? $account->last_updated  : NULL;
		$wpdb->update( 
			'alumni', 
			array( 
				'initiation_date' => $initiation_date,
				'salutation' => $data['Salutation'],
				'suffix' => $data['Suffix'],
				'email' => $data['Email'],
				'phone' => $data['Phone Number'],
				'address' => $data['Address'],
				'city' => $data['City'],
				'state' => $data['State'],
				'zipcode' => $data['Zip Code'],
				'college' => $_POST['college_name'],
				'fraternity' => $_POST['frat_name'],
				'pledge_class' => $pledge_class,
				'last_updated' => $last_update,
				'dump_fields' => $dump_fields
			), 
			array( 'id' => $alum_id ), 
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			), 
			array( '%d' ) 
		);
		//$wpdb->show_errors(); 
		//$wpdb->print_error();
		$alum_group = $wpdb->get_row( "SELECT id FROM alum_groups WHERE group_id = '$group_id' AND alum_id = '$alum_id'" );
		//If not insert it
		if(empty($alum_group)) {
			$wpdb->insert( 
				'alum_groups', 
				array( 
					'group_id' => $group_id, 
					'alum_id' => $alum_id,
					'status' => 1
				), 
				array( 
					'%d', 
					'%d',
					'%d'
				) 
			);
			//$wpdb->show_errors(); 
			//$wpdb->print_error();
		}
		//$existing_accounts++;
		$response['updated'] = 1;
		$response['updated_id'] = $alum_id;
	}
	else {
		$response['existing'] = 1;
		$response['existing_id'] = $alum_id;
	}
	return $response;
}
?>