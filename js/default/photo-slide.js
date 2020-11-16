/* ================================================================ 
This copyright notice must be untouched at all times.
Copyright (c) 2009 Stu Nicholls - stunicholls.com - all rights reserved.
=================================================================== */
$(document).ready(function(){
current = 1;
button = 1;
images = 10;
width = 480;

$('#p1').animate({"left": "0px"}, 400, "swing");
$('#b1').css("backgroundPosition", "left bottom")
$('#b1 b').css("color","#fff");

$("#next").click(function() {
		button = current;
		current++
	if (current == (images+1) ) {current = 1}
animateLeft(current,button)
});

$("#previous").click(function() {
		button = current;
		current--
	if (current == 0 ) {current = images}
animateRight(current,button)
});

$("#buttons div").click(function() {
	button=current;
	clickButton = $(this).attr('id');
	current = parseInt(clickButton.slice(1));
	if (current > button) {animateLeft(current,button)}
	if (current < button) {animateRight(current,button)}
});

$("#buttons div b").mouseover(function() {
	if (($(this).css("color")) == "#16a" || ($(this).css("color")) == "rgb(17, 102, 170)") {$(this).css("color","#000");}
});

$("#buttons div b").mouseout(function() {
	if (($(this).css("color")) == "#000" || ($(this).css("color")) == "rgb(0, 0, 0)") {$(this).css("color","#16a");}
});

function animateLeft(current,button) {
	$('#p'+current).css("left",width +"px");
	$('#p'+current).animate({"left": "0px"}, 400, "swing");
	$('#p'+button).animate({"left": -width+"px"}, 400, "swing");
	setbutton()
}

function animateRight(current,button) {
	$('#p'+current).css("left",-width+"px");
	$('#p'+current).animate({"left": "0px"}, 400, "swing");
	$('#p'+button).animate({"left": width+"px"}, 400, "swing");
	setbutton()
}

function setbutton () {
	$('#b'+button).css("backgroundPosition", "left top")
	$('#b'+button+' b').css("color","#16a");
	$('#b'+current).css("backgroundPosition", "left bottom")
	$('#b'+current+' b').css("color","#fff");
}

});

