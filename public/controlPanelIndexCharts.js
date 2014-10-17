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

// User browser pie chart
var usedBrowsers = document.getElementById('used-browsers').getContext('2d');
var browsersData = [
    {
        value: 300,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Red"
    },
    {
        value: 50,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Green"
    },
    {
        value: 100,
        color: "#FDB45C",
        highlight: "#FFC870",
        label: "Yellow"
    }
]
new Chart(usedBrowsers).Pie(browsersData,options);

// Active subscriptions doughnut chart
var activeSubscriptions = document.getElementById('active-subscriptions').getContext('2d');
var subscriptionsData = [
    {
        value: 50,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Green"
    },
    {
        value: 100,
        color: "#FDB45C",
        highlight: "#FFC870",
        label: "Yellow"
    }
];

new Chart(activeSubscriptions).Doughnut(subscriptionsData, options);
