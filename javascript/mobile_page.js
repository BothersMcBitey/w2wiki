function enable_modible_mode(){
    //toolbar    
    document.getElementById("left").style.display = "none";
    document.getElementById("right").style.display = "none";

    //titlebar
    document.getElementById("logo").style.display = "none";
    document.getElementById("edittime").style.display = "none";
    document.getElementById("titledate").style.display = "none";
}

enable_modible_mode();