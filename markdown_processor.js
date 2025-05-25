console.log("hi")

document.getElementById("foot").innerHTML += "<h1>HI THERE</h1>";

function make_toc(){

}

function inject_header_ids(content){
    h1_tags = content.getElementsByTagName("h1")
    for(var id_count = 0; i < h1_tags.length; i++){
        console.log(h1_tags[id_count])
    }
}

function process_md(content){
    console.log(content)
}

process_md(document.getElementById("markdown-content"))