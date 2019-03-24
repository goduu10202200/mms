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
	         text: 'Time (min)'
	    },
       categories: ['0', '5', '10', '15', '20', '25'
              ,'30', '35', '40', '45', '55', '60']
   };
   var yAxis = [{
      title: {
         text: 'Temperature (\xB0C)'
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
      valueSuffix: '\xB0C'
   }

   var legend = {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle',
      borderWidth: 0
   };

   var series =  [
      {
         name: '平均線速',
         data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2,
            26.5, 23.3, 18.3, 13.9, 9.6]
      }, 
      {
         name: '平均計米',
         data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8,
            24.1, 20.1, 14.1, 8.6, 2.5]
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
        window.location = 'report_problem.html';
    });
    $('#report_2').click(function(){
        window.location = 'report_product.html';
    });
    $('#report_3').click(function(){
        window.location = 'report_degree.html';
    });
});

lay('#version').html('-v'+ laydate.v);
laydate.render({
  elem: '#time_range_select'
  ,range: true
});
