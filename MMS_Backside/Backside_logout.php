<?php
session_start();
//登出函數
function logout() 
{
	unset($_SESSION['account']);
	unset($_SESSION['password']);
	return true;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

	<head>
		<meta charset="UTF8">
		<title>登出中...</title>
	</head>

	<body>
		<h1 style="text-align: center;">登出中...</h1>
		<div style="text-align: center;">
			<?php
			logout();//使用登出函數
			echo "登出中...";
			echo '<meta http-equiv="refresh" content="2; url=../login.html">';
			?>
		</div>
	</body>

</html>