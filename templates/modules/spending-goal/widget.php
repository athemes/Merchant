<?php
/**
 * Template for Spending Discount Goal Widget content.
 *
 * @var $args array template args
 *
 * @since 1.0
 */

defined( 'ABSPATH' ) || exit;

$is_shortcode        = $args['is_shortcode'] ?? false;
$spending_percentage = $args['spending_percentage'] ?? 0;

if ( $spending_percentage >= 100 ) {
	$text = $args['text_goal_reached'] ?? '';
} elseif ( $spending_percentage > 0 ) {
	$text = $args['text_goal_started'] ?? '';
} else {
	$text = $args['text_goal_zero'] ?? '';
}

$classes   = array( 'merchant-spending-goal-widget' );
$classes[] = 'merchant-spending-goal-widget__' . ( $is_shortcode ? 'shortcode' : 'regular' );
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
    <div class="merchant-spending-goal-widget-content">
        <div class="merchant-spending-goal-widget-text"><?php echo wp_kses( $text, merchant_kses_allowed_tags( array( 'bdi' ) ) ); ?></div>
        <div class="merchant-spending-goal-widget-progress-bar">
            <div class="merchant-spending-goal-widget-progress-bar-content" style="width:<?php echo esc_attr( $spending_percentage ); ?>%">
                <div class="merchant-spending-goal-widget-progress-bar-filled"></div>
            </div>
        </div>
    </div>

    <div class="merchant-spending-goal-widget-label">
        <div class="merchant-spending-goal-widget-label-content">
            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.5 19.75C7.89782 19.75 8.27936 19.592 8.56066 19.3107C8.84196 19.0294 9 18.6478 9 18.25C9 17.8522 8.84196 17.4706 8.56066 17.1893C8.27936 16.908 7.89782 16.75 7.5 16.75C7.10218 16.75 6.72064 16.908 6.43934 17.1893C6.15804 17.4706 6 17.8522 6 18.25C6 18.6478 6.15804 19.0294 6.43934 19.3107C6.72064 19.592 7.10218 19.75 7.5 19.75ZM17.25 19.75C17.6478 19.75 18.0294 19.592 18.3107 19.3107C18.592 19.0294 18.75 18.6478 18.75 18.25C18.75 17.8522 18.592 17.4706 18.3107 17.1893C18.0294 16.908 17.6478 16.75 17.25 16.75C16.8522 16.75 16.4706 16.908 16.1893 17.1893C15.908 17.4706 15.75 17.8522 15.75 18.25C15.75 18.6478 15.908 19.0294 16.1893 19.3107C16.4706 19.592 16.8522 19.75 17.25 19.75Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0 1C0 0.801088 0.0790176 0.610322 0.21967 0.46967C0.360322 0.329018 0.551088 0.25 0.75 0.25H2.327C2.6531 0.24985 2.97036 0.355972 3.23074 0.552295C3.49112 0.748617 3.68043 1.02444 3.77 1.338L4.53 4H20.786C20.902 4.00018 21.0163 4.02726 21.1201 4.0791C21.2239 4.13095 21.3142 4.20615 21.3839 4.2988C21.4537 4.39144 21.5011 4.49901 21.5222 4.61305C21.5434 4.72708 21.5379 4.84448 21.506 4.956L19.032 13.618C18.8977 14.0881 18.614 14.5016 18.2237 14.7961C17.8334 15.0906 17.3579 15.2499 16.869 15.25H7.88C7.39111 15.2499 6.91555 15.0906 6.52529 14.7961C6.13502 14.5016 5.85128 14.0881 5.717 13.618L3.247 4.973C3.24341 4.96208 3.24008 4.95108 3.237 4.94L2.327 1.75H0.75C0.551088 1.75 0.360322 1.67098 0.21967 1.53033C0.0790176 1.38968 0 1.19891 0 1ZM4.959 5.5L7.16 13.206C7.20476 13.3627 7.29934 13.5005 7.42943 13.5987C7.55952 13.6969 7.71804 13.75 7.881 13.75H16.869C17.0318 13.7497 17.1901 13.6965 17.32 13.5984C17.4499 13.5003 17.5443 13.3625 17.589 13.206L19.792 5.5H4.959Z" fill="currentColor"/>
                <path d="M11.8679 12V7H13.1321V12H11.8679ZM10 10.1321V8.86794H15V10.1321H10Z" fill="currentColor"/>
            </svg>
			<?php echo esc_html( $spending_percentage ) . '%'; ?>
        </div>
    </div>
</div>
