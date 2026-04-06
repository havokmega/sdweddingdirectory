$ = jQuery;

var optionDonut = {
  chart: {
      type: 'donut',
      width: '100%',
      height: 300
  },
  dataLabels: {
      enabled: false,
  },
  plotOptions: {
      pie: {
          customScale: 0.8,
          donut: {
              size: '75%',
          },
          offsetY: 0,
      },
      stroke: {
          colors: undefined
      }
  },
  title: {
      style: {
          fontSize: '18px'
      }
  },
  series: [21, 23, 19, 14, 6],
  labels: ['Venue', 'Catering', 'Food Items', 'Cakes', 'Transportation'],
  legend: {
      position: 'bottom',
      offsetY: 0
  }
}

var donut = new ApexCharts(
  document.querySelector("#donut"),
  optionDonut
)
donut.render();


// on smaller screen, change the legends position for donut
var mobileDonut = function() {
  if ($(window).width() < 768) {
      donut.updateOptions({
          plotOptions: {
              pie: {
                  offsetY: -15,
              }
          },
          legend: {
              position: 'bottom'
          }
      }, false, false)
  } else {
      donut.updateOptions({
          plotOptions: {
              pie: {
                  offsetY: 20,
              }
          },
          legend: {
              position: 'bottom'
          }
      }, false, false)
  }
}

$(window).resize(function() {
  mobileDonut()
});