var startday = '2016-07-23 23:59:59 GMT-0700';

function Days_left ()
{
	var day= document.getElementById("day");
	var hour= document.getElementById("hour");
	var min= document.getElementById("min");
	var secs= document.getElementById("sec");
var now = new Date();
var time = (Date.parse(startday) - Date.parse(now));
var sec = Math.floor ( (time/1000) % 60);
var mins = Math.floor ( (time/(1000*60)) %60);
var hours = Math.floor ( (time/(1000*60*60)) %24);
var Days = Math.floor (time/(1000*60*60*24));
day.innerHTML = Days;
hour.innerHTML = hours;
min.innerHTML = mins;
secs.innerHTML = sec;
}

setInterval("Days_left()", 1000);
