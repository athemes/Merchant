<?php
/**
 * Template for added to cart popup layouts admin preview.
 *
 * @var $args array template args
 *
 * @since 1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="popup layout-1">
    <div class="popup-header">
        <h3 class="popup-header-title">Added to cart</h3>
        <div class="popup-close">
            <span class="close-button popup-close-js" title="Close">
                <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.333 1.43359L1.73047 10.0361M1.73047 1.43359L10.333 10.0361" stroke="black" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                </svg>
            </span>
        </div>
    </div>
    <div class="popup-body">
        <div class="added-product">
            <div class="popup-product-image">
                <a href="#" target="_blank" title="Album">
                    <div class="grey_background" style="width: 200px; height: 200px;"></div>
                </a>
            </div>
            <div class="popup-product-content">
                <div class="popup-product-name">
                    <a href="#" target="_blank">Your Product’s Name</a>
                </div>
                <p class="popup-product-description">An amazing product you can't refuse. Briefly expand your benefits.</p>
                <div class="popup-product-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
							echo esc_html( get_woocommerce_currency_symbol() ); ?></span>15.00</span></div>
                <div class="popup-cart-info">
                    <div class="info-item shipping-cost">
                        <span class="info-label">Shipping Cost</span>
                        <span class="info-value"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
						            echo esc_html( get_woocommerce_currency_symbol() ); ?></span>20.00</span></span>
                    </div>
                    <div class="info-item tax-amount">
                        <span class="info-label">Tax Amount</span>
                        <span class="info-value"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
						            echo esc_html( get_woocommerce_currency_symbol() ); ?></span>2.00</span></span>
                    </div>
                    <div class="info-item cart-total">
                        <span class="info-label">Cart Total</span>
                        <span class="info-value"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
						            echo esc_html( get_woocommerce_currency_symbol() ); ?></span>884.00</span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup-actions">
            <a href="#" class="merchant-button button-filled view-cart">
                View Cart </a>
            <a href="#" class="merchant-button continue-shopping popup-close-js">
                Continue Shopping </a>
            <a href="#" class="merchant-button checkout">Checkout</a>
        </div>
        <div class="suggested-products">
            <div class="recently-viewed-products">
                <h3 class="section-title">Recently Viewed Products</h3>
                <ul class="viewed-products">
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3>Product Name</h3></a>
                            <div class="product-price">
                            <span class="woocommerce-Price-amount amount">
                                <span class="woocommerce-Price-currencySymbol">
                                    <?php
                                    echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                </span>33.00</span>
                            </div>
                        </div>
                    </li>
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3>Product Name</h3></a>
                            <div class="product-price">
                            <span class="woocommerce-Price-amount amount">
                                <span class="woocommerce-Price-currencySymbol">
                                    <?php
                                    echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                </span>18.00</span> – <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
							            echo esc_html( get_woocommerce_currency_symbol() ); ?></span>45.00</span>
                            </div>
                        </div>
                    </li>
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3>Product Name</h3></a>
                            <div class="product-price">
                            <span class="woocommerce-Price-amount amount">
                                <span class="woocommerce-Price-currencySymbol">
                                    <?php
                                    echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                </span>11.00</span>
                            </div>
                        </div>
                    </li>
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3>Product Name</h3></a>
                            <div class="product-price">
                            <span class="woocommerce-Price-amount amount">
                                <span class="woocommerce-Price-currencySymbol">
                                    <?php
                                    echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                </span>45.00</span>
                            </div>
                        </div>
                    </li>
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3>Product Name</h3></a>
                            <div class="product-price">
                            <span class="woocommerce-Price-amount amount">
                                <span class="woocommerce-Price-currencySymbol">
                                    <?php
                                    echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                </span>37.00</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
