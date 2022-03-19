/**
 * Load Weather app
 */
google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var records, data, options, tempChart, humidityChart;

    records = JSON.parse(document.getElementById('temperatureData').value);
    data = google.visualization.arrayToDataTable(records);

    options = {
        title: 'Office temperature',
        curveType: 'function',
        legend: {position: 'bottom'}
    };

    tempChart = new google.visualization.LineChart(document.getElementById('temperature_chart'));
    tempChart.draw(data, options);

    records = JSON.parse(document.getElementById('humidityData').value);
    data = google.visualization.arrayToDataTable(records);

    options = {
        title: 'Office humidity',
        curveType: 'function',
        legend: {position: 'bottom'}
    };

    humidityChart = new google.visualization.LineChart(document.getElementById('humidity_chart'));
    humidityChart.draw(data, options);
}

