<?php

$db_host = 'localhost';
$db_user = 'alumclou_webmin';
$db_pass = 'SS0p9o8iSS';
$db_name = 'alumclou_website';

try {
    $db = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_user, $db_pass,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} 
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$db_host = 'localhost';
$db_user = 'alumclou_webmin';
$db_pass = 'SS0p9o8iSS';
$db_name = 'alumclou_dev';

try {
    $db2 = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_user, $db_pass,
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} 
catch (PDOException $e) {
    echo '2 Connection failed: ' . $e->getMessage();
}

/*
//Get users on db with my email
$sql = "SELECT * FROM alumni WHERE email = :email";
$query = $db->prepare($sql);
$query->execute(array(':email' => 'judsonc75@gmail.com'));
$users = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($users as $user) {
	$alumni_id =  $user['id'];
	echo 'ALUMNI ID: ' . $user['id'] . '<br>';
	if( $user['user_id'] != '') {
		echo 'USER ID: ' . $user['user_id'] . '<br>';
	}
	if($user['user_id'] != 1 && $user['id'] != 1) {
		//Get old data
		$sql2 = "SELECT * FROM alumni WHERE id = :id";
		$query2 = $db2->prepare($sql2);
		$query2->execute(array(':id' => $alumni_id));
		$ouser = $query2->fetch(PDO::FETCH_ASSOC);
		echo '<pre>'; print_r($ouser); echo '</pre>';
		//update new db
		$sql = "UPDATE alumni SET email = :email, occupation = :occupation, occupation2 = :occupation2, occupation_description = :occupation_description, last_updated = :last_updated WHERE id = :id";
		$query = $db->prepare($sql);
		$query->execute(array(':id' => $alumni_id, ':email' => $ouser['email'], ':occupation' => $ouser['occupation'], ':occupation2' => $ouser['occupation2'], ':occupation_description' => $ouser['occupation_description'], ':last_updated' => $ouser['last_updated'] ));

/*		
echo "\nPDOStatement::errorInfo():\n";
$arr = $query->errorInfo();
print_r($arr);
*\/
		//exit;
	}
}
*/

//Get users on db with No city
$sql = "SELECT * FROM alumni WHERE city = ''";
$query = $db->prepare($sql);
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($users as $user) {
	$alumni_id =  $user['id'];
	echo 'ALUMNI ID: ' . $user['id'] . '<br>';
	if( $user['user_id'] != '') {
		echo 'USER ID: ' . $user['user_id'] . '<br>';
	}
	if($user['user_id'] != 1 && $user['id'] != 1) {
		//Get old data
		$sql2 = "SELECT * FROM alumni WHERE id = :id";
		$query2 = $db2->prepare($sql2);
		$query2->execute(array(':id' => $alumni_id));
		$ouser = $query2->fetch(PDO::FETCH_ASSOC);
		echo '<pre>'; print_r($ouser); echo '</pre>';
		//update new db
		$sql = "UPDATE alumni SET city = :city, state = :state, email = :email, phone = :phone, zipcode = :zipcode, last_updated = :last_updated WHERE id = :id";
		$query = $db->prepare($sql);
		$query->execute(array(':id' => $alumni_id, ':email' => $ouser['email'], ':city' => $ouser['city'], ':state' => $ouser['state'], ':zipcode' => $ouser['zipcode'], ':phone' => $ouser['phone'], ':last_updated' => $ouser['last_updated'] ));

/*	
echo "\nPDOStatement::errorInfo():\n";
$arr = $query->errorInfo();
print_r($arr);
*/

		//exit;
	}
}


?>