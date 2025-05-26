window.onscroll = function() {toggleScrollButton()};

function toggleScrollButton() {
  //console.log(document.body.scrollTop)
  if (document.body.scrollTop > 160) { // || document.documentElement.scrollTop > 40) {
    //console.log("show")
    document.getElementById("top").style.visibility = "visible";
    document.getElementById("bottom").style.visibility = "visible"
    //jQuery(".float_button").show();
  } else {
    //jQuery("float_button").hide();
    //console.log("hide")
    document.getElementById("top").style.visibility = "hidden"
    document.getElementById("bottom").style.visibility = "hidden"
  }
}
