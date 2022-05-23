function dateEnglishToFrench(date){
    var dateToProcess = new  Date(date), month = dateToProcess.getMonth() + 1, day = dateToProcess.getDate(), year = dateToProcess.getFullYear();

    month = month.toString();
    day = day.toString();

    if(month.length < 2)
        month = '0' + month;

    if(day.length < 2)
        day = '0' + day;


    return day + "/" + month + "/" + year;
}