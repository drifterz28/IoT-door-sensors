<?php
date_default_timezone_set('America/Los_Angeles');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$timeStamp = date('o-m-d H:i:s');
$state = $_GET['state'];
$area = $_GET['area'];
$action = $_GET['action'];
$id = $_GET['id'];
$date = $_GET['date'];
$dateRange = $_GET['dateRange'];

// Create (connect to) SQLite database in file
$db = new PDO('sqlite:search.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');

$db->exec("CREATE TABLE IF NOT EXISTS door (id INTEGER PRIMARY KEY AUTOINCREMENT, area TEXT, state TEXT, timeStamp DATETIME DEFAULT (DATETIME(CURRENT_TIMESTAMP, 'LOCALTIME')))");

function showRows($db ) {
	$rows = array();
	$sql ='SELECT * from door ORDER BY id DESC LIMIT 20';
	foreach ($db->query($sql, PDO::FETCH_ASSOC) as $row) {
		$rows[] = $row;
	}
	return json_encode($rows);
}

if(empty($_GET)) {
	echo showRows($db);
}

if($action === 'delete' && !empty($id)) {
	$sql = "DELETE FROM door WHERE id = :id";
	$store = $db->prepare($sql);
	$store->execute(array(':id' => $id));
	echo showRows($db);
}

if(!empty($state) && !empty($area)) {
	$store_sql = "INSERT INTO door (area, state, timeStamp) VALUES (:area, :state, :timeStamp)";
	$store = $db->prepare($store_sql);
	$store->execute(array(
		':area' => $area,
		':state' => $state,
		':timeStamp' => $timeStamp
	));
	echo 'done';
}
