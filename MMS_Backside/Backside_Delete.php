<?php    
	header("Content-Type:text/html;charset=utf-8");
	
//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
$sql = "truncate table mms_input_seat";
$sql2 = "truncate table mms_output_seat";
$sql3= "truncate table mms_machine";
$sql4= "truncate table mms_input_work";
$sql5= "truncate table mms_output_work";
$sql6= "truncate table mms_machine_status";
$sql7= "truncate table mms_order";
try{
	//PDO的連接語法
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');
	//執行SQL指令
	$con_mms->query($sql);
	$con_mms->query($sql2);
	$con_mms->query($sql3);
	$con_mms->query($sql4);
	$con_mms->query($sql5);
	$con_mms->query($sql6);
	$con_mms->query($sql7);
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>	