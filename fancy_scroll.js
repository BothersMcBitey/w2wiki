window.onscroll = function() {toggleScrollButton()};

function toggleScrollButton() {
  if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
    document.getElementById("top").style.visibility = "visible";
    document.getElementById("bottom").style.visibility = "visible"
    //jQuery(".float_button").show();
  } else {
    //jQuery("float_button").hide();
    document.getElementById("top").style.visibility = "none"
    document.getElementById("bottom").style.visibility = "none"
  }
}