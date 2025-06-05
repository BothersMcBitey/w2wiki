//document.getElementById('text_edit').addEventListener('keydown', function(e) {
document.addEventListener('keydown', function(e) {
  if (e.key == 's' && e.ctrlKey) {
    e.preventDefault();
    console.log("Page Saved!")
    var save_button = document.getElementById("save");
    save_button.click();
    /*var start = this.selectionStart;
    var end = this.selectionEnd;

    // set textarea value to: text before caret + tab + text after caret
    this.value = this.value.substring(0, start) +
      "\t" + this.value.substring(end);

    // put caret at right position again
    this.selectionStart =
      this.selectionEnd = start + 1;*/
  }
});