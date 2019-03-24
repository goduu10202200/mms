<?php session_start(); ?>
<?php
if( !isset($_POST['account']) || !isset($_POST['password']) || $_POST['account']=="" || $_POST['password']=="" ){
//若沒有從login.html的submit或帳密為空白，就導回login.html  
header("location:../login.html");
}
else {
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

//抓取login.html的帳號、密碼欄位值 
$Id =$_POST['account'];
$password =  $_POST['password'];
 


// 建立SQL字串，並執行SQL指令，先在SQL指令中要用?留下未來要Binding的資料，在excute中用array來Bind Data，這樣可避免SQL Injection的駭客攻擊
$sql = "SELECT * FROM `Sys_Account` WHERE (Account=? AND Password=?)";

//執行SQL指令，並取得資料結果集合
$sth = $con_mms->prepare($sql);
$sth->execute(array($Id,$password));
$result = $sth->fetch(PDO::FETCH_ASSOC);

if( $result) {  //若有資料，表示帳號密碼正確，設定Session，並導向 index.php
	$_SESSION['LoginSuccess'] = true;
	$_SESSION['account']=$Id ;
	$_SESSION['password']=$password;
	header("location:../immediate_Machine.php"); 
}
else {  
	echo "<h1 style='color:red;'>帳號密碼錯誤，請重新登入。</h1>";
	echo "<p><a href='../login.html'>回到登入畫面</a></p>";
}
	$con_mms = NULL;
}
 
?>