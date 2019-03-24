<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php
		//連接帳號資料表，檢查帳密是否正確
		$db_server = "localhost"; //資料庫主機位置
		$db_user = "root"; //資料庫的使用帳號
		$db_password = "1234"; //資料庫的使用密碼
		$db_name = "MMS"; //資料庫名稱

		try{
			//PDO的連接語法
			$con_mms = new PDO("mysql:host=$db_server;dbname=$db_name",$db_user,$db_password);;
			//設定為utf8編碼，必要設定
			$con_mms->query('SET NAMES "utf8"');  
		}catch (PDOException $e){
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}

		// 建立SQL字串，並執行SQL指令，先在SQL指令中要用?留下未來要Binding的資料，在excute中用array來Bind Data，這樣可避免SQL Injection的駭客攻擊
		$sql = "SELECT * FROM `mms_machine`  ORDER BY `mms_machine`.`Time` DESC LIMIT 0,1 ";

		//執行SQL指令
		$str=$con_mms->query($sql);
		$row = $str->fetch(PDO::FETCH_ASSOC);
	?>
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
		function $_xmlHttpRequest() {
			if (window.ActiveXObject) {
				xmlHTTP = new ActiveXObject("Microsoft.XMLHTTP");
			}
			else if (window.XMLHttpRequest) {
				xmlHTTP = new XMLHttpRequest();
			}
		}

		function Show_Data() {
			//讀取顯示資料的Value
			var m1 = document.getElementById("m1").value
			var m2 = document.getElementById("m2").value
			var m3 = document.getElementById("m3").value
			var m4 = document.getElementById("m4").value
			var m5 = document.getElementById("m5").value
			var m6 = document.getElementById("m6").value
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
			$_xmlHttpRequest();
			xmlHTTP.open("GET", "Backside_Machine.php?select_op=" + time, true);

			xmlHTTP.onreadystatechange = function Check_Data() {
				if (xmlHTTP.readyState == 4) {
					if (xmlHTTP.status == 200) {
						//取得testshow.php顯示的資料
						var str = xmlHTTP.responseText;
						var words = str.split(',');
						//判斷有沒有抓到資料，如果str不等於0→有抓到，否則沒抓到→顯示原本Value的值
						if (str != 0) {
							document.getElementById("m1").innerHTML = words[0];
							document.getElementById("m1").value = words[0];

							document.getElementById("m2").innerHTML = words[1];
							document.getElementById("m2").value = words[1];

							document.getElementById("m3").innerHTML = words[2];
							document.getElementById("m3").value = words[2];

							document.getElementById("m4").innerHTML = words[3];
							document.getElementById("m4").value = words[3];

							document.getElementById("m5").innerHTML = words[4];
							document.getElementById("m5").value = words[4];

							document.getElementById("m6").innerHTML = words[5];
							document.getElementById("m6").value = words[5];
							check = 0;
						} else {
							document.getElementById("m1").value = m1;
							document.getElementById("m1").innerHTML = m1;

							document.getElementById("m2").value = m2;
							document.getElementById("m2").innerHTML = m2;

							document.getElementById("m3").value = m3;
							document.getElementById("m3").innerHTML = m3;

							document.getElementById("m4").value = m4;
							document.getElementById("m4").innerHTML = m4;

							document.getElementById("m5").value = m5;
							document.getElementById("m5").innerHTML = m5;

							document.getElementById("m6").value = m6;
							document.getElementById("m6").innerHTML = m6;
							check = 1;
						}
					}
				}
			}
			xmlHTTP.send(null);
		}

		function start_time() {
			setInterval(Show_Data, 1000)
		}
	</script>
	
	<title>顯示C11-I1資料</title>
</head>

<body>
	
	機台資料：
	<div id="m1" value=""></div>
	<div id="m2" value=""></div>
	<div id="m3" value=""></div>
	<div id="m4" value=""></div>
	<div id="m5" value=""></div>
	<div id="m6" value=""></div>
	<!-- <div id="show_c11_i1_input_seat" value=""></div> -->
</body>
<script>
	start_time();
</script>

</html>