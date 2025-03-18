<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="merchant-single-product-preview">
    <div class="mrc-preview-single-product-elements">
        <div class="mrc-preview-left-column">
            <div class="mrc-preview-product-image-wrapper">
                <div class="mrc-preview-product-image"></div>
                <div class="mrc-preview-product-image-thumbs">
                    <div class="mrc-preview-product-image-thumb"></div>
                    <div class="mrc-preview-product-image-thumb"></div>
                    <div class="mrc-preview-product-image-thumb"></div>
                </div>
            </div>
        </div>
        <div class="mrc-preview-right-column">
            <div class="main-product-name"><?php
				echo esc_html__( 'Your Product Name', 'merchant' ); ?></div>
            <div class="product-price">
                <del>$55</del>
                <ins>$45</ins>
            </div>
            <div class="main-product-description"><?php
				echo esc_html__( 'Product description', 'merchant' ); ?></div>
            <div class="mrc-preview-bundle-wrapper slider mrc-mw-60">
                <h4 class="mrc-preview-bundle-title">
					<?php
					echo esc_html__( 'This is bundle title', 'merchant' ); ?>
                </h4>
                <p class="mrc-preview-bundle-description">
					<?php
					echo esc_html__( 'This is bundle description', 'merchant' ); ?>
                </p>
                <div class="products">
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-checkbox">
                            <input type="checkbox" disabled="disabled" checked>
                        </div>
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"><?php
								echo esc_html__( 'Your Product Name', 'merchant' ); ?></div>
                            <div class="product-price">
                                <del>$55</del>
                                <ins>$45</ins>
                            </div>
                        </div>
                    </div>
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-checkbox">
                            <input type="checkbox" disabled="disabled" checked>
                        </div>
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"><?php
								echo esc_html__( 'Your Product Name', 'merchant' ); ?></div>
                            <div class="product-price">
                                <del>$55</del>
                                <ins>$45</ins>
                            </div>
                        </div>
                    </div>
                    <div class="mrc-preview-bundle-product">
                        <div class="mrc-preview-bundle-checkbox">
                            <input type="checkbox" disabled="disabled" checked>
                        </div>
                        <div class="mrc-preview-bundle-product-image"></div>
                        <div class="mrc-preview-bundle-product-info">
                            <div class="mrc-preview-bundle-product-title"><?php
								echo esc_html__( 'Your Product Name', 'merchant' ); ?></div>
                            <div class="product-price">
                                <del>$55</del>
                                <ins>$45</ins>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mrc-preview-addtocart-placeholder"></div>
        </div>
    </div>
</div>