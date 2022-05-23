// Get the full URL and the path
var url = (window.location.origin + window.location.pathname);
var path = (window.location.pathname);
path = path.substring(0, path.indexOf("races"));
url = url.substring(0, url.indexOf("races"));
//console.log(path);

// Get the URL params
var params = window.location.pathname.split("/");
// Get only the part of the URL we want to use
params.splice(0, params.indexOf("races"));

var container = document.getElementById("container");
var containerSpinner = document.getElementById("container-spinner");

function showRaceInfo(raceData) {

    var title = document.createElement("h2");
    title.classList.add("text-center");
    title.innerHTML = "Course n°" + raceData.round + " - " + raceData.raceName + " - " + dateEnglishToFrench(raceData.date);
    container.appendChild(title);

    var info = document.createElement("p");
    info.classList.add("text-center");
    info.innerHTML = 'Lieu : <a href="' + path + 'circuits/' + raceData.Circuit.circuitId + '">' + raceData.Circuit.circuitName + '</a> (' + raceData.Circuit.Location.country + ')';
    container.appendChild(info);

    var tableContainer = document.createElement("div");
    tableContainer.classList.add("table-responsive");

    // Table with head
    var table = document.createElement("table");
    table.classList.add("table", "table-striped", "border");

    let thead = document.createElement("thead");
    let trhead = document.createElement("tr");

    let thrpos = document.createElement("th");
    thrpos.innerHTML = "Position"
    trhead.appendChild(thrpos);

    let thrpilot = document.createElement("th");
    thrpilot.innerHTML = "Pilote"
    trhead.appendChild(thrpilot);

    let thrconstructor = document.createElement("th");
    thrconstructor.innerHTML = "Écurie"
    trhead.appendChild(thrconstructor);

    let thrtime = document.createElement("th");
    thrtime.innerHTML = "Temps"
    trhead.appendChild(thrtime);

    let thrpoints = document.createElement("th");
    thrpoints.innerHTML = "Points"
    trhead.appendChild(thrpoints);

    thead.appendChild(trhead);
    table.appendChild(thead);

    let tableBody = document.createElement("tbody");
    table.appendChild(tableBody);

    for (let raceInfo of raceData.Results) {
        //console.log(raceInfo);
        let tr = document.createElement("tr");

        let pos = document.createElement("td");
        pos.appendChild(document.createTextNode(raceInfo.position));
        tr.appendChild(pos);

        let pilot = document.createElement("td");
        let pilotLink = document.createElement("a");

        if (raceInfo.Driver.hasOwnProperty("permanentNumber"))
            pilotLink.appendChild(document.createTextNode(raceInfo.Driver.givenName + " " + raceInfo.Driver.familyName + "  #" + raceInfo.Driver.permanentNumber));
        else
            pilotLink.appendChild(document.createTextNode(raceInfo.Driver.givenName + " " + raceInfo.Driver.familyName));

        pilotLink.setAttribute("href", url + "drivers/" + raceInfo.Driver.driverId);
        pilot.appendChild(pilotLink);
        tr.appendChild(pilot);

        let constructor = document.createElement("td");
        let constructorLink = document.createElement("a");
        constructorLink.appendChild(document.createTextNode(raceInfo.Constructor.name));
        constructorLink.setAttribute("href", url + "constructors/" + raceInfo.Constructor.constructorId);
        constructor.appendChild(constructorLink);
        tr.appendChild(constructor);

        let time = document.createElement("td");
        if (raceInfo.hasOwnProperty("Time"))
            time.appendChild(document.createTextNode(raceInfo.Time.time));
        else {
            if (raceInfo.status.charAt(0) == "+")
                time.appendChild(document.createTextNode(raceInfo.status));
            else
                time.appendChild(document.createTextNode("Abandon - " + raceInfo.status));
        }

        tr.appendChild(time);

        let points = document.createElement("td");
        points.appendChild(document.createTextNode(raceInfo.points));
        tr.appendChild(points);

        tableBody.appendChild(tr);
    }

    container.removeChild(containerSpinner);
    container.classList.remove("text-center");

    tableContainer.appendChild(table);
    container.appendChild(tableContainer);
}

function showFutureRace(raceData) {
    container.removeChild(containerSpinner);
    container.classList.remove("text-center");

    var title = document.createElement("h2");
    title.classList.add("text-center");
    title.innerHTML = "Course n°" + raceData.round + " - " + raceData.season;
    container.appendChild(title);

    var info = document.createElement("p");
    info.classList.add("text-center");
    info.innerHTML = "Prochainement...";
    container.appendChild(info);
}

// Request for GET race info
var request = new XMLHttpRequest();
request.onreadystatechange = function () {
    if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        var response = JSON.parse(this.responseText);

        if (response.MRData.total > 0)
            showRaceInfo(response.MRData.RaceTable.Races[0]);
        else
            showFutureRace(response.MRData.RaceTable);

        //console.log(response);
    }
};
request.open("GET", "https://ergast.com/api/f1/" + params[1] + "/" + params[2] + "/results.json");
request.send();