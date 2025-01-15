;( function( $, window, document, undefined ) {
	'use strict';

	$( document ).ready( function( $ ) {
		// Cache DOM elements
		const $table = $( '.js-campaigns-table' );
		const $searchInput = $( '.js-campaign-search' );
		const $filterSelect = $( '.js-filter-module' );
		const $rows = $table.find( 'tbody tr' );
		const $pagination = $( '.js-pagination' );
		const $bulkActionBtn = $( '.js-bulk-action' );

		// Initialize pagination variables
		let rowsPerPage = parseInt( $pagination.data( 'rows-per-page' ) );
		let totalRows = parseInt( $pagination.data( 'total-rows' ) );
		let totalPages = Math.ceil( totalRows / rowsPerPage );
		let currentPage = 1;

		// ----------------------------------------
		// Event Handlers
		// ----------------------------------------

		// Table header sorting
		$table.on( 'click', '.merchant-sort', function( e ) {
			e.preventDefault();
			const $th = $( this ).closest( 'th' );
			const sortBy = $( this ).data( 'sort' );
			const direction = $th.find( '.sorting-indicators' ).hasClass( 'asc' ) ? 'desc' : 'asc';
			sortTable( $th, sortBy, direction );
		} );

		// "Select All" checkbox
		$table.find( 'thead th:first-child input[type="checkbox"]' ).on( 'change', function() {
			const isChecked = $( this ).prop( 'checked' );
			$table
				.find( 'tbody tr:not(.is-hidden) input[type="checkbox"]:not(.toggle-switch-checkbox)' )
				.prop( 'checked', isChecked );
		} );

		// Module filter
		$filterSelect.on( 'change', function() {
			currentPage = 1;
			filterTableAndUpdatePagination( $( this ).val(), $searchInput.val() );
		} );

		// Search input
		$searchInput.on( 'input', debounce( function() {
			currentPage = 1;
			filterTableAndUpdatePagination( $filterSelect.val(), $( this ).val() );
		}, 300 ) );

		// Clear search
		$searchInput.on( 'search', function() {
			if ( $( this ).val() === '' ) {
				currentPage = 1;
				filterTableAndUpdatePagination( $filterSelect.val(), '' );
			}
		} );

		// Pagination clicks
		$pagination.on( 'click', '.pagination-button', function( e ) {
			e.preventDefault();
			const newPage = parseInt( $( this ).data( 'page' ) );

			if ( isNaN( newPage ) || newPage === currentPage ) {
				return;
			}

			currentPage = newPage;
			showCurrentPageRows();
			updatePaginationButtons();

			$table[0].scrollIntoView( { behavior: 'smooth', block: 'start' } );
		} );

		// Status toggle - Single row
		$table.on( 'change', '.js-status input[type="checkbox"]', function() {
			const $checkbox = $( this );
			const $row = $checkbox.closest( 'tr' );
			const moduleId = $row.attr( 'data-module-id' );

			const campaignData = {
				[ moduleId ]: {
					campaign_key: $row.attr( 'data-campaign-key' ),
					campaigns: [ {
						campaign_id: $row.attr( 'data-campaign-id' ),
						status: $checkbox.prop( 'checked' ) ? 'enable' : 'disable',
					} ],
				},
			};

			ajaxStatusUpdate( campaignData, $checkbox, [ $checkbox ], true );
		} );

		// Bulk action
		$bulkActionBtn.on( 'click', function( e ) {
			e.preventDefault();

			const $select = $( this ).closest( '.bulk-action' ).find( 'select' );
			const statusAction = $select.val();

			if ( ! statusAction ) {
				alert( 'Please select an action.' );
				return;
			}

			const $checkboxes = $table.find( 'tbody tr:not(.is-hidden) input[type="checkbox"]:not(.toggle-switch-checkbox):checked' );

			if ( ! $checkboxes.length ) {
				alert( 'Please select campaigns.' );
				return;
			}

			const campaignData = {};

			$checkboxes.each( function() {
				const $row = $( this ).closest( 'tr' );
				const moduleId = $row.attr( 'data-module-id' );

				if ( ! campaignData[ moduleId ] ) {
					campaignData[ moduleId ] = {
						campaign_key: $row.attr( 'data-campaign-key' ),
						campaigns: [],
					};
				}

				campaignData[ moduleId ].campaigns.push( {
					campaign_id: $row.attr( 'data-campaign-id' ),
					status: statusAction,
				} );
			} );

			ajaxStatusUpdate( campaignData, $( this ), $checkboxes );
		} );

		// ----------------------------------------
		// Core Functions
		// ----------------------------------------

		function filterTableAndUpdatePagination( moduleId, searchTerm = '' ) {
			let visibleCount = 0;
			searchTerm = searchTerm.toLowerCase();

			$rows.each( function() {
				const $row = $( this );
				const rowModuleId = $row.attr( 'data-module-id' );
				const campaignName = $row.find( '.js-campaign-name' ).text().toLowerCase();
				const moduleName = $row.find( '.js-module-name' ).text().toLowerCase();

				const moduleMatch = ! moduleId || rowModuleId === moduleId;
				const searchMatch = ! searchTerm || campaignName.includes( searchTerm ) || moduleName.includes( searchTerm );

				if ( moduleMatch && searchMatch ) {
					$row.removeClass( 'filtered-out' );
					visibleCount++;
				} else {
					$row.addClass( 'filtered-out' );
				}
			} );

			totalRows  = visibleCount;
			totalPages = Math.max( 1, Math.ceil( totalRows / rowsPerPage ) );

			$pagination.toggle( totalPages > 1 );

			showCurrentPageRows();
			updatePaginationButtons();
			updateNoResults( visibleCount === 0 );
		}

		function showCurrentPageRows() {
			console.log(totalRows, rowsPerPage)

			const startIndex = ( currentPage - 1 ) * rowsPerPage;
			const endIndex = startIndex + rowsPerPage;

			$rows
				.hide()
				.addClass( 'is-hidden' );

			$rows.filter( ':not(.filtered-out)' ).each( function( index ) {
				if ( index >= startIndex && index < endIndex ) {
					$( this ).show().removeClass( 'is-hidden' );
				}
			} );

			updateRowStyles();

			// Uncheck "Select All" checkbox
			$table.find( 'thead th:first-child input[type="checkbox"]' ).prop( 'checked', false );
		}

		function updatePaginationButtons() {
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
		}

		// ----------------------------------------
		// Helper Functions
		// ----------------------------------------

		function sortTable( $th, sortBy, direction ) {
			const rows = $table.find( 'tbody tr' ).get();

			const sortedRows = rows.sort(( a, b ) => {
				const $a = $( a ).find( `td.merchant__${ sortBy }` );
				const $b = $( b ).find( `td.merchant__${ sortBy }` );
				const aValue = getCellValue( $a, sortBy );
				const bValue = getCellValue( $b, sortBy );

				return direction === 'asc'
					? ( aValue < bValue ? -1 : 1 )
					: ( aValue > bValue ? -1 : 1 );
			} );

			$table.find( 'th .sorting-indicators' ).removeClass( 'asc desc' );
			$th.find( '.sorting-indicators' ).addClass( direction );

			$table.find( 'tbody' ).empty().append( sortedRows );
			showCurrentPageRows();
		}

		function getCellValue( $cell, sortBy ) {
			const text = $cell.text().trim();

			switch ( sortBy ) {
				case 'campaign-name':
				case 'module-name':
					return text.toLowerCase();

				case 'impressions':
				case 'clicks':
				case 'orders':
					return parseInt( text.replace( /,/g, '' ) );

				case 'revenue':
				case 'ctr':
					return parseFloat( text.replace( /[^0-9.-]+/g, '' ) );

				default:
					return text.toLowerCase();
			}
		}

		function updateRowStyles() {
			const $visibleRows = $table.find( 'tbody tr:visible' );
			$visibleRows.removeClass( 'alternate' );
			$visibleRows.each( function( index ) {
				if ( index % 2 === 1 ) {
					$( this ).addClass( 'alternate' );
				}
			} );
		}

		function updateNoResults( show ) {
			let $noResults = $table.next( '.no-results-message' );

			if ( show ) {
				if ( !$noResults.length ) {
					$noResults = $( '<div class="no-results-message" style="text-align: center; padding: 20px;">No matching campaigns found</div>' );
					$table.after( $noResults );
				}
				$noResults.show();
			} else if ( $noResults.length ) {
				$noResults.hide();
			}
		}

		function ajaxStatusUpdate( campaignData, $el, $checkboxes, singleRow = false ) {
			const $loader = '<span class="spinner is-active"></span>';

			$el.prop( 'disabled', true );

			if ( singleRow ) {
				$el.closest( '.merchant-toggle-switch' ).append( $loader );
				$el.closest( 'tr' ).css( 'opacity', '.7' );
			} else {
				$table.css( 'opacity', '.7' );
				$el.closest( '.bulk-action' ).append( $loader );
			}

			$.ajax( {
				url: merchant?.ajax_url,
				type: 'POST',
				data: {
					action: 'merchant_update_campaign_status',
					nonce: merchant?.nonce,
					campaign_data: campaignData,
				},
				success: function( response ) {
					if ( ! response.success ) {
						return;
					}

					if ( ! singleRow ) {
						$checkboxes?.each( function() {
							$table.find( 'thead th:first-child input[type="checkbox"]' ).prop( 'checked', false );
							$( this )
								.prop( 'checked', false )
								.closest( 'tr' )
								.find( '.js-status input[type="checkbox"]' )
								.prop( 'checked', response.data.status === 'enable' );
						} );
					}

					$( document ).trigger( 'merchant_campaign_status_updated', [
						response.data,
						$el,
						$checkboxes,
						singleRow,
						campaignData
					] );
				},
				error: function( error ) {
					console.log( error );
				},
				complete: function() {
					$( '.spinner' ).remove();
					$el.prop( 'disabled', false );

					if ( singleRow ) {
						$el.closest( 'tr' ).css( 'opacity', '' );
					} else {
						$table.css( 'opacity', '' );
					}
				}
			} );
		}

		function debounce( func, wait ) {
			let timeout;
			return function( ...args ) {
				clearTimeout( timeout );
				timeout = setTimeout( () => func.apply( this, args ), wait );
			};
		}

		// Initial setup
		showCurrentPageRows();
		updatePaginationButtons();
	} );
} )( jQuery, window, document );
