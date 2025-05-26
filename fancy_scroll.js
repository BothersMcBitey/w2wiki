window.onscroll = function() {toggleScrollButton()};

function toggleScrollButton() {
  if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
    jQuery(".float_button").show();
  } else {
    jQuery("float_button").hide();
  }
}