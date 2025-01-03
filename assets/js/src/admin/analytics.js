'use strict';

(function ($) {
	let merchantChart = {
		impressionsChart: null,
		revenueChart: null,
		avgOrderValChart: null,
		columnChartOptions: {
			series: [{}],
			noData: {
				text: 'No data available',
				align: 'center',
				verticalAlign: 'middle',
				offsetX: 0,
				offsetY: 0,
				style: {
					color: '#686868',
					fontSize: '18px',
				}
			},
			chart: {
				type: 'bar',
				height: 350,
				stacked: false,
				toolbar: {
					show: false,
					offsetX: -10,
					offsetY: 10,
					tools: {
						download: false,
						selection: true,
						zoom: true,
						zoomin: true,
						zoomout: true,
						pan: false,
						reset: true,
					}
				},
				zoom: {
					enabled: false,
					allowMouseWheelZoom: false,
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
			tooltip: {
				enabled: false
			}
		},
		revenueChartOptions: {
			series: [{}],
			noData: {
				text: 'No data available',
				align: 'center',
				verticalAlign: 'middle',
				offsetX: 0,
				offsetY: 0,
				style: {
					color: '#686868',
					fontSize: '18px',
				}
			},
			chart: {
				type: 'area',
				height: 350,
				stacked: false,
				toolbar: {
					show: false,
					offsetX: -10,
					offsetY: 10,
					tools: {
						download: false,
						selection: true,
						zoom: true,
						zoomin: true,
						zoomout: true,
						pan: false,
						reset: true,
					}
				},
				zoom: {
					enabled: false,
					allowMouseWheelZoom: false,
				}
			},
			stroke: {
				curve: 'smooth',
				dashArray: 6,
				width: 2,
				lineCap: 'round',
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
				tooltip: {
					enabled: false,
				},
			},
			tooltip: {
				fixed: {

					offsetX: 0,
					offsetY: 0,
				},
				enabled: true,
				theme: false,
				custom: function ({series, seriesIndex, dataPointIndex, w}) {
					let current_data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
					return `<div class="arrow-box">
								<div class="box-wrapper">
									<div class="box-column big">
										<div class="head">
											<div class="box-title">Total Income</div>
											<div class="box-value">${merchant_analytics.currency_name} ${current_data.y}</div>
										</div>
										<div class="orders-count">
											<strong>${current_data.orders_count}</strong> ${merchant_analytics.labels.orders}
										</div>
									</div>
									<div class="separator"></div>
									<div class="box-column small">
										<div class="head">
											<svg width="64" height="47" viewBox="0 0 64 41" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M0.5 41V22L12.5 26H16.5L24 19L27 16.5L33.5 15L42.5 19L47 17.5L63.5 1V41H0.5Z" fill="url(#paint0_linear_4098_14532)"/>
												<path d="M0.5 21.5L7.89394 25.3339C11.5717 27.2409 16.0444 26.6827 19.1408 23.9304L26.846 17.0814C29.8153 14.4419 34.2623 14.351 37.3371 16.8667V16.8667C40.5519 19.497 45.2367 19.2633 48.1738 16.3262L63.5 1" stroke="#3661EA" stroke-width="0.5" stroke-dasharray="3 3"/>
												<defs>
												<linearGradient id="paint0_linear_4098_14532" x1="23.753" y1="1" x2="16.791" y2="39.8525" gradientUnits="userSpaceOnUse">
												<stop stop-color="#CDE1FE"/>
												<stop offset="1" stop-color="#FBFCFF" stop-opacity="0"/>
												</linearGradient>
												</defs>
											</svg>
										</div>
										<div class="change-percentage ${current_data.diff_type}">
											<strong>${current_data.difference}%</strong>
										</div>
									</div>
								</div>
							</div>`
				}
			}
		},
		avgOrderValChartOptions: {
			series: [{}],
			noData: {
				text: 'No data available',
				align: 'center',
				verticalAlign: 'middle',
				offsetX: 0,
				offsetY: 0,
				style: {
					color: '#686868',
					fontSize: '18px',
				}
			},
			chart: {
				type: 'area',
				height: 350,
				stacked: false,
				toolbar: {
					show: false,
					offsetX: -10,
					offsetY: 10,
					tools: {
						download: false,
						selection: true,
						zoom: true,
						zoomin: true,
						zoomout: true,
						pan: false,
						reset: true,
					}
				},
				zoom: {
					enabled: false,
					allowMouseWheelZoom: false,
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
				tooltip: {
					enabled: false,
				},
			},
			tooltip: {
				enabled: true,
				theme: false,
				custom: function ({series, seriesIndex, dataPointIndex, w}) {
					let current_data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
					return `<div class="arrow-box-aov">
								<div class="box-title">${merchant_analytics.labels.orders_aov}</div>
								<div class="box-value">${current_data.amount} <span class="diff ${current_data.diff_type}">${current_data.difference}%</span></div>
							</div>`
				}
			}
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
			let chartEl = $('.revenue-chart').get(0);
			let activeData = [
				{
					x: 'Dec 23 2017',
					y: 49,
					orders_count: 10,
					difference: 7.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 24 2017',
					y: 44,
					orders_count: 12,
					difference: 17.2,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 25 2017',
					y: 36,
					orders_count: 8,
					difference: 11.3,
					diff_type: 'increase'
				},
				{
					x: 'Dec 26 2017',
					y: 58,
					orders_count: 15,
					difference: 4.2,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 27 2017',
					y: 34,
					orders_count: 7,
					difference: 7.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 28 2017',
					y: 32,
					orders_count: 6,
					difference: 5.6,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 29 2017',
					y: 55,
					orders_count: 11,
					difference: 2.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 30 2017',
					y: 51,
					orders_count: 10,
					difference: 9.2,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 31 2017',
					y: 67,
					orders_count: 14,
					difference: 1.2,
					diff_type: 'increase'
				},
				{
					x: 'Jan 01 2018',
					y: 22,
					orders_count: 5,
					difference: 3.2,
					diff_type: 'decrease'
				},
				{
					x: 'Jan 02 2018',
					y: 34,
					orders_count: 7,
					difference: 7.2,
					diff_type: 'increase'
				}
			];
			let options = merchantChart.revenueChartOptions;
			options.series[0].data = activeData
			this.revenueChart = new ApexCharts(chartEl, options)
			this.revenueChart.render()
		},
		avgOrderValChartRender: function () {
			let chartEl = $('.avg-order-value-chart').get(0);
			let activeData = [
				{
					x: 'Dec 23 2017',
					y: 49,
					amount: '$49',
					difference: 3.5,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 24 2017',
					y: 44,
					amount: '$44',
					difference: 7.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 25 2017',
					y: 36,
					amount: '$36',
					difference: 11.3,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 26 2017',
					y: 58,
					amount: '$58',
					difference: 4.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 27 2017',
					y: 34,
					amount: '$34',
					difference: 7.2,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 28 2017',
					y: 32,
					amount: '$32',
					difference: 5.6,
					diff_type: 'increase'
				},
				{
					x: 'Dec 29 2017',
					y: 55,
					amount: '$55',
					difference: 2.2,
					diff_type: 'decrease'
				},
				{
					x: 'Dec 30 2017',
					y: 51,
					amount: '$51',
					difference: 9.2,
					diff_type: 'increase'
				},
				{
					x: 'Dec 31 2017',
					y: 67,
					amount: '$67',
					difference: 1.2,
					diff_type: 'decrease'
				},
				{
					x: 'Jan 01 2018',
					y: 22,
					amount: '$22',
					difference: 3.2,
					diff_type: 'increase'
				},
				{
					x: 'Jan 02 2018',
					y: 34,
					amount: '$34',
					difference: 7.2,
					diff_type: 'decrease'
				}
			];
			let options = merchantChart.avgOrderValChartOptions;
			options.series[0].data = activeData
			this.avgOrderValChart = new ApexCharts(chartEl, options)
			this.avgOrderValChart.render()
		}
	}
	$(document).ready(function () {
		merchantChart.revenueChartRender()
		merchantChart.avgOrderValChartRender()
		merchantChart.impressionsChartRender()
	})
})(jQuery);