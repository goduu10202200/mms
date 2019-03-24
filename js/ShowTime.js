function ShowTime(){
	var NowDate=new Date();
	// var Y=NowDate.getFullYear();
	var M=NowDate.getMonth();
	var D=NowDate.getDate();
	var h=NowDate.getHours();
	if(h<10){
		h='0'+h;
	}
	var DAY=NowDate.getDay();
	if(DAY==0){
		DAY="日";
	}
	else if(DAY==1){
		DAY="一";
	}
	else if(DAY==2){
		DAY="二";
	}
	else if(DAY==3){
		DAY="三";
	}
	else if(DAY==4){
		DAY="四";
	}
	else if(DAY==5){
		DAY="五";
	}
	else if(DAY==6){
		DAY="六";
	}
	var m=NowDate.getMinutes();
	if(m<10){
		m='0'+m;
	}
	var s=NowDate.getSeconds();
	if(s<10){
		s='0'+s;
	}　
	document.getElementById('time').innerHTML = (M+1)+'月'+D+'日 週'+DAY+' '+h+':'+m+':'+s;
	setTimeout('ShowTime()',1000);
	// document.write(NowDate.getFullYear()+ " 年 " + (NowDate.getMonth()+1) + " 月 " + NowDate.getDate() + " 日");
	}
