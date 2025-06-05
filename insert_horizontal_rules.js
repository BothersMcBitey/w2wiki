function inject_horizontal_rules(content){
    var h_tags = content.querySelectorAll("h1, h2")
    //let hr = document.createElement("hr")
    console.log(hr)
    for(var id_count = 0; id_count < h_tags.length; id_count++){
        h_tags[id_count].after(document.createElement("hr"))
    }
}

inject_horizontal_rules(document.getElementById("markdown-content"))