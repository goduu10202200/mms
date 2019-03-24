<html>
<head><meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>抓取模擬器資料</title>
<script>
function clear(){  //清除上一筆資料
    document.getElementById('show').innerHTML='';
}
function cleartrans()
{
    document.getElementById('trans').innerHTML='';
}


</script>
</head>
<body>
[{<br>
<font id="show"></font><br>
<font id="speed">'線速：':'0',</font><br>
<font id="trans">'轉換率：':'0',</font><br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
'   ：':'  ',<br>
}]

<?php
function speed($speedtime,$c11o1_meter,$lasttime,$lastoutputmeter,$lastspeed) {
     //計算速度
     $speed=$lastspeed;
     if($c11o1_meter==($lastoutputmeter+1))
     {      
         $speed=3600/($speedtime-$lasttime);
     }
     if($c11o1_meter==0)
     {
         $speed=0;
     }
return $speed;
}
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
set_time_limit(0);
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "sa";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//新增製令
/*$sql = "CREATE TABLE order (
    time time(6) NOT NULL, 
    C11_I1_Meter int(255) NOT NULL,
    C11_I1_Position varchar(255) NOT NULL,
    C11_O1_Meter int(255) NOT NULL,
    C11_O1_Position varchar(255) NOT NULL,
    C11_Thermal int(255) NOT NULL
    )";*/
//


$sql = "truncate table c11";
if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Error deleting record: " . $conn->error;
}



$sec=0;
$min=0;
$hour=0;
$time="";
$speed=0;
$speedfinal="";
$lastoutputmeter=0;//現在output meter-1的第一筆
$lastoutputmeter2=0;//現在input meter-1的第一筆output meter
$lastinputmeter=100;
$speedtime=0;
$lasttime=0;
$transrate=0;
$lasttransrate=0;

ob_end_flush();
    for($i=0;$i<=60;$i++)
    {
        $min=$i;
            for($n=0;$n<=59;$n++)
            {           
                        $sec=$n;
                        $time=(String)$hour.":".(String)$min.":".(String)$sec;
						if($min==60)
						{
							$time="1:0:0";
						}
                    
                    //抓c11i1 meter
                    $handle = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Meter&time=".$time,"rb");
                    $content = "";
                    while (!feof($handle)) {
                        $content .= fread($handle, 10000);
                    }
                    fclose($handle);
                    $content = json_decode($content,true);
                    $c11i1_meter=(string)$content['data'];
    
                    //抓c11i1 position
                    $handle2 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Position&time=".$time,"rb");
                    $content2 = "";
                    while (!feof($handle2)) {
                        $content2 .= fread($handle2, 10000);
                    }
                    fclose($handle2);
                    $content2 = json_decode($content2,true);
                    $c11i1_position=(string)$content2['data'];
    
                    //抓c11o1 meter
                    $handle3 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Meter&time=".$time,"rb");
                    $content3 = "";
                    while (!feof($handle3)) {
                        $content3 .= fread($handle3, 10000);
                    }
                    fclose($handle3);
                    $content3 = json_decode($content3,true);
                    $c11o1_meter=(string)$content3['data'];
   
                    //抓c11o1 position
                    $handle4 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-O1-Position&time=".$time,"rb");
                    $content4 = "";
                    while (!feof($handle4)) {
                        $content4 .= fread($handle4, 10000);
                    }
                    fclose($handle4);
                    $content4 = json_decode($content4,true);
                    $c11o1_position=(string)$content4['data'];
    
                    //抓c11 thermal
                    $handle5 = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=%E8%B3%87%E7%AE%A1%E4%B8%89C11%E4%BC%B8%E7%B7%9A%E6%A9%9F&sid=C11-Thermal&time=".$time,"rb");
                    $content5 = "";
                    while (!feof($handle5)) {
                        $content5 .= fread($handle5, 10000);
                    }
                    fclose($handle5);
                    $content5 = json_decode($content5,true);
                    $c11_thermal=(string)$content5['data'];

                    $show="'時間：':'".$time."',<br>'c11i1 meter：':'".$c11i1_meter."',<br>'c11i1 position：':'".$c11i1_position."',<br>'c11o1 meter：':'".$c11o1_meter."',<br>'c11o1 position：':'".$c11o1_position."',<br>'c11 thermal：':'".$c11_thermal."',";
					$show2="'時：':'".$time."',
'1：':'".$c11i1_meter."',
'2：':'".$c11i1_position."',
'3：':'".$c11o1_meter."',
'4：':'".$c11o1_position."',
'5：':'".$c11_thermal."',
";

					$newfile = fopen("dataz.txt", "w")  or die("Unable to open file!");//創建txt
					fwrite($newfile,"[{
");
					fwrite($newfile, $show2);
					
                   if($time!="0:0:0"&&$time!="1:0:0")
                   {
                       $lastsec=$sec-1;
                       $lastmin=$min;
                       if($lastsec<0)
                       {
                           $lastsec=59;
                           $lastmin=$min-1;
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
                   /*if($min==27&&$sec==0)   // 指定分鐘
                   {
                    $lastoutputmeter=$c11o1_meter;
                    $lasttime=($min*60)+$sec;
                    $lastinputmeter=$c11i1_meter;
                    $lastoutputmeter2=$c11o1_meter;
                   }*/
                   
                   if($c11o1_meter==0&&$c11o1_position!="0"&&$lastc11o1_position=="0")//if上軸則開始計算速度
                   {
                       $lasttime=($min*60)+$sec;
                       $lastoutputmeter=0;
                      
                   }
                   if($c11o1_meter==($lastoutputmeter+1))
                   {
                       $speedtime=($min*60)+$sec;
                      
                                                                                                                  
                   }
                   if($c11i1_meter!=0&&$c11i1_position!="0"&&$lastinputmeter==0)
                   {
                    $lasttime=($min*60)+$sec;
                    $lastoutputmeter=$c11o1_meter;
                   }
                   $speed=speed($speedtime,$c11o1_meter,$lasttime,$lastoutputmeter,$speed);
                   if($c11i1_meter==0&&$c11i1_position=="0")
                   {
                       $speed=0;
                       
                   }
                   

                   if($c11o1_meter==($lastoutputmeter+1))
                   {
                    $lasttime=$speedtime;
                    $lastoutputmeter=$c11o1_meter;
                                                                                                                  
                   }
                   $speedfinal=$speedfinal.",".(string)$speed;

                   //轉換率

                        $transrate=TransferRate($c11i1_meter,$lastinputmeter,$c11o1_meter,$lastoutputmeter2,$transrate);
                        if($c11i1_meter==0&&$c11i1_position=="0")
                        {
                            $transrate=0;
                            $lastinputmeter=0;
                        }
                        if($c11o1_meter==0&&$c11o1_position=="0")
                        {
                            $transrate=0;
                            //$lastoutputmeter2=0;
                        }
                        if($lastinputmeter==0&&$c11i1_meter!=0&&$c11i1_position!="0")
                        {
                            $lastinputmeter=$c11i1_meter;
                            $lastoutputmeter2=$c11o1_meter;
                        }
                    
                        

                        
                        if($c11i1_meter==($lastinputmeter-1))
                        {
                            $lastinputmeter=$c11i1_meter;
                            $lastoutputmeter2=$c11o1_meter;
                        }
                        if($c11i1_meter==0&$c11o1_meter==200&& $lastoutputmeter2!=200)
                         {
                            $lastinputmeter=$c11i1_meter;
                            $lastoutputmeter2=$c11o1_meter;
                         }
                   
                    
				
						
			 
					
					
                    echo "<script>showspeed()</script>";
                    flush();	
                    echo "<script>showtransrate()</script>";
                    flush();					

				
					
					fwrite($newfile, "'速：':'");
                    fwrite($newfile, (string)$speedfinal);
                    fwrite($newfile, "','轉換率：':'");
                    fwrite($newfile, (string)$transrate);
                    fwrite($newfile, "',
                   

'2':'  ',             
'投入軸座：':'  ',
'投入軸狀態：':'  ',
'產出軸座：':'  ',
'產出軸座態：':'  ',
'投入軸：':'  ',
'產出軸：':'  ',
'投入線段：':'  ',
'產出線段：':'  ',
'計米長度：':'  ',
}]");						
						fclose($newfile);
						
					
                    ?>
                    <script>
                       
                        function show()//顯示下一筆資料
                        {
                            document.getElementById('show').innerHTML="<?php 
                                    echo $show;
                            ?>";                                                       
                        }
                        function showspeed()//顯示速度
                        {                           
                            document.getElementById('speed').innerHTML="'線速：':'<?php 
                                    echo (string)$speed;
                            ?>',";                              
                        }
                        function showtransrate()//顯示轉換率
                        {                           
                            document.getElementById('trans').innerHTML="'轉換率：':'<?php 
                                    echo (string)$transrate;
                            ?>',";                              
                        }

                    </script>

                    




                 <?php   
                    echo "<script>clear()</script>";      
                    flush();              
                    $sql = "INSERT INTO c11 (time,C11_I1_Meter,C11_I1_Position,C11_O1_Meter,C11_O1_Position,C11_Thermal,speed,transrate)
                    VALUES ('".$time."','".$c11i1_meter."','".$c11i1_position."','".$c11o1_meter."','".$c11o1_position."','".$c11_thermal."','".$speed."','".$transrate."')";
                    
                    echo "<script>show()</script>";
                    flush();
                    sleep(0.3);
                    
                    if ($conn->query($sql) === TRUE) 
                    {
                        
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }    
					if($min==60)
					{						
						break;
					}
            }
            
    }
  ?>
    
    



<?php
$conn->close();

/* 
$handle = fopen("http://etouch20.cycu.edu.tw:5146/simone/read/?mid=資管三C11伸線機&sid=C11-I1-Meter&time=".$time,"rb");
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);
$content = json_decode($content,true);
echo $content['time']."<br>";
echo $content['data']."<br>";

$servername = "localhost";
$username = "root";
$password = "1234";*/



 /*$file = fopen("out.html", 'w');    //開啟檔案
 fwrite($file, $data);            //寫入檔案                                   
 fclose($file);                    //關閉檔案
 */


?>

</body>





                                                              