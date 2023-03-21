google.charts.load('current', {'packages':['table']});
google.charts.setOnLoadCallback(getData);
//google.charts.setOnLoadCallback(drawTable);

function getData(){
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

    xhttp.open("GET", "api/getSongs", true);
    xhttp.setRequestHeader('Accept', 'application/json');
    xhttp.send();

    var xhttp2 = new XMLHttpRequest();
    xhttp2.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(this.responseText);
            if(responseObj.error  == true){
                document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">'+responseObj.message+'</span>';
                //document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Error.</span>';
            }else{
                //document.querySelector("#submitFeedback").innerHTML = "This is actually executing.";
                drawTable02(responseObj.data);
                document.querySelector("#submitFeedback").innerHTML = "";
            }
        }else{
            document.querySelector("#submitFeedback").innerHTML = "Ready State: "+this.readyState+"  Status: "+this.status+ "  Response: "+this.responseText;
        }
    };

    xhttp2.open("GET", "api/getSongs", true);
    xhttp2.setRequestHeader('Accept', 'application/json');
    xhttp2.send();

    var xhttp3 = new XMLHttpRequest();
    xhttp3.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(this.responseText);
            if(responseObj.error  == true){
                document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">'+responseObj.message+'</span>';
                //document.querySelector("#submitFeedback").innerHTML = '<span class="text-danger">Error.</span>';
            }else{
                //document.querySelector("#submitFeedback").innerHTML = "This is actually executing.";
                drawTable03(responseObj.data);
                document.querySelector("#submitFeedback").innerHTML = "";
            }
        }else{
            document.querySelector("#submitFeedback").innerHTML = "Ready State: "+this.readyState+"  Status: "+this.status+ "  Response: "+this.responseText;
        }
    };

    xhttp3.open("GET", "api/getSongs", true);
    xhttp3.setRequestHeader('Accept', 'application/json');
    xhttp3.send();


}
function drawTable(someData) {
//function drawTable() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('string', 'Artist');
    data.addColumn('string', 'Length');

    data.addRows(someData);

    var table01 = new google.visualization.Table(document.getElementById('songs_table_div'));

    table01.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});
}

function drawTable02(someData) {
//function drawTable() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('string', 'Artist');
    data.addColumn('string', 'Length');

    data.addRows(someData);

    var table02 = new google.visualization.Table(document.getElementById('playlist_table_div'));

    table02.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});
}

function drawTable03(someData) {
//function drawTable() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('string', 'Artist');
    data.addColumn('string', 'Length');

    data.addRows(someData);

    var table03 = new google.visualization.Table(document.getElementById('requests_table_div'));

    table03.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});
}


