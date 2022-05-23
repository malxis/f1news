// Get the URL params
var params = window.location.pathname.split("/");
// Get only the part of the URL we want to use
params.splice(0, params.indexOf(pageWanted));

var container = document.getElementById("container");
var containerSpinner = document.getElementById("container-spinner");

var pageTitle = document.getElementById("page-title");
var pageDescription = document.getElementById("page-description");
var pageImage = document.getElementById("page-image");

// Request for GET page info from F1 API
var request = new XMLHttpRequest();
request.onreadystatechange = function () {
    if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        let responseF1 = JSON.parse(this.responseText);
        var pageWikipediaLink = 0;
        switch (pageWanted) {
            case "circuits":
                pageWikipediaLink = responseF1.MRData.CircuitTable.Circuits[0].url.split("/")[4];
                break;
            case "drivers":
                pageWikipediaLink = responseF1.MRData.DriverTable.Drivers[0].url.split("/")[4];
                break;
            case "constructors":
                pageWikipediaLink = responseF1.MRData.ConstructorTable.Constructors[0].url.split("/")[4];
                break;
            default:
                pageWikipediaLink = responseF1.MRData.CircuitTable.Circuits[0].url.split("/")[4];
        }

        // Request for GET french wikipedia page link from wikipedia API
        var requestFrenchPageLink = new XMLHttpRequest();
        requestFrenchPageLink.onreadystatechange = function () {
            if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                let responseFrenchPageLink = JSON.parse(this.responseText);

                if (responseFrenchPageLink.query.pages[Object.keys(responseFrenchPageLink.query.pages)[0]].hasOwnProperty("langlinks")) {
                    let wikipediaFrenchLink = encodeURIComponent(responseFrenchPageLink.query.pages[Object.keys(responseFrenchPageLink.query.pages)[0]].langlinks[0]["*"].replaceAll(" ", "_"));

                    // Request for GET french wikipedia page from wikipedia API
                    var requestFrenchPage = new XMLHttpRequest();
                    requestFrenchPage.onreadystatechange = function () {
                        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                            let responseFrenchPage = JSON.parse(this.responseText);

                            // Request for GET page image from wikipedia API
                            var requestImage = new XMLHttpRequest();
                            requestImage.onreadystatechange = function () {
                                if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                                    let responseImage = JSON.parse(this.responseText);

                                    // Image
                                    let responseImageObject = responseImage.query.pages[Object.keys(responseImage.query.pages)[0]].original;
                                    //console.log(responseImageObject);
                                    // Page Wikipedia
                                    let responseFrenchPageObject = responseFrenchPage.query.pages[Object.keys(responseFrenchPage.query.pages)[0]];
                                    //console.log(responseFrenchPageObject);
                                    // page API F1
                                    var responseF1Object = 0;
                                    switch (pageWanted) {
                                        case "circuits":
                                            responseF1Object = responseF1.MRData.CircuitTable;
                                            break;
                                        case "drivers":
                                            responseF1Object = responseF1.MRData.DriverTable;
                                            break;
                                        case "constructors":
                                            responseF1Object = responseF1.MRData.ConstructorTable;
                                            break;
                                        default:
                                            responseF1Object = responseF1.MRData.CircuitTable;
                                    }
                                    //console.log(responseF1Object);

                                    container.removeChild(containerSpinner);
                                    container.classList.remove("text-center");

                                    pageTitle.innerHTML = responseFrenchPageObject.title;
                                    pageDescription.innerHTML = getWikipediaDescription(responseFrenchPageObject.extract);
                                    pageImage.setAttribute("alt", "Photo de" + responseFrenchPageObject.title);

                                    if (responseImageObject != undefined)
                                        pageImage.setAttribute("src", responseImageObject.source);
                                }
                            };

                            // Get image of page from wikipedia API
                            requestImage.open("GET", "https://fr.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&piprop=original&origin=*&titles=" + wikipediaFrenchLink);
                            requestImage.send();
                        }
                    };

                    // Get french wikipedia page data from wikipedia API
                    requestFrenchPage.open("GET", "https://fr.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exlimit=max&exsentences=20&explaintext&origin=*&titles=" + wikipediaFrenchLink);
                    requestFrenchPage.send();
                } else {
                    container.removeChild(containerSpinner);
                    container.classList.remove("text-center");

                    pageDescription.classList.add("text-center");
                    pageDescription.innerHTML = "La description de cette page sera disponible prochainement...";
                }
            }
        };
        // Get french wikipedia link from wikipedia API
        requestFrenchPageLink.open("GET", "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=langlinks&titles=" + pageWikipediaLink + "&lllang=fr&origin=*");
        requestFrenchPageLink.send();

    }
};
// Get content for page inputed from F1 API
request.open("GET", "https://ergast.com/api/f1/" + pageWanted + "/" + params[1] + ".json");
request.send();



function getWikipediaDescription(wikipediaText){
    if(wikipediaText.includes("==")){
        let texts = wikipediaText.split("==");
        return texts[0];
    } else {
        return wikipediaText;
    }
}