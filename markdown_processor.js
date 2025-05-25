console.log("hi")

document.getElementById("foot").innerHTML += "<h1>HI THERE</h1>";

function make_toc(){

}

function inject_header_ids(content){
    var h1_tags = content.querySelectorAll("h1, h2")/*getElementsByTagName("h1") + content.getElementsByTagName("h2")*/
    for(var id_count = 0; id_count < h1_tags.length; id_count++){
        console.log(h1_tags[id_count])
    }
}

function process_md(content){
    console.log(content)
    inject_header_ids(content)
}

process_md(document.getElementById("markdown-content"))