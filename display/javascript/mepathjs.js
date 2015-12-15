
// code to prepare and display youtube videos
function createDiv(vid)
{
var youtube = "youtube";
var ytno = (youtube + vid);

var tubevideo = unescape(document.getElementById(ytno).title);
//var embb = document.write(tubevideo);



var divTag = document.createElement("div");
divTag.id = "div1";
divTag.setAttribute("float","left");
divTag.setAttribute("align","left");
divTag.style.margin = "0px auto";
//divTag.className ="sumphoto";
divTag.innerHTML = tubevideo;

var newone = "newone";
var divid = newone + vid;

document.getElementById(divid).appendChild(divTag);



}


