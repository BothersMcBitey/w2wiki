console.log("hi")

document.getElementById("foot").innerHTML += "<h1>HI THERE</h1>";

function make_toc(){

}

function inject_header_ids(content){
    var h12_tags = content.querySelectorAll("h1, h2")/*getElementsByTagName("h1") + content.getElementsByTagName("h2")*/
    for(var id_count = 0; id_count < h12_tags.length; id_count++){
        console.log(h12_tags[id_count])
        h12_tags[id_count].id = h12_tags[id_count].innerHTML
    }
}

function process_md(content){
    console.log(content)
    inject_header_ids(content)
}

process_md(document.getElementById("markdown-content"))