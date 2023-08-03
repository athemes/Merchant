/**
 * Merchant Pagination.
 * 
 */

'use strict';

var merchant = merchant || {};
merchant.pagination = {
  // Defaults are set to woocommerce pagination, but we can change 
  // these defaults to make blog pagination for example
  defaults: {
    button: document.querySelector('.merchant-pagination-button'),
    pagination: document.querySelector('.woocommerce-pagination'),
    next: document.querySelector('.woocommerce-pagination .next'),
    itemsWrapper: document.querySelector('.site-main .products'),
    itemsTag: 'li',
    items: '.site-main .products .product',
    currentPage: 1,
    totalPages: document.querySelector('.merchant-pagination-button') !== null ? parseInt(document.querySelector('.merchant-pagination-button').getAttribute('data-total-pages')) : 0,
    infiniteScroll: document.querySelector('.merchant-pagination-button') !== null && document.querySelector('.merchant-pagination-button').getAttribute('data-pagination-type') === 'infinite-scroll' ? true : false,
    eventPrefix: 'merchant.shop',
    triggerOffset: 200
  },
  // To ensure better compatibility with plugins like WP Rocket that has
  // options to defer/lazy-load JS files, each JS script should have your own 
  // 'domReady' function. This way the script has no dependecies and can be loaded standalone.
  domReady: function domReady(fn) {
    if (typeof fn !== 'function') {
      return;
    }
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
  },
  init: function init() {
    if (typeof this.defaults.button === 'undefined') {
      return false;
    }
    if (null === this.defaults.pagination) {
      return false;
    }
    var _this = this,
      is_mobile = matchMedia('screen and (max-width: 767px)').matches ? true : false;
    this.ename = is_mobile ? 'touchend' : 'click';
    this.defaults.button.addEventListener('click', this.loadMoreButtonEventHandler.bind(this));
    this.defaults.button.addEventListener('touchend', this.loadMoreButtonEventHandler.bind(this));

    // Infinite Scroll
    if (this.defaults.infiniteScroll) {
      this.infiniteScroll();

      // Check if the button it's on view in the first load of the page
      if (this.isAlmostInViewport(this.defaults.button)) {
        this.defaults.button.dispatchEvent(new Event(this.ename));
      }
    }
    window.dispatchEvent(new Event(_this.defaults.eventPrefix + '.pagination.initialized'));
  },
  loadMoreButtonEventHandler: function loadMoreButtonEventHandler(e) {
    e.preventDefault();
    if (!this.defaults.button.classList.contains('loading')) {
      this.loadMorePosts(this.defaults);
    }
    this.defaults.button.classList.add('loading');
  },
  loadMorePosts: function loadMorePosts() {
    if (this.defaults.currentPage >= this.defaults.totalPages) {
      return false;
    }
    var _this = this,
      ajax = new XMLHttpRequest(),
      nextURL = this.defaults.next.getAttribute('href');
    ajax.open('GET', nextURL, true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        var html = document.createElement('html');
        html.innerHTML = this.responseText;

        // Masonry
        var is_masonry_layout = typeof Masonry === 'function' && _this.defaults.itemsWrapper.classList.contains('masonry-brick'),
          msnry = is_masonry_layout ? Masonry.data(_this.defaults.itemsWrapper.parentElement) : '';
        var is_shop_masonry_layout = typeof Masonry === 'function' && _this.defaults.itemsWrapper.classList.contains('masonry'),
          shop_masonry_first_item = is_shop_masonry_layout ? _this.defaults.itemsWrapper.querySelector(_this.defaults.items) : '',
          shop_msnry = is_shop_masonry_layout ? Masonry.data(_this.defaults.itemsWrapper) : '';
        var items = html.querySelectorAll(_this.defaults.items);
        for (var i = 0; i < items.length; i++) {
          var list_item = document.createElement(_this.defaults.itemsTag);
          list_item.setAttribute('class', items[i].classList.value);
          if (!is_masonry_layout && !is_shop_masonry_layout) {
            list_item.classList.add('mrc-animated');
            list_item.classList.add('mrcPagFadeInShort');
            list_item.classList.add('mrc-anim-duration-300ms');
            list_item.classList.add('mrc-anim-fowards');
            list_item.setAttribute('style', 'animation-delay: ' + i * 200 + 'ms;');
          }
          list_item.innerHTML = items[i].innerHTML;
          _this.defaults.itemsWrapper.append(list_item);
          if (is_masonry_layout) {
            msnry.appended(list_item);
            msnry.layout();
          } else if (is_shop_masonry_layout) {
            list_item.style.width = shop_masonry_first_item.style.width;
            list_item.style.marginBottom = shop_masonry_first_item.style.marginBottom;
            shop_msnry.appended(list_item);
            shop_msnry.layout();
          }
        }
        _this.defaults.button.classList.remove('loading');
        _this.maybeInitExtraFeatures();
        _this.updateNextURL();

        // Infinite Scroll
        if (_this.defaults.infiniteScroll && _this.isAlmostInViewport(_this.defaults.button)) {
          _this.defaults.button.dispatchEvent(new Event('click'));
        }
        _this.hideButton();
        window.dispatchEvent(new Event(_this.defaults.eventPrefix + '.pagination.items.added'));
      }
    };
    ajax.send();
  },
  updateNextURL: function updateNextURL() {
    var nextPage = this.defaults.currentPage < this.defaults.totalPages ? this.defaults.currentPage + 1 : this.defaults.currentPage,
      nextURL = this.defaults.next.getAttribute('href').replace('/page/' + nextPage, '/page/' + (nextPage + 1));
    if (this.defaults.next.getAttribute('href').indexOf('paged=') > 0) {
      nextURL = this.defaults.next.getAttribute('href').replace('paged=' + nextPage, 'paged=' + (nextPage + 1));
    } else if (this.defaults.next.getAttribute('href').indexOf('/comment-page-') > 0) {
      nextURL = this.defaults.next.getAttribute('href').replace('/comment-page-' + nextPage, '/comment-page-' + (nextPage + 1));
    } else if (this.defaults.next.getAttribute('href').indexOf('cpage=') > 0) {
      nextURL = this.defaults.next.getAttribute('href').replace('cpage=' + nextPage, 'cpage=' + (nextPage + 1));
    }
    this.defaults.currentPage++;
    this.defaults.next.setAttribute('href', nextURL);
  },
  hideButton: function hideButton() {
    if (this.defaults.currentPage >= this.defaults.totalPages) {
      this.defaults.button.remove();
    }
  },
  infiniteScroll: function infiniteScroll() {
    var _this = this;
    window.addEventListener('scroll', function () {
      if (_this.isAlmostInViewport(_this.defaults.button)) {
        _this.defaults.button.dispatchEvent(new Event(_this.ename));
      }
    });
  },
  maybeInitExtraFeatures: function maybeInitExtraFeatures() {
    // Quick View
    if (document.querySelector('.merchant-quick-view-popup') !== null) {
      if (typeof merchant.quickView !== 'undefined') {
        merchant.quickView.init();
      }
    }

    // Wishlist
    if (document.querySelector('.merchant-wishlist-button') !== null) {
      if (typeof merchant.wishList !== 'undefined') {
        merchant.wishList.init();
      }
    }

    // AddToAny Share Buttons
    if (typeof a2a !== 'undefined') {
      a2a.init_all();
    }
  },
  isAlmostInViewport: function isAlmostInViewport(el) {
    var rect = el.getBoundingClientRect();
    return rect.bottom - this.defaults.triggerOffset <= (window.innerHeight || document.documentElement.clientHeight);
  }
};

// Initialize.
merchant.pagination.domReady(setTimeout(function () {
  merchant.pagination.init();
}, 100));