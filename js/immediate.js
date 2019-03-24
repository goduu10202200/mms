var speed_bar;
//投入工作儀表板
function speed_input() {
  var speed_bar = new JustGage({
    id: "speed_bar_input",
    value: (parseInt(document.getElementById("speed_input").value) / 6),
    min: 0,
    max: 350,
    title: "投入線段線速",
    label: "m/hr",
    levelColors: [
      "#FF3333",
      "#FFFF77",
      "#99FF99"
    ]
  });

  setInterval(function () {
    speed_bar.refresh((parseInt(document.getElementById("speed_input").value) / 6));
  }, 1000);
};

//產出工作儀表板
function speed_output() {
  var speed_bar = new JustGage({
    id: "speed_bar_output",
    value: document.getElementById("speed_input").value,
    min: 0,
    max: 2000,
    title: "產出線段線速",
    label: "m/hr",
    levelColors: [
      "#FF3333",
      "#FFFF77",
      "#99FF99"
    ]
  });

  setInterval(function () {
    speed_bar.refresh(document.getElementById("speed_input").value);
  }, 1000);
};

$(document).ready(function () {
  speed_output();
  speed_input();
});