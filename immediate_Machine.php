<!DOCTYPE html>
<html lang="en">
<?php
		//連接帳號資料表，檢查帳密是否正確
		$db_server = "localhost"; //資料庫主機位置
		$db_user = "root"; //資料庫的使用帳號
		$db_password = "1234"; //資料庫的使用密碼
		$db_name = "MMS"; //資料庫名稱

		try{
			//PDO的連接語法
			$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);
			//設定為utf8編碼，必要設定
			$con_mms->query('SET NAMES "utf8"');  
		}catch (PDOException $e){
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}

		// 建立SQL字串，並執行SQL指令，先在SQL指令中要用?留下未來要Binding的資料，在excute中用array來Bind Data，這樣可避免SQL Injection的駭客攻擊
		$sql = "SELECT * FROM `mms_machine`  ORDER BY `mms_machine`.`Time` DESC LIMIT 0,1 ";
		$sql_time = "SELECT * FROM `sys_time`  ";
		//執行SQL指令
		$str=$con_mms->query($sql);
		$str_time=$con_mms->query($sql_time);
		$row = $str->fetch(PDO::FETCH_ASSOC);
		$row_time = $str_time->fetch(PDO::FETCH_ASSOC);

		// // //Setup Web Service
		//  $test_text="阿朱要不要吃宵夜";
		//  $url="";
		//  $client = new SoapClient("http://tts.itri.org.tw/TTSService/Soap_1_3.php?wsdl");
		//  // Invoke Call to ConvertSimple
		//  $result=$client->ConvertSimple("MMS","MMS5","$test_text");
		//  // Iterate through the returned string array
		//  $resultArray=explode("&",$result);
		//  //echo( $resultArray[2]);
		//  if($resultArray[0]=="0" && $resultArray[1]="success"){
		//  	$convertID= $resultArray[2];
		//  	echo ($convertID);
		//  	$result2=$client->ConvertText("MMS","MMS5",".$test_text.","Angela",100, 17, "wav");
		//  	$resultArray2=explode("&",$result2);
		//  	$result3=$client->GetConvertStatus("MMS","MMS5","$resultArray2[2]");
		//  	$resultArray3=explode("&",$result3);
		//  	if($resultArray3[0]=="0" && $resultArray3[2]="2"){
		//  		$url=$resultArray3[4];
		//  		echo $url;
		//  	}else{
		//  		echo "url error";
		// 	 }
		// }
			
?>
<head>
	<meta charset="UTF-8">
	<title>伸線機台管理系統 | 即時機台狀態</title>
	<link href="css/jquery-ui.css" rel="stylesheet">
	<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/framework.css">
	<link rel="stylesheet" type="text/css" href="css/immediate_Machine.css">
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/jquery-3.2.1.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/framework.js"></script>
	<script src="js/immediate_Machine.js"></script>

	

	<script type="text/javascript">

		var xmlHTTP;
		var time_new = '<?php echo $row["Time"]; ?>';
		var split_time_new = time_new.split(':');
		// var h = split_time_new[0];
		// var m = split_time_new[1];
		// var s = split_time_new[2];
		 var h = 0;
		 var m = 0;
		 var s = 0;
		var check = 0;
		
		function Show_Data() {
			//讀取顯示資料的Value
			var m1 = document.getElementById("m1").value
			
			var ID_Machine = document.getElementById("ID_Machine").value;
			var Type_Machine = document.getElementById("Type_Machine").value;
			var ID_Order = document.getElementById("ID_Order").value;
			var Speed = document.getElementById("Speed").value;
			var Transrate = document.getElementById("Transrate").value;
			var Thermal = document.getElementById("Thermal").value;
			var Status_Machine = document.getElementById("Status_Machine").value;
			//左下角燈號
			var fw_LightBox_depiction = document.getElementById("fw_LightBox_depiction").value;
			
			//右側提醒
			var Notification_machine = document.getElementById("Notification_machine").value;
			var Notification_thermal = document.getElementById("Notification_thermal").value;
			
			var time = h + ":" + m + ":" + s;
			if (time != "1:0:0" && check == 0) {
				s++;
				if (s == 60) {
					m++;
					s = 0;
				}
				if (m == 60) {
					h = 1;
					m = 0;
					s = 0;
				}
			}
			// 發送 Ajax 查詢請求並處理
			var request = new XMLHttpRequest();

			var url = "MMS_Backside/Backside_Machine.php";
        	var params = "time=" +time ;

			request.open("POST", url, true);
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        	request.send(params);


			request.onreadystatechange = function Check_Data() {
				if (request.readyState === 4) {
					if (request.status === 200) {
						//取得testshow.php顯示的資料
						//var str = xmlHTTP.responseText;
						var type = request.getResponseHeader("Content-Type");
						var data = JSON.parse(request.responseText);
						//判斷有沒有抓到資料，如果str不等於0→有抓到，否則沒抓到→顯示原本Value的值
						if (data != 0) {
							//document.getElementById('p_speed2').innerHTML = data.var_array.speed;
							document.getElementById("m1").innerHTML = data.time;
							document.getElementById("m1").value = data.time;

							document.getElementById("ID_Machine").innerHTML = data.ID_Machine;
							document.getElementById("ID_Machine").value = data.ID_Machine;

							document.getElementById("Type_Machine").innerHTML = data.Type_Machine;
							document.getElementById("Type_Machine").value = data.Type_Machine;

							document.getElementById("ID_Order").innerHTML =data.ID_Order;
							document.getElementById("ID_Order").value =data.ID_Order;

							document.getElementById("Speed").innerHTML =data.Speed;
							document.getElementById("Speed").value =data.Speed;

							document.getElementById("Transrate").innerHTML =data.Transrate;
							document.getElementById("Transrate").value =data.Transrate;
							
							//右側機台狀態
							document.getElementById("Status_Machine").innerHTML =data.Status_Machine;
							document.getElementById("Status_Machine").value = data.Status_Machine;
							//左下角機台狀態
							document.getElementById("fw_LightBox_depiction").innerHTML =data.Status_Machine;
							document.getElementById("fw_LightBox_depiction").value = data.Status_Machine;

							//右側機台燈號
							switch(data.Status_Machine)  
							{
							   	case "運轉中" || "穩定增速狀態" || "穩定降速狀態" || "整備中" || "待命中" || "準備中":		
							      	// 顯示右側燈號_綠
										document.getElementById('LightBox_circle').className ="LightBox_circleG_before";
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleG_before";
									break;
							   	case "線速過低" || "線速過高" || "線速不穩定":
							      	// 顯示右側燈號_黃
										document.getElementById('LightBox_circle').className ="LightBox_circleY_before";
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleY_before";
									break;
							   	case "異常停機─緊急狀況":
							     	// 顯示右側燈號_紫
										document.getElementById('LightBox_circle').className ="LightBox_circleP_before";
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleP_before";
									break;
							   	case "休假停機" || "固定維修":
							     	// 顯示右側燈號_紅
										document.getElementById('LightBox_circle').className ="LightBox_circleR_before";
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleR_before";
									break;
							   	default:
								   // 顯示右側燈號_綠
									 document.getElementById('LightBox_circle').className ="LightBox_circleG_before";
									 document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleG_before";
									break;
							}

							// 警告視窗
							switch(data.Status_Machine)
						{
								case "線速過低":
								$('#alarm_box').text("線速過低");
								$( "#alarm_box" ).dialog( "open" );
								break;

								case "線速過高":
								$('#alarm_box').text("線速過高");
								$( "#alarm_box" ).dialog( "open" );
								break;

								case "線速不穩定":
								$('#alarm_box').text("線速不穩定");
								$( "#alarm_box" ).dialog( "open" );
								break;

								case "異常停機─緊急狀況":
								$('#alarm_box').text("異常停機");
								$( "#alarm_box" ).dialog( "open" );
								break;

						}
						switch(data.Status_Thermal)
						{
								case "溫度過高":
								$('#alarm_box').text("機台溫度過高");
								$( "#alarm_box" ).dialog( "open" );
								break;

								case "溫度過低":
								$('#alarm_box').text("機台溫度過低");
								$( "#alarm_box" ).dialog( "open" );
								break;
						}

							if(data.Status_Thermal=="溫度過高"){
								var machine_height=document.getElementById("Thermal");
								machine_height.style.color="red";
								machine_height.innerHTML =data.Thermal+"	(機台溫度過高)";
								machine_height.value = data.Thermal+"	(機台溫度過高)";
								//右側提醒
								document.getElementById("Notification_thermal").innerHTML ="機台溫度過高";
								document.getElementById("Notification_thermal").value = "機台溫度過高";

							}else{
								var machine_height=document.getElementById("Thermal");
								machine_height.style.color="black";
								document.getElementById("Thermal").innerHTML =data.Thermal;
								document.getElementById("Thermal").value = data.Thermal;
								//右側提醒
								document.getElementById("Notification_thermal").innerHTML ="機台溫度正常";
								document.getElementById("Notification_thermal").value = "機台溫度正常";
							}
							//右側提醒
							if(data.Status_Machine== "異常停機─緊急狀況" || data.Status_Machine=="線速過高" || data.Status_Machine=="線速過低"){
								document.getElementById("Notification_machine").innerHTML =data.Status_Machine;
								document.getElementById("Notification_machine").value = data.Status_Machine;
							}else{
								document.getElementById("Notification_machine").innerHTML ="機台狀態正常";
								document.getElementById("Notification_machine").value = "機台狀態正常";
							}

							
							
							check = 0;
						} else {
							document.getElementById("m1").value = m1;
							document.getElementById("m1").innerHTML = m1;

							document.getElementById("ID_Machine").value = ID_Machine;
							document.getElementById("ID_Machine").innerHTML = ID_Machine;

							document.getElementById("Type_Machine").value = Type_Machine;
							document.getElementById("Type_Machine").innerHTML = Type_Machine;

							document.getElementById("ID_Order").value = ID_Order;
							document.getElementById("ID_Order").innerHTML = ID_Order;

							document.getElementById("Speed").value = Speed;
							document.getElementById("Speed").innerHTML = Speed;

							document.getElementById("Transrate").value = Transrate;
							document.getElementById("Transrate").innerHTML = Transrate;

							document.getElementById("Thermal").value = Thermal;
							document.getElementById("Thermal").innerHTML = Thermal;

							document.getElementById("Status_Machine").innerHTML =Status_Machine;
							document.getElementById("Status_Machine").value = Status_Machine;
							//左下角燈號
							document.getElementById("fw_LightBox_depiction").innerHTML =fw_LightBox_depiction;
							document.getElementById("fw_LightBox_depiction").value = fw_LightBox_depiction;
							
							//右側提醒
							document.getElementById("Notification_machine").innerHTML =Notification_machine;
							document.getElementById("Notification_machine").value = Notification_machine;
							
							document.getElementById("Notification_thermal").innerHTML =Notification_thermal;
							document.getElementById("Notification_thermal").value = Notification_thermal;

							
							check = 1;
						}
					}else{
                    	alert("發生錯誤: " + request.status);
					}
				}
			}
		}
	</script>
	<!-- 撥號 -->
	<!-- <script type="text/javascript">
	 function playAudio() {
        var audioEl = document.getElementById("hidd_audio");
        url="<?php echo $url ?>";
		audioEl.setAttribute("src", url);
        audioEl.load();
        audioEl.play();
        //document.getElementById(btnID).setAttribute('src', "images/playing.png");
    }
	</script>  -->
	<!-- 撥號End -->
	<script>
		function start_time() {
				setInterval(Show_Data, 70);
				//setInterval(playAudio, 2000);			
			}
	</script>
	<style>
		.ui-widget-header,
		.ui-state-default,
		ui-button {
			background: #e23b3b;
			border: none;
			color: #FFFFFF;
			font-weight: bold;
		}
	</style>
</head>

<body>
	<div id="notification"></div>
	<!--右下通知圖示-->
	<div id="notification_box" title="通知">
		<!--通知視窗-->
		<div id="Notification_machine">
		</div>
		<div id="Notification_thermal">
		</div>
	</div>

	<div id="alarm_box" title="警告">
	</div>

	<ul id="setting_box">
	<form id="form1" action="MMS_Backside/Backside_Instert_Machine.php" method="post">
		<div>
  			<li>投入軸座URL：<input type="textbox" name="i1_seat"></li>
  			<li>投入記米URL：<input type="textbox" name="i1_meter"></li>
  			<li>產出軸座URL：<input type="textbox" name="o1_seat"></li>
  			<li>產出記米URL：<input type="textbox" name="o1_seat"></li>
  			<li>機台溫度URL：<input type="textbox" name="c11"></li>
  			<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;製令：<input type="textbox" name="order"></li>
  			<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;時：<input id="th" type="textbox" name="th" value="<?php echo $row_time["h"]?>"></li>
  			<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分：<input id="tm" type="textbox" name="tm" value="<?php echo $row_time["m"]?>" ></li>
  			<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;秒：<input id="ts" type="textbox" name="ts" value="<?php echo $row_time["s"]?>" ></li>
  			<div style="display: inline-flex;">
			<a href="MMS_Backside/InstertDB.php" target="_blank" title="開始運作">開始運作</a> &nbsp
			<a href="MMS_Backside/Backside_Delete.php" target="_blank" title="停止運作">停止運作</a> &nbsp
  				<li><input id="setting_box_button" type="submit" name="tbutton" value="送出"></li>
  			<!-- <div id="setting_box_triangle"></div>	 -->
  			</div>
		  </div>
	</form>
	</ul>

	<!-- 內容 -->
	<div id="content">
		<div id="top_bar">
			<span></span>
			<header>
				<a href="immediate.php">
					<img src="img/icon/home.png" id="home">
				</a>
				<img src="img/icon/divide.png" id="divide">
				<a id="header_title">即時機台狀態</a>
			</header>
			<div id="top_bar_right">
				<div id="time"></div>
				<img src="img/icon/setting_G.png" id="setting_icon" class="top_bar_setting">
			</div>
		</div>
		<article>
			<div id="LightBox">
				<div class="LightBox_div">
					<div class="LightBox_background">
						<span class="LightBox_nail_1"></span>
						<span class="LightBox_nail_2"></span>
						<span class="LightBox_nail_3"></span>
						<span class="LightBox_nail_4"></span>
						<div id="LightBox_circle" class="LightBox_circleG_before"></div>
					</div>
				</div>
			</div>
			<div id="LightTxt">
				<span id="Status_Machine"  class="LightTxt_title"></span>

				<span id="m1"></span>
				<hr/>
				<div class="LightTxt_tab">
					<div id="LightTxt_tr1" class="LightTxt_tr">
						<span class="LightTxt_th">機台編號</span>
						<span id="ID_Machine" class="LightTxt_td"></span>
					</div>
					<div id="LightTxt_tr2" class="LightTxt_tr">
						<span class="LightTxt_th">機台種類</span>
						<span id="Type_Machine" class="LightTxt_td"></span>
					</div>
					<div id="LightTxt_tr3" class="LightTxt_tr">
						<span class="LightTxt_th">目前製令</span>
						<span id="ID_Order" class="LightTxt_td"></span>
					</div>
					<div id="LightTxt_tr4" class="LightTxt_tr">
						<span class="LightTxt_th">目前線速</span>
						<span id="Speed" class="LightTxt_td"></span>
					</div>
					<div id="LightTxt_tr5" class="LightTxt_tr">
						<span class="LightTxt_th">目前轉換率</span>
						<span id="Transrate" class="LightTxt_td"></span>
					</div>
					<div id="LightTxt_tr6" class="LightTxt_tr">
						<span class="LightTxt_th">目前溫度</span>
						<span id="Thermal" class="LightTxt_td"></span>
					</div>
				</div>
			</div>
		</article>
	</div>



	<!--左側bar-->
	<aside>
		<div id="sidebar_top">
			<!-- 網頁最上方的時間及設定條 -->
			<img src="img/icon/menu.png" class="top_bar_menu">
			<img src="img/icon/walsin.png" class="top_bar_logo">
		</div>
		<!--<a class="sidebar_title">機台資訊</a>-->
		<ul class="sidebar">
			<!-- 左側的MENU -->
			<li>
				<span class="sidebar_hover"></span>
				<a href="immediate.php">
					<img src="img/icon/eye_G.png" class="sidebar_icon"> 即時監控
					<img src="img/icon/arrow_G.png" class="arrow">
				</a>
			</li>
			<li>
				<span class="sidebar_hover"></span>
				<a href="Work_search.php">
					<img src="img/icon/search_G.png" class="sidebar_icon"> 工作查詢
					<img src="img/icon/arrow_G.png" class="arrow">
				</a>
			</li>
			<li>
				<span class="sidebar_hover"></span>
				<a href="problem.php">
					<img src="img/icon/warning_G.png" class="sidebar_icon"> 問題管理
					<img src="img/icon/arrow_G.png" class="arrow">
				</a>
			</li>
			<li>
				<span class="sidebar_hover"></span>
				<a href="report_problem.php">
					<img src="img/icon/bar-chart_G.png" class="sidebar_icon"> 報表分析
					<img src="img/icon/arrow_G.png" class="arrow">
				</a>
			</li>
		</ul>
		<div id="sidebar_bottom">
			<!-- 燈號 -->
			<a href="immediate_Machine.php">
				<div id="fw_LightBox">
					<div id="fw_LightBox_depiction"></div>
					<div class="fw_LightBox_div">
						<div class="fw_LightBox_background">
							<span class="fw_LightBox_nail_1"></span>
							<span class="fw_LightBox_nail_2"></span>
							<span class="fw_LightBox_nail_3"></span>
							<span class="fw_LightBox_nail_4"></span>
							<div id="fw_LightBox_circle" class="fw_LightBox_circleG_before"></div>
						</div>
					</div>
				</div>
				<a id="copyright">Copyright © Walsin Lihwa Corp</a>
			</a>
		</div>
	</aside>
	<div style="display:none;">
        <audio id="hidd_audio" src="" preload="auto" controls></audio>
    </div>
</body>
<script>
	start_time();
</script>

</html>