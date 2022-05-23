// Get the full URL
var url = (window.location.origin + window.location.pathname);
url = url.substring(0, url.indexOf("calendar"));

// Get the URL params
var params = window.location.pathname.split("/");
// Get only the part of the URL we want to use
params.splice(0, params.indexOf("calendar"));

var yearWanted = 0;
if (params[1] == null)
    yearWanted = new Date().getFullYear(); // If no params for the year, we set the current year by default
else
    yearWanted = params[1];

var container = document.getElementById("container");

let tableCalendarContainer = document.createElement("div");
tableCalendarContainer.classList.add("table-responsive");
var tableCalendar = document.createElement("table");
let tableCalendarTitle = document.createElement("h1");

let tablePilotStadingContainer = document.createElement("div");
tablePilotStadingContainer.classList.add("table-responsive");
var tablePilotStading = document.createElement("table");
let tablePilotStandingTitle = document.createElement("h1");

let tableConstructorStandingContainer = document.createElement("div");
tableConstructorStandingContainer.classList.add("table-responsive");
var tableConstructorStanding = document.createElement("table");
var tableConstructorStandingTitle = document.createElement("h1");

var spinner = document.createElement("div");
spinner.classList.add("text-center");
var spinnerItem = document.createElement("div");
spinnerItem.classList.add("spinner-border", "text-primary");
spinner.appendChild(spinnerItem);


// Show the select menu for select the wanted year of the calendar
var requestSeason = new XMLHttpRequest();
requestSeason.onreadystatechange = function () {
    if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        var responseSeason = JSON.parse(this.responseText);

        var selectSeason = document.createElement("select");
        selectSeason.classList.add("form-select", "mb-3", "mt-2");

        for (let season of responseSeason.MRData.SeasonTable.Seasons) {
            let optionseason = document.createElement("option");
            optionseason.innerHTML = season.season;
            if (season.season == yearWanted) {
                optionseason.setAttribute("selected", true);
            }
            selectSeason.appendChild(optionseason);
        }

        container.appendChild(selectSeason);

        selectSeason.addEventListener("change", function () {
            requestCalendar(selectSeason.value);
            requestDriversStanding(selectSeason.value);
            requestConstructorsStanding(selectSeason.value);

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", url + "set_year_looked_at", true);
            xhttp.setRequestHeader("Content-Type", "application/json");
            var data = {
                year: selectSeason.value
            };
            xhttp.send(JSON.stringify(data));

        });

        // Display the year wanted for the first calendar display
        requestCalendar(yearWanted);
        requestDriversStanding(yearWanted);
        requestConstructorsStanding(yearWanted);
    }
};
requestSeason.open("GET", "http://ergast.com/api/f1/seasons.json?limit=1000");
requestSeason.send();



// Function to get calendar of a specific year
function requestCalendar(year) {
    if (container.contains(tableCalendarContainer)) {
        container.removeChild(tableCalendarContainer);
        tableCalendar.innerHTML = "";
    }
    if (container.contains(tableCalendarTitle)) {
        container.removeChild(tableCalendarTitle);
        tableCalendarTitle.innerHTML = "";
    }
    container.appendChild(spinner);
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
            let response = JSON.parse(this.responseText);
            container.classList.remove("spinner-border", "text-primary", "d-flex", "justify-content-center");

            tableCalendarTitle.classList.add("text-center");
            tableCalendarTitle.innerHTML = "Calendrier " + year;
            container.appendChild(tableCalendarTitle);

            tableCalendar.classList.add("table", "table-striped", "border");

            let thead = document.createElement("thead");
            let trhead = document.createElement("tr");

            let thrnom = document.createElement("th"); // Nom (Lieu-Pays)
            thrnom.innerHTML = "Nom"
            trhead.appendChild(thrnom);

            let thrlieu = document.createElement("th");
            thrlieu.innerHTML = "Lieu"
            trhead.appendChild(thrlieu);

            let trhdate = document.createElement("th");
            trhdate.innerHTML = "Date"
            trhead.appendChild(trhdate);

            if (response.MRData.RaceTable.Races[0].hasOwnProperty("time")) {
                let thrhour = document.createElement("th");
                thrhour.innerHTML = "Heure"
                trhead.appendChild(thrhour);
            }

            thead.appendChild(trhead);
            tableCalendar.appendChild(thead);

            let tableBody = document.createElement("tbody");
            tableCalendar.appendChild(tableBody);

            for (let race of response.MRData.RaceTable.Races) {
                //console.log(race);
                let tr = document.createElement("tr");

                let raceName = document.createElement("td");
                let raceNameLink = document.createElement("a");
                raceNameLink.setAttribute("href", url + "races/" + race.season + "/" + race.round);
                raceNameLink.appendChild(document.createTextNode(race.raceName));
                raceName.appendChild(raceNameLink);
                tr.appendChild(raceName);

                let circuitName = document.createElement("td");
                let circuitNameLink = document.createElement("a");
                circuitNameLink.setAttribute("href", url + "circuits/" + race.Circuit.circuitId)
                circuitNameLink.appendChild(document.createTextNode(race.Circuit.circuitName + " - " + race.Circuit.Location.country));
                circuitName.appendChild(circuitNameLink);
                tr.appendChild(circuitName);

                let date = document.createElement("td");
                date.appendChild(document.createTextNode(dateEnglishToFrench(race.date)));
                tr.appendChild(date);

                if (race.hasOwnProperty("time")) {
                    let time = document.createElement("td");
                    raceHour = new Date(race.date + " " + race.time);

                    time.appendChild(document.createTextNode(raceHour.toLocaleTimeString("fr-FR").substring(0, 5)));
                    tr.appendChild(time);
                }

                tableBody.appendChild(tr);
            }
            tableCalendarContainer.appendChild(tableCalendar)
            container.appendChild(tableCalendarContainer);
            container.removeChild(spinner);
        }
    };
    request.open("GET", "https://ergast.com/api/f1/" + year + ".json");
    request.send();
}

// Function to get pilot standing of a specific year
function requestDriversStanding(year) {
    if (container.contains(tablePilotStadingContainer)) {
        container.removeChild(tablePilotStadingContainer);
        tablePilotStading.innerHTML = "";
    }
    if (container.contains(tablePilotStandingTitle)) {
        container.removeChild(tablePilotStandingTitle);
        tablePilotStandingTitle.innerHTML = "";
    }
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
            let response = JSON.parse(this.responseText);

            tablePilotStandingTitle.classList.add("text-center");
            tablePilotStandingTitle.innerHTML = "Classement Pilote " + year;
            container.appendChild(tablePilotStandingTitle);

            tablePilotStading.classList.add("table", "table-striped", "border");

            let thead = document.createElement("thead");
            let trhead = document.createElement("tr");

            let thrPos = document.createElement("th");
            thrPos.innerHTML = "Pos"
            trhead.appendChild(thrPos);

            let thrDriver = document.createElement("th");
            thrDriver.innerHTML = "Pilote"
            trhead.appendChild(thrDriver);

            let thrConstructor = document.createElement("th");
            thrConstructor.innerHTML = "Écurie"
            trhead.appendChild(thrConstructor);

            let thrPoints = document.createElement("th");
            thrPoints.innerHTML = "Points"
            trhead.appendChild(thrPoints);

            let thrWins = document.createElement("th");
            thrWins.innerHTML = "Victoires"
            trhead.appendChild(thrWins);

            thead.appendChild(trhead);
            tablePilotStading.appendChild(thead);

            let tableBody = document.createElement("tbody");
            tablePilotStading.appendChild(tableBody);

            for (let pilotStanding of response.MRData.StandingsTable.StandingsLists[0].DriverStandings) {
                let tr = document.createElement("tr");

                let pos = document.createElement("td");
                pos.appendChild(document.createTextNode(pilotStanding.position));
                tr.appendChild(pos);

                let driver = document.createElement("td");
                let driverLink = document.createElement("a");
                if (pilotStanding.Driver.hasOwnProperty("permanentNumber"))
                    driverLink.appendChild(document.createTextNode(pilotStanding.Driver.givenName + " " + pilotStanding.Driver.familyName + "  #" + pilotStanding.Driver.permanentNumber));
                else
                    driverLink.appendChild(document.createTextNode(pilotStanding.Driver.givenName + " " + pilotStanding.Driver.familyName));

                driverLink.setAttribute("href", url + "drivers/" + pilotStanding.Driver.driverId);
                driver.appendChild(driverLink);
                tr.appendChild(driver);

                let constructor = document.createElement("td");
                if(pilotStanding.Constructors[0]) {
                    let constructorLink = document.createElement("a");
                    constructorLink.setAttribute("href", url + "constructors/" + pilotStanding.Constructors[0].constructorId)
                    constructorLink.appendChild(document.createTextNode(pilotStanding.Constructors[0].name));
                    constructor.appendChild(constructorLink);
                } else {
                    constructor.innerHTML = "-"
                }
                tr.appendChild(constructor);

                let points = document.createElement("td");
                points.appendChild(document.createTextNode(pilotStanding.points));
                tr.appendChild(points);

                let wins = document.createElement("td");
                wins.appendChild(document.createTextNode(pilotStanding.wins));
                tr.appendChild(wins);

                tableBody.appendChild(tr);
            }
            tablePilotStadingContainer.appendChild(tablePilotStading)
            container.appendChild(tablePilotStadingContainer);
        }
    };
    request.open("GET", "https://ergast.com/api/f1/" + year + "/driverStandings.json");
    request.send();
}

// Function to get constructor standing of a specific year
function requestConstructorsStanding(year) {
    if (container.contains(tableConstructorStandingContainer)) {
        container.removeChild(tableConstructorStandingContainer);
        tableConstructorStanding.innerHTML = "";
    }
    if (container.contains(tableConstructorStandingTitle)) {
        container.removeChild(tableConstructorStandingTitle);
        tableConstructorStandingTitle.innerHTML = "";
    }
    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
            let response = JSON.parse(this.responseText);

            if (response.MRData.StandingsTable.StandingsLists.length > 0) {

                tableConstructorStandingTitle.classList.add("text-center");
                tableConstructorStandingTitle.innerHTML = "Classement Constructeur " + year;
                container.appendChild(tableConstructorStandingTitle);

                tableConstructorStanding.classList.add("table", "table-striped", "border");

                let thead = document.createElement("thead");
                let trhead = document.createElement("tr");

                let thrPos = document.createElement("th");
                thrPos.innerHTML = "Pos"
                trhead.appendChild(thrPos);

                let thrConstructor = document.createElement("th");
                thrConstructor.innerHTML = "Écurie"
                trhead.appendChild(thrConstructor);

                let thrNationality = document.createElement("th");
                thrNationality.innerHTML = "Nationalité"
                trhead.appendChild(thrNationality);

                let thrPoints = document.createElement("th");
                thrPoints.innerHTML = "Points"
                trhead.appendChild(thrPoints);

                let thrWins = document.createElement("th");
                thrWins.innerHTML = "Victoires"
                trhead.appendChild(thrWins);

                thead.appendChild(trhead);
                tableConstructorStanding.appendChild(thead);

                let tableBody = document.createElement("tbody");
                tableConstructorStanding.appendChild(tableBody);

                for (let constructorStanding of response.MRData.StandingsTable.StandingsLists[0].ConstructorStandings) {
                    let tr = document.createElement("tr");

                    let pos = document.createElement("td");
                    pos.appendChild(document.createTextNode(constructorStanding.position));
                    tr.appendChild(pos);

                    let constructor = document.createElement("td");
                    let constructorLink = document.createElement("a");
                    constructorLink.setAttribute("href", url + "constructors/" + constructorStanding.Constructor.constructorId)
                    constructorLink.appendChild(document.createTextNode(constructorStanding.Constructor.name));
                    constructor.appendChild(constructorLink);
                    tr.appendChild(constructor);

                    let nationality = document.createElement("td");
                    nationality.appendChild(document.createTextNode(constructorStanding.Constructor.nationality));
                    tr.appendChild(nationality);

                    let points = document.createElement("td");
                    points.appendChild(document.createTextNode(constructorStanding.points));
                    tr.appendChild(points);

                    let wins = document.createElement("td");
                    wins.appendChild(document.createTextNode(constructorStanding.wins));
                    tr.appendChild(wins);

                    tableBody.appendChild(tr);
                }
                tableConstructorStandingContainer.appendChild(tableConstructorStanding);
                container.appendChild(tableConstructorStandingContainer);
            }
        }
    };
    request.open("GET", "https://ergast.com/api/f1/" + year + "/constructorStandings.json");
    request.send();
}