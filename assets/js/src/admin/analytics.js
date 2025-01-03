'use strict';

(function ($) {
	let merchantChart = {
		impressionsChart: null,
		revenueChart: null,
		avgOrderValChart: null,
		columnChartOptions: {
			series: [
				{
					name: 'Data',
				},
			],
			chart: {
				type: 'bar',
				height: 350,
				stacked: false,
				toolbar: {
					show: false
				}
			},
			plotOptions: {
				bar: {
					columnWidth: '20%',
					// borderRadius: 5,
					borderRadius: 5,
					borderRadiusApplication: 'end',
					colors: {
						backgroundBarColors: ['#ebeffd'],
						backgroundBarRadius: 4,
					}
				},
			},
			colors: ['#3A63E9'],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: true,
				borderColor: '#D8D8D8',
				strokeDashArray: 5,
				position: 'back',
				xaxis: {
					lines: {
						show: true,
						offsetX: 60,
						style: {
							dashArray: 5,
						}
					}
				},
				yaxis: {
					lines: {
						show: false
					}
				}
			},
			xaxis: {
				axisTicks: {
					show: false
				},
				axisBorder: {
					show: true,
					color: '#D8D8D8',
					height: 1,
				},
			},
		},
		revenueChartOptions: {
			series: [
				{
					name: 'Data',
				},
			],
			chart: {
				type: 'area',
				height: 350,
				stacked: false,
				toolbar: {
					show: false
				}
			},
			stroke: {
				curve: 'smooth',
				dashArray: 6,
				width: 2,
			},
			fill: {
				type: 'gradient',
				gradient: {
					// shadeIntensity: 1,
					inverseColors: false,
					opacityFrom: 0.55,
					opacityTo: 0.05,
					stops: [10, 100]
				},
			},
			markers: {
				size: 5,
				colors: ['#fff'],
				strokeColors: '#3A63E9',
				strokeWidth: 2,
				hover: {
					size: 6,
				},
			},
			colors: ['#3A63E9'],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: true,
				borderColor: '#D8D8D8',
				strokeDashArray: 5,
				position: 'back',
				xaxis: {
					lines: {
						show: true,
						offsetX: 60,
						style: {
							dashArray: 5,
						}
					}
				},
				yaxis: {
					lines: {
						show: false
					}
				}
			},
			xaxis: {
				axisTicks: {
					show: false
				},
				axisBorder: {
					show: true,
					color: '#D8D8D8',
					height: 1,
				},
			},
			tooltip: {
				title: {
					enabled: false
				},
				custom: function({series, seriesIndex, dataPointIndex, w}) {
					console.log(dataPointIndex)
					return `<div class="arrow_box">xxx</div>`
				}
			}
		},
		avgOrderValChartOptions: {
			series: [
				{
					name: 'Data',
				},
			],
			chart: {
				type: 'area',
				height: 350,
				stacked: false,
				toolbar: {
					show: false
				}
			},
			stroke: {
				curve: 'straight',
				dashArray: 6,
				width: 2,
			},
			fill: {
				type: 'gradient',
				gradient: {
					// shadeIntensity: 1,
					inverseColors: false,
					opacityFrom: 0.55,
					opacityTo: 0.05,
					stops: [10, 100]
				},
			},
			markers: {
				size: 5,
				colors: ['#fff'],
				strokeColors: '#7880CA',
				strokeWidth: 2,
				hover: {
					size: 6,
				},
			},
			colors: ['#7880CA'],
			dataLabels: {
				enabled: false
			},
			grid: {
				show: true,
				borderColor: '#D8D8D8',
				strokeDashArray: 5,
				position: 'back',
				xaxis: {
					lines: {
						show: true,
						offsetX: 60,
						style: {
							dashArray: 5,
						}
					}
				},
				yaxis: {
					lines: {
						show: false
					}
				}
			},
			xaxis: {
				axisTicks: {
					show: false
				},
				axisBorder: {
					show: true,
					color: '#D8D8D8',
					height: 1,
				},
			},
		},
		impressionsChartRender: function () {
			let chartEl = $('.impressions-chart').get(0);
			let activeData = [
				{
					x: 'Dec 23 2017',
					y: 49
				},
				{
					x: 'Dec 24 2017',
					y: 44
				},
				{
					x: 'Dec 25 2017',
					y: 36
				},
				{
					x: 'Dec 26 2017',
					y: 58
				},
				{
					x: 'Dec 27 2017',
					y: 34
				},
				{
					x: 'Dec 28 2017',
					y: 32
				},
				{
					x: 'Dec 29 2017',
					y: 55
				},
				{
					x: 'Dec 30 2017',
					y: 51
				},
				{
					x: 'Dec 31 2017',
					y: 67
				},
				{
					x: 'Jan 01 2018',
					y: 22
				},
				{
					x: 'Jan 02 2018',
					y: 34
				}
			];
			let options = merchantChart.columnChartOptions;
			options.series[0].data = activeData
			this.impressionsChart = new ApexCharts(chartEl, options)
			this.impressionsChart.render()
		},
		revenueChartRender: function () {
			let chartEl = $('.impressions-chart').get(1);
			let activeData = [
				{
					x: 'Dec 23 2017',
					y: 49,
					custom: 'Custom tooltip',
				},
				{
					x: 'Dec 24 2017',
					y: 44,
					custom: 'Custom tooltip',
				},
				{
					x: 'Dec 25 2017',
					y: 36
				},
				{
					x: 'Dec 26 2017',
					y: 58
				},
				{
					x: 'Dec 27 2017',
					y: 34
				},
				{
					x: 'Dec 28 2017',
					y: 32
				},
				{
					x: 'Dec 29 2017',
					y: 55
				},
				{
					x: 'Dec 30 2017',
					y: 51
				},
				{
					x: 'Dec 31 2017',
					y: 67
				},
				{
					x: 'Jan 01 2018',
					y: 22
				},
				{
					x: 'Jan 02 2018',
					y: 34
				}
			];
			let options = merchantChart.revenueChartOptions;
			options.series[0].data = activeData
			this.revenueChart = new ApexCharts(chartEl, options)
			this.revenueChart.render()
		},
		avgOrderValChartRender: function () {
			let chartEl = $('.impressions-chart').get(2);
			let activeData = [
				{
					x: 'Dec 23 2017',
					y: 49
				},
				{
					x: 'Dec 24 2017',
					y: 44
				},
				{
					x: 'Dec 25 2017',
					y: 36
				},
				{
					x: 'Dec 26 2017',
					y: 58
				},
				{
					x: 'Dec 27 2017',
					y: 34
				},
				{
					x: 'Dec 28 2017',
					y: 32
				},
				{
					x: 'Dec 29 2017',
					y: 55
				},
				{
					x: 'Dec 30 2017',
					y: 51
				},
				{
					x: 'Dec 31 2017',
					y: 67
				},
				{
					x: 'Jan 01 2018',
					y: 22
				},
				{
					x: 'Jan 02 2018',
					y: 34
				}
			];
			let options = merchantChart.avgOrderValChartOptions;
			options.series[0].data = activeData
			this.avgOrderValChart = new ApexCharts(chartEl, options)
			this.avgOrderValChart.render()
		}
	}
	$(document).ready(function () {
		merchantChart.impressionsChartRender()
		merchantChart.revenueChartRender()
		merchantChart.avgOrderValChartRender()
	})
})(jQuery);