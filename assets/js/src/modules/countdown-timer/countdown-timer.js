;( function( $, window, document, undefined ) {
    'use strict';

    $( document ).ready( function( $ ) {
        new CountDownTimer( $ );
    } );

} )( jQuery, window, document );

const defaultStyles = {
    classic: {
        labelsColor: '',
        digitsColor: '#444444',
        digitsBackground: '',
        borderColor: '',
        fontSizeDigits: 16,
        fontSizeLabels: 16,
    },
    progress: {
        labelsColor: '',
        digitsColor: '#3858E9',
        digitsBackground: '#3858E9',
        borderColor: '',
        progressColor: '#3858E9',
        fontSizeDigits: 16,
        fontSizeLabels: 16,
    },
    circles: {
        labelsColor: '#7C7C7C',
        digitsColor: '#000',
        digitsBackground: '#fff',
        borderColor: '#444444',
        progressColor: '#3858E9',
        fontSizeDigits: 22,
        fontSizeLabels: 10,
        width: 80,
        height: 80,
    },
    squares: {
        labelsColor: '#180B40',
        digitsColor: '#000',
        digitsBackground: '#fff',
        borderColor: '#000000',
        fontSizeDigits: 28,
        fontSizeLabels: 12,
        width: 65,
        height: 65,
    },
    minimalist: {
        labelsColor: '#7C7C7C',
        digitsColor: '#000000',
        digitsBackground: '#F6F7F7',
        borderColor: '',
        fontSizeDigits: 40,
        fontSizeLabels: 12,
        width: 80,
        height: 80,
    },
    cards: {
        labelsColor: '#3858E9',
        digitsColor: '#3858E9',
        digitsBackground: '#E0E2EC',
        borderColor: '#000000',
        fontSizeDigits: 40,
        fontSizeLabels: 12,
        width: 80,
        height: 80,
    },
    modern: {
        labelsColor: '#180B40',
        digitsColor: '#fff',
        digitsBackground: '#180B40',
        borderColor: '',
        fontSizeDigits: 36,
        fontSizeLabels: 12,
        width: 80,
        height: 95,
    },
}

class CountDownTimer {
    constructor( $ ) {
        this.countDownWrapper = $( '.merchant-countdown-timer' );
        this.countDownTimer   = this.countDownWrapper.find( '.merchant-countdown-timer-countdown' );

        if ( ! this.countDownTimer.length ) {
            return;
        }

        this.total;
        this.days;
        this.hours;
        this.minutes;
        this.seconds;

        this.currentTimeInitial = this.getCurrentTime();

        this.theme     = this.countDownWrapper.attr( 'data-theme' );
        this.timerType = this.countDownWrapper.attr( 'data-countdown-type' );

        this.startDate = new Date( this.countDownWrapper.attr( 'data-start-date'  ) || null );
        this.endDate   = new Date( this.countDownWrapper.attr( 'data-end-date'  ) || null );

        this.startTime = this.startDate.getTime();
        this.endTime   = this.endDate.getTime();

        this.minExpiration = parseInt( this.countDownWrapper.attr( 'data-min-expiration' ) );
        this.maxExpiration = parseInt( this.countDownWrapper.attr( 'data-max-expiration' ) );
        this.offPeriod = parseInt( this.countDownWrapper.attr( 'data-off-period' ) );

        this.timerCookie = 'merchant_countdown_timer';
        this.timerOffPeriodCookie = 'merchant_countdown_timer_off_period';

        this.variationsDates = this.countDownWrapper.attr( 'data-variations-dates' );

        this.variationsDates = this.variationsDates ? JSON.parse( this.variationsDates ) : [];

        this.flipTimerKeys = {
            days: { value: '', currentValue: '' },
            hours: { value: '', currentValue: '' },
            minutes: { value: '', currentValue: '' },
            seconds: { value: '', currentValue: '' },
        }

        // Clear cookie page load on admin
        if ( merchant?.is_admin ) {
            this.deleteCookie( this.timerCookie );
            this.deleteCookie( this.timerOffPeriodCookie );
        }

        this.init();
    }

    init() {
        this.buildTimerMarkup();
        this.setTimerData();
        this.startTimer();

        this.events();
    }

    events() {
        this.updateVariationTimer();
        this.updateAdminPreview();
    }

    buildTimerMarkup() {
        let countDownEl;

        if ( [ 'classic', 'progress' ].includes( this.theme ) ) {
            countDownEl = `<span></span>`;
        } else if ( this.theme === 'circles' ) {
            countDownEl = `
                <div class="cd-days">
                    <svg class="cd-svg">
                        <circle class="circle-static" r="47%" cx="50%" cy="50%"></circle>
                        <circle class="circle-dynamic" r="47%" cx="50%" cy="50%"></circle>
                    </svg>
                    <div>
                        <span class="cd-value">00</span>
                        <span class="cd-label">Days</span>
                    </div>
                </div>
                <div class="cd-hours">
                    <svg class="cd-svg">
                        <circle class="circle-static" r="47%" cx="50%" cy="50%"></circle>
                        <circle class="circle-dynamic" r="47%" cx="50%" cy="50%"></circle>
                    </svg>
                    <div>
                        <span class="cd-value">00</span>
                        <span class="cd-label">Hours</span>
                    </div>
                </div>
                <div class="cd-minutes">
                    <svg class="cd-svg">
                        <circle class="circle-static" r="47%" cx="50%" cy="50%"></circle>
                        <circle class="circle-dynamic" r="47%" cx="50%" cy="50%"></circle>
                    </svg>
                    <div>
                        <span class="cd-value">00</span>
                        <span class="cd-label">Minutes</span>
                    </div>
                </div>
                <div class="cd-seconds">
                    <svg class="cd-svg">
                        <circle class="circle-static" r="47%" cx="50%" cy="50%"></circle>
                        <circle class="circle-dynamic" r="47%" cx="50%" cy="50%"></circle>
                    </svg>
                    <div>
                        <span class="cd-value">00</span>
                        <span class="cd-label">Seconds</span>
                    </div>
                </div>
            `;
        } else if ( this.theme === 'modern' ) {
            countDownEl = `
                <div class="cd-flip">
                    <div class="cd-flip-card cd-days">
                        <div class="cd-flip-card-wrapper">
                            <div class="cd-flip-card-inner">
                                <span class="cd-flip-card-top cd-value">00</span>
                                <span class="cd-flip-card-back" data-time="00">
                                    <span class="cd-flip-card-bottom" data-time="00"></span>
                                </span>
                            </div>
                        </div>
                        <span class="cd-flip-clock-label cd-label">Days</span>
                    </div>

                    <div class="cd-flip-card cd-hours">
                        <div class="cd-flip-card-wrapper">
                            <div class="cd-flip-card-inner">
                                <span class="cd-flip-card-top cd-value">00</span>
                                <span class="cd-flip-card-back" data-time="00">
                                    <span class="cd-flip-card-bottom" data-time="00"></span>
                                </span>
                            </div>
                        </div>
                        <span class="cd-flip-clock-label cd-label">Hours</span>
                    </div>

                    <div class="cd-flip-card cd-minutes">
                        <div class="cd-flip-card-wrapper">
                            <div class="cd-flip-card-inner">
                                <span class="cd-flip-card-top cd-value">00</span>
                                <span class="cd-flip-card-back" data-time="00">
                                    <span class="cd-flip-card-bottom" data-time="00"></span>
                                </span>
                            </div>
                        </div>
                        <span class="cd-flip-clock-label cd-label">Minutes</span>
                    </div>
                    
                    <div class="cd-flip-card cd-seconds">
                        <div class="cd-flip-card-wrapper">
                            <div class="cd-flip-card-inner">
                                <span class="cd-flip-card-top cd-value">00</span>
                                <span class="cd-flip-card-back" data-time="00">
                                    <span class="cd-flip-card-bottom" data-time="00"></span>
                                </span>
                            </div>
                        </div>
                        <span class="cd-flip-clock-label cd-label">Seconds</span>
                    </div>

                </div>
            `;
        } else {
            countDownEl = `
                <div class="cd-days">
                    <span class="cd-value" data-time="00">00</span>
                    <span class="cd-label">Days</span>
                </div>
                <div class="cd-hours">
                    <span class="cd-value" data-time="00">00</span>
                    <span class="cd-label" data-time="">Hours</span>
                </div>
                <div class="cd-minutes">
                    <span class="cd-value" data-time="00">00</span>
                    <span class="cd-label">Minutes</span>
                </div>
                <div class="cd-seconds">
                    <span class="cd-value" data-time="00">00</span>
                    <span class="cd-label">Seconds</span>
                </div>
            `;
        }

        this.countDownWrapper.find( '.cd-progress' ).remove();

        // Add the timer
        this.countDownTimer.html( countDownEl );
        if ( this.theme === 'progress' ) {
            jQuery( '<progress class="cd-progress" value="0" max="100"></progress>' ).insertBefore( this.countDownTimer );
        }
    }

    setTimerData() {
        if ( this.timerType === 'sale-dates' ) {
            this.endTime = this.endDate.getTime();
        } else if ( this.timerType === 'evergreen' ) {
            // Get data from cookie
            const cookieTimer     = this.getCookie( this.timerCookie );
            const cookieOffPeriod = this.getCookie( this.timerOffPeriodCookie );

            const randomTimeInSeconds = this.getRandomValueForCountDown( this.minExpiration, this.maxExpiration );
            const timeInMilliseconds = randomTimeInSeconds * 1000;

            const futureTime = this.getCurrentTime() + timeInMilliseconds;
            const futureDate = new Date( futureTime );

            const offPeriodMilliseconds = futureTime + ( this.offPeriod * 1000 );

            // If off-period found in cookie but not the timer, means countdown was finished and now it's off-period time.
            // So don't start the countdown now.
            const startCountDown = ! ( ! cookieTimer && cookieOffPeriod );

            // If no countdown started yet or off-period is finishes, start the countdown
            if ( startCountDown ) {
                if ( ! cookieTimer ) {
                    this.setCookie( this.timerCookie, futureDate, futureTime );
                    this.setCookie( this.timerOffPeriodCookie, this.offPeriod, offPeriodMilliseconds );

                    this.endTime = futureDate.getTime();
                } else {
                    // If cookie found
                    this.endTime = new Date( cookieTimer ).getTime();

                    if ( ! cookieOffPeriod ) {
                        this.setCookie( this.timerOffPeriodCookie, this.offPeriod, offPeriodMilliseconds );
                    }
                }
            } else {
                this.endTime = 0;
                this.displayTimer( 0 );
            }
        }
    }

    startTimer() {
        const remaining = this.getRemainingTime();
        if ( remaining ) {
            this.displayTimer( remaining, this.timerEnd )
        } else {
            this.timerEnd();
        }

        if ( this.theme === 'circles' ) {
            this.progressCircles();
        }  else if ( this.theme === 'progress' ) {
            this.progressBar();
        }
    }

    displayTimer( timeLeftInMilliseconds, timerEndCallback ) {
        timerEndCallback = timerEndCallback || function() {};

        const classicOrProgress = [ 'classic', 'progress' ].includes( this.theme );

        this.days = Math.floor( timeLeftInMilliseconds / ( 1000 * 60 * 60 * 24 ) );
        this.days = classicOrProgress ? this.days : this.days.toString().padStart( 2, '0' );

        this.hours = Math.floor( ( timeLeftInMilliseconds % ( 1000 * 60 * 60 * 24 ) ) / ( 1000 * 60 * 60 ) ).toString().padStart( 2, '0' );
        this.minutes = Math.floor( ( timeLeftInMilliseconds % ( 1000 * 60 * 60)) / ( 1000 * 60 ) ).toString().padStart( 2, '0' );
        this.seconds = Math.floor( ( timeLeftInMilliseconds % ( 1000 * 60 ) ) / 1000 ).toString().padStart( 2, '0' );

        if ( classicOrProgress ) {
            this.countDownTimer.find( 'span' ).html( `${this.days} <span>days ${ this.theme === 'progress' ? ' : ' : '' } </span> ${this.hours}:${this.minutes}:${this.seconds}` );
        } else if ( this.theme === 'modern' ) {
            this.flipTimer();
        } else {
            this.countDownTimer.find( '.cd-days .cd-value' ).html( this.days ).attr( 'data-time', this.days );
            this.countDownTimer.find( '.cd-hours .cd-value' ).html( this.hours ).attr( 'data-time', this.hours );
            this.countDownTimer.find( '.cd-minutes .cd-value' ).html( this.minutes ).attr( 'data-time', this.minutes );
            this.countDownTimer.find( '.cd-seconds .cd-value' ).html( this.seconds ).attr( 'data-time', this.seconds );
        }

        if ( timeLeftInMilliseconds <= 0 ) {
            timerEndCallback.call( this );
        } else {
            this.countDownWrapper.show();
            setTimeout( this.startTimer.bind( this ), 1000 );
        }
    }

    timerEnd() {
        // Keep showing the timer on admin even if countdown finishes
        merchant?.is_admin ? this.countDownWrapper.show() : this.countDownWrapper.hide();

        this.displayTimer( 0 );
        this.deleteCookie( this.timerCookie );
    }

    updateVariationTimer() {
        if ( this.timerType !== 'sale-dates' ) {
            return;
        }

        const $ = jQuery;

        const self = this;

        $( 'input[name="variation_id"]' ).on( 'change', function() {
            const currentVariationId = parseInt( $( this ).val() );
            if ( currentVariationId && self.variationsDates.length ) {
                const currentVariation = self.variationsDates.find( date => parseInt( date?.id ) === currentVariationId );

                const { start, end } = currentVariation || {};

                const startDate = typeof start === 'object' ? start?.date : start;
                const endDate = typeof end === 'object' ? end?.date : end;

                // Update global data
                self.startTime = startDate ? new Date( startDate ).getTime() : 0;
                self.endTime   = endDate ? new Date( endDate ).getTime() : 0;

                self.displayTimer( 0 );
                self.startTimer();
            }
        } );
    }

    flipTimer() {
        const self = this;
        const $flipCards = this.countDownTimer.find( '.cd-flip-card' );

        $flipCards.each( function() {
            const $card = jQuery( this );
            let flipKey = '';

            Object.keys( self.flipTimerKeys ).forEach( function( unit ) {
                if ( $card.hasClass( 'cd-' + unit ) ) {
                    flipKey = unit;
                    return false;
                }
            } );

            const currentValue = self.flipTimerKeys[ flipKey ].currentValue;
            const newVal = self[ flipKey ];

            if ( newVal !== currentValue ) {
                const top = $card.find( '.cd-flip-card-top' );
                const back = $card.find( '.cd-flip-card-back' );
                const backBottom = $card.find( '.cd-flip-card-back .cd-flip-card-bottom' );

                if ( currentValue >= 0 ) {
                    back.attr( 'data-time', currentValue );
                }

                self.flipTimerKeys[ flipKey ].currentValue = newVal;

                top.html( newVal );

                backBottom.attr( 'data-time', newVal );

                $card.removeClass( 'cd-flipped' );

                void $card[0].offsetWidth;

                $card.addClass( 'cd-flipped' );
            }
        } );
    }

    getCurrentTime( time = true ) {
        const date = new Date();
        return time ? date.getTime() : date;
    }

    getRemainingTime() {
        const currentTime = this.getCurrentTime();

        if ( this.startTime > currentTime || this.endTime < currentTime ) {
            return 0;
        }

        return Math.max( this.endTime - currentTime, 0 );
    }

    progressBar() {
        const currentTime= this.getCurrentTime();

        // Set start time to current time when page loads if not already defined.
        this.startTime = this.startTime || this.currentTimeInitial;

        this.total = this.endTime - this.startTime;
        const passedTime = currentTime - this.startTime;

        const passedTimePercent = ( passedTime / this.total ) * 100;

        const timeLeftPercent = 100 - passedTimePercent;

        const $progressBar = this.countDownWrapper.find( '.cd-progress' );

        if ( $progressBar.length ) {
            $progressBar.attr( 'value', Math.floor( timeLeftPercent ) );
        }
    }

    progressCircles() {
        const circleWidth  = parseInt( this.countDownWrapper.find( '.merchant-countdown-timer-countdown' ).css( '--merchant-digits-width' ) );

        const _seconds = 60;

        // 3 times circleWidth fits `stroke-dasharray` properly
        const strokeDasharray = ( parseInt( circleWidth ) * 3 );

        const getStrokeOffset = ( remainingSeconds ) => {
            const step = strokeDasharray / 60;
            return ( _seconds - parseInt( remainingSeconds ) ) * step;
        }

        this.countDownTimer.find( '.circle-dynamic' ).css( 'stroke-dasharray', strokeDasharray );

        const elements = {
            days: {
                $el : this.countDownTimer.find( '.cd-days .circle-dynamic' ),
                offset: getStrokeOffset( this.days ),
            },
            hours: {
                $el : this.countDownTimer.find( '.cd-hours .circle-dynamic' ),
                offset: getStrokeOffset( this.hours ),
            },
            minutes: {
                $el : this.countDownTimer.find( '.cd-minutes .circle-dynamic' ),
                offset: getStrokeOffset( this.minutes ),
            },
            seconds: {
                $el : this.countDownTimer.find( '.cd-seconds .circle-dynamic' ),
                offset: getStrokeOffset( this.seconds ),
            },
        }

        for ( const key in elements ) {
            if ( Object.hasOwnProperty.call( elements, key ) ) {
                const { $el, offset } = elements[ key ] || {};
                if ( $el.length ) {
                    $el.css( 'stroke-dashoffset', offset )
                }
            }
        }
    }

    setCookie( name, value, expiration ) {
        const date = new Date( expiration );

        const expires = `expires=${ date.toUTCString() }`;
        document.cookie = `${ name }=${ value };${ expires };path=/`;
    }

    getCookie( name ) {
        const cookies = document.cookie.split( ';' );

        for ( let cookie of cookies ) {
            const [ cookieName, cookieValue ] = cookie.trim().split( '=' );
            if ( cookieName === name ) {
                return decodeURIComponent( cookieValue );
            }
        }

        return null;
    }

    deleteCookie( name ) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

    getRandomValueForCountDown ( min, max ) {
        return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
    }

    // Admin preview
    updateAdminPreview() {
        const self = this;
        const $ = jQuery;

        // On save
        $( document ).on( 'save.merchant', function ( e, module ) {
            if ( module === 'countdown-timer' ) {
                self.deleteCookie( self.timerOffPeriodCookie );
            }
        } );

        // On timer type select
        $( document ).on( 'change', '.merchant-field-end_date select', function () {
            self.timerType = $( this ).val();

            self.deleteCookie( self.timerCookie );
            self.deleteCookie( self.timerOffPeriodCookie );

            self.init();
        } );

        // On theme select
        $( document ).on( 'change', '.merchant-choices-theme input', function () {
            self.theme = $( '.merchant-choices-theme input:checked' ).val();

            $( '.merchant-choices-theme input' ).each( function() {
                self.countDownWrapper.removeClass( 'merchant-countdown-timer-' + $( this ).val() );
            } );

            // Add the class and update attr corresponding to the currently selected theme
            self.countDownWrapper.attr( 'data-theme', self.theme ).addClass( 'merchant-countdown-timer-' + self.theme );

            // Re-build the timer
            self.buildTimerMarkup();

            // Change to default styles
            self.updateStyles();
        } );

        // On date select
        $( document ).on( 'change.merchant-datepicker', function ( e, date, input ) {
            if ( ! input ) {
                return;
            }

            self.displayTimer( 0 );

            const name = input?.attr( 'name' );

            // Update timer data
            if ( name === 'merchant[sale_start_date]' ) {
                self.startTime = new Date( date );
            }

            if ( name === 'merchant[sale_end_date]' ) {
                self.endTime = new Date( date );
            }

            // Hide
            self.countDownTimer.hide();

            // Start timer
            self.startTimer();

            // Show
            setTimeout( () => self.countDownTimer.show(), 500 );
        } );

        // On evergreen fields change
        let timer = null;
        $( document ).on( 'input', '.merchant-countdown-evergreen-field input', function() {
            self.displayTimer( 0 );

            clearTimeout( timer );

            timer = setTimeout( () => {
                let minDays = 0;
                let maxDays = 0
                let minHours = 2;
                let maxHours = 26;
                let minMinutes = 0;
                let maxMinutes = 0;

                self.deleteCookie( self.timerCookie );
                self.deleteCookie( self.timerOffPeriodCookie );

                $( '.merchant-countdown-evergreen-field input' ).each( function() {
                    const name = $( this ).attr( 'name' );
                    const value = +$( this ).val();

                    switch ( name ) {
                        case 'merchant[min_expiration_deadline_days]':
                            minDays = value;
                            break;
                        case 'merchant[max_expiration_deadline_days]':
                            maxDays = value;
                            break;
                        case 'merchant[min_expiration_deadline]':
                            minHours = value;
                            break;
                        case 'merchant[max_expiration_deadline]':
                            maxHours = value;
                            break;
                        case 'merchant[min_expiration_deadline_minutes]':
                            minMinutes = value;
                            break;
                        case 'merchant[max_expiration_deadline_minutes]':
                            maxMinutes = value;
                            break;
                    }
                } );

                self.minExpiration = minDays * 24 * 60 * 60 + minHours * 60 * 60 + minMinutes * 60;
                self.maxExpiration = maxDays * 24 * 60 * 60 + maxHours * 60 * 60 + maxMinutes * 60;

                self.setTimerData();
                self.startTimer();
            }, 500 );
        } );

        $( document ).on( 'input', '.merchant-field-cool_off_period input', function() {
            self.clearOffPeriodCookie = true;
        } );

        // On alignment change
        $( document ).on( 'change', '.merchant-field-sale_ending_alignment select', function() {
            $( this ).find( 'option' ).each( function() {
                self.countDownWrapper.removeClass( 'merchant-countdown-timer--' + $( this ).val() );
            } );

            self.countDownWrapper.addClass( 'merchant-countdown-timer--' + $( this ).val() );
        } );
    }

    updateStyles() {
        const $ = jQuery;

        const properties = {
            'digits_font_size': 'fontSizeDigits',
            'labels_font_size': 'fontSizeLabels',
            'labels_color': 'labelsColor',
            'digits_color': 'digitsColor',
            'digits_background': 'digitsBackground',
            'progress_color': 'progressColor',
            'digits_border': 'borderColor',
            'digits_width': 'width',
            'digits_height': 'height'
        };

        for ( const [ inputName, propertyName ] of Object.entries( properties ) ) {
            const value = defaultStyles[ this.theme ]?.[ propertyName ];
            if ( value ) {
                $( `input[name="merchant[${inputName}]"]` )
                    .val( value )
                    .attr( 'value', value )
                    .trigger( 'input' )
                    .trigger( 'change' );
            }
        }
    }
}
