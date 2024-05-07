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
<div class="popup layout-2">
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
                <a href="#" title="Album">
                    <div class="grey_background" style="width: 200px; height: 200px;"></div>
                </a>
            </div>
            <div class="popup-product-content">
                <div class="popup-product-name">
                    <a href="#">Your Product’s Name</a>
                </div>
                <p class="popup-product-description">An amazing product you can't refuse. Briefly expand your benefits.</p>
                <div class="popup-product-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">£</span>15.00</span></div>
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
        <div class="suggested-products">
            <div class="buy-x-get-y suggested-products-content hidden">
                <h3 class="section-title"><?php
				    esc_html_e( 'Buy X Get Y', 'merchant' ); ?></h3>
                <div class="offer-products">
                    <div class="offer-column">
                        <div class="offer-product first-product">
                            <div class="offer-title" style="color: #fff;background-color: #d61313;">
							    <?php
							    esc_html_e( 'Buy 1', 'merchant' ); ?>
                            </div>
                            <div class="image-wrapper">
                                <a href="#">
                                    <div class="grey_background" style="height: 123px; width: 123px"></div>
                                </a>
                            </div>
                            <div class="product-summary">
                                <a href="#">
                                    <h3><?php
									    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
                                <div class="product-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
										    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>15.00</span></div>
                            </div>
                            <div class="arrow-icon" style="background-color: #3858e9;">
                                <svg width="15" height="8" viewBox="0 0 15 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.1031 4.29105C14.2983 4.09579 14.2983 3.77921 14.1031 3.58395L10.9211 0.401966C10.7258 0.206704 10.4092 0.206704 10.214 0.401966C10.0187 0.597228 10.0187 0.913811 10.214 1.10907L13.0424 3.9375L10.214 6.76593C10.0187 6.96119 10.0187 7.27777 10.214 7.47303C10.4092 7.6683 10.7258 7.6683 10.9211 7.47303L14.1031 4.29105ZM0.59375 4.4375H13.7495V3.4375H0.59375V4.4375Z"
                                            fill="#fff"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="offer-column">
                        <div class="offer-product second-product" style="border-color: #2b2b2b; border-radius: 5px;">
                            <div class="offer-title" style="color: #fff;background-color: #d61313;">
							    <?php
							    esc_html_e( 'Get 3 with 50% off', 'merchant' ); ?>
                            </div>
                            <div class="image-wrapper">
                                <a href="#">
                                    <div class="grey_background" style="height: 123px; width: 123px"></div>
                                </a>
                            </div>
                            <div class="product-summary">
                                <a href="#">
                                    <h3><?php
									    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
                                <div class="product-price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
										    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>25.00</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="frequently-bought-together-popup suggested-products-content hidden">
                <h3 class="section-title"><?php
				    esc_html_e( 'Frequently Bought Together', 'merchant' ); ?></h3>
                <div class="offer-products">
                    <div class="offer-column">
                        <div class="offer-product main-product">
                            <div class="image-wrapper">
                                <a href="#">
                                    <div class="grey_background" style="height: 158px; width: 158px"></div>
                                </a>
                            </div>
                            <div class="product-summary">
                                <a href="#">
                                    <h3><?php
									    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
                                <div class="product-price">
                                    <span class="woocommerce-Price-amount amount">
                                        <span class="woocommerce-Price-currencySymbol"><?php
	                                        echo esc_html( get_woocommerce_currency_symbol() ); ?>
                                        </span>
                                        15.00
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="offer-column half-width">
                        <div class="offer-icon">
                            <span class="plus-icon"></span>
                        </div>
                    </div>
                    <div class="offer-column slider-area">
                        <div class="offer-product" data-slick-index="0" aria-hidden="false" style="width: 256px;" tabindex="0">
                            <div class="image-wrapper">
                                <a href="#" tabindex="0">
                                    <div class="grey_background" style="height: 158px; width: 158px"></div>
                                </a>
                            </div>
                            <div class="product-summary">
                                <a href="#" tabindex="0">
                                    <h3><?php
									    esc_html_e( 'Product Name', 'merchant' ); ?></h3>
                                </a>
                                <div class="product-price">
                                    <del aria-hidden="true"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
											    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>18.00</span></del>
                                    <span class="screen-reader-text"><?php
									    esc_html_e( 'Original price was:', 'merchant' ); ?> <?php
									    echo esc_html( get_woocommerce_currency_symbol() ); ?>18.00.</span>
                                    <ins aria-hidden="true"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
											    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>16.00</span></ins>
                                    <span class="screen-reader-text"><?php
									    esc_html_e( 'Current price is:', 'merchant' ); ?> <?php
									    echo esc_html( get_woocommerce_currency_symbol() ); ?>16.00.</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="offer-column narrow">
                        <div class="offer-price">
                            <h4>
							    <?php
							    esc_html_e( 'Bundle Price', 'merchant' ); ?>
                            </h4>
                            <del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
									    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>31.00</span></del>
                            <ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php
									    echo esc_html( get_woocommerce_currency_symbol() ); ?></span>15.50</span></ins>
                        </div>
                    </div>
                </div>
            </div>
            <div class="recently-viewed-products slider-products suggested-products-content hidden">
                <h3 class="section-title"><?php
				    esc_html_e( 'Recently Viewed Products', 'merchant' ); ?></h3>
                <ul class="products-list">
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
            <div class="related-products slider-products suggested-products-content hidden">
                <h3 class="section-title"><?php
				    esc_html_e( 'Related Products', 'merchant' ); ?></h3>
                <ul class="products-list">
                    <li class="product">
                        <div class="image-wrapper">
                            <a href="#" tabindex="-1">
                                <div class="grey_background" style="height: 186px; width: 186px"></div>
                            </a>
                        </div>
                        <div class="product-summary">
                            <a href="#" tabindex="-1">
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
                                <h3><?php
								    esc_html_e( 'Product Name', 'merchant' ); ?></h3></a>
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
        <div class="merchant-hide-mobile">
            <div class="popup-actions">
                <a href="#" class="merchant-button button-filled view-cart">
                    View Cart </a>
                <a href="#" class="merchant-button continue-shopping popup-close-js">
                    Continue Shopping </a>
                <a href="#" class="merchant-button checkout">Checkout</a>
            </div>
        </div>
    </div>
</div>