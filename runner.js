$(document).ready(function() {
	var canvas = $("#myCanvas");
	var context = canvas.get(0).getContext("2d");
	var canvasWidth = canvas.width();
	var canvasHeight = canvas.height();
$(window).resize(resizeCanvas);
	function resizeCanvas() {
	canvas.attr("width", $(window).get(0).innerWidth);
	canvas.attr("height", $(window).get(0).innerHeight);
	canvasWidth = canvas.width();
	canvasHeight = canvas.height();
	};
resizeCanvas();
	var play =false;
	var playAnimation = true;
	var startButton = $("#startAnimation");
	var stopButton = $("#stopAnimation");
		startButton.hide();
		
		startButton.click(function() {
			$(this).hide();
			stopButton.show();
			playAnimation = true;
			animate();
		});
		stopButton.click(function() {
			$(this).hide();
			startButton.show();
			playAnimation = false;
		});
	function stop(){
		context.clearRect(0, 0, canvasWidth, canvasHeight);
		alert("GAME OVER!");
	};
	var Obstacle = function(x, y) {
		this.x = x;
		this.y = y;
	};
	var obstacles = new Array();
	var x = 520;
	for (var i = 0; i < 30; i++) {
	var y = 15+Math.random()*50;
	obstacles.push(new Obstacle(x, y));
	x += 400;
	};
	var player = {
    X:40,
    Y:350,
    speedY:5};
	
	var flag = 0;
	
	
	function checkforcollision(tmpObstacle)
	{
		if (!(tmpObstacle.x+ 20 < player.X) &&
			!(player.X + 20 < tmpObstacle.x) &&
			!(350 + tmpObstacle.y < player.Y) &&
			!(player.Y + 50 < 350)) {
				playAnimation = false;
				stop();
			};
	}
	function animate() {
	context.clearRect(0, 0, canvasWidth, canvasHeight);
	context.fillStyle = "rgb(255, 255, 255)";
	context.fillRect(player.X, player.Y, 20, 50);
	
	context.strokeStyle = "rgba(255, 255, 255, 0.5)";
	context.beginPath();
	context.moveTo(0, 400);
	context.lineTo(canvasWidth, 400);
	context.closePath();
	context.stroke();
	
	obstaclesLength = obstacles.length;
	for (var i = 0; i < obstaclesLength; i++) {
	var tmpObstacle = obstacles[i];
	context.fillStyle = "rgb(0, 255, 255)";
	context.fillRect(tmpObstacle.x, 350, 20, tmpObstacle.y);
	tmpObstacle.x -= 2;
	checkforcollision(tmpObstacle);
	};
	if (playAnimation) {
		setTimeout(animate, 33);
	};
};
$(window).keydown(function(e) {
	var keyCode = e.keyCode;
	if(keyCode==32){
		player.Y = 220;
		playAnimation = true;
	};
});
$(window).keyup(function(e) {
	var keyCode = e.keyCode;
	if(keyCode==32){
		player.Y = 350;
		play= false;
	};
});
animate();
});