/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./resources/js/pages/dashboard-job.init.js ***!
  \**************************************************/
/*
Template Name: HardwareHouse - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Dashboard job Init Js File
*/
// get colors array from the string
function getChartColorsArray(chartId) {
  if (document.getElementById(chartId) !== null) {
      var colors = document.getElementById(chartId).getAttribute("data-colors");
      
      if (colors) {
          colors = JSON.parse(colors);
          return colors.map(function (value) {
              var newValue = value.replace(" ", "");
              if (newValue.indexOf(",") === -1) {
                  var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                  
                  if (color){
                    color = color.replace(" ", "");
                    return color;
                  }
                  else return newValue;;
              } else {
                  var val = value.split(',');
                  if (val.length == 2) {
                      var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                      rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                      return rgbaColor;
                  } else {
                      return newValue;
                  }
              }
          });
      }
  }
} // Job View


var areacharteathereumColors = getChartColorsArray("eathereum_sparkline_charts");

if (areacharteathereumColors) {
  var options = {
    series: [{
      name: "Job View",
      data: [36, 21, 65, 22, 35, 50, 87, 98]
    }],
    chart: {
      width: 130,
      height: 46,
      type: "area",
      sparkline: {
        enabled: true
      },
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "smooth",
      width: 1.5
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        inverseColors: false,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [50, 100, 100, 100]
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(seriesName) {
            return '';
          }
        }
      },
      marker: {
        show: false
      }
    },
    colors: areacharteathereumColors
  };
  var chart = new ApexCharts(document.querySelector("#eathereum_sparkline_charts"), options);
  chart.render();
} // new_application_charts


var newApplicationColors = getChartColorsArray("new_application_charts");

if (newApplicationColors) {
  var options = {
    series: [{
      name: "New Application",
      data: [36, 48, 10, 74, 35, 50, 70, 73]
    }],
    chart: {
      width: 130,
      height: 46,
      type: "area",
      sparkline: {
        enabled: true
      },
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "smooth",
      width: 1.5
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        inverseColors: false,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [50, 100, 100, 100]
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(seriesName) {
            return '';
          }
        }
      },
      marker: {
        show: false
      }
    },
    colors: newApplicationColors
  };
  var chart = new ApexCharts(document.querySelector("#new_application_charts"), options);
  chart.render();
} // total_approved_charts


var totalApprovedColors = getChartColorsArray("total_approved_charts");

if (totalApprovedColors) {
  var options = {
    series: [{
      name: "Total Approved",
      data: [60, 14, 5, 60, 30, 43, 65, 84]
    }],
    chart: {
      width: 130,
      height: 46,
      type: "area",
      sparkline: {
        enabled: true
      },
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "smooth",
      width: 1.5
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        inverseColors: false,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [50, 100, 100, 100]
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(seriesName) {
            return '';
          }
        }
      },
      marker: {
        show: false
      }
    },
    colors: totalApprovedColors
  };
  var chart = new ApexCharts(document.querySelector("#total_approved_charts"), options);
  chart.render();
} // total_rejected_charts


var totalApprovedColors = getChartColorsArray("total_rejected_charts");

if (totalApprovedColors) {
  var options = {
    series: [{
      name: "Total Rejected",
      data: [32, 22, 7, 55, 20, 45, 36, 20]
    }],
    chart: {
      width: 130,
      height: 46,
      type: "area",
      sparkline: {
        enabled: true
      },
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: "smooth",
      width: 1.5
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        inverseColors: false,
        opacityFrom: 0.45,
        opacityTo: 0.05,
        stops: [50, 100, 100, 100]
      }
    },
    tooltip: {
      fixed: {
        enabled: false
      },
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function formatter(seriesName) {
            return '';
          }
        }
      },
      marker: {
        show: false
      }
    },
    colors: totalApprovedColors
  };
  var chart = new ApexCharts(document.querySelector("#total_rejected_charts"), options);
  chart.render();
}

var ApplicationReveicedTimeColors = getChartColorsArray("application-received-time");

if (ApplicationReveicedTimeColors) {
  var options = {
    series: [{
      name: 'Received Application',
      data: [34, 44, 54, 21, 12, 43, 33, 80, 66]
    }],
    chart: {
      type: 'line',
      height: 378,
      toolbar: {
        show: false
      }
    },
    // stroke: {
    //     curve: 'stepline',
    // },
    stroke: {
      width: 3,
      curve: 'smooth'
    },
    labels: ['8 PM', '9 PM', '10 PM', '11 PM', '12 PM', '1 AM', '2 AM', '3 AM', '4 AM'],
    dataLabels: {
      enabled: false
    },
    colors: ApplicationReveicedTimeColors,
    markers: {
      hover: {
        sizeOffset: 4
      }
    }
  };
  var chart = new ApexCharts(document.querySelector("#application-received-time"), options);
  chart.render();
}
/******/ })()
;