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
		$sql = "SELECT * FROM `mms_order`";
		$sql_machine_status = "SELECT * FROM `mms_machine_status`  ORDER BY `mms_machine_status`.`Time` DESC LIMIT 0,1 ";
		//執行SQL指令
		$str=$con_mms->query($sql);
		$row = $str->fetch(PDO::FETCH_ASSOC);

		$str_machine_status=$con_mms->query($sql_machine_status);
		$row_machine_status = $str_machine_status->fetch(PDO::FETCH_ASSOC);
	?>
	<head>
		<meta charset="UTF-8">
		<title>伸線機台管理系統 | 製令查詢</title>
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/framework.css">
		<link rel="stylesheet" type="text/css" href="css/Work_search_data.css">
		<script src="js/jquery-3.2.1.min.js"></script>
      	<script src="js/jquery-3.2.1.js"></script>
     	<script src="js/jquery-ui.js"></script>
		<script src="js/framework.js"></script><script src="laydate/laydate.js"></script>
     	<script src="js/Work_search_data.js"></script>

		 <script type="text/javascript">
		var xmlHTTP;
		var time_new = '<?php echo $row_machine_status["Time"]; ?>';
		var split_time_new = time_new.split(':');
		
		var h = split_time_new[0];
		var m = split_time_new[1];
		var s = split_time_new[2];
		//var h = 0;
		//var m = 0;
		//var s = 1;
		var check = 0;
		
		function Show_Data() {
			//讀取顯示資料的Value

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

			var url = "MMS_Backside/Backside_Machine_Status.php";
        	var params = "time=" +time ;

			request.open("POST", url, true);
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        	request.send(params);


			request.onreadystatechange = function Check_Data() {
				if (request.readyState === 4) {
					if (request.status === 200) {
						//取得testshow.php顯示的資料
						var type = request.getResponseHeader("Content-Type");
						var data = JSON.parse(request.responseText);
						//判斷有沒有抓到資料，如果str不等於0→有抓到，否則沒抓到→顯示原本Value的值
						if (data != 0) {
							//左下角機台狀態
							document.getElementById("fw_LightBox_depiction").innerHTML =data.Status_Machine;
							document.getElementById("fw_LightBox_depiction").value = data.Status_Machine;


							if(data.Status_Thermal=="溫度過高"){
								//右側提醒
								document.getElementById("Notification_thermal").innerHTML ="機台溫度過高";
								document.getElementById("Notification_thermal").value = "機台溫度過高";

							}else{
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

							//右側機台燈號
							switch(data.Status_Machine)  
							{
							   	case "運轉中" || "穩定增速狀態" || "穩定降速狀態" || "整備中" || "待命中" || "準備中":		
							      	// 顯示右側燈號_綠
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleG_before";
									break;
							   	case "線速過低" || "線速過高" || "線速不穩定":
							      	// 顯示右側燈號_黃
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleY_before";
									break;
							   	case "異常停機─緊急狀況":
							     	// 顯示右側燈號_紫
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleP_before";
									break;
							   	case "休假停機" || "固定維修":
							     	// 顯示右側燈號_紅
										document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleR_before";
									break;
							   	default:
								   // 顯示右側燈號_綠
									 document.getElementById('fw_LightBox_circle').className ="fw_LightBox_circleG_before";
									break;
							}


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

						} else {
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
		function start_time() {
			setInterval(Show_Data, 1000)
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
		<div id="notification"></div>					<!--右下通知圖示-->
		<div id="notification_box" title="通知">			<!--通知視窗--> 
			<div id="Notification_machine"></div>
			<div id="Notification_thermal"></div>
		</div>
		<div id="setting_box" title="系統設定">				<!--設定視窗-->
		
		</div>
		<div id="alarm_box" title="警告">
		</div>

		<!-- 內容 -->
		<div id="content">
			<div id="top_bar">					
				<span></span>
				<header>
					<a href="immediate.php"><img src="img/icon/home.png" id="home"></a>
					<img src="img/icon/divide.png" id="divide">
					<a id="header_title">工作查詢</a>
				</header>
				<div id="top_bar_right">
					<div id="time"></div>
					<img src="img/icon/setting_G.png" id="setting_icon" class="top_bar_setting">
				</div>
			</div>
			<article>
				<input type="text"  name="" id="time_range_select" placeholder="　　請點擊以選取日期區間">
				<div id="work_list">
					<table align="center">
					<tr>
						<th>製令ID</th>
						<th>發生時間</th>
					</tr>
					<tr>
						<?php $datetime = date ("Y-m-d H:i:s" , mktime(date('H')+7, date('i'), date('s'), date('m'), date('d'), date('Y'))) ; ?>
						<td><?php echo $row['ID_Order'] ?></td>
						<td><?php echo $datetime ?></td>
						
					</tr>
					</table>
				</div>

			</article>
		</div>


				
		<!--左側bar-->
		<aside>
			<div id="sidebar_top">					<!-- 網頁最上方的時間及設定條 -->
				<img src="img/icon/menu.png" class="top_bar_menu">
				<img src="img/icon/walsin.png" class="top_bar_logo">
			</div>
			<!--<a class="sidebar_title">機台資訊</a>-->
			<ul class="sidebar">		<!-- 左側的MENU -->
				<li>
					<span class="sidebar_hover"></span>
					<a href="immediate.php">
						<img src="img/icon/eye_G.png" class="sidebar_icon">
						即時監控
						<img src="img/icon/arrow_G.png" class="arrow">
					</a>
				</li>
				<li>
					<span class="sidebar_hover"></span>
					<a href="Work_search.php" class="Currently_page">
						<img src="img/icon/search_G.png" class="sidebar_icon">
						工作查詢
						<img src="img/icon/arrow_G.png" class="arrow">
					</a>
				</li>
				<li>
					<span class="sidebar_hover"></span>
					<a href="problem.php">
						<img src="img/icon/warning_G.png" class="sidebar_icon">
						問題管理
						<img src="img/icon/arrow_G.png" class="arrow">
					</a>
				</li>
				<li>
					<span class="sidebar_hover"></span>
					<a href="report_problem.php">
						<img src="img/icon/bar-chart_G.png" class="sidebar_icon">
						報表分析
						<img src="img/icon/arrow_G.png" class="arrow">
					</a>
				</li>
			</ul>
			<div id="sidebar_bottom">				<!-- 燈號 -->	
				<a href="immediate_Machine.php"> 
					<div id="fw_LightBox">
						<div id="fw_LightBox_depiction">機台正常</div>
						<div class="fw_LightBox_div">
							<div class="fw_LightBox_background">
								<span class="fw_LightBox_nail_1"></span>
								<span class="fw_LightBox_nail_2"></span>
								<span class="fw_LightBox_nail_3"></span>
								<span class="fw_LightBox_nail_4"></span>
								<div id="fw_LightBox_circle" class="fw_LightBox_circle_before"></div>
							</div>
						</div>
					</div>
					<a id="copyright">Copyright © Walsin Lihwa Corp</a> 
				</a>
			</div>
		</aside>
	</body>
	<script>
		start_time();
	</script>
</html>