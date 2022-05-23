const raceFinishedContainer = document.getElementsByClassName("race-finished");
const raceComingContainer = document.getElementsByClassName("race-coming");

function displayRaceList(raceList, parentElementList){
    for(let race of raceList){

        let asideNewsListItem = createAsideNewsListItem(race.raceName, race.date);

        for(let item of parentElementList){
            item.appendChild(asideNewsListItem.cloneNode(true));
        }
    }
}

function displayNoRace(parentElementList){
    
    let asideNewsListItem = createAsideNewsListItem("<i>Aucune course</i>", "");

    for(let item of parentElementList){
        item.appendChild(asideNewsListItem.cloneNode(true));
    }

}

function createAsideNewsListItem(text, date){
    let raceFinishedSmallText = document.createElement("small");
    raceFinishedSmallText.classList.add("text-muted","float-end");
    if(date != ""){
        raceFinishedSmallText.innerHTML = dateEnglishToFrench(date);
    }

    let raceFinishedListItem = document.createElement("li");
    raceFinishedListItem.classList.add("list-group-item");
    raceFinishedListItem.innerHTML = text + "<br>";

    raceFinishedListItem.appendChild(raceFinishedSmallText);

    return raceFinishedListItem;
}

let request = new XMLHttpRequest();
request.onreadystatechange = function() {
    if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
        let response = JSON.parse(this.responseText);

        let raceFinishedNumber = 0;
        let raceComingNumber = 0;

        let raceFinishedList = [];
        let raceComingList = [];

        for(let race of response.MRData.RaceTable.Races){

            let raceDate = new Date(race.date);
            let dateNow = new Date();

            // Race finished
            if(raceDate < dateNow){
                raceFinishedList.push(race);
                raceFinishedNumber++;
                if(raceFinishedNumber > 5){
                    raceFinishedList.shift();
                }
            } 
            
            // Race coming
            else {
                if(raceComingNumber < 5){
                    raceComingList.push(race);
                    raceComingNumber++;
                }
            }
        }

        // Reverse the order of the race finished array to display the last finished race in first position
        raceFinishedList.reverse();

        // Create and display the past 5 race finished
        if(raceFinishedList.length > 0){
            displayRaceList(raceFinishedList, raceFinishedContainer);
        } else {
            displayNoRace(raceFinishedContainer);
        }

        // Create and display the next 5 race coming
        if(raceComingList.length > 0){
            displayRaceList(raceComingList, raceComingContainer);
        } else {
            displayNoRace(raceComingContainer);
        }
    }
};
request.open("GET", "https://ergast.com/api/f1/current.json");
request.send();