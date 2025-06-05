function inject_horizontal_rules(content){
    // major lines
    var h1_tags = content.querySelectorAll("h1")    
    for(var id_count = 0; id_count < h1_tags.length; id_count++){
        let hr =document.createElement("hr");
        hr.classList.add("major")
        h1_tags[id_count].after(hr);
    }
    // minor lines
    var h2_tags = content.querySelectorAll("h2")   
    for(var id_count = 0; id_count < h2_tags.length; id_count++){
        let hr =document.createElement("hr");
        hr.classList.add("minor")
        h2_tags[id_count].after(hr);
    }
}

inject_horizontal_rules(document.getElementById("markdown-content"))