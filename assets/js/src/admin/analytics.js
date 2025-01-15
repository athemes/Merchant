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

				return await $.ajax({
					url: this.AJAX_URL,
					method: 'GET',
					data: data,
				});
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
		 * @param {string} compareStartDate - The start date for the comparison range.
		 * @param {string} compareEndDate - The end date for the comparison range.
		 * @returns {Object} - The prepared data object.
		 */
		prepareAjaxData: function (action, startDate, endDate, compareStartDate = '', compareEndDate = '') {
			return {
				action: action,
				nonce: this.NONCE,
				start_date: startDate,
				end_date: endDate,
				compare_start_date: compareStartDate,
				compare_end_date: compareEndDate,
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
					this.prepareAjaxData('merchant_get_impressions_chart_data', startDate, endDate),
					'.impressions-chart-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					this.impressionsChart.updateSeries([{data: response.data}]);
				}
			} catch (error) {
				console.error('Error fetching impressions data:', error);
			}
		},

		/**
		 * Updates the revenue chart with new data.
		 * @param {object} data - The selected date.
		 */
		updateRevenueChart: async function (data) {
			const [startDate, endDate] = data.formattedDate;

			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('merchant_get_revenue_chart_data', startDate, endDate),
					'.revenue-chart-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					this.revenueChart.updateSeries([{data: response.data}]);
				}
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
					this.prepareAjaxData('merchant_get_avg_order_value_chart_data', startDate, endDate),
					'.aov-chart-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					this.avgOrderValChart.updateSeries([{data: response.data}]);
				}
			} catch (error) {
				console.error('Error fetching AOV data:', error);
			}
		},

		/**
		 * Updates the overview cards with new data.
		 * @param {Object} dates - The selected date ranges.
		 * @returns {Promise<void>}
		 */
		updateOverviewCards: async function (dates) {
			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('merchant_get_analytics_cards_data', dates.startDate, dates.endDate, dates.compareStartDate, dates.compareEndDate),
					'.merchant-analytics-overview-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					// Update the cards with the new data
					this.updateCardsWithData(response.data);
				}
			} catch (error) {
				console.error('Error fetching cards data:', error);
			}
		},

		/**
		 * Updates the overview cards with new data.
		 * @param {Object} data - The data to update the cards with.
		 */
		updateCardsWithData: function (data) {
			const container = $('.merchant-analytics-overview-section');
			// Update Revenue Card
			if (data.revenue) {
				this.updateSingleCard(
					container.find('.overview-card.revenue'),
					data.revenue.revenue_second_period_currency, // Value
					data.revenue.revenue_change[0], // Change percentage
					data.revenue.revenue_change[1] // Change type (increase/decrease)
				);
			}

			// Update Orders Card
			if (data.orders) {
				this.updateSingleCard(
					container.find('.overview-card.total-orders'),
					data.orders.orders_second_period, // Value
					data.orders.orders_change[0], // Change percentage
					data.orders.orders_change[1] // Change type (increase/decrease)
				);
			}

			// Update AOV Card
			if (data.aov) {
				this.updateSingleCard(
					container.find('.overview-card.aov'),
					data.aov.aov_second_period_currency, // Value
					data.aov.change[0], // Change percentage
					data.aov.change[1] // Change type (increase/decrease)
				);
			}

			// Update Conversion Rate Card
			if (data.conversion) {
				this.updateSingleCard(
					container.find('.overview-card.conversion-rate'),
					data.conversion.conversion_second_period_percentage, // Value
					data.conversion.change[0], // Change percentage
					data.conversion.change[1] // Change type (increase/decrease)
				);
			}

			// Update Impressions Card
			if (data.impressions) {
				this.updateSingleCard(
					container.find('.overview-card.impressions'),
					data.impressions.impressions_second_period, // Value
					data.impressions.change[0], // Change percentage
					data.impressions.change[1] // Change type (increase/decrease)
				);
			}
		},

		/**
		 * Updates the performing campaigns table with new data.
		 *
		 * @param {Object} dates - The selected date ranges.
		 *
		 * @returns {Promise<void>}
		 */
		updatePerformingCampaignsTable: async function (dates) {
			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('merchant_get_analytics_table_data', dates.startDate, dates.endDate, dates.compareStartDate, dates.compareEndDate),
					'.merchant-analytics-overview-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					// Update the cards with the new data
					this.updateTopCampaignsWithData(response.data, dates.container);
				}
			} catch (error) {
				console.error('Error fetching cards data:', error);
			}
		},

		/**
		 * Updates the top campaigns table with new data.
		 * @param data
		 * @param container
		 */
		updateTopCampaignsWithData: function (data, container) {
			let table_body = container.find('tbody');
			container.find('table th').removeClass('asc desc');
			table_body.empty();
			$.each(data, function (campaignId, campaign) {
				// Create the HTML for each row using template literals
				const rowHTML = `
		            <tr>
		                <td>${campaign.campaign_info.module_name}: ${campaign.campaign_info.campaign_title}</td>
		                <td>${campaign.impressions}</td>
		                <td>${campaign.clicks}</td>
		                <td class="${campaign.ctr.change[1]}">${campaign.ctr.change[0] === 0 ? '-' : campaign.ctr.change[0]}</td>
		                <td>${campaign.orders}</td>
		                <td>${campaign.revenue}</td>
		            </tr>
		        `;

				// Append the row HTML to the container
				$(table_body).append(rowHTML);
			});
		},

		/**
		 * Updates a single card with new data.
		 * @param {jQuery} card - The card element to update.
		 * @param {string} value - The new value to display.
		 * @param {string} change - The change percentage to display.
		 * @param {string} change_type - The type of change (increase/decrease).
		 * @returns {void}
		 */
		updateSingleCard: function (card, value, change, change_type) {
			if (value) {
				card.find('.card-value').html(value);
			}
			if (change_type && change) {
				card.find('.card-change').removeClass('increase decrease').addClass(change_type).html(change + '%');
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
				const initialValue = datePicker.val();
				let selectedDates = [];
				if (initialValue) {
					selectedDates = initialValue.split(',').map(dateStr => new Date(dateStr.trim()));
				}
				new AirDatepicker(datePicker.getPath(), {
					maxDate: new Date(),
					locale: JSON.parse(merchant_datepicker_locale),
					range: true,
					position: 'bottom right',
					timepicker: true,
					timeFormat: 'HH:mm:59',
					dateFormat: 'yyyy-MM-dd',
					selectedDates: selectedDates, // Set the selected dates
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
		 * Initializes the overview cards.
		 */
		initOverviewCards: function () {
			let container = $('.merchant-analytics-overview-section');

			// Initialize the date picker
			this.datePickerInit(container, {
				onSelectHandler: () => {
					// Get both date range inputs
					const firstInput = container.find('.first-date-range .date-range-input');
					const secondInput = container.find('.second-date-range .date-range-input');

					const firstDateRange = firstInput.val().split(',').map(dateStr => dateStr.trim());
					const secondDateRange = secondInput.val().split(',').map(dateStr => dateStr.trim());

					// Ensure both date ranges have exactly two dates
					if (firstDateRange.length === 2 && secondDateRange.length === 2) {
						this.updateOverviewCards({
							startDate: firstDateRange[0],
							endDate: firstDateRange[1],
							compareStartDate: secondDateRange[0],
							compareEndDate: secondDateRange[1]
						});
					}
				}
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
					if (data.formattedDate.length === 2) {
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
		},

		/**
		 * Initializes the top campaigns table.
		 */
		initTopCampaignsTable: function () {
			let container = $('.merchant-analytics-section.campaigns-table');
			let self = this;
			// Initialize the date picker
			this.datePickerInit(container, {
				onSelectHandler: () => {
					// Get both date range inputs
					const firstInput = container.find('.first-date-range .date-range-input');
					const secondInput = container.find('.second-date-range .date-range-input');

					const firstDateRange = firstInput.val().split(',').map(dateStr => dateStr.trim());
					const secondDateRange = secondInput.val().split(',').map(dateStr => dateStr.trim());

					// Ensure both date ranges have exactly two dates
					if (firstDateRange.length === 2 && secondDateRange.length === 2) {
						self.updatePerformingCampaignsTable({
							startDate: firstDateRange[0],
							endDate: firstDateRange[1],
							compareStartDate: secondDateRange[0],
							compareEndDate: secondDateRange[1],
							container: container
						});
					}
				}
			});


			if (container.length) {
				this.setupSortableTableEventListeners(container);
			}
		},

		setupSortableTableEventListeners: function (container) {
			let self = this;
			container.find('th').on('click', (event) => {
				self.sortableTable($(event.currentTarget), container);
			});
		},

		sortableTable: function (header, container) {
			let self = this;
			const column = header.index();
			const type = header.data('sort');
			const currentOrder = header.hasClass('asc') ? 'desc' : 'asc';

			// Remove previous sorting classes
			container.find('th').removeClass('asc desc');

			// Add class to indicate current sorting order
			header.addClass(currentOrder);

			const tbody = container.find('tbody');
			const rows = tbody.find('tr').toArray();

			rows.sort((a, b) => {
				const keyA = $(a).find('td').eq(column).text();
				const keyB = $(b).find('td').eq(column).text();

				let valueA, valueB;

				if (type === 'int') {
					valueA = parseInt(keyA.replace(/[^0-9]/g, ''), 10);
					valueB = parseInt(keyB.replace(/[^0-9]/g, ''), 10);
				} else if (type === 'float') {
					valueA = parseFloat(keyA.replace(/[^0-9.]/g, ''));
					valueB = parseFloat(keyB.replace(/[^0-9.]/g, ''));
				} else {
					valueA = keyA;
					valueB = keyB;
				}

				if (currentOrder === 'asc') {
					return valueA < valueB ? -1 : valueA > valueB ? 1 : 0;
				} else {
					return valueA > valueB ? -1 : valueA < valueB ? 1 : 0;
				}
			});

			// Append sorted rows back to the tbody
			tbody.append(rows);
		}
	};

	// Initialize charts when the document is ready
	$(document).ready(function () {
		merchantAnalyticsChart.initOverviewCards();
		merchantAnalyticsChart.revenueChartRender();
		merchantAnalyticsChart.avgOrderValChartRender();
		merchantAnalyticsChart.impressionsChartRender();
		merchantAnalyticsChart.initTopCampaignsTable();
	});
})(jQuery);