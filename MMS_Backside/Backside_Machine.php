<?php 
	session_start();    
	header("Content-Type:text/html;charset=utf-8");
	
   $time=$_POST['time'];

//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
$sql = "Select *From mms_machine Where Time='$time'";
$sql_machine_status = "Select *From mms_machine_status Where Time='$time'";
try{
	//PDO的連接語法
	global $con_mms;
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');  
	//執行SQL指令
	$str=$con_mms->query($sql);
	$str_machine_status= $con_mms->query($sql_machine_status);
	//計算執行SQL指令的行數 
	$row_count = $str->rowCount();
	//執行SQL
	$row_machine_status = $str_machine_status->fetch(PDO::FETCH_ASSOC);
	//判斷有沒有行數
	if($row_count!=0){
		while($row = $str->fetch(PDO::FETCH_ASSOC)) {
			$response_data = Array(
				"time"				=> $row['Time'],
				"ID_Machine"		=> $row['ID_Machine'],
				"Type_Machine"  	=>$row['Type_Machine'],
				"ID_Order" 			=> $row['ID_Order'],
				"Speed" 			=> $row['Speed'],
				"Transrate"			=>$row['Transrate'],
				"Thermal" 			=> $row['Thermal'],
				"Status_Machine" 	=>$row_machine_status['Status_Machine'],
				"Status_Thermal" 	=>$row_machine_status['Status_Thermal']
			);
			//echo $row['Time'].','.$row['ID_Machine'].','.$row['Type_Machine'].','.$row['ID_Order'].','.$row['Speed'].','.$row['Thermal']; //如果搜尋到資料→顯示資料	
			/*顯示資料*/
				echo json_encode ($response_data); 
			/*End*/				
		} 
	
	}else{
		echo "0";
	}	
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>	