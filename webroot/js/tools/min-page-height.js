var minPageHeight = document.getElementById("min-page-height");
var header = document.getElementById("header");
var footer = document.getElementById("footer");
var panelAside = document.getElementById("panel-aside");

function setFooterBottom(){
    let PageHeight = window.innerHeight-(header.offsetHeight+footer.offsetHeight)-parseInt(window.getComputedStyle(minPageHeight).getPropertyValue("margin-top"))-parseInt(window.getComputedStyle(minPageHeight).getPropertyValue("margin-bottom"));

    if(panelAside != null)
        panelAside.style.minHeight = PageHeight + "px";

    minPageHeight.style.minHeight = PageHeight + "px";
}

setFooterBottom();

window.onresize = setFooterBottom;