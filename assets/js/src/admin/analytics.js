'use strict';

(function ($) {
	/**
	 * Main object for managing merchant analytics charts.
	 * @namespace merchantAnalyticsChart
	 */
	const merchantAnalyticsChart = {
		AJAX_URL: merchant_analytics.ajax_url,
		NONCE: merchant_analytics.nonce,
		impressionsChart: null,
		revenueChart: null,
		avgOrderValChart: null,

		/**
		 * Options for the impressions chart (bar chart).
		 * @type {Object}
		 */
		columnChartOptions: {
			series: [{data: []}],
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

		/**
		 * Options for the revenue chart (area chart).
		 * @type {Object}
		 */
		revenueChartOptions: {
			series: [{data: []}],
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
			legend: {
				show: false // This hides the legend
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
			colors: ['#3A63E9', '#393939'],
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
				}
			},
			tooltip: {
				fixed: {
					offsetX: 0,
					offsetY: 0,
				},
				enabled: true,
				theme: false,
				custom: function ({series, seriesIndex, dataPointIndex, w}) {
					const current_data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
					return `
                        <div class="arrow-box">
                            <div class="box-wrapper">
                                <div class="box-column big">
                                    <div class="head">
                                        <div class="box-title">Total Income</div>
                                        <div class="box-value">${current_data.number_currency}</div>
                                    </div>
                                    <div class="orders-count">
                                        <strong>${current_data.orders_count}</strong> ${merchant_analytics.labels.orders}
                                    </div>
                                </div>
                                <div class="separator"></div>
                                <div class="box-column small">
                                    <div class="head">
                                        <svg width="64" height="47" viewBox="0 0 64 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <!-- SVG content -->
                                        </svg>
                                    </div>
                                    <div class="change-percentage ${current_data.diff_type}">
                                        <strong>${current_data.difference}%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>`;
				}
			}
		},

		/**
		 * Options for the average order value (AOV) chart (area chart).
		 * @type {Object}
		 */
		avgOrderValChartOptions: {
			series: [{data: []}],
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
					const current_data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
					return `
                        <div class="arrow-box-aov">
                            <div class="box-title">${merchant_analytics.labels.orders_aov}</div>
                            <div class="box-value">${current_data.number_currency} <span class="diff ${current_data.diff_type}">${current_data.difference}%</span></div>
                        </div>`;
				}
			}
		},

		/**
		 * Sends an AJAX request and returns the response.
		 * @param {Object} data - The data to send with the request.
		 * @param {string} [loadingIndicatorSelector] - Selector for the loading indicator element.
		 * @returns {Promise} - The resolved response or rejected error.
		 */
		sendAjaxRequest: async function (data, loadingIndicatorSelector = '') {
			try {
				if (loadingIndicatorSelector) {
					$(loadingIndicatorSelector).addClass('show');
				}

				const response = await $.ajax({
					url: this.AJAX_URL,
					method: 'GET',
					data: data,
				});

				return response;
			} catch (error) {
				console.error('AJAX request failed:', error);
				throw error;
			} finally {
				if (loadingIndicatorSelector) {
					$(loadingIndicatorSelector).removeClass('show');
				}
			}
		},

		/**
		 * Prepares data for an AJAX request.
		 * @param {string} action - The action to perform.
		 * @param {string} startDate - The start date for the data range.
		 * @param {string} endDate - The end date for the data range.
		 * @returns {Object} - The prepared data object.
		 */
		prepareAjaxData: function (action, startDate, endDate) {
			return {
				action: action,
				nonce: this.NONCE,
				start_date: startDate,
				end_date: endDate,
			};
		},

		/**
		 * Updates the impressions chart with new data.
		 * @param {object} data - The selected date.
		 */
		updateImpressionsChart: async function (data) {
			const [startDate, endDate] = data.formattedDate;

			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('get_impressions_chart_data', startDate, endDate),
					'.impressions-chart-loading'
				);
				console.log('Impressions data:', response);
				this.impressionsChart.updateSeries([{data: response.data}]);
			} catch (error) {
				console.error('Error fetching impressions data:', error);
			}
		},

		/**
		 * Updates the revenue chart with new data.
		 * @param {object} data - The selected date.
		 */
		updateRevenueChart: async function (data) {
			//console.log(formattedDate)
			const [startDate, endDate] = data.formattedDate;

			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('get_revenue_chart_data', startDate, endDate),
					'.revenue-chart-loading'
				);
				console.log('Revenue data:', response);
				this.revenueChart.updateSeries([{data: response.data}]);
			} catch (error) {
				console.error('Error fetching revenue data:', error);
			}
		},

		/**
		 * Updates the average order value (AOV) chart with new data.
		 * @param {object} data - The selected date.
		 */
		updateAOVChart: async function (data) {
			const [startDate, endDate] = data.formattedDate;

			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('get_avg_order_value_chart_data', startDate, endDate),
					'.aov-chart-loading'
				);
				console.log('AOV data:', response);
				this.avgOrderValChart.updateSeries([{data: response.data}]);
			} catch (error) {
				console.error('Error fetching AOV data:', error);
			}
		},

		/**
		 * Initializes the date picker for a chart container.
		 * @param {jQuery} container - The container element for the chart.
		 * @param {Object} options - Options for the date picker.
		 * @param {Function} options.onSelectHandler - Callback function for date selection.
		 */
		datePickerInit: function (container, {onSelectHandler}) {
			const inputs = container.find('.date-range-input');

			inputs.each(function () {
				const datePicker = $(this);
				new AirDatepicker(datePicker.getPath(), {
					maxDate: new Date(),
					locale: JSON.parse(merchant_datepicker_locale),
					range: true,
					position: 'left top',
					timepicker: true,
					timeFormat: 'HH:mm:59',
					dateFormat: 'yyyy-MM-dd',
					multipleDatesSeparator: ',',
					onSelect: function (data) {
						if (typeof onSelectHandler === 'function') {
							onSelectHandler(data);
						} else {
							console.error('onSelectHandler is not a function');
						}
					}
				});
			});
		},

		/**
		 * Renders a chart and initializes its date picker.
		 * @param {jQuery} container - The container element for the chart.
		 * @param {Object} chartOptions - Options for the ApexCharts instance.
		 * @param {Function} updateFunction - Function to call when the date is selected.
		 * @param {string} loadingIndicatorSelector - Selector for the loading indicator.
		 * @returns {ApexCharts} - The rendered chart instance.
		 */
		renderChart: function (container, chartOptions, updateFunction, loadingIndicatorSelector) {
			const chartEl = container.find('.chart');
			const chart = new ApexCharts(chartEl.get(0), chartOptions);
			chart.render();
			chart.updateSeries([{data: JSON.parse(chartEl.attr('data-period'))}]);

			this.datePickerInit(container, {
				onSelectHandler: (data) => {
					if(data.formattedDate.length === 2) {
						updateFunction(data);
					}
				}
			});

			return chart;
		},

		/**
		 * Renders the revenue chart.
		 */
		revenueChartRender: function () {
			const container = $('.revenue-chart-section');
			this.revenueChart = this.renderChart(
				container,
				this.revenueChartOptions,
				(data) => this.updateRevenueChart(data),
				'.revenue-chart-section .merchant-analytics-loading-spinner'
			);
		},

		/**
		 * Renders the average order value (AOV) chart.
		 */
		avgOrderValChartRender: function () {
			const container = $('.aov-chart-section');
			this.avgOrderValChart = this.renderChart(
				container,
				this.avgOrderValChartOptions,
				(data) => this.updateAOVChart(data),
				'.aov-chart-section .merchant-analytics-loading-spinner'
			);
		},

		/**
		 * Renders the impressions chart.
		 */
		impressionsChartRender: function () {
			const container = $('.impressions-chart-section');
			this.impressionsChart = this.renderChart(
				container,
				this.columnChartOptions,
				(data) => this.updateImpressionsChart(data),
				'.impressions-chart-section .merchant-analytics-loading-spinner'
			);
		}
	};

	// Initialize charts when the document is ready
	$(document).ready(function () {
		merchantAnalyticsChart.revenueChartRender();
		merchantAnalyticsChart.avgOrderValChartRender();
		merchantAnalyticsChart.impressionsChartRender();
	});
})(jQuery);