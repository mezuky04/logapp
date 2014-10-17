var buyers = document.getElementById('active-users-chart').getContext('2d');

var buyerData = {
    labels : ["Sunday","Monday","Tuesday","Wednesday","Thursday", "Friday", "Saturday"],
    datasets : [
        {
            fillColor : "rgba(172,194,132,0.4)",
            strokeColor : "#ACC26D",
            pointColor : "#fff",
            pointStrokeColor : "#9DB86D",
            data : [203,156,99,251,305,247,590]
        }
    ]
}
var options = {
    scaleShowGridLines : false,
    bezierCurve : true,
    bezierCurveTension : 0.4,
    pointDotRadius : 6
//        pointHitDetectionRadius : 30

}
new Chart(buyers).Line(buyerData, options);