window.onscroll = function() {toggleScrollButton()};
toggleScrollButton();
console.log("What?")
function toggleScrollButton() {
  console.log(document.body.scrollTop)
  if (document.body.scrollTop > 0) { // || document.documentElement.scrollTop > 40) {
    //console.log("show")
    document.getElementById("top").style.visibility = "visible";
    //document.getElementById("bottom").style.visibility = "visible"
    //jQuery(".float_button").show();
  } else {
    //jQuery("float_button").hide();
    //console.log("hide")
    document.getElementById("top").style.visibility = "hidden"
    //document.getElementById("bottom").style.visibility = "hidden"
  }

  if (document.body.scrollTop < (document.body.scrollHeight - document.body.clientHeight - 100)) { // || document.documentElement.scrollTop > 40) {
    //console.log("show")
    document.getElementById("bottom").style.visibility = "visible"
    //jQuery(".float_button").show();
  } else {
    //jQuery("float_button").hide();
    //console.log("hide")
    document.getElementById("bottom").style.visibility = "hidden"
  }
}
