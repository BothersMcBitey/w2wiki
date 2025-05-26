window.onscroll = function() {toggleScrollButton()};

function toggleScrollButton() {
  //console.log(document.body.scrollTop)
  if (document.body.scrollTop > 160) { // || document.documentElement.scrollTop > 40) {
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

  console.log("=========================================")
  console.log(document.body.scrollTop)
  console.log(document.body.scrollHeight)
  console.log(document.body.clientHeight)
  console.log(document.body.scrollHeight - document.body.clientHeight - 50)
  if (document.body.scrollTop < (document.body.scrollHeight - document.body.clientHeight - 50)) { // || document.documentElement.scrollTop > 40) {
    //console.log("show")
    document.getElementById("bottom").style.visibility = "visible"
    //jQuery(".float_button").show();
  } else {
    //jQuery("float_button").hide();
    //console.log("hide")
    document.getElementById("bottom").style.visibility = "hidden"
  }
}
