function enable_modible_mode(){
    //toolbar    
    document.getElementById("left").style.display = "none";
    document.getElementById("right").style.display = "none";

    //titlebar
    document.getElementById("logo").style.display = "none";
    document.getElementById("edittime").style.display = "none";
    document.getElementById("titledate").style.display = "none";

    //Body
    /*document.getElementById("main").style.width = "90%";
    document.getElementById("main").style.paddingLeft ="5%";
    document.getElementById("main").style.paddingRight = "5%";*/
    /*document.getElementById("main").style = `
        background-color: white;
        padding-top: 1em;
        padding-bottom: 1em;
        margin: 0 10vw;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-size: 1rem;
        width: 90%;
        padding-left: 5%;
        padding-right: 5%;`
    document.getElementById("padding").style.width = "90%";
    document.getElementById("padding").style.paddingLeft ="5%";
    document.getElementById("padding").style.paddingRight = "5%";*/


}

enable_modible_mode();