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

			// 建立SQL字串
			$sql = "SELECT * FROM `mms_machine` limit  0,1777 ";
			$sql_speed = "SELECT `Speed` FROM `mms_machine` limit  0,1777  ";
			$sql_machine_status = "SELECT * FROM `mms_machine_status`  ORDER BY `mms_machine_status`.`Time` DESC LIMIT 0,1 ";
			//執行SQL指令
			$str=$con_mms->query($sql);
			$sql_speed=$con_mms->query($sql_speed);

			$str_machine_status=$con_mms->query($sql_machine_status);
			$row_machine_status = $str_machine_status->fetch(PDO::FETCH_ASSOC);

			$xtext="";
			$data="";

			$check_time=0;
			$count_data=0;

			$v0=0;
			$a=0;
			$v=0;
			$t=1;
			foreach ($row=$str->fetchAll() as $datainfo)
			{
				if($check_time==0){
					$v0=$datainfo['Speed'];
				}
				$a=($datainfo['Speed']-$v0)/$t;
				if($check_time==120){
					
					$v=$v0+$a*$t;

					//$count_data=$count_data/120;
					$xtext .= $datainfo['Time']."','";
					$data .= $v.",";
					//$count_data=0;
					$check_time=0;
				}	
				//$count_data=$count_data+$datainfo['Speed'];
				$check_time++ ;	
				$t++;
			}

	?>
	<head>
		<meta charset="UTF-8">
		<title>伸線管理系統 | 報表分析</title>
		<link rel="stylesheet" type="text/css" href="css/framework.css">
		<link rel="stylesheet" type="text/css" href="css/report.css">
        <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="laydate/laydate.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/framework.js"></script>
		<script src="js/problem.js"></script>
		<!-- highcharts -->
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		

		<script type="text/javascript">
		var xmlHTTP;
		var time_new = '<?php echo $row_machine_status["Time"]; ?>';
		var split_time_new = time_new.split(':');
		
		// var h = split_time_new[0];
		// var m = split_time_new[1];
		// var s = split_time_new[2];
		var h = 0;
		var m = 20;
		var s = 00;
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
							// 警告視窗
				
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

		<!-- highcharts -->
		<script>
		$(document).ready(function() {
			var title_1 = {
				text: '問題報表'   
			};
			var title_2 = {
				text: '生產效能分析'   
			};
			var title_3 = {
				text: '溫度報表'   
			};

			var subtitle = {
					text: 'Copyright © Walsin Lihwa Corp'
			};
			var xAxis = {
					title: {
						text: 'Time'
					},
					categories:['<?php echo $xtext ;?>']
				//categories: ['0', '5', '10', '15', '20', '25'
						//,'30', '35', '40', '45', '55', '60']
			};
			var yAxis = [{
				title: {
					text: 'Speed (m/hr)'
				},
				plotLines: [{
					value: 10,
					width: 1,
					color: '#808080'
				}]
				}
				//,{
			//      title: {
			//       text: 'Temperature (\xB0C)'
			//    },
			//    plotLines: [{
			//       value: 10,
			//       width: 1,
			//       color: '#808080'
			//    }]
			// }
			];   

			var tooltip = {
				valueSuffix: ' m/hr'
			}

			var legend = {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle',
				borderWidth: 0
			};

			var series =  [
				{
					name: '線速(2min)',
					data:[<?php echo $data ;?>]
					//data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2,
						//26.5, 23.3, 18.3, 13.9, 9.6]
				},  
			];

			var json = {};
			if(document.getElementById('report_1').className=='report_point')
				{
					json.title = title_1;
				}
				else if(document.getElementById('report_2').className=='report_point')
				{
					json.title = title_2;
				}
				else if(document.getElementById('report_3').className=='report_point')
				{
					json.title = title_3;
				}

				json.subtitle = subtitle;
				json.xAxis = xAxis;
				json.yAxis = yAxis;
				json.tooltip = tooltip;
				json.legend = legend;
				json.series = series;

			$('#container').highcharts(json);
			});

			$(document).ready(function(){
				$('#report_1').click(function(){
					window.location = 'report_problem.php';
				});
				$('#report_2').click(function(){
					window.location = 'report_product.php';
				});
				$('#report_3').click(function(){
					window.location = 'report_degree.php';
				});
			});

			lay('#version').html('-v'+ laydate.v);
			laydate.render({
			elem: '#time_range_select'
			,range: true
			});	

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
	
		<div id="content">
			<div id="top_bar">					
				<span></span>
				<header>
					<a href="immediate.php"><img src="img/icon/home.png" id="home"></a>
					<img src="img/icon/divide.png" id="divide">
					<a id="header_title">報表分析</a>
				</header>
				<div id="top_bar_right">
					<div id="time"></div>
					<img src="img/icon/setting_G.png" id="setting_icon" class="top_bar_setting">
				</div>
			</div>

			<section>
				<div id="report_1"><a>問題報表</a></div>
				<div id="report_2"  class="report_point"><a>生產效能分析</a></div>
				<div id="report_3"><a>溫度報表</a></div>
			</section>

			<article>
				<input type="text"  name="" id="time_range_select" placeholder="  請點擊以選取日期區間">				
				<div id="container">
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
					<a href="Work_search.php">
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
					<a href="report_problem.php" class="Currently_page">
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