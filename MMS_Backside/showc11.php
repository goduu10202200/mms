<!DOCTYPE html>
<html>
<head>
	<title>判斷投入軸座上有無軸</title>
	<script>
		function clear(){  //清除上一筆資料
	    	document.getElementById('show').innerHTML='';
		}
	</script>
</head>
<body>
<p id="show"></p>
<?php
set_time_limit(0); //限制傳輸時間

//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
$sql = "truncate table mms_input_seat";
$sql2 = "truncate table mms_output_seat";
try{
	//PDO的連接語法
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');
	//執行SQL指令
	//$con_mms->query($sql);
	//$con_mms->query($sql2);
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}



/*檢查連線*/
/*if ($con_mms->connect_error) {
    die("Connection failed: " . $con_mms->connect_error);
}
$sql = "truncate table mms_output_seat";
if ($con_mms->query($sql) === TRUE) {

} else {
    echo "Error deleting record: " . $con_mms->error;
}*/
/*End*/

/*宣告變數*/
$time=""; //紀錄時間
$c11_i1_meter=""; //紀錄C11_i1米數
$c11_i1_position=""; //紀錄C11_i1感測有無軸

$c11o1_check=0; //判別產出軸座是不是在換軸
$c11i1_check=0;//判別是投入軸座不是在換軸
$c11i1_check2=0;
$check_speed=1;//檢查時間
$c11_o1_meter=""; //紀錄C11_o1米數
$c11_o1_position=""; //紀錄C11_o1感測有無軸
$result_c11_seat=""; //取出Get_C11I1的陣列值
$mydate=Date('Y/m/d');
$type_c11_input="投入軸座";
$type_c11_output="產出軸座";

$speed=0;
$lastmeter=0;
$speedtime=0;
$lasttime=0;
$hour=0;
$check_c11_i1_meter=0;
$check_c11_i1_time=0;
/*End*/

ob_end_flush();
/*抓投入軸座、產出軸座資料*/
function Get_C11I1($h,$m,$s){
	if($h==0 && $m==59 && $s==59){
		$time="1:00:00";
	}else{
		$time=(String)$h.":".(String)$m.":".(String)$s;
	}

	// /*抓c11-I1 Meter*/
	// $handle = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Meter&time=".$time,"rb");
	// $content = "";
	// while (!feof($handle)) {
	// 	$content .= fread($handle, 10000);
	// }
	// fclose($handle);
	// $content = json_decode($content,true);
	// $c11_i1_meter=$content['data'];
	// /*End*/

	// /*抓c11-I1 Position*/
	// $handle_c11i1_position = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Position&time=".$time,"rb");
	// $content_c11i1_position = "";
	// while (!feof($handle_c11i1_position)) {
	// 	$content_c11i1_position .= fread($handle_c11i1_position, 10000);
	// }
	// fclose($handle_c11i1_position);
	// $content_c11i1_position = json_decode($content_c11i1_position,true);
	// $c11_i1_position=$content_c11i1_position['data'];
	// /*End*/
	//  /*抓c11-O1 Meter*/
	//  $handle2 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Meter&time=".$time,"rb");
	//  $content2 = "";
	//  while (!feof($handle2)) {
	//      $content2 .= fread($handle2, 10000);
	//  }
	//  fclose($handle2);
	//  $content2 = json_decode($content2,true);
	//  $c11_o1_meter=$content2['data'];
	//  /*End*/

	//  /*抓c11-O1 Position*/
    //  $handle_c11o1_position = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Position&time=".$time,"rb");
    //  $content_c11o1_position = "";
    //  while (!feof($handle_c11o1_position)) {
    //      $content_c11o1_position .= fread($handle_c11o1_position, 10000);
    //  }
    //  fclose($handle_c11o1_position);
    //  $content_c11o1_position = json_decode($content_c11o1_position,true);
    //  $c11_o1_position=$content_c11o1_position['data'];
	 /*End*/
	//連接帳號資料表，檢查帳密是否正確
	$db_server = "localhost"; //資料庫主機位置
	$db_user = "root"; //資料庫的使用帳號
	$db_password = "1234"; //資料庫的使用密碼
	$db_name = "MMS"; //資料庫名稱
	flush();
	$sql = "Select *From mms_input_seat Where Time='$time'";
	$sql2 = "Select *From mms_output_seat Where Time='$time'";
	flush();
	try{
		//PDO的連接語法
		global $con_mms;
		$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
		//設定為utf8編碼，必要設定
		$con_mms->query('SET NAMES "utf8"');
		//執行SQL指令
		$str=$con_mms->query($sql);
		$str2=$con_mms->query($sql2);
		$row = $str->fetch(PDO::FETCH_ASSOC);
		$row2 = $str2->fetch(PDO::FETCH_ASSOC);
	}catch (PDOException $e){
		 print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	/*回傳時間、投入計米、投入感測、產出計米、產出感測*/
	return array($time,$row['Calculate_Meter'], $row['Now_Axis'],$row2['Calculate_Meter'],$row2['Now_Axis'],$row2['Speed']);
	/*End*/
}
/*End*/
// function speed($speedtime,$c11o1_meter,$lasttime,$lastmeter,$lastspeed) {
// 	//計算速度
// 	$speed=$lastspeed;
// 	if($c11o1_meter==($lastmeter+1))
// 	{
// 		$speed=3600/($speedtime-$lasttime);
// 	}
// 	if($c11o1_meter==0)
// 	{
// 		$speed=0;
// 	}
// return $speed;
// }
/*判斷機台狀態*/
 function Determine_C11i1_Seat($time,$C11_i1_meter,$C11_i1_position,$C11_o1_meter,$C11_o1_position,$c11i1_check,$c11o1_check,$speed,$check_speed){
	if($C11_i1_position=="0" && $C11_o1_position!="0"){			//如果投入軸座沒有軸、產出軸座有軸→整備中
		if($speed==0){											//如果投入軸座沒有軸、線速=0→異常停機─緊急狀況
			echo "<script>clear()</script>";
			echo "<script>show_c11_i1_error_closing()</script>";
		}else if($c11i1_check==1){			//否則如果投入軸座在換軸中且投入記米!=0 →換軸中
			echo "<script>clear()</script>";
			echo "<script>show_output2()</script>";
		}else{
			echo "<script>clear()</script>";
			echo "<script>show_c11_i1_seat_curb()</script>";
		}

	}else if($C11_i1_position!="0" && $C11_o1_position=="0"){	//否則如果投入軸座有軸、產出軸座沒有軸
		if($c11o1_check==1 && $C11_i1_meter<=5){				//如果產出軸座在換軸中且投入記米<=5 →空軸、整備中(代表這是最後一個產出軸)
			echo "<script>clear()</script>";
			echo "<script>show_c11_i1_seat_curb()</script>";
		}
		else if($c11o1_check==1 && $C11_i1_meter!=0){			//否則如果產出軸座在換軸中且投入記米!=0 →換軸中
			echo "<script>clear()</script>";
			echo "<script>show_output()</script>";
		}else{													//否則顯示空軸、整備中
			echo "<script>clear()</script>";
			echo "<script>show_c11_i1_seat_curb()</script>";
		}
	}else if($C11_i1_position!="0" && $C11_o1_position!="0"){	//否則如果投入轴座、產出軸座有軸
		if($C11_i1_meter!=0 && $C11_o1_meter!=0){				//如果投入、產出記米!=0 →運轉中
			if($C11_o1_meter<=10){								//如果產出計米<=10、線速<=1300 →穩定增速
				if($speed<=1300){
					echo "<script>clear()</script>";
 	    			echo "<script>show_c11_i1_steady_hspeed()</script>";
				}
			}
			if($C11_o1_meter>=189){
				if($speed<=1100){								//如果產出計米>=189、線速<=1100 →穩定降速
					echo "<script>clear()</script>";
 	    			echo "<script>show_c11_i1_steady_dspeed()</script>";
				}
			}
			if($speed>1300){
				if($check_speed==30){							//如果線速過高且超過30秒 →線速過高
					echo "<script>clear()</script>";
 	    			echo "<script>show_c11_i1_hspeed()</script>";
				}
			}
			else if($speed<1100 && $speed>0){
				if($check_speed==30){							//如果線速過低且超過30秒→線速過低
					echo "<script>clear()</script>";
 	    			echo "<script>show_c11_i1_lspeed()</script>";
				}
			}
			else{
				echo "<script>clear()</script>";				//否則顯示運轉中
 	    		echo "<script>show_c11_i1_time()</script>";
			}

		}else{													//否則顯示準備中
			echo "<script>clear()</script>";
			echo "<script>show_c11_i1_seat_ready()</script>";
		}
	  }
	 else{														//否則不符合上述條件顯示 空軸
		echo "<script>clear()</script>";
		echo "<script>show_time()</script>";

	 }
 }
/*End*/
/*執行抓資料*/
for($h=0;$h<=1;$h++){
    if($h==1){
		break;
    }else{
	    for ($m=0; $m<=59; $m++) {
	    	for ($s=0; $s<=59; $s++) {
				$result_c11_seat=Get_C11I1($h,$m,$s);
				/*線速*/
				// if($result_c11_seat[0]!="0:0:0"&&$result_c11_seat[0]!="1:0:0")//取得上一秒的上軸狀態
                //    {
                //        $lastsec=$s-1;
                //        $lastmin=$m;
                //        if($lastsec<0)
                //        {
                //            $lastsec=59;
                //            $lastmin=$m-1;
                //        }
                //        $lastsectime=(String)$hour.":".(String)$lastmin.":".(String)$lastsec;
				// 	   $lastposition = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Position&time=".$lastsectime,"rb");
				// 	   $content_lastpostion = "";
                //        while (!feof($lastposition)) {
                //            $content_lastpostion .= fread($lastposition, 10000);
				// 		}
				// 	
				// 	   fclose($lastposition);
                //        $content_lastpostion = json_decode($content_lastpostion,true);
				// 	   $lastc11o1_position=(string)$content_lastpostion['data'];

                //    }
                //    if($result_c11_seat[3]==0&&$result_c11_seat[4]!="0"&&$lastc11o1_position=="0")//if上軸則紀錄時間
                //    {
                //        $lasttime=($m*60)+$s;
                //        $lastmeter=0;

				//    }
				//    if($result_c11_seat[1]==0 && $result_c11_seat[2]=="0" && $result_c11_seat[4]!="0"){ //紀錄投入軸座換軸時間
				// 		$check_c11_i1_time=($m*60)+$s;
				// 		$check_c11_i1_meter=$result_c11_seat[3];
			   	// 	}
                //    if($result_c11_seat[3]==($lastmeter+1))//if現在的長度比上次紀錄的長度多1則記錄現在時間
                //    {
				// 		if($check_c11_i1_time!=0 && $check_c11_i1_meter!=0){
				// 			$lasttime=$check_c11_i1_time;
				// 			$lastmeter=$check_c11_i1_meter;
				// 			$speedtime=($m*60)+$s;
				// 			$check_c11_i1_time=0;
				// 			$check_c11_i1_meter=0;
				// 		}else {
				// 			$speedtime=($m*60)+$s;
				// 		}
				//    }
				//    //執行線速
				// 	$speed=speed($speedtime,$result_c11_seat[3],$lasttime,$lastmeter,$speed);
				   
				//    if($result_c11_seat[2]=="0"&&$result_c11_seat[1]==0)
                //    {
                //        $speed=0;
				//    }

                //    if($result_c11_seat[3]==($lastmeter+1))//呼叫完將現在的長度/時間指定給上一次長度/時間
                //    {
                //     $lasttime=$speedtime;
                //     $lastmeter=$result_c11_seat[3];

                //    }
?>


				<!--單一區塊顯示-->
				<script>
					//整備中
					function show_c11_i1_seat_curb(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：整備中 <br>";
						echo "投入軸座：$result_c11_seat[2] ";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：$result_c11_seat[4]";
						echo "產出計米：$result_c11_seat[3] 米, ";
						echo "線速：0";
					    ?>";
					}
					//準備中
					function show_c11_i1_seat_ready(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：準備中 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] ";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4]";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：0";
					    ?>";
					}
					//運轉中
					function show_c11_i1_time(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：運轉中 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//異常停機─緊急狀態
					function show_c11_i1_error_closing(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態： 異常停機─緊急狀態 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//穩定增速
					function show_c11_i1_steady_hspeed(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：運轉中、穩定增速狀態 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//穩定降速
					function show_c11_i1_steady_dspeed(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：運轉中、穩定降速狀態 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//線速過高
					function show_c11_i1_hspeed(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：運轉中、線速過高 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//線速過低
					function show_c11_i1_lspeed(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp;";
						echo "機台狀態：運轉中、線速過低 <br>";
						echo "投入軸座：在軸中─$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米 <br>";
						echo "產出軸座：在軸中─$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：$result_c11_seat[5]";

					    ?>";
					}
					//產出軸座換軸中
					function show_output(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp; <br>";
						echo "機台狀態：整備中 <br>";
						echo "投入軸座：$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米, <br>";
						echo "產出軸座狀態：換軸中 <br>";
						echo "產出軸座：$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：0";
					    ?>";
					}
					//投入軸座換軸中
					function show_output2(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp; <br>";
						echo "機台狀態：整備中 <br>";
						echo "投入軸座狀態：換軸中 <br>";
						echo "投入軸座：$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米, <br>";
						echo "產出軸座：$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：0";
					    ?>";
					}
					//空軸
					function show_time(){
						document.getElementById('show').innerHTML="<?php
						echo "Time： $result_c11_seat[0]&nbsp;&nbsp; <br>";
						echo "機台狀態：待命中 <br>";
						echo "軸座狀態：空軸 <br>";
						echo "投入軸座：$result_c11_seat[2] &nbsp";
						echo "投入計米：$result_c11_seat[1] 米, <br>";
						echo "產出軸座：$result_c11_seat[4] &nbsp";
						echo "產出計米：$result_c11_seat[3] 米";
						echo "線速：0";
					    ?>";
					}
				</script>
				<!--End-->
<?php
				//檢查時間是不是30秒，判斷線速
				
				if($check_speed==30){
					$check_speed=0;
				}
				$check_speed++;
				/*機台判斷*/
				if($result_c11_seat[2]!="0" ){
					if( $result_c11_seat[4]!="0" ){
						$c11o1_check=1;
						Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$result_c11_seat[5],$check_speed);
					}else{
						$c11i1_check=1;
						Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$result_c11_seat[5],$check_speed);
					}
				}
				else if($result_c11_seat[2]=="0" && $c11i1_check==1){
					$c11i1_check=1;
					$c11o1_check=0;
	                Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$result_c11_seat[5],$check_speed);
				}else if($result_c11_seat[4]=="0" && $c11o1_check==1){
					$c11i1_check=1;
					$c11o1_check=1;
	                Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$result_c11_seat[5],$check_speed);
	            }else{
					$c11i1_check=0;
	                $c11o1_check=0;
	                Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$result_c11_seat[5],$check_speed);
	            }
	            /*End*/

	            /*建立SQL字串─新增資料*/
	              flush();
	              	//$sql = "INSERT INTO mms_input_seat (ID_Seat,Type,Now_Axis,Calculate_Meter,ID_Order,Time,Date)
						  //VALUES ('C11-I1','".$type_c11_input."','".$result_c11_seat[2]."','".$result_c11_seat[1]."','MO101','".$result_c11_seat[0]."','".$mydate."')";
					//$sql2 = "INSERT INTO mms_output_seat (ID_Seat,Type,Now_Axis,Calculate_Meter,ID_Order,Time,Date,Speed)
                    	//VALUES ('C11-I1','".$type_c11_output."','".$result_c11_seat[4]."','".$result_c11_seat[3]."','MO101','".$result_c11_seat[0]."','".$mydate."','".$speed."')";
	              flush();
	              /*End*/
	              sleep(0.9);
	              try{
				  		//執行SQL指令
						  //$con_mms->query($sql);
						  //$con_mms->query($sql2);
				  }catch (PDOException $e){
				  	 	print "Error!: " . $e->getMessage() . "<br/>";
				  		die();
				  }
					/*
		            if ($con_mms->query($sql) === TRUE) {
		               	}else {
		                echo "Error: " . $sql . "<br>" . $con_mms->error;
		            }*/

	    	}
	    }
    }

}
?>
<?php
	$con_mms = NULL;
?>
</body>
</html>