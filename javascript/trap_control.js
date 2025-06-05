//document.getElementById('text_edit').addEventListener('keydown', function(e) {
document.addEventListener('keydown', function(e) {
  if (e.ctrlKey) {
    if(e.key == 's'){
      e.preventDefault();
      console.log("Page Saved!")
      var save_button = document.getElementById("save");
      save_button.click();
    }
  }
});