google.charts.load('current', {'packages':['table']});
google.charts.setOnLoadCallback(getEvents);
//google.charts.setOnLoadCallback(drawTable);

function getEvents(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(this.responseText);
            if(responseObj.error  == true){
                document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">'+responseObj.message+'</span>';
                //document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Error.</span>';
            }else{
                //document.querySelector("#submitFeedback").innerHTML = "This is actually executing.";
                drawTable(responseObj.data);
                document.querySelector("#submitFeedback").innerHTML = "";
            }
        }else{
            document.querySelector("#submitFeedback").innerHTML = "Ready State: "+this.readyState+"  Status: "+this.status+ "  Response: "+this.responseText;
        }
    };

    xhttp.open("GET", "api/dashboard/getDJEvents", true);
    xhttp.setRequestHeader('Accept', 'application/json');
    xhttp.send();
}
function drawTable(someData) {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('string', 'Date');

    let tableArray = [];
    for(let eventArray of someData){
        let eventID = eventArray[0];
        let name = eventArray[1];
        let date = eventArray[2];
        let link = '<a class="listLink" href="playlist/'+eventID+'">'+name+'</a>';
        let newEventArray = [link, date];
        tableArray.push(newEventArray);
    }

    data.addRows(tableArray);


    var table = new google.visualization.Table(document.getElementById('table_div'));

    table.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});
}