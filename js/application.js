

 var hour0=0; 
 var min0=0;
 var sec0=0;
 var timeId=null;
 
 function form2 (v) { return (v<10?'0'+v:v); }
 
 function changetime() {
  sec0--;
  if (sec0<0) {
   sec0=59;
   min0--;
   if (min0<0) {
    min0=59;
    if (hour0>0) hour0--;
    else { hour0=min0=sec0=0; }
   }
  }
}

function showtime () {
  var t=hour0*3600+min0*60+sec0;
  if (t>0) {
   document.getElementById('clock1').innerHTML = form2(hour0)+':'+form2(min0)+':'+form2(sec0);
   changetime();
   window.setTimeout("showtime();",1000);
  }
  else if (t<1) {
   document.getElementById('clock1').innerHTML = '';
   window.clearTimeout (timeID);
  }
 }
 
 function inittime (hour,min,sec) {
  hour0=Math.max(hour,0); min0=Math.max(min,0); sec0=Math.max(sec,0);
  timeID=window.setTimeout("showtime();",1000);
}

