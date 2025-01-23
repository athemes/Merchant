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
		 * @param method - The HTTP method to use for the request.
		 * @returns {Promise} - The resolved response or rejected error.
		 */
		sendAjaxRequest: async function (data, loadingIndicatorSelector = '', method = 'GET') {
			try {
				if (loadingIndicatorSelector) {
					$(loadingIndicatorSelector).addClass('show');
				}

				return await $.ajax({
					url: this.AJAX_URL,
					method: method,
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
					this.prepareAjaxData('merchant_get_top_performing_campaigns_table_data', dates.startDate, dates.endDate, dates.compareStartDate, dates.compareEndDate),
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
		 * Updates the all campaigns table with new data.
		 * @param dates - The selected date ranges.
		 * @returns {Promise<void>} - The resolved promise.
		 */
		updateAllCampaignsTable: async function (dates) {
			try {
				const response = await this.sendAjaxRequest(
					this.prepareAjaxData('merchant_get_all_campaigns_table_data', dates.startDate, dates.endDate, '', ''),
					'.merchant-analytics-overview-section .merchant-analytics-loading-spinner'
				);
				if (response.success) {
					// Update the cards with the new data
					this.updateAllCampaignsWithData(response.data, dates.container);
					this.populateFilterSelect(dates.container);
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
		 * Updates all campaigns table with new data.
		 * @param data - The data to update the table with.
		 * @param container - The container element for the table.
		 */
		updateAllCampaignsWithData: function (data, container) {
			let self = this;
			let rowsHTML = [];
			let table_body = container.find('tbody');
			table_body.empty();
			container.find('table th').removeClass('asc desc');
			container.find('.js-campaign-search').val('');
			container.find('.no-results-message').hide();

			let count = 0;

			const $pagination = container.find('.js-pagination');
			const rowsPerPage = parseInt($pagination.attr('data-rows-per-page'));

			$.each(data, function (moduleIndex, module_object) {
				// Extract module and campaign info
				let moduleId = module_object.module_id;
				// check if module_object.campaigns is not empty
				if (module_object.campaigns.length > 0) {
					// Loop through each campaign
					module_object.campaigns.forEach((campaign, index) => {
						count++;
						let switcherId = `${moduleId}-campaign-${moduleIndex}-${index}`;
						rowsHTML.push(`
				            <tr
				            	class="${count > rowsPerPage ? 'is-hidden' : ''}"
				            	${count > rowsPerPage ? 'style="display: none;"' : ''}
				                data-module-id="${moduleId}"
				                data-campaign-key="${campaign.campaign_key}"
				                data-campaign-id="${campaign.campaign_id}"
				                data-row-count="${count}">
				                <td><input type="checkbox" name="campaign_select[]" value="${campaign.title}" /></td>
				                <td class="merchant__campaign-name js-campaign-name">${campaign.title}</td>
				                <td class="merchant__module-name js-module-name" data-module-id="${module_object.module_id}">${module_object.module_name}</td>
				                <td class="merchant__status merchant-module-page-setting-field-switcher js-status">
				                    ${campaign.status === 'active' || campaign.status === 'inactive' ?
							`<div class="merchant-toggle-switch">
								                <input type="checkbox" id="${switcherId}" name="merchant[${switcherId}]" value="${campaign.status === 'active' ? '1' : ''}" ${campaign.status === 'active' ? 'checked ' : ''}class="toggle-switch-checkbox">
								                <label class="toggle-switch-label" for="${switcherId}">
								                    <span class="toggle-switch-inner"></span>
								                    <span class="toggle-switch-switch"></span>
								                </label>
											</div>` :
							'-'
						}
				                </td>
				                <td class="merchant__impressions">${campaign.impression}</td>
				                <td class="merchant__clicks">${campaign.clicks}</td>
				                <td class="merchant__revenue">${campaign.revenue ?? '-'}</td>
				                <td class="merchant__ctr">${campaign.ctr}</td>
				                <td class="merchant__orders">${campaign.orders}</td>
				                <td class="merchant__edit">
				                    <a href="${module_object.edit_url || '#'}" target="_blank">
				                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
				                            <path d="M8.30399 1.00174C8.90067 0.405063 9.8596 0.405063 10.4563 1.00174L10.7333 1.27876C11.33 1.87543 11.33 2.83437 10.7333 3.43104L6.51398 7.65037C6.3435 7.82085 6.10909 7.97001 5.85338 8.03394L3.7224 8.65192C3.55193 8.69454 3.36014 8.65192 3.23228 8.50276C3.08311 8.3749 3.04049 8.18311 3.08311 8.01263L3.70109 5.88166C3.76502 5.62594 3.91419 5.39154 4.08467 5.22106L8.30399 1.00174ZM9.73175 1.72627C9.53996 1.53448 9.22031 1.53448 9.02852 1.72627L8.38923 2.34425L9.39079 3.3458L10.0088 2.70651C10.2006 2.51473 10.2006 2.19508 10.0088 2.00329L9.73175 1.72627ZM4.68134 6.15869L4.31908 7.41596L5.57635 7.0537C5.66159 7.03239 5.72552 6.98977 5.78945 6.92584L8.66626 4.04903L7.68601 3.06878L4.8092 5.94559C4.74527 6.00952 4.70265 6.07345 4.68134 6.15869ZM4.61741 1.83281C4.89444 1.83281 5.12885 2.06722 5.12885 2.34425C5.12885 2.64258 4.89444 2.85568 4.61741 2.85568H2.23072C1.7406 2.85568 1.37834 3.23926 1.37834 3.70807V9.50431C1.37834 9.99444 1.7406 10.3567 2.23072 10.3567H8.02697C8.49578 10.3567 8.87936 9.99444 8.87936 9.50431V7.11763C8.87936 6.8406 9.09245 6.60619 9.39079 6.60619C9.66782 6.60619 9.90222 6.8406 9.90222 7.11763V9.50431C9.90222 10.5485 9.04983 11.3796 8.02697 11.3796H2.23072C1.18655 11.3796 0.355469 10.5485 0.355469 9.50431V3.70807C0.355469 2.6852 1.18655 1.83281 2.23072 1.83281H4.61741Z" fill="#565865"/>
				                        </svg>
				                        Edit
				                    </a>
				                </td>
				            </tr>
				        `);
					});
				}
			});

			$(table_body).append(rowsHTML.join(''));

			// Reset pagination initial state
			self.updatePaginationButtons(1, parseInt($pagination.attr('data-total-pages-initial')), parseInt($pagination.attr('data-total-rows-initial')));
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
					selectedDates = initialValue.split(' - ').map(dateStr => new Date(dateStr.trim()));
				}
				new AirDatepicker(datePicker.getPath(), {
					maxDate: new Date(),
					locale: JSON.parse(merchant_datepicker_locale),
					range: true,
					position: 'bottom right',
					dateFormat: 'yyyy-MM-dd',
					selectedDates: selectedDates, // Set the selected dates
					multipleDatesSeparator: ' - ',
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

					const firstDateRange = firstInput.val().split(' - ').map(dateStr => dateStr.trim());
					const secondDateRange = secondInput.val().split(' - ').map(dateStr => dateStr.trim());

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
			if (!chartEl.length) {
				return;
			}

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

					const firstDateRange = firstInput.val().split(' - ').map(dateStr => dateStr.trim());
					const secondDateRange = secondInput.val().split(' - ').map(dateStr => dateStr.trim());

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

		/**
		 * Initializes the all campaigns table.
		 */
		initAllCampaignsTable: function () {
			let container = $('.merchant-analytics-section.all-campaigns-table');
			let self = this;
			// Initialize the date picker
			this.datePickerInit(container, {
				onSelectHandler: () => {
					// Get both date range inputs
					const firstInput = container.find('.first-date-range .date-range-input');
					const firstDateRange = firstInput.val().split(' - ').map(dateStr => dateStr.trim());

					// Ensure both date ranges have exactly two dates
					if (firstDateRange.length === 2) {
						self.updateAllCampaignsTable({
							startDate: firstDateRange[0],
							endDate: firstDateRange[1],
							container: container
						});
					}
				}
			});

			if (container.length) {
				self.setupSortableTableEventListeners(container);
				self.populateFilterSelect(container);
			}
		},

		/**
		 * Filters the table based on the the available module in the table
		 *
		 * @param container - The table container element.
		 */
		populateFilterSelect: function (container) {
			let table = container.find('.js-campaigns-table');
			let selectorField = $('.filter-campaign select')
			// Clear all options except the first one
			$(selectorField).find('option:not(:first)').remove();

			// Get unique values from the specified table column
			let values = [];
			$(table).find('tr .js-module-name').each(function () {
				let value = $(this).attr('data-module-id');
				let label = $(this).text().trim();

				// Check if the value is not already in the values array
				if (value && !values.some(item => item.value === value)) {
					values.push({
						value: value,
						label: label
					});
				}
			});

			// Sort the values alphabetically (optional)
			values.sort();

			// Append new options to the select field
			$.each(values, function (index, item) {
				$(selectorField).append($('<option>', {
					value: item.value,
					text: item.label
				}));
			});
		},

		/**
		 * Add event listeners to the sortable table.
		 * @param container
		 */
		setupSortableTableEventListeners: function (container) {
			let self = this;
			container.find('th:not(.no-sort)').on('click', (event) => {
				self.sortableTable($(event.currentTarget), container);
			});

			const table = $('.js-campaigns-table');
			const searchInput = $('.js-campaign-search');
			const filterSelect = $('.js-filter-module');
			const bulkActionBtn = $('.js-bulk-action');
			const $pagination = $( '.js-pagination' );

			// "Select All" checkbox
			table.find('thead th:first-child input[type="checkbox"]').on('change', function () {
				const isChecked = $(this).prop('checked');
				table
					.find('tbody tr:not(.is-hidden) input[type="checkbox"]:not(.toggle-switch-checkbox)')
					.prop('checked', isChecked);
			});

			// Status - Single row
			table.on('change', '.js-status input[type="checkbox"]', function () {
				const checkbox = $(this);
				const row = checkbox.closest('tr');
				const moduleId = row.attr('data-module-id');

				const campaignData = {
					[moduleId]: {
						campaign_key: row.attr('data-campaign-key'),
						campaigns: [{
							campaign_id: row.attr('data-campaign-id'),
							status: checkbox.prop('checked') ? 'active' : 'inactive',
						}],
					},
				};

				self.updateCampaignStatus(campaignData, checkbox, [checkbox], true);
			});

			// Status - Bulk action
			bulkActionBtn.on('click', function (e) {
				e.preventDefault();

				const $select = $(this).closest('.bulk-action').find('select');
				const statusAction = $select.val();

				if (!statusAction) {
					alert('Please select an action.');
					return;
				}

				const $checkboxes = table.find('tbody tr:not(.is-hidden) input[type="checkbox"]:not(.toggle-switch-checkbox):checked');

				if (!$checkboxes.length) {
					alert('Please select campaigns.');
					return;
				}

				const campaignData = {};

				$checkboxes.each(function () {
					const $row = $(this).closest('tr');
					const moduleId = $row.attr('data-module-id');

					if (!campaignData[moduleId]) {
						campaignData[moduleId] = {
							campaign_key: $row.attr('data-campaign-key'),
							campaigns: [],
						};
					}

					campaignData[moduleId].campaigns.push({
						campaign_id: $row.attr('data-campaign-id'),
						status: statusAction,
					});
				});

				self.updateCampaignStatus(campaignData, $(this), $checkboxes);
			});

			// Search input
			searchInput.on('input', self.debounce(function () {
				self.filterTableTable(filterSelect.val(), table, $(this).val());
			}, 300))

			// Module filter
			filterSelect.on('change', function () {
				self.filterTableTable($(this).val(), table, '');
				searchInput.val('');
			});

			// Pagination clicks
			$pagination.on( 'click', '.pagination-button', function( e ) {
				e.preventDefault();
				let currentPage = parseInt( $( this ).attr( 'data-current-page' ) );

				const nextPage = parseInt( $( this ).attr( 'data-page' ) );

				if ( isNaN( nextPage ) || nextPage === currentPage ) {
					return;
				}

				currentPage = nextPage;

				self.paginateRows(currentPage,table.find('tbody tr'));

				self.updatePaginationButtons(currentPage);
			} );
		},

		/**
		 * Make the table sortable by the selected column.
		 * @param header - The header element that was clicked.
		 * @param container - The table container element.
		 */
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
		},

		/**
		 * Filter the table rows based on the selected module and search term.
		 * @param moduleId
		 * @param $table
		 * @param searchTerm
		 */
		filterTableTable: function (moduleId, $table, searchTerm = '') {
			if (!$table.length) {
				return;
			}
			const self = this;

			let visibleCount = 0;

			const $rows = $table.find('tbody tr');

			$rows.each(function () {
				const $row = $(this);
				const rowModuleId = $row.attr('data-module-id');
				const campaignName = $row.find('.js-campaign-name').text().toLowerCase();
				const moduleName = $row.find('.js-module-name').text().toLowerCase();

				const moduleMatch = !moduleId || rowModuleId === moduleId;
				const searchMatch = !searchTerm || campaignName.includes(searchTerm) || moduleName.includes(searchTerm);

				if (moduleMatch && searchMatch) {
					$row.show().removeClass('filtered-out is-hidden');
					visibleCount++;
				} else {
					$row.hide().addClass('filtered-out');
				}
			});

			const currentPage = 1;
			const totalRows  = visibleCount;
			const rowsPerPage = parseInt($table.closest('.merchant-page-campaigns').find('.js-pagination').attr('data-rows-per-page'));
			const totalPages = Math.max( 1, Math.ceil( totalRows / rowsPerPage ) );

			// Update after filtering
			self.paginateRows(currentPage, $rows);
			self.updateNoResults( visibleCount === 0, $table );
			self.updatePaginationButtons(currentPage, totalPages, totalRows);
		},

		/**
		 * Update the table to show rows for the selected page.
		 *
		 * @param currentPage
		 * @param $rows
		 */
		paginateRows: function ( currentPage = 1, $rows ) {
			const $pagination = $('.js-pagination');

			const rowsPerPage = parseInt( $pagination.attr( 'data-rows-per-page' ) );
			const startIndex = ( currentPage - 1 ) * rowsPerPage;
			const endIndex = startIndex + rowsPerPage;

			$rows.hide().addClass( 'is-hidden' );

			$rows
				.filter( ':not(.filtered-out)' )
				.each( function( index ) {
					if ( index >= startIndex && index < endIndex ) {
						$( this ).show().removeClass( 'is-hidden' );
					}
				}
			);
		},

		/**
		 * Show no rows found message.
		 *
		 * @param show
		 * @param $table
		 */
		updateNoResults: function ( show, $table ) {
			let $noResults = $table.next( '.no-results-message' );

			if ( show ) {
				if ( !$noResults.length ) {
					$noResults = $( '<div class="no-results-message" style="">No matching campaigns found</div>' );
					$table.after( $noResults );
				}
				$noResults.show();
			} else if ( $noResults.length ) {
				$noResults.hide();
			}
		},

		/**
		 * Update the pagination buttons.
		 *
		 * @param currentPage
		 * @param totalPages
		 * @param totalRows
		 */
		updatePaginationButtons: function( currentPage, totalPages, totalRows ) {
			const $pagination = $('.js-pagination');

			$pagination.attr( 'data-current-page', currentPage )

			// If totalPages provided, update it to use the latest value
			if ( totalPages ) {
				$pagination.attr( 'data-total-pages', totalPages )
			}
			if ( totalRows ) {
				$pagination.attr( 'data-total-rows', totalRows )
			}

			// Get the latest value
			totalPages = parseInt( $pagination.attr( 'data-total-pages' ) );

			let html = '';

			if ( currentPage > 1 ) {
				html += `
		          <button class="pagination-button prev-page" data-page="${ currentPage - 1 }">
		            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="#565865">
		              <path d="M5.16797 11.3301L0.521484 6.48047C0.394531 6.32812 0.34375 6.17578 0.34375 6.02344C0.34375 5.89648 0.394531 5.74414 0.496094 5.61719L5.14258 0.767578C5.37109 0.513672 5.77734 0.513672 6.00586 0.742188C6.25977 0.970703 6.25977 1.35156 6.03125 1.60547L1.79102 6.02344L6.05664 10.4922C6.28516 10.7207 6.28516 11.127 6.03125 11.3555C5.80273 11.584 5.39648 11.584 5.16797 11.3301Z"/>
		            </svg>
		          </button>
				`;
			}

			if ( totalPages > 1 ) {
				for ( let i = 1; i <= totalPages; i++ ) {
					html += `
          			<button class="pagination-button${ i === currentPage ? ' pagination-active' : '' }" data-page="${ i }">${ i }</button>
				`;
				}
			}

			if ( currentPage < totalPages ) {
				html += `
		          <button class="pagination-button next-page" data-page="${ currentPage + 1 }">
		            <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="#565865">
		              <path d="M1.80664 0.742188L6.45312 5.5918C6.55469 5.71875 6.63086 5.87109 6.63086 6.02344C6.63086 6.17578 6.55469 6.32812 6.45312 6.42969L1.80664 11.2793C1.57812 11.5332 1.17188 11.5332 0.943359 11.3047C0.689453 11.0762 0.689453 10.6953 0.917969 10.4414L5.18359 5.99805L0.917969 1.58008C0.689453 1.35156 0.689453 0.945312 0.943359 0.716797C1.17188 0.488281 1.57812 0.488281 1.80664 0.742188Z"/>
		            </svg>
		          </button>
				`;
			}

			$pagination.html( html );

			// Update results
			const $paginationNotice = $('.js-pagination-results');
			if ( totalPages > 1 ) {
				const _totalRows = parseInt($pagination.attr( 'data-total-rows'))
				const rowsPerPage = parseInt( $pagination.attr( 'data-rows-per-page' ) );
				const startIndex = ( currentPage - 1 ) * rowsPerPage;
				const endIndex = startIndex + rowsPerPage;

				$paginationNotice.find( '.pagination-start-row' ).text(startIndex ? startIndex : 1)
				$paginationNotice.find( '.pagination-end-row' ).text(endIndex > _totalRows ? _totalRows: endIndex)
				$paginationNotice.find( '.pagination-total-rows' ).text(totalRows)
				$paginationNotice.show();
			} else {
				$paginationNotice.hide();
			}
		},

		/**
		 * Update the campaign status.
		 * @param campaignData - The campaign data to update.
		 * @param el - The element that triggered the update.
		 * @param checkboxes - The checkboxes to update.
		 * @param singleRow - Whether to update a single row or multiple rows.
		 */
		updateCampaignStatus: async function (campaignData, el, checkboxes, singleRow = false) {
			const self = this;
			const $table = el.closest('.campaigns-table').find('.js-campaigns-table');

			const $loader = '<span class="spinner is-active"></span>';

			el.prop('disabled', true);

			if (singleRow) {
				el.closest('.merchant-toggle-switch').append($loader);
				el.closest('tr').css('opacity', '.7');
			} else {
				$table.css('opacity', '.7');
				el.closest('.bulk-action').append($loader);
			}

			try {
				await this.sendAjaxRequest(
					{
						action: 'merchant_update_campaign_status',
						nonce: self.NONCE,
						campaign_data: campaignData,
					},
					'',
					'POST'
				).then((response) => {
					if (response.success) {
						if (!singleRow) {
							checkboxes?.each(function () {
								$table.find('thead th:first-child input[type="checkbox"]').prop('checked', false);
								$(this)
									.prop('checked', false)
									.closest('tr')
									.find('.js-status input[type="checkbox"]')
									.prop('checked', response.data.status === 'active');
							});
						}

						$(document).trigger('merchant_campaign_status_updated', [
							response.data,
							el,
							checkboxes,
							singleRow,
							campaignData
						]);
					}

					$('.spinner').remove();
					el.prop('disabled', false);

					if (singleRow) {
						el.closest('tr').css('opacity', '');
					} else {
						$table.css('opacity', '');
					}
				})
			} catch (error) {
				console.error('Error fetching campaign status data:', error);
			}
		},

		/**
		 * Debounce function to limit the number of times a function is called.
		 * @param func - The function to debounce.
		 * @param wait - The time to wait before calling the function.
		 * @returns {(function(...[*]): void)|*} - The debounced function.
		 */
		debounce: function (func, wait) {
			let timeout;
			return function (...args) {
				clearTimeout(timeout);
				timeout = setTimeout(() => func.apply(this, args), wait);
			};
		}
	};

	// Initialize charts when the document is ready
	$(document).ready(function () {
		merchantAnalyticsChart.initOverviewCards();
		merchantAnalyticsChart.revenueChartRender();
		merchantAnalyticsChart.avgOrderValChartRender();
		merchantAnalyticsChart.impressionsChartRender();
		merchantAnalyticsChart.initTopCampaignsTable();
		merchantAnalyticsChart.initAllCampaignsTable();
	});
})(jQuery);