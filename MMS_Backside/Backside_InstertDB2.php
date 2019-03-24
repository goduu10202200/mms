<?php
set_time_limit(0); //限制傳輸時間

//連接帳號資料表，檢查帳密是否正確
$db_server = "localhost"; //資料庫主機位置
$db_user = "root"; //資料庫的使用帳號
$db_password = "1234"; //資料庫的使用密碼
$db_name = "MMS"; //資料庫名稱

// 建立SQL字串
try{
	//PDO的連接語法
	$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
	//設定為utf8編碼，必要設定
	$con_mms->query('SET NAMES "utf8"');
}catch (PDOException $e){
 	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

/*宣告變數*/
$var_array = Array();

/*宣告線速、轉換率變數*/
$test_count = $_POST["test_count"];
$speed = $_POST["speed"];
$lastmeter = $_POST["lastmeter"];
$speedtime = $_POST["speedtime"];
$lasttime = $_POST["lasttime"];
$check_c11_i1_meter = $_POST["check_c11_i1_meter"];
$check_c11_i1_time = $_POST["check_c11_i1_time"];
$lastc11o1_position = $_POST["lastc11o1_position"];
$lastoutputmeter2 = $_POST["lastoutputmeter2"];
$lastinputmeter = $_POST["lastinputmeter"];
$transrate = $_POST["transrate"];
$lasttransrate = $_POST["lasttransrate"];
$check_speed=$_POST["check_speed"];//檢查時間
$c11o1_check=$_POST["c11o1_check"];//判別產出軸座是不是在換軸
$c11i1_check=$_POST["c11i1_check"];//判別是投入軸座不是在換軸
$check_Iwork=$_POST["check_Iwork"];//檢查投入工作
$check_Owork=$_POST["check_Owork"];//檢查產出工作

/*End*/

/*紀錄抓模擬機台資料變數*/
$time=$_POST['time'];//紀錄時間
$m=$_POST['m'];//紀錄時間
$s=$_POST['s'];//紀錄時間
$c11_i1_meter=""; //紀錄C11_i1米數
$c11_i1_position=""; //紀錄C11_i1感測有無軸
$c11_o1_meter=""; //紀錄C11_o1米數
$c11_o1_position=""; //紀錄C11_o1感測有無軸
$c11_thermal="";//紀錄機台溫度
$c11_o1_line="";//產出軸座線段
$output_work="";//產出工作
/*End*/



/*紀錄機台資料變數*/
$result_c11_seat=""; //取出Get_C11I1的陣列值
$mydate=Date('Y/m/d');
$type_c11_input="投入軸座";
$type_c11_output="產出軸座";
/*End*/

/*機台判斷*/
$c11i1_seat="";
$c11i1_seat_thermal="";
/*End*/
/*
//紀錄線速變數
$speed=0;
$lastmeter=0;
$speedtime=0;
$lasttime=0;
$hour=0;
$check_c11_i1_meter=0;
$check_c11_i1_time=0;
$lastc11o1_position="";
//End

//紀錄轉換率變數
$lastoutputmeter2=0;//現在input meter-1的第一筆output meter
$lastinputmeter=100;
$transrate=0;
$lasttransrate=0;
//End
*/

/*End*/

ob_end_flush();
/*抓投入軸座、產出軸座資料*/
function Get_C11I1($time){
	 /*抓c11-I1 Meter*/
	 $handle = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Meter&time=".$time,"rb");
	 $content = "";
	 while (!feof($handle)) {
	 	$content .= fread($handle, 10000);
	 }
	 fclose($handle);
	 $content = json_decode($content,true);
	 $c11_i1_meter=$content['data'];
	 /*End*/

	 /*抓c11-I1 Position*/
	 $handle_c11i1_position = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Position&time=".$time,"rb");
	 $content_c11i1_position = "";
	 while (!feof($handle_c11i1_position)) {
	 	$content_c11i1_position .= fread($handle_c11i1_position, 10000);
	 }
	 fclose($handle_c11i1_position);
	 $content_c11i1_position = json_decode($content_c11i1_position,true);
	 $c11_i1_position=$content_c11i1_position['data'];
	 /*End*/
	  /*抓c11-O1 Meter*/
	  $handle2 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Meter&time=".$time,"rb");
	  $content2 = "";
	  while (!feof($handle2)) {
	      $content2 .= fread($handle2, 10000);
	  }
	  fclose($handle2);
	  $content2 = json_decode($content2,true);
	  $c11_o1_meter=$content2['data'];
	  /*End*/

	  /*抓c11-O1 Position*/
      $handle_c11o1_position = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Position&time=".$time,"rb");
      $content_c11o1_position = "";
      while (!feof($handle_c11o1_position)) {
          $content_c11o1_position .= fread($handle_c11o1_position, 10000);
      }
      fclose($handle_c11o1_position);
      $content_c11o1_position = json_decode($content_c11o1_position,true);
      $c11_o1_position=$content_c11o1_position['data'];
	 /*End*/

	/*抓c11 Thermal*/
	$handle = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-Thermal&time=".$time,"rb");
	$content = "";
	while (!feof($handle)) {
		$content .= fread($handle, 10000);
	}
	fclose($handle);
	$content = json_decode($content,true);
	$c11_thermal=$content['data'];
	/*End*/

	/*回傳時間、投入計米、投入感測、產出計米、產出感測*/
	return array($time,$c11_i1_meter, $c11_i1_position,$c11_o1_meter,$c11_o1_position,$c11_thermal);
	/*End*/
}
/*End*/

/*判斷機台狀態*/
function Determine_C11i1_Seat($time,$C11_i1_meter,$C11_i1_position,$C11_o1_meter,$C11_o1_position,$c11i1_check,$c11o1_check,$speed,$check_speed){
	//(int)$speed=$speed2;
	if($C11_i1_position=="0" && $C11_o1_position!="0"){			//如果投入軸座沒有軸、產出軸座有軸→整備中
		if($speed==0){											//如果投入軸座沒有軸、線速=0→異常停機─緊急狀況
			//echo "<script>clear()</script>";
			//echo "<script>show_c11_i1_error_closing()</script>";
			return array($time,"投入軸座空軸","異常停機─緊急狀況");
		}else if($c11i1_check==1){			//否則如果投入軸座在換軸中且投入記米!=0 →換軸中
			//echo "<script>clear()</script>";
			//echo "<script>show_output2()</script>";
			return array($time,"投入軸座換軸中","整備中");
		}else{
			//echo "<script>clear()</script>";
			//echo "<script>show_c11_i1_seat_curb()</script>";
			return array($time,$C11_o1_position,"整備中");
		}

	}else if($C11_i1_position!="0" && $C11_o1_position=="0"){	//否則如果投入軸座有軸、產出軸座沒有軸
		if($c11o1_check==1 && $C11_i1_meter<=5){				//如果產出軸座在換軸中且投入記米<=5 →空軸、整備中(代表這是最後一個產出軸)
			//echo "<script>clear()</script>";
			//echo "<script>show_c11_i1_seat_curb()</script>";
			return array($time,"產出軸座空軸","整備中");
		}
		else if($c11o1_check==1 && $C11_i1_meter!=0){			//否則如果產出軸座在換軸中且投入記米!=0 →換軸中
			//echo "<script>clear()</script>";
			//echo "<script>show_output()</script>";
			return array($time,"產出軸座換軸中","整備中");
		}else{													//否則顯示空軸、整備中
			//echo "<script>clear()</script>";
			//echo "<script>show_c11_i1_seat_curb()</script>";
			return array($time,$C11_i1_position,"整備中");
		}
	}else if($C11_i1_position!="0" && $C11_o1_position!="0"){	//否則如果投入轴座、產出軸座有軸
		if($C11_i1_meter!=0 && $C11_o1_meter!=0){				//如果投入、產出記米!=0 →運轉中
			if($C11_o1_meter<=10){								//如果產出計米<=10、線速<=1300 →穩定增速
				if($speed<=1300){
					//echo "<script>clear()</script>";
					//echo "<script>show_c11_i1_steady_hspeed()</script>";
					 return array($time,"都上軸","穩定增速");
				}
			}
			if($C11_o1_meter>=189){
				if($speed<=1100){								//如果產出計米>=189、線速<=1100 →穩定降速
					//echo "<script>clear()</script>";
					 //echo "<script>show_c11_i1_steady_dspeed()</script>";
					 return array($time,"都上軸","穩定降速");
				}
			}
			if($speed>1300){
				if($check_speed>=30){							//如果線速過高且超過30秒 →線速過高
					// echo "<script>clear()</script>";
					 // echo "<script>show_c11_i1_hspeed()</script>";
					 return array($time,"都上軸","線速過高");
				}
			}
			if($speed<1100 ){
				if($check_speed>=30){							//如果線速過低且超過30秒→線速過低
					//echo "<script>clear()</script>";
					//echo "<script>show_c11_i1_lspeed()</script>";
					return array($time,"都上軸","線速過低");	
				}
			}
				//echo "<script>clear()</script>";				//否則顯示運轉中
				//echo "<script>show_c11_i1_time()</script>";
				return array($time,"都上軸","運轉中");
			

		}else{													//否則顯示準備中
			//echo "<script>clear()</script>";
			//echo "<script>show_c11_i1_seat_ready()</script>";
			return array($time,"都上軸","準備中");
		}
	  }
	 else{														//否則不符合上述條件顯示 空軸
		//echo "<script>clear()</script>";
		//echo "<script>show_time()</script>";
		return array($time,"空軸","待命中");

	 }
 }
/*End*/
/*機台溫度判斷*/
function Determine_C11i1_Thermal($time,$C11_i1_meter,$C11_i1_position,$C11_o1_meter,$C11_o1_position,$thermal){
	if($thermal>120){
		return array($time,$thermal,"溫度過高");
	}else{
		return array($time,$thermal,"溫度正常");
	}
}
/*End*/ 

/*計算線速*/
function count_speed($speedtime,$c11o1_meter,$lasttime,$lastmeter,$lastspeed) {
	$speed=$lastspeed;
	if($c11o1_meter==($lastmeter+1))
	{
		$speed=3600/($speedtime-$lasttime);
	}
	if($c11o1_meter==0)
	{
		$speed=0;
	}
return $speed;
}
/*End*/

/*計算轉換率*/
function TransferRate($c11i1_meter,$lastinputmeter,$c11o1_meter,$lastoutputmeter2,$lasttransrate){
    $transrate=$lasttransrate;
    if($c11i1_meter==($lastinputmeter-1))
    {
        $transrate=($c11o1_meter-$lastoutputmeter2);
        if($transrate<0)
        {
            $transrate=200+$transrate;
        }
    }
    if($c11i1_meter==0&&$c11o1_meter==200&&$lastoutputmeter2!=200)
    {
        $transrate=(200-$lastoutputmeter2);
    }

    return $transrate;
}
/*End*/

/*執行程式*/

/*抓資料*/
$result_c11_seat=Get_C11I1($time);
/*End*/				

/*線速*/
$hour =0;
if($result_c11_seat[0]!="0:0:0"&&$result_c11_seat[0]!="1:0:0")//取得上一秒的上軸狀態
{
	$lastsec=$s-1;
    $lastmin=$m;
	
	if($lastsec<0)
    {
        $lastsec=59;
		$lastmin=$m-1;
    }
	$lastsectime=(String)$hour.":".(String)$lastmin.":".(String)$lastsec;
	$lastposition = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Position&time=".$lastsectime,"rb");
	$content_lastpostion = "";
	while (!feof($lastposition)) {
		$content_lastpostion .= fread($lastposition, 10000);
	}
	fclose($lastposition);
	$content_lastpostion = json_decode($content_lastpostion,true);
	$lastc11o1_position=(string)$content_lastpostion['data'];
}

if($result_c11_seat[3]==0&&$result_c11_seat[4]!="0"&&$lastc11o1_position=="0")//if上軸則紀錄時間
{
    $lasttime=($m*60)+$s;
    $lastmeter=0;
}

if($result_c11_seat[1]==0 && $result_c11_seat[2]=="0" && $result_c11_seat[4]!="0"){ //紀錄投入軸座換軸時間
	$check_c11_i1_time=($m*60)+$s;
	$check_c11_i1_meter=$result_c11_seat[3];
}

if($result_c11_seat[3]==($lastmeter+1))//if現在的長度比上次紀錄的長度多1則記錄現在時間
{
	if($check_c11_i1_time!=0 && $check_c11_i1_meter!=0){
		$lasttime=$check_c11_i1_time;
		$lastmeter=$check_c11_i1_meter;
		$speedtime=($m*60)+$s;
		$check_c11_i1_time=0;
		$check_c11_i1_meter=0;
	}else {
		$speedtime=($m*60)+$s;
	}
}

//執行線速
$speed=count_speed($speedtime,$result_c11_seat[3],$lasttime,$lastmeter,$speed);				   

if($result_c11_seat[2]=="0" && $result_c11_seat[1]==0)
{
	$speed=0;
}

if($result_c11_seat[3]==($lastmeter+1))//呼叫完將現在的長度/時間指定給上一次長度/時間
{
    $lasttime=$speedtime;
    $lastmeter=$result_c11_seat[3];
}
/*End*/

/*轉換率*/
$transrate=TransferRate($result_c11_seat[1],$lastinputmeter,$result_c11_seat[3],$lastoutputmeter2,$transrate);				  
				   
if($result_c11_seat[1]==0&&$result_c11_seat[2]=="0")
{
	$transrate=0;
	$lastinputmeter=0;
}
if($result_c11_seat[3]==0&&$result_c11_seat[4]=="0")
{
	$transrate=0;
	//$lastoutputmeter2=0;
}
if($lastinputmeter==0&&$result_c11_seat[1]!=0&&$result_c11_seat[2]!="0")
{
	$lastinputmeter=$result_c11_seat[1];
	$lastoutputmeter2=$result_c11_seat[3];
}				   
				   
if($result_c11_seat[1]==($lastinputmeter-1))
{
	$lastinputmeter=$result_c11_seat[1];
	$lastoutputmeter2=$result_c11_seat[3];
}
if($result_c11_seat[1]==0 && $result_c11_seat[3]==200&& $lastoutputmeter2!=200)
{
	$lastinputmeter=$result_c11_seat[1];
	$lastoutputmeter2=$result_c11_seat[3];
}
/*End*/ 
//檢查時間是不是30秒，判斷線速
				
if($check_speed==59){
	$check_speed=0;
}
$check_speed++;
/*機台判斷*/
if($result_c11_seat[2]!="0" ){
	if( $result_c11_seat[4]!="0" ){
		$c11o1_check=1;
		$c11i1_seat=Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$speed,$check_speed);
	}else{
		$c11i1_check=1;
		$c11i1_seat=Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$speed,$check_speed);
	}
}
else if($result_c11_seat[2]=="0" && $c11i1_check==1){
	$c11i1_check=1;
	$c11o1_check=0;
	$c11i1_seat=Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$speed,$check_speed);
}else if($result_c11_seat[4]=="0" && $c11o1_check==1){
	$c11i1_check=1;
	$c11o1_check=1;
	$c11i1_seat=Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$speed,$check_speed);
}else{
	$c11i1_check=0;
	$c11o1_check=0;
	$c11i1_seat=Determine_C11i1_Seat($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$c11i1_check,$c11o1_check,$speed,$check_speed);
}
//機台溫度
$c11i1_seat_thermal=Determine_C11i1_Thermal($result_c11_seat[0],$result_c11_seat[1],$result_c11_seat[2],$result_c11_seat[3],$result_c11_seat[4],$result_c11_seat[5]);
/*End*/


//判斷投入工作
if($result_c11_seat[2]!="0"){						   
	$check_Iwork=1;
}else if($result_c11_seat[2]=="0" && $check_Iwork==1) {
   $check_Iwork=0;	
}
//End

/*判斷產出工作、線段*/
if($check_Owork>=3){
	$check_Owork=3;
}
if($result_c11_seat[4]!="0" && $lastc11o1_position=="0"){
	$check_Owork++;
}
if($check_Owork==1){
	$c11_o1_line="L201";
	$output_work="OJ01";
}else if($check_Owork==2){
	$c11_o1_line="L202";
	$output_work="OJ02";
}else if($check_Owork==3){
	$c11_o1_line="L203";
	$output_work="OJ03";
}
/*End*/


$test_count=$test_count++;
if( isset($_POST["status"]) ){
		$var_array = Array(
			"speed"					=>	$speed,
			"lastmeter"				=>	$lastmeter,
			"speedtime"				=>	$speedtime,
			"lasttime"				=>	$lasttime,
			"check_c11_i1_meter"	=>	$check_c11_i1_meter,
			"check_c11_i1_time"		=>	$check_c11_i1_time,
			"lastc11o1_position"	=>	$lastc11o1_position,
			"lastoutputmeter2"		=>	$lastoutputmeter2,
			"lastinputmeter"		=>	$lastinputmeter,
			"transrate"				=>	$transrate,
			"lasttransrate"			=>	$lasttransrate,
			"check_speed"			=>  $check_speed,
			"check_Owork"			=>  $check_Owork, //檢查產出工作
			"c11o1_check"			=>  $c11o1_check, //判別產出軸座是不是在換軸
			"c11i1_check"			=>  $c11i1_check, //判別是投入軸座不是在換軸
			"check_Iwork"			=>  $check_Iwork //檢查投入工作
		);
}

/*宣告顯示資料*/
$response_data = Array(
	"time"	=> $result_c11_seat[0],
	"c11_i1_meter"	=> $result_c11_seat[1],
	"c11_i1_position" =>$result_c11_seat[2],
	"c11_o1_meter" 	=> $result_c11_seat[3],
	"c11_o1_position" => $result_c11_seat[4],
	"c11_i1_thermal" => $result_c11_seat[5],
	"speed" 		=>$speed,
	"transrate" 	=>$transrate,
	"test_count"	=>	$test_count,
	"var_array"		=> $var_array
);
/*End*/

/*顯示資料*/
	echo json_encode ($response_data); 
/*End*/





/*建立SQL字串─新增資料*/

 flush();
 	
	$sql = "INSERT INTO mms_input_seat (ID_Seat,Type,Now_Axis,Calculate_Meter,ID_Order,Time,Date)
		VALUES ('C11-I1','".$type_c11_input."','".$result_c11_seat[2]."','".$result_c11_seat[1]."','MO101','".$result_c11_seat[0]."','".$mydate."')";
	$sql2 = "INSERT INTO mms_output_seat (ID_Seat,Type,Now_Axis,Calculate_Meter,ID_Order,Time,Date,Speed)
       	VALUES ('C11-I1','".$type_c11_output."','".$result_c11_seat[4]."','".$result_c11_seat[3]."','MO101','".$result_c11_seat[0]."','".$mydate."','".$speed."')";
	$sql3 =	"INSERT INTO mms_machine  (ID_Machine,Type_Machine,ID_Order,Speed,Transrate,Thermal,Time,Date)
	   VALUES ('C11','伸線機','MO101','".$speed."','".$transrate."','".$result_c11_seat[5]."','".$result_c11_seat[0]."','".$mydate."')"; 

	/*投入工作*/				
	if($result_c11_seat[2]!="0"){						   	
		$sql4 =	"INSERT INTO mms_input_work  (ID_Iwork,ID_Order,ID_Machine,ID_Iseat,ID_ILine,Type_Line,Time_Begin,Time_Over,Length_Begin,Length_Over,Date)
			VALUES ('IJ01','MO101','C11','C11_I1','L101','銅條(A)','".$result_c11_seat[0]."',' ','".$result_c11_seat[1]."',' ','".$mydate."')"; 
		
	}else if($result_c11_seat[2]=="0" && $check_Iwork==1) {
		$sql4 =	"INSERT INTO mms_input_work  (ID_Iwork,ID_Order,ID_Machine,ID_Iseat,ID_ILine,Type_Line,Time_Begin,Time_Over,Length_Begin,Length_Over,Date)
				VALUES ('IJ01','MO101','C11','C11_I1','L101','銅條(A)',' ','".$result_c11_seat[0]."',' ','".$result_c11_seat[1]." ','".$mydate."')";			
	}else{
		$sql4="";
	}
	/*End*/
					
	 /*產出工作*/
	if($result_c11_seat[4]!="0"){					   
		$sql5 =	"INSERT INTO mms_output_work  (ID_Owork,ID_Order,ID_Machine,ID_Oseat,ID_OLine,Type_Line,Time_Begin,Time_Over,Length_Begin,Length_Over,ID_Oaxis,Date)
			VALUES ('".$output_work."','MO101','C11','C11_I1','".$c11_o1_line."','銅絲(A)','".$result_c11_seat[0]."',' ','".$result_c11_seat[3]."',' ','".$result_c11_seat[4]."','".$mydate."')"; 	
	}else if($result_c11_seat[4]=="0" && $lastc11o1_position!="0") { 
		$sql5 =	"INSERT INTO mms_output_work  (ID_Owork,ID_Order,ID_Machine,ID_Oseat,ID_OLine,Type_Line,Time_Begin,Time_Over,Length_Begin,Length_Over,ID_Oaxis,Date)
			VALUES ('".$output_work."','MO101','C11','C11_I1','".$c11_o1_line."','銅絲(A)','','".$result_c11_seat[0]."',' ','".$result_c11_seat[3]."','".$result_c11_seat[4]."','".$mydate."')";	
	}else{
		$sql5="";
	}
	/*End*/
	$sql6="INSERT INTO mms_machine_status  (Time,Status_Seat,Status_Machine,Status_Thermal,Date)
		VALUES ('".$c11i1_seat[0]."','".$c11i1_seat[1]."','".$c11i1_seat[2]."','".$c11i1_seat_thermal[2]."','".$mydate."')";
flush();
/*End*/
sleep(0.5);

try{
	//執行SQL指令
	$con_mms->query($sql);
	$con_mms->query($sql2);
	$con_mms->query($sql3);
	$con_mms->query($sql4);
	$con_mms->query($sql5);
	$con_mms->query($sql6);
}catch (PDOException $e){
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
<?php
	$con_mms = NULL;
?>