google.charts.load('current', {'packages':['table']});
google.charts.setOnLoadCallback(getData);
//google.charts.setOnLoadCallback(drawTable);

function getData() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj;
            try {
               responseObj = JSON.parse(this.responseText);
            } catch(error){
                console.log('Error parsing JSON:', error, this.responseText);
            }

            if (responseObj.error == true) {
                document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">' + responseObj.message + '</span>';
                //document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Error.</span>';
            } else {
                //document.querySelector("#submitFeedback").innerHTML = "This is actually executing.";
                drawTable(responseObj.data);
                document.querySelector("#submitFeedback").innerHTML = "";
            }
        } else {
            document.querySelector("#submitFeedback").innerHTML = "Ready State: " + this.readyState + "  Status: " + this.status + "  Response: " + this.responseText;
        }
    };

    xhttp.open("GET", "api/getSongsByEventID", true);
    xhttp.setRequestHeader('Accept', 'application/json');
    xhttp.send();

}
function drawTable(someData) {

    var data = new google.visualization.DataTable();

    //data.addColumn('string', 'id');
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Artist');
    data.addColumn('string', 'Length');

    data.addRows(someData);

    var table = new google.visualization.Table(document.getElementById('table_div'));

    table.getSelection(1, 4);

    table.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});


}