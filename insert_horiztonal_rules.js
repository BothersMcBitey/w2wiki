function inject_horizontal_rules(content){
    var h_tags = content.querySelectorAll("h1, h2")
    for(var id_count = 0; id_count < h_tags.length; id_count++){
        h_tags[id_count].after("<hr>")
    }
}

let markdown_html = document.getElementById("markdown-content")
inject_horizontal_rules(markdown_html)