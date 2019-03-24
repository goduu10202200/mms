<html>
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
		$sql_sys_time = "SELECT * FROM `sys_time`  ";
		//執行SQL指令
		
		$str_sys_time=$con_mms->query($sql_sys_time);

		$row_sys_time = $str_sys_time->fetch(PDO::FETCH_ASSOC);
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/JavaScript">

    var var_time ='';
    var h = '<?php echo $row_sys_time["h"]; ?>';
	var m = '<?php echo $row_sys_time["m"]; ?>';
	var s = '<?php echo $row_sys_time["s"]; ?>';
    // var h = 0;
	// var m = 0;
	// var s =0;
	var check = 0;
    var check_db=0;
    var status = "init";

	//紀錄線速變數
    var test_count = 0;
	var speed = 0;
	var lastmeter = 0;
	var speedtime = 0;
	var lasttime = 0;
	var check_c11_i1_meter = 0;
	var check_c11_i1_time = 0;
	var lastc11o1_position = "";
	//End
	
	//紀錄轉換率變數
	var lastoutputmeter2 = 0;//現在input meter-1的第一筆output meter
	var lastinputmeter = 100;
	var transrate = 0;
	var lasttransrate = 0;
    //檢查變數
    var check_speed=0;
    var c11o1_check=0;//判別產出軸座是不是在換軸
    var c11i1_check=0;//判別是投入軸座不是在換軸
    var check_Iwork=0;//檢查投入工作
    var check_Owork=0;//檢查產出工作
/*End*/

    function do_ajax(){
        check_db++;
        if(check_db>=2){
            check_db==2;
        }
        var p_time = document.getElementById("p_time").value
		var p_Imeter = document.getElementById("p_Imeter").value
		var p_Iposition = document.getElementById("p_Iposition").value
		var p_Ometer = document.getElementById("p_Ometer").value
		var p_Oposition = document.getElementById("p_Oposition").value
		var p_thermal = document.getElementById("p_thermal").value
        var p_speed = document.getElementById("p_speed").value
        var p_transrate = document.getElementById("p_transrate").value
        
       
		if (var_time != "1:0:0" && check == 0) {
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
        var var_time = h + ":" + m + ":" + s;
        // 發送 Ajax 查詢請求並處理
        var request = new XMLHttpRequest();
        //判斷秒數
        // s2=s-1;
        // if(s2<0){
        //     s2=59;
        // }
        var url = "Backside_InstertDB2.php";
        var params = "time=" + var_time +
                     "&m=" + m + 
                     "&s=" + s +
                     "&test_count=" + test_count +
                     "&status=" + status +
                     "&speed=" + speed +
                     "&lastmeter=" + lastmeter +
                     "&speedtime=" + speedtime +
                     "&lasttime=" + lasttime +
                     "&check_c11_i1_meter=" + check_c11_i1_meter +
                     "&check_c11_i1_time=" + check_c11_i1_time +
                     "&lastc11o1_position=" + lastc11o1_position +
                     "&lastoutputmeter2=" + lastoutputmeter2 +
                     "&lastinputmeter=" + lastinputmeter +
                     "&transrate=" + transrate +
                     "&lasttransrate=" + lasttransrate+
                     "&check_speed=" + check_speed+
                     "&c11o1_check=" + c11o1_check+
                     "&c11i1_check=" + c11i1_check+
                     "&check_Iwork=" + check_Iwork+
                     "&check_Owork=" + check_Owork;


                        
        request.open("POST", url, true);
        //request.open("GET", ?);
        // POST 請求必須設置表頭在 open() 下面，send() 上面
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        request.send(params);
        if(status == "init"){
            status = "process";
        }

        request.onreadystatechange = function() {
            // 伺服器請求完成
            if (request.readyState === 4) {
                // 伺服器回應成功
                if (request.status === 200) {
                    var type = request.getResponseHeader("Content-Type");   // 取得回應類型
                    var data = JSON.parse(request.responseText);
                        if(data!=""){
                            document.getElementById('p_time').innerHTML = data.time;
                            document.getElementById('p_Imeter').innerHTML = data.c11_i1_meter;
                            document.getElementById('p_Iposition').innerHTML = data.c11_i1_position;
                            document.getElementById('p_Ometer').innerHTML = data.c11_o1_meter;
                            document.getElementById('p_Oposition').innerHTML = data.c11_o1_position;
                            document.getElementById('p_thermal').innerHTML = data.c11_i1_thermal;
                            document.getElementById('p_speed').innerHTML = data.speed;
                            //document.getElementById('p_speed2').innerHTML = data.var_array.speed;
                            document.getElementById('p_transrate').innerHTML = data.transrate;
                            check=0;
                        }else{
                            document.getElementById('p_time').innerHTML = p_time;
                            document.getElementById('p_Imeter').innerHTML = p_Imeter;
                            document.getElementById('p_Iposition').innerHTML = p_Iposition;
                            document.getElementById('p_Ometer').innerHTML = p_Ometer;
                            document.getElementById('p_Oposition').innerHTML = p_Oposition;
                            document.getElementById('p_thermal').innerHTML = p_thermal;
                            document.getElementById('p_speed').innerHTML = p_speed;
                            //document.getElementById('p_speed2').innerHTML = data.var_array.speed;
                            document.getElementById('p_transrate').innerHTML = p_transrate;
                            check=1;
                        }
                        test_count = data.test_count;
                        
                        speed = data.var_array.speed;
                        lastmeter = data.var_array.lastmeter;
                        speedtime = data.var_array.speedtime;
                        lasttime = data.var_array.lasttime;
                        check_c11_i1_meter = data.var_array.check_c11_i1_meter;
                        check_c11_i1_time = data.var_array.check_c11_i1_time;
                        lastc11o1_position = data.var_array.lastc11o1_position;
                        lastoutputmeter2 = data.var_array.lastoutputmeter2;
                        lastinputmeter = data.var_array.lastinputmeter;
                        transrate = data.var_array.transrate;
                        lasttransrate = data.var_array.lasttransrate;
                        check_speed=data.var_array.check_speed;
                        c11o1_check=data.var_array.c11o1_check;
                        c11i1_check=data.var_array.c11i1_check;
                        check_Iwork=data.var_array.check_Iwork;
                        check_Owork=data.var_array.check_Owork;
                        if(var_time!="1:0:0"){
                            do_ajax();
                        }
                       
                } else {
                    alert("發生錯誤: " + request.status);
                }
            }
        }
    }
    
    window.onload = function() {
        do_ajax();
    };
    </script>
</head>

<body>
    <p>
        Time:
        <p id="p_time"></p>
        <br> Imeter:
        <p id="p_Imeter"></p>
        <br> Iposition:
        <p id="p_Iposition"></p>
        <br> Ometer:
        <p id="p_Ometer"></p>
        <br> Oposition:
        <p id="p_Oposition"></p>
        <br> thermal:
        <p id="p_thermal"></p>
        <br> speed:
        <p id="p_speed"></p>
        <br> transrate:
        <p id="p_transrate"></p>
        <br>
    </p>

</body>


</html>