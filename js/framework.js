$(document).ready(function () {
	ShowTime();


	// 右下方通知
	$('#notification').click(function () {
		$('#notification').css('background-image', 'url(img/icon/notification_ring.png)');
		$('#notification').css('background-color', '#74b1ff');
		$('#notification').css('box-shadow', '-1px 1px 10px #66a8ff');
		$('#notification').css('transform', 'rotateY(180deg)');
		$('#notification').css('opacity', '1');

		$('#notification').mouseout(function () {
			$('#notification').css('background-image', 'url(img/icon/notification_B.png)');
			$('#notification').css('background-color', '#b5b5b5');
			$('#notification').css('transform', 'rotateY(0deg)');
			$('#notification').css('box-shadow', '1px 1px 30px #b5b5b5');
			$('#notification').css('opacity', '0.5');
			$('#notification').hover(function () {
				$('#notification').css('box-shadow', '1px 1px 60px #444');
				$('#notification').mouseout(function () {
					$('#notification').css('box-shadow', '1px 1px 30px #444');
				});
			});
		});
	});

	/*上方bar時間置中*/
	topbar_right_width = $(window).width() - 180;
	content_height = $(window).height() - 50;
	$("#top_bar_right").css("width", topbar_right_width);
	$("#content").css("width", topbar_right_width);
	$("#top_bar").css("width", topbar_right_width);
	$("#top_bar>span").css("width", topbar_right_width);
	$(window).resize(function () {
		topbar_right_width = $(window).width() - 180;
		$("#top_bar_right").css("width", topbar_right_width);
		$("#content").css("width", topbar_right_width);
		$("#top_bar").css("width", topbar_right_width);
		$("#top_bar>span").css("width", topbar_right_width);
	});
});



// 顯示燈號
/*function fw_bright() {
	setInterval(function () {
		document.getElementById('fw_LightBox_circle').className = "fw_LightBox_circleG_after";
	}, 1500);
	setInterval(function () {
		document.getElementById('fw_LightBox_circle').className = "fw_LightBox_circleG_before";
	}, 3000);
}*/


// 顯示時間
function ShowTime() {
	var NowDate = new Date();
	var Y = NowDate.getFullYear();
	var M = NowDate.getMonth();
	var D = NowDate.getDate();
	var h = NowDate.getHours();
	if (h < 10) {
		h = '0' + h;
	}
	var DAY = NowDate.getDay();
	if (DAY == 0) {
		DAY = "日";
	}
	else if (DAY == 1) {
		DAY = "一";
	}
	else if (DAY == 2) {
		DAY = "二";
	}
	else if (DAY == 3) {
		DAY = "三";
	}
	else if (DAY == 4) {
		DAY = "四";
	}
	else if (DAY == 5) {
		DAY = "五";
	}
	else if (DAY == 6) {
		DAY = "六";
	}
	var m = NowDate.getMinutes();
	if (m < 10) {
		m = '0' + m;
	}
	var s = NowDate.getSeconds();
	if (s < 10) {
		s = '0' + s;
	}
	document.getElementById('time').innerHTML = NowDate.getFullYear() + "年 " + (M + 1) + '月' + D + '日 週' + DAY + '　' + h + ':' + m + ':' + s;
	setTimeout('ShowTime()', 1000);
	// document.write( + (NowDate.getMonth()+1) + " 月 " + NowDate.getDate() + " 日");
}

/*各種視窗*/
$(function () {
	/*提醒圖示點擊觸發視窗*/
	$("#notification").click(function () {
		$(".ui-widget-header").css('background-color', '#AAA');
		$("#notification_box").dialog("open");

	});
	/*提醒視窗*/
	$("#notification_box").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		position: {
			my: "center",
			at: "center"
		}
		// buttons: {
		//     OK: function() {$(this).dialog("close");}
		// }, 
	});


	/*提醒圖示點擊觸發視窗*/
	// $( "#setting_icon" ).click(function() {
	//     $( "#setting_box" ).dialog( "open" );
	// });
	/*系統視窗*/
	$("#setting_icon").click(function () {
		$("#setting_box").css({ 'opacity': '1', 'z-index': '10' });
		// $( "#setting_box" ).menu();
		$("#setting_icon").click(function () {
			$("#setting_box").css({ 'opacity': '0', 'z-index': '-11' });
			$("#setting_icon").click(function () {
				$("#setting_box").css({ 'opacity': '1', 'z-index': '10' });
				$("#setting_icon").click(function () {
					$("#setting_box").css({ 'opacity': '0', 'z-index': '-11' });
				});
			});
		});
	});

	/*警告視窗*/
	$("#alarm_box").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		height: 200,
		width: 300
	});
	//線速警告
	// if(document.getElementById('speed_input').innerHTML==0)
	// {
	// 	$('#alarm_box').text("線速過高");
	// 	$( "#alarm_box" ).dialog( "open" );
	// }	
});