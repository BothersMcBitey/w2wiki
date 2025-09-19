// trap Ctrl key inputs
document.addEventListener('keydown', function(e) {
  if (e.ctrlKey) {
    // Ctrl+S to trigger the "save" button on edit pages 
    if(e.key == 's'){
      e.preventDefault();
      console.log("Page Saved!")
      var save_button = document.getElementById("save");
      save_button.click();
    }
  }
});

// trap Tab key when focused on edit window to insert a tab character
document.getElementById('text_edit').addEventListener('keydown', function(e) {
  if (e.key == 'Tab') {
    e.preventDefault();
    var start = this.selectionStart;
    var end = this.selectionEnd;
    // set textarea value to: text before caret + tab + text after caret
    this.value = this.value.substring(0, start) +
      "\t" + this.value.substring(end);
    // put caret at right position again
    this.selectionStart =
      this.selectionEnd = start + 1;
  }
});