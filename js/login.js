/*網頁開啟時分隔線延展特效*/
function onload_hr() {
	document.getElementsByTagName("hr")[0].setAttribute("class", "hr_extend");
}

/*驗證碼*/
var code;
function createCode() {
	code = "";
	var codeLength = 6; //驗證碼長度
	var checkCode = document.getElementById("checkCode");
	var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
	'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); //所有候選組成驗證碼的字符，也可用中文
	for (var i = 0; i < codeLength; i++){
		var charNum = Math.floor(Math.random() * 52);
		code += codeChars[charNum];
	}
	if (checkCode){
		checkCode.className = "code";
		checkCode.innerHTML = code;
	}
}
function validateCode(){
	var inputCode = document.getElementById("inputCode").value;
	if (inputCode.length <= 0){
		alert("請輸入驗證碼！");
	}
	else if (inputCode.toUpperCase() != code.toUpperCase()){
		alert("驗證碼輸入有誤！");
		createCode();
	}
	else{
		alert("驗證碼正確！");
	}
}