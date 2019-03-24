$(document).ready(function () {
	immediate_Machine_start();
	//bright();
});
function immediate_Machine_start() {
	// 網頁開啟時分隔線延展特效
	document.getElementsByTagName("hr")[0].setAttribute("class", "hr_extend");

	// 顯示表格
	setInterval(function () {
		document.getElementById('LightTxt_tr1').style.opacity = "1";
	}, 000);
	setInterval(function () {
		document.getElementById('LightTxt_tr2').style.opacity = "1";
	}, 300);
	setInterval(function () {
		document.getElementById('LightTxt_tr3').style.opacity = "1";
	}, 600);
	setInterval(function () {
		document.getElementById('LightTxt_tr4').style.opacity = "1";
	}, 900);
	setInterval(function () {
		document.getElementById('LightTxt_tr5').style.opacity = "1";
	}, 1200);
	setInterval(function () {
		document.getElementById('LightTxt_tr6').style.opacity = "1";
	}, 1500);

}
/*
function bright() {
	setInterval(function () {
		document.getElementById('LightBox_circle').className = "LightBox_circleG_after";
	}, 1500);
	setInterval(function () {
		document.getElementById('LightBox_circle').className = "LightBox_circleG_before";
	}, 3000);
}*/