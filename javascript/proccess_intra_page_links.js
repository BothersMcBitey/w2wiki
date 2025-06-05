function inject_header_ids(content){
    var h_tags = content.querySelectorAll("h1, h2, h3, h4, h5, h6")
    for(var id_count = 0; id_count < h_tags.length; id_count++){
        h_name = h_tags[id_count].innerHTML
        h_name = h_name.replace(/\s/g, "-")
        h_tags[id_count].id = h_name.toLowerCase();
    }
}

function lower_markdown_links(content){
    var anchors = content.querySelectorAll("a")
    console.log("All Anchors =========================================")
    console.log(anchors)
    for(var a_count = 0; a_count < anchors.length; a_count++){
        console.log(anchors[a_count])
    }
}

let markdown_html = document.getElementById("markdown-content")
lower_markdown_links(markdown_html)
inject_header_ids(markdown_html)
