<?php 
	session_start();    
	header("Content-Type:text/html;charset=utf-8");

//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

$h =$_POST['th'];
$m =  $_POST['tm'];
$s =  $_POST['ts'];
$order =  $_POST['order'];
try{
	//PDO的連接語法
	global $con_mms;
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');  
	//建立SQL指令
	$sql = "UPDATE sys_time 
			SET `h`= '".$h."' , `m` = '".$m."' , `s` = '".$s."' " ;

	$sql2 ="INSERT INTO mms_order (ID_Order)
		VALUES ('".$order."')";
		
	//執行SQL指令
	$str=$con_mms->query($sql);
	$str2=$con_mms->query($sql2);
	//執行SQL
	$row = $str->fetch(PDO::FETCH_ASSOC);
	$row2 = $str2->fetch(PDO::FETCH_ASSOC);
	if($row){
		header("location:../immediate_Machine.php"); 
	}else{
		header("location:../immediate_Machine.php");
	}

}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>	