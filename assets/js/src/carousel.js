/**
 * Siema v1.5.1
 * Lightweight and simple carousel in pure JavaScript
 * https://pawelgrzybek.github.io/siema/
 *
 * Copyright (c) 2017 Paweł Grzybek (https://pawelgrzybek.com/)
 *
 * Released under the MIT License
 * 
 * Modified by aThemes
 * 
 * @cc_on 
 */

'use strict';

class Siema {
    /**
     * Create a Siema.
     * @param {Object} options - Optional settings object.
     */
    constructor(options) {

        // Merge defaults with user's settings
        this.config = Siema.mergeSettings(options);

        // Resolve parent selector's type
        this.parentSelector = typeof this.config.parentSelector === 'string' ? document.querySelector(this.config.parentSelector) : this.config.parentSelector;

        // Resolve selector's type
        this.selector = typeof this.config.selector === 'string' ? document.querySelector(this.config.selector) : this.config.selector;

        if( this.parentSelector ) {
            this.selector = this.parentSelector.querySelector( '.merchant-carousel-stage' );
        }

        // Early throw if selector doesn't exists
        if (this.selector === null) {
            throw new Error('Something wrong with your selector 😭');
        }

        if( this.parentSelector.getAttribute( 'data-initialized' ) === 'true' ) {
            return false;
        }

        // update perPage number dependable of user value
        this.resolveSlidesNumber();

        // Create global references
        this.selectorWidth = this.selector.offsetWidth;
        this.innerElements = [].slice.call(this.selector.children);
        this.currentSlide = this.config.loop ?
            this.config.startIndex % this.innerElements.length :
            Math.max(0, Math.min(this.config.startIndex, this.innerElements.length - this.perPage));
        this.transformProperty = Siema.webkitOrNot();

        // Bind all event handlers for referencability
        ['resizeHandler', 'touchstartHandler', 'touchendHandler', 'touchmoveHandler', 'mousedownHandler', 'mouseupHandler', 'mouseleaveHandler', 'mousemoveHandler', 'clickHandler', 'navNextHandler', 'navPrevHandler'].forEach(method => {
            this[method] = this[method].bind(this);
        });

        // Build markup and apply required styling to elements
        this.init();
    }

    /**
     * Overrides default settings with custom ones.
     * @param {Object} options - Optional settings object.
     * @returns {Object} - Custom Siema settings.
     */
    static mergeSettings(options) {
        const settings = {
            selector: '.siema',
            duration: 200,
            easing: 'ease-out',
            perPage: 1,
            startIndex: 0,
            draggable: true,
            multipleDrag: true,
            threshold: 20,
            loop: false,
            rtl: false,
            onInit: () => {},
            onChange: () => {},
        };

        const userSttings = options;
        for (const attrname in userSttings) {
            settings[attrname] = userSttings[attrname];
        }

        return settings;
    }

    /**
     * Determine if browser supports unprefixed transform property.
     * Google Chrome since version 26 supports prefix-less transform
     * @returns {string} - Transform property supported by client.
     */
    static webkitOrNot() {
        const style = document.documentElement.style;
        if (typeof style.transform === 'string') {
            return 'transform';
        }
        return 'WebkitTransform';
    }

    /**
     * Attaches listeners to required events.
     */
    attachEvents() {
        // Resize element on window resize
        window.addEventListener('resize', this.resizeHandler);

        // If element is draggable / swipable, add event handlers
        if (this.config.draggable) {
            // Keep track pointer hold and dragging distance
            this.pointerDown = false;
            this.drag = {
                startX: 0,
                endX: 0,
                startY: 0,
                letItGo: null,
                preventClick: false,
            };

            // Touch events
            this.selector.addEventListener('touchstart', this.touchstartHandler);
            this.selector.addEventListener('touchend', this.touchendHandler);
            this.selector.addEventListener('touchmove', this.touchmoveHandler);

            // Mouse events
            this.selector.addEventListener('mousedown', this.mousedownHandler);
            this.selector.addEventListener('mouseup', this.mouseupHandler);
            this.selector.addEventListener('mouseleave', this.mouseleaveHandler);
            this.selector.addEventListener('mousemove', this.mousemoveHandler);

            // Click
            this.selector.addEventListener('click', this.clickHandler);

            // Navigation
            this.parentSelector.querySelector( '.merchant-carousel-nav-next' ).addEventListener('click', this.navNextHandler);
            this.parentSelector.querySelector( '.merchant-carousel-nav-prev' ).addEventListener('click', this.navPrevHandler);
        }
    };

    /**
     * Detaches listeners from required events.
     */
    detachEvents() {
        window.removeEventListener('resize', this.resizeHandler);
        this.selector.removeEventListener('touchstart', this.touchstartHandler);
        this.selector.removeEventListener('touchend', this.touchendHandler);
        this.selector.removeEventListener('touchmove', this.touchmoveHandler);
        this.selector.removeEventListener('mousedown', this.mousedownHandler);
        this.selector.removeEventListener('mouseup', this.mouseupHandler);
        this.selector.removeEventListener('mouseleave', this.mouseleaveHandler);
        this.selector.removeEventListener('mousemove', this.mousemoveHandler);
        this.selector.removeEventListener('click', this.clickHandler);
        this.parentSelector.querySelector( '.merchant-carousel-nav-next' ).removeEventListener('click', this.navNextHandler);
        this.parentSelector.querySelector( '.merchant-carousel-nav-prev' ).removeEventListener('click', this.navPrevHandler);
    }

    /**
     * Builds the markup and attaches listeners to required events.
     */
    init() {

        // build navigation
        this.buildNavigation();

        this.attachEvents();

        // hide everything out of selector's boundaries
        this.selector.style.overflow = 'hidden';

        // rtl or ltr
        this.selector.style.direction = this.config.rtl ? 'rtl' : 'ltr';

        // build a frame and slide to a currentSlide
        this.buildSliderFrame();

        this.config.onInit.call(this);

        this.parentSelector.querySelector( '.merchant-carousel-stage' ).classList.add( 'show' );
        
        if( this.parentSelector !== null ) {
            this.parentSelector.setAttribute( 'data-initialized', true );
        }

    }

    buildNavigation() {
        var next       = document.createElement('a'),
            nextSVG    = document.createElementNS("http://www.w3.org/2000/svg", "svg"),
            prev       = document.createElement('a'),
            prevSVG    = document.createElementNS("http://www.w3.org/2000/svg", "svg");

        
        // Next button
        next.role = 'button';
        next.href = '#';
        next.className = 'merchant-carousel-nav merchant-carousel-nav-next';

        nextSVG.setAttribute( 'width', 18 );
        nextSVG.setAttribute( 'height', 18 );
        nextSVG.setAttribute( 'viewBox', '0 0 10 16' );
        nextSVG.setAttribute( 'fill', 'none' );
        nextSVG.setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
        nextSVG.setAttribute( 'class', 'stroke-based' );
        nextSVG.innerHTML = '<path d="M1.5 14.667L8.16667 8.00033L1.5 1.33366" stroke="#242021" stroke-width="1.5"></path>';
        
        next.append( nextSVG );
        this.parentSelector.querySelector( '.merchant-carousel-wrapper' ).append( next );

        // Prev button
        prev.role = 'button';
        prev.href = '#';
        prev.className = 'merchant-carousel-nav merchant-carousel-nav-prev';

        prevSVG.setAttribute( 'width', 18 );
        prevSVG.setAttribute( 'height', 18 );
        prevSVG.setAttribute( 'viewBox', '0 0 10 16' );
        prevSVG.setAttribute( 'fill', 'none' );
        prevSVG.setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
        prevSVG.setAttribute( 'class', 'stroke-based' );
        prevSVG.innerHTML = '<path d="M8.5 1.33301L1.83333 7.99967L8.5 14.6663" stroke="#242021" stroke-width="1.5"></path>';

        prev.append( prevSVG );
        this.parentSelector.querySelector( '.merchant-carousel-wrapper' ).append( prev );
    }

    /**
     * Build a sliderFrame and slide to a current item.
     */
    buildSliderFrame() {
        if( this.innerElements.length <= this.perPage ) {
            this.parentSelector.classList.add( 'no-nav' );
            this.parentSelector.querySelector( '.merchant-carousel-nav-next' )?.remove();
            this.parentSelector.querySelector( '.merchant-carousel-nav-prev' )?.remove();
            return false;
        }
    
        var widthItem = (this.selectorWidth + this.config.margin) / this.perPage;
        var itemsToBuild = this.config.loop ? this.innerElements.length + 2 * this.perPage : this.innerElements.length;
    
        // Create frame and apply styling
        this.sliderFrame = document.createElement('div');
        this.sliderFrame.style.width = widthItem * itemsToBuild + 'px';
        this.enableTransition();
    
        if (this.config.draggable) {
            this.selector.style.cursor = '-webkit-grab';
        }
    
        // Create a document fragment to put slides into it
        var docFragment = document.createDocumentFragment();
    
        // Loop through the slides, add styling and add them to document fragment
        if (this.config.loop) {
            for (var i = this.innerElements.length - this.perPage; i < this.innerElements.length; i++) {
                var element = this.buildSliderFrameItem(this.innerElements[i].cloneNode(true));
                docFragment.appendChild(element);
            }
        }
        for (var _i = 0; _i < this.innerElements.length; _i++) {
            var element = this.buildSliderFrameItem(this.innerElements[_i]);
            docFragment.appendChild(element);
        }
        if (this.config.loop) {
            for (var _i2 = 0; _i2 < this.perPage; _i2++) {
                var element = this.buildSliderFrameItem(this.innerElements[_i2].cloneNode(true));
                docFragment.appendChild(element);
            }
        }
    
        // Add fragment to the frame
        this.sliderFrame.appendChild(docFragment);
    
        // Clear selector (just in case something is there) and insert a frame
        this.selector.innerHTML = '';
        this.selector.appendChild(this.sliderFrame);
    
        // Go to currently active slide after initial build
        this.slideToCurrent();
    }

    buildSliderFrameItem(elm) {
        var elementContainer = document.createElement('div');
        elementContainer.style.cssFloat = this.config.rtl ? 'right' : 'left';
        elementContainer.style.float = this.config.rtl ? 'right' : 'left';
        elementContainer.style.width = (this.config.loop ? 100 / (this.innerElements.length + this.perPage * 2) : 100 / this.innerElements.length) + '%';
        elementContainer.style.marginRight = this.config.margin + 'px';
        elementContainer.appendChild(elm);
        return elementContainer;
    }

    /**
     * Determinates slides number accordingly to clients viewport.
     */
    resolveSlidesNumber() {
        if (typeof this.config.perPage === 'number') {
            this.perPage = this.config.perPage;
        } else if (typeof this.config.perPage === 'object') {
            this.perPage = 1;
            for (const viewport in this.config.perPage) {
                if (window.innerWidth >= viewport) {
                    this.perPage = this.config.perPage[viewport];
                }
            }
        }
    }

    /**
     * Go to previous slide.
     * @param {number} [howManySlides=1] - How many items to slide backward.
     * @param {function} callback - Optional callback function.
     */
    prev(howManySlides = 1, callback) {
        // early return when there is nothing to slide
        if (this.innerElements.length <= this.perPage) {
            return;
        }

        const beforeChange = this.currentSlide;

        if (this.config.loop) {
            const isNewIndexClone = this.currentSlide - howManySlides < 0;
            if (isNewIndexClone) {
                this.disableTransition();

                const mirrorSlideIndex = this.currentSlide + this.innerElements.length;
                const mirrorSlideIndexOffset = this.perPage;
                const moveTo = mirrorSlideIndex + mirrorSlideIndexOffset;
                const offset = (this.config.rtl ? 1 : -1) * moveTo * (this.selectorWidth / this.perPage);
                const dragDistance = this.config.draggable ? this.drag.endX - this.drag.startX : 0;

                this.sliderFrame.style[this.transformProperty] = `translate3d(${offset + dragDistance}px, 0, 0)`;
                this.currentSlide = mirrorSlideIndex - howManySlides;
            } else {
                this.currentSlide = this.currentSlide - howManySlides;
            }
        } else {
            this.currentSlide = Math.max(this.currentSlide - howManySlides, 0);
        }

        if (beforeChange !== this.currentSlide) {
            this.slideToCurrent(this.config.loop);
            this.config.onChange.call(this);
            if (callback) {
                callback.call(this);
            }
        }
    }

    /**
     * Go to next slide.
     * @param {number} [howManySlides=1] - How many items to slide forward.
     * @param {function} callback - Optional callback function.
     */
    next(howManySlides = 1, callback) {
        // early return when there is nothing to slide
        if (this.innerElements.length <= this.perPage) {
            return;
        }

        const beforeChange = this.currentSlide;

        if (this.config.loop) {
            const isNewIndexClone = this.currentSlide + howManySlides > this.innerElements.length - this.perPage;
            if (isNewIndexClone) {
                this.disableTransition();

                const mirrorSlideIndex = this.currentSlide - this.innerElements.length;
                const mirrorSlideIndexOffset = this.perPage;
                const moveTo = mirrorSlideIndex + mirrorSlideIndexOffset;
                const offset = (this.config.rtl ? 1 : -1) * moveTo * (this.selectorWidth / this.perPage);
                const dragDistance = this.config.draggable ? this.drag.endX - this.drag.startX : 0;

                this.sliderFrame.style[this.transformProperty] = `translate3d(${offset + dragDistance}px, 0, 0)`;
                this.currentSlide = mirrorSlideIndex + howManySlides;
            } else {
                this.currentSlide = this.currentSlide + howManySlides;
            }
        } else {
            this.currentSlide = Math.min(this.currentSlide + howManySlides, this.innerElements.length - this.perPage);
        }
        if (beforeChange !== this.currentSlide) {
            this.slideToCurrent(this.config.loop);
            this.config.onChange.call(this);
            if (callback) {
                callback.call(this);
            }
        }
    }

    /**
     * Disable transition on sliderFrame.
     */
    disableTransition() {
        this.sliderFrame.style.webkitTransition = `all 0ms ${this.config.easing}`;
        this.sliderFrame.style.transition = `all 0ms ${this.config.easing}`;
    }

    /**
     * Enable transition on sliderFrame.
     */
    enableTransition() {
        if( typeof this.sliderFrame === 'undefined' ) {
            return false;
        }
        this.sliderFrame.style.webkitTransition = `all ${this.config.duration}ms ${this.config.easing}`;
        this.sliderFrame.style.transition = `all ${this.config.duration}ms ${this.config.easing}`;
    }

    /**
     * Go to slide with particular index
     * @param {number} index - Item index to slide to.
     * @param {function} callback - Optional callback function.
     */
    goTo(index, callback) {
        if (this.innerElements.length <= this.perPage) {
            return;
        }
        const beforeChange = this.currentSlide;
        this.currentSlide = this.config.loop ?
            index % this.innerElements.length :
            Math.min(Math.max(index, 0), this.innerElements.length - this.perPage);
        if (beforeChange !== this.currentSlide) {
            this.slideToCurrent();
            this.config.onChange.call(this);
            if (callback) {
                callback.call(this);
            }
        }
    }

    /**
     * Moves sliders frame to position of currently active slide
     */
    slideToCurrent(enableTransition) {
        var _this = this;
        var currentSlide = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide;
        var offset = (this.config.rtl ? 1 : -1) * currentSlide * ((this.selectorWidth + this.config.margin) / this.perPage);

        if( typeof this.sliderFrame === 'undefined' ) {
            return false;
        }

        if (enableTransition) {
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    _this.enableTransition();
                    _this.sliderFrame.style[_this.transformProperty] = 'translate3d(' + offset + 'px, 0, 0)';
                });
            });
        } else {
            this.enableTransition();
            this.sliderFrame.style[this.transformProperty] = 'translate3d(' + offset + 'px, 0, 0)';
        }
    }

    /**
     * Recalculate drag /swipe event and reposition the frame of a slider
     */
    updateAfterDrag() {
        const movement = (this.config.rtl ? -1 : 1) * (this.drag.endX - this.drag.startX);
        const movementDistance = Math.abs(movement);
        const howManySliderToSlide = this.config.multipleDrag ? Math.ceil(movementDistance / (this.selectorWidth / this.perPage)) : 1;

        const slideToNegativeClone = movement > 0 && this.currentSlide - howManySliderToSlide < 0;
        const slideToPositiveClone = movement < 0 && this.currentSlide + howManySliderToSlide > this.innerElements.length - this.perPage;

        if (movement > 0 && movementDistance > this.config.threshold && this.innerElements.length > this.perPage) {
            this.prev(howManySliderToSlide);
        } else if (movement < 0 && movementDistance > this.config.threshold && this.innerElements.length > this.perPage) {
            this.next(howManySliderToSlide);
        }
        this.slideToCurrent(slideToNegativeClone || slideToPositiveClone);
    }

    /**
     * When window resizes, resize slider components as well
     */
    resizeHandler() {
        // update perPage number dependable of user value
        this.resolveSlidesNumber();

        // relcalculate currentSlide
        // prevent hiding items when browser width increases
        if (this.currentSlide + this.perPage > this.innerElements.length) {
            this.currentSlide = this.innerElements.length <= this.perPage ? 0 : this.innerElements.length - this.perPage;
        }

        this.selectorWidth = this.selector.offsetWidth;

        this.buildSliderFrame();
    }

    /**
     * Clear drag after touchend and mouseup event
     */
    clearDrag() {
        this.drag = {
            startX: 0,
            endX: 0,
            startY: 0,
            letItGo: null,
            preventClick: this.drag.preventClick
        };
    }

    /**
     * touchstart event handler
     */
    touchstartHandler(e) {
        // Prevent dragging / swiping on inputs, selects and textareas
        const ignoreSiema = ['TEXTAREA', 'OPTION', 'INPUT', 'SELECT'].indexOf(e.target.nodeName) !== -1;
        if (ignoreSiema) {
            return;
        }

        e.stopPropagation();
        this.pointerDown = true;
        this.drag.startX = e.touches[0].pageX;
        this.drag.startY = e.touches[0].pageY;
    }

    /**
     * touchend event handler
     */
    touchendHandler(e) {
        e.stopPropagation();
        this.pointerDown = false;
        this.enableTransition();
        if (this.drag.endX) {
            this.updateAfterDrag();
        }
        this.clearDrag();
    }

    /**
     * touchmove event handler
     */
    touchmoveHandler(e) {
        e.stopPropagation();

        if (this.drag.letItGo === null) {
        this.drag.letItGo = Math.abs(this.drag.startY - e.touches[0].pageY) < Math.abs(this.drag.startX - e.touches[0].pageX);
        }

        if (this.pointerDown && this.drag.letItGo) {
        e.preventDefault();
        this.drag.endX = e.touches[0].pageX;
        this.sliderFrame.style.webkitTransition = `all 0ms ${this.config.easing}`;
        this.sliderFrame.style.transition = `all 0ms ${this.config.easing}`;

        const currentSlide = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide;
        const currentOffset = currentSlide * ((this.selectorWidth + this.config.margin) / this.perPage);
        const dragOffset = (this.drag.endX - this.drag.startX);
        const offset = this.config.rtl ? currentOffset + dragOffset : currentOffset - dragOffset;
        this.sliderFrame.style[this.transformProperty] = `translate3d(${(this.config.rtl ? 1 : -1) * offset}px, 0, 0)`;
        }
    }

    /**
     * mousedown event handler
     */
    mousedownHandler(e) {
        // Prevent dragging / swiping on inputs, selects and textareas
        const ignoreSiema = ['TEXTAREA', 'OPTION', 'INPUT', 'SELECT'].indexOf(e.target.nodeName) !== -1;
        if (ignoreSiema) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();
        this.pointerDown = true;
        this.drag.startX = e.pageX;
    }

    /**
     * mouseup event handler
     */
    mouseupHandler(e) {
        e.stopPropagation();
        this.pointerDown = false;
        this.selector.style.cursor = '-webkit-grab';
        this.enableTransition();
        if (this.drag.endX) {
            this.updateAfterDrag();
        }
        this.clearDrag();
    }

    /**
     * mousemove event handler
     */
    mousemoveHandler(e) {
        e.preventDefault();
        if (this.pointerDown) {
        // if dragged element is a link
        // mark preventClick prop as a true
        // to detemine about browser redirection later on
        if ( typeof e.target.closest( 'a' ) !== 'null' ) {
            this.drag.preventClick = true;
        }
        if( typeof this.sliderFrame === 'undefined' ) {
            return false;
        }
        this.drag.endX = e.pageX;
        this.selector.style.cursor = '-webkit-grabbing';
        this.sliderFrame.style.webkitTransition = `all 0ms ${this.config.easing}`;
        this.sliderFrame.style.transition = `all 0ms ${this.config.easing}`;

        const currentSlide = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide;
        const currentOffset = currentSlide * ((this.selectorWidth + this.config.margin) / this.perPage);
        const dragOffset = (this.drag.endX - this.drag.startX);
        const offset = this.config.rtl ? currentOffset + dragOffset : currentOffset - dragOffset;
        this.sliderFrame.style[this.transformProperty] = `translate3d(${(this.config.rtl ? 1 : -1) * offset}px, 0, 0)`;
        }
    }

    /**
     * mouseleave event handler
     */
    mouseleaveHandler(e) {
        if (this.pointerDown) {
            this.pointerDown = false;
            this.selector.style.cursor = '-webkit-grab';
            this.drag.endX = e.pageX;
            this.drag.preventClick = false;
            this.enableTransition();
            this.updateAfterDrag();
            this.clearDrag();
        }
    }

    /**
     * click event handler
     */
    clickHandler(e) {
        // if the dragged element is a link
        // prevent browsers from folowing the link
        if (this.drag.preventClick) {
            e.preventDefault();
        }
        this.drag.preventClick = false;
    }

    navNextHandler(e) {
        e.preventDefault();
        this.next(1);
    }

    navPrevHandler(e) {
        e.preventDefault();
        this.prev(1);
    }

    /**
     * Remove item from carousel.
     * @param {number} index - Item index to remove.
     * @param {function} callback - Optional callback to call after remove.
     */
    remove(index, callback) {
        if (index < 0 || index >= this.innerElements.length) {
            throw new Error('Item to remove doesn\'t exist 😭');
        }

        // Shift sliderFrame back by one item when:
        // 1. Item with lower index than currenSlide is removed.
        // 2. Last item is removed.
        const lowerIndex = index < this.currentSlide;
        const lastItem = this.currentSlide + this.perPage - 1 === index;

        if (lowerIndex || lastItem) {
            this.currentSlide--;
        }

        this.innerElements.splice(index, 1);

        // build a frame and slide to a currentSlide
        this.buildSliderFrame();

        if (callback) {
            callback.call(this);
        }
    }

    /**
     * Insert item to carousel at particular index.
     * @param {HTMLElement} item - Item to insert.
     * @param {number} index - Index of new new item insertion.
     * @param {function} callback - Optional callback to call after insert.
     */
    insert(item, index, callback) {
        if (index < 0 || index > this.innerElements.length + 1) {
            throw new Error('Unable to inset it at this index 😭');
        }
        if (this.innerElements.indexOf(item) !== -1) {
            throw new Error('The same item in a carousel? Really? Nope 😭');
        }

        // Avoid shifting content
        const shouldItShift = index <= this.currentSlide > 0 && this.innerElements.length;
        this.currentSlide = shouldItShift ? this.currentSlide + 1 : this.currentSlide;

        this.innerElements.splice(index, 0, item);

        // build a frame and slide to a currentSlide
        this.buildSliderFrame();

        if (callback) {
            callback.call(this);
        }
    }

    /**
     * Prepernd item to carousel.
     * @param {HTMLElement} item - Item to prepend.
     * @param {function} callback - Optional callback to call after prepend.
     */
    prepend(item, callback) {
        this.insert(item, 0);
        if (callback) {
            callback.call(this);
        }
    }

    /**
     * Append item to carousel.
     * @param {HTMLElement} item - Item to append.
     * @param {function} callback - Optional callback to call after append.
     */
    append(item, callback) {
        this.insert(item, this.innerElements.length + 1);
        if (callback) {
            callback.call(this);
        }
    }

    /**
     * Removes listeners and optionally restores to initial markup
     * @param {boolean} restoreMarkup - Determinants about restoring an initial markup.
     * @param {function} callback - Optional callback function.
     */
    destroy(restoreMarkup = false, callback) {
        this.detachEvents();

        this.selector.style.cursor = 'auto';

        if (restoreMarkup) {
            const slides = document.createDocumentFragment();
            for (let i = 0; i < this.innerElements.length; i++) {
                slides.appendChild(this.innerElements[i]);
            }
            this.selector.innerHTML = '';
            this.selector.appendChild(slides);
            this.selector.removeAttribute('style');
        }

        if (callback) {
            callback.call(this);
        }
    }
}

/**
 * Merchant Carousel
 * 
 */

let merchant = merchant || {};

merchant.carousel = {

    domReady: function( fn ) {
		if ( typeof fn !== 'function' ) {
			return;
		}
	
		if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
			return fn();
		}

        const that = this;
	
		document.addEventListener( 'DOMContentLoaded', function () {
            that.init(); // Required for some themes
        } );

        jQuery( document ).on( 'photoSliderTriggered', function() {
            that.init();
        } )
	},
	init: function() {
		this.build();
		this.events();
	},
	build: function() {
		if( document.querySelector( '.merchant-carousel' ) === null && document.querySelector( '.has-cross-sells-carousel' ) === null && document.querySelector( '.merchant-woocommerce-mini-cart__cross-sell' ) === null ) {
			return false;
		}

		var carouselEls  = document.querySelectorAll( '.merchant-carousel, #masthead .cross-sells, .merchant-side-mini-cart .cross-sells, .cart-collaterals .cross-sells' );
		for( var carouselEl of carouselEls ) {
			if( carouselEl.querySelector( '.merchant-carousel-stage' ) === null ) {
				carouselEl.querySelector( '.products' ).classList.add( 'merchant-carousel-stage' );
			} 

			if( carouselEl.getAttribute( 'data-initialized' ) !== 'true' ) {
				
				var perPage = carouselEl.getAttribute( 'data-per-page' );
				if( perPage === null ) {
					var stageClassList = carouselEl.querySelector( '.products' ).classList.value;

					[ 1, 2, 3, 4, 5 ].forEach( function( columns ) {
						if( stageClassList.indexOf( 'columns-' + columns ) > 0 ) {
							perPage = columns;
						}
					});
				}

                let loop = carouselEl.getAttribute( 'data-loop' ) !== '0';

				
				// Mount carousel wrapper
				var	wrapper = document.createElement('div'),
					stage 	= carouselEl.querySelector( '.merchant-carousel-stage' );

				wrapper.className = 'merchant-carousel-wrapper';
				wrapper.innerHTML = stage.outerHTML;
				stage.remove();

				carouselEl.append( wrapper );

				// Margin
				let margin = 30;
				if( typeof merchant_carousel !== 'undefined' ) {
					margin = parseInt( merchant_carousel.margin_desktop );
				} else if( carouselEl.closest( '.merchant-woocommerce-mini-cart__cross-sell' ) !== null ) {
					margin = 15;
				}

				// Initialize
				var carousel = new Siema({
					parentSelector: carouselEl,
					selector: '.merchant-carousel-stage',
					duration: 200,
					easing: 'ease-out',
					perPage: perPage !== null ? {
						0: 1,
						768: 2,
						1025: parseInt( perPage )
					} : 2,
					startIndex: 0,
					draggable: true,
					multipleDrag: false,
					threshold: 20,
					loop,
					rtl: false,
					// autoplay: true, TO DO
					margin: margin,
					onInit: function() {
                        window.dispatchEvent( new Event( 'merchant.carousel.initialized' ) );

                        // Fix for theme that has lazy-load but not working
                        this?.innerElements?.forEach( item => {
                           const img = item.querySelector( 'img' );
                           const src = img?.getAttribute( 'src' );

                           if ( src?.startsWith( 'data' ) ) {
                               img.src = img?.getAttribute( 'data-src' )
                           }
                       } );
					}
				});
			}
		}
	},
	events: function() {
		var _this = this;

		if( typeof jQuery !== 'undefined' ) {
			var onpageload = true;

			jQuery( document.body ).on( 'wc_fragment_refresh added_to_cart removed_from_cart', function(){
				setTimeout(function(){
					var mini_cart 	   = document.getElementById( 'site-header-cart' ),
						mini_cart_list = mini_cart?.querySelector( '.cart_list' );

					if( mini_cart_list !== null ) {
						if( mini_cart_list?.children.length > 2 ) {
							mini_cart?.classList.remove( 'mini-cart-has-no-scroll' );
							mini_cart?.classList.add( 'mini-cart-has-scroll' );
						} else {
							mini_cart?.classList.remove( 'mini-cart-has-scroll' );
							mini_cart?.classList.add( 'mini-cart-has-no-scroll' );
						}
					}
					_this.build();

					onpageload = false;
				}, onpageload ? 1000 : 0 );
			});
		}
	}
}

// Initialize.
merchant.carousel.domReady( function(){
    merchant?.carousel?.init();
} );

jQuery( document ).on( 'photoSliderTriggered', function() {
    merchant?.carousel?.init();
} )