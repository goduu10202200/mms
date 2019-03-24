var speed_bar;
      
      window.onload = function(){
        var speed_bar = new JustGage({
          id: "speed_bar", 
          value: getRandomInt(0, 30), 
          min: 0,
          max: 100,
          title: "Speedometer",
          label: "km/h",
          levelColors: [
            "#222222",
            "#555555",
            "#CCCCCC"
            ]    
        });
      
        setInterval(function() {
          speed_bar.refresh(getRandomInt(80, 100));
        }, 800);
      };