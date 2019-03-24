<?php    
	header("Content-Type:text/html;charset=utf-8");
	
   $select_op=$_GET['select_op'];

//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
$sql = "Select *From mms_input_seat Where Time='$select_op'";

try{
	//PDO的連接語法
	global $con_mms;
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');  
	//執行SQL指令
	$str=$con_mms->query($sql);
	//計算執行SQL指令的行數 
	$row_count = $str->rowCount();
	//判斷有沒有行數
	if($row_count!=0){
		while($row = $str->fetch(PDO::FETCH_ASSOC)) {
			echo "時間：".$row['Time'].' ,軸座：'.$row['Now_Axis']; //如果搜尋到資料→顯示資料					
		} 
	}else{
		echo "0";
	}	
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>	