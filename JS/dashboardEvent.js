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
//function drawTable() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('string', 'Date');
    data.addColumn('string', 'Requests');

    data.addRows(someData);

    // data.addColumn('string', 'Name');
    // data.addColumn('number', 'Salary');
    // data.addColumn('boolean', 'Full Time Employee');
    //
    // data.addRows([
    //     ['Mike',  {v: 10000, f: '$10,000'}, true],
    //     ['Jim',   {v:8000,   f: '$8,000'},  false],
    //     ['Alice', {v: 12500, f: '$12,500'}, true],
    //     ['Bob',   {v: 7000,  f: '$7,000'},  true]
    // ]);

    var table = new google.visualization.Table(document.getElementById('table_div'));

    table.draw(data, {showRowNumber: true, width: '100%', height: '100%', page: 'enable', pageSize: 20, allowHtml: true, showRowNumber: false});
}


const addEvent = function (){
    //document.querySelector("#submitFeedback").innerHTML = "TEST";
    let name = document.querySelector("#name").value;
    let state = document.querySelector("#state")[document.querySelector("#state").selectedIndex].innerHTML;
    let date = document.querySelector("#date").value;
    console.log(date);
    let assocArray = {name: name, state: state, date: date};
    let JSONpayload = JSON.stringify(assocArray);

    $.post("event", {JSONpayload: JSONpayload}, function (response){
        //$("#submitFeedback").html(response);
        try {
            let responseObj = JSON.parse(response);
            if (responseObj.error == true) {
                console.log('Post success error true');
                document.querySelector("#submitFeedback").innerHTML = responseObj.message;
                //document.querySelector("#submitFeedback").innerHTML = "TRUE";
            } else {
                console.log('Post success error false');
                console.log(responseObj.message);
                document.querySelector("#submitFeedback").innerHTML = responseObj.message;
                //document.querySelector("#submitFeedback").innerHTML = "FALSE";
            }
        } catch (e) {
            //$("#submitFeedback").html(response);
            document.querySelector("#submitFeedback").innerHTML = "Caught Exception." + response;
        }
    })
        .done(function() {
            //alert( "second success" );
            google.charts.setOnLoadCallback(getEvents);
        })
        .fail(function(xhr, status, error) {
            document.querySelector("#submitFeedback").innerHTML = "Fail.";
            console.log("post fail");
            //console.log(xhr);
            console.log(status);
            console.log(error);
            console.log(xhr.responseText);
            //alert( "error" );
        })
        .always(function() {
            //alert( "finished" );
        });
}


const submitButton = document.querySelector("#btnAddEvent");
submitButton.addEventListener("click", ()=>{addEvent()}, false);

