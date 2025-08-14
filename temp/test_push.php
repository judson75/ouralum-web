<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';



$alum->sendAlumPushNotifications(array('user_id' => 30, 'message' => 'TEST', 'title' => "Title'"));


?>