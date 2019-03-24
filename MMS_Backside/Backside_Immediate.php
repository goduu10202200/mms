<?php 
	session_start();    
	header("Content-Type:text/html;charset=utf-8");
	
$time=$_GET['time'];
$ID_Output_Axis=$_GET['ID_Output_Axis'];
//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
$sql_input = "Select * From mms_input_seat Where Time='$time'";
$sql_input_work = "Select * From mms_input_work Where Time_Begin='$time'";
$sql_input_check="SELECT * FROM `mms_input_work` ORDER BY `mms_input_work`.`Time_Begin` ASC LIMIT 0,1";
$sql_output = "Select *From mms_output_seat Where Time='$time'";
$sql_machine_status = "Select *From mms_machine_status Where Time='$time'";


try{
	//PDO的連接語法
	global $con_mms;
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');  
	//執行SQL指令
	$str_input=$con_mms->query($sql_input);
	
	$str_output=$con_mms->query($sql_output);
	$str_machine_status=$con_mms->query($sql_machine_status);
	//計算執行SQL指令的行數 
	$row_count_input = $str_input->rowCount();
	
	//抓產出工作資料
	$row_output=$str_output->fetch(PDO::FETCH_ASSOC);
	//抓機台狀態
	$row_machine_statust=$str_machine_status->fetch(PDO::FETCH_ASSOC);
	//判斷有沒有行數
	if($row_count_input!=0){
		while($row=$str_input->fetch(PDO::FETCH_ASSOC)) {
			//判斷投入工作
			if($row['Now_Axis']!="0"){
				//執行SQL指令
				$str_input_check=$con_mms->query($sql_input_check);
				$str_input_work=$con_mms->query($sql_input_work);
				//抓SQL資料
				$row_input_check = $str_input_check->fetch(PDO::FETCH_ASSOC); //抓第一筆時間
				$row_input_work = $str_input_work->fetch(PDO::FETCH_ASSOC); //抓投入工作
				//開始時間
				$Itime=$row_input_check['Time_Begin'];
				//投入工作ID
				$Name_Iwork=$row_input_check['ID_Iwork'];
				//進行時間
				$Itime_Now=$row_input_work['Time_Begin'];
				//開始製令
				$Order_Iwork=$row_input_work['ID_Order'];
			}else{
				$Itime="";
				$Name_Iwork="IJ01";
				$Itime_Now="";
				$Order_Iwork="";
			}
			//End
			if($row_output['Now_Axis']!="0"){
				//建立SQL字串
				$sql_output_check="Select *From `mms_output_work` Where `ID_Oaxis` ='".$row_output['Now_Axis']."' ORDER BY `mms_output_work`.`Time_Begin` ASC LIMIT 0,1";
				$sql_output_work = "Select *From `mms_output_work` Where `ID_Oaxis` ='".$row_output['Now_Axis']."' And Time_Begin='".$time."'";
				//Select *From `mms_output_work` Where `ID_Oaxis` ='K1012' 
				//執行SQL指令
				$str_output_check=$con_mms->query($sql_output_check);
				$str_output_work=$con_mms->query($sql_output_work);
				//抓SQL資料
				$row_output_check = $str_output_check->fetch(PDO::FETCH_ASSOC); //抓第一筆時間
				$row_output_work = $str_output_work->fetch(PDO::FETCH_ASSOC); //抓產出工作
				//開始時間
				$Otime=$row_output_check['Time_Begin'];
				//產出工作ID
				$Name_Owork=$row_output_check['ID_Owork'];
				//進行時間
				$Otime_Now=$row_output_work['Time_Begin'];
			}else{
				$Otime="";
				$Name_Owork="";
				$Otime_Now="";
				//$Order_Iwork="";
			}
			echo $Name_Iwork.','.$row['Now_Axis'].','.$Order_Iwork.','.$row['Calculate_Meter'].','.$Itime.','.$Itime_Now.
			','.$Name_Owork.','.$row_output['Now_Axis'].','.$row_output['ID_Order'].','.$row_output['Calculate_Meter'].','.$Otime.','.$Otime_Now.','.$row_output['Speed'].','.$row_machine_statust['Status_Machine'].','.$row_machine_statust['Status_Thermal'].','; //如果搜尋到資料→顯示資料					
		} 
	}else{
		echo "0";
	}	
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>	