console.log("hi")

document.getElementById("foot").innerHTML += "<h1>HI THERE</h1>";

function make_toc(){

}

function inject_header_ids(content){
    var h_tags = content.querySelectorAll("h1, h2, h3, h4, h5, h6")
    for(var id_count = 0; id_count < h_tags.length; id_count++){
        console.log(h_tags[id_count])
        h_name = h_tags[id_count].innerHTML
        h_name = hname.replace(/\s/g, "-")
        h_tags[id_count].id = h_name
    }
}

function process_md(content){
    console.log(content)
    inject_header_ids(content)
}

process_md(document.getElementById("markdown-content"))