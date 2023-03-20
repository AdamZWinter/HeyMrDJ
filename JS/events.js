google.charts.load('current', {'packages':['table']});
google.charts.setOnLoadCallback(drawTable);

function drawTable() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Name');
    data.addColumn('number', 'Salary');
    data.addColumn('boolean', 'Full Time Employee');
    data.addRows();

    var table = new google.visualization.Table(document.getElementById('table_div'));

    table.draw(data, {width: '100%', height: '100%'});
}