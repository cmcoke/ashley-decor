<?php

/**
 * Ashley Decor functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ashley_decor
 */

if (! defined('ASHLEY_DECOR_VERSION')) {
  define('ASHLEY_DECOR_VERSION', '0.1.3'); // Incremented version
}

if (! defined('ASHLEY_DECOR_TYPOGRAPHY_CLASSES')) {
  define(
    'ASHLEY_DECOR_TYPOGRAPHY_CLASSES',
    'prose prose-neutral max-w-none prose-a:text-primary'
  );
}

/**
 * Theme Setup
 */
if (! function_exists('ashley_decor_setup')) :
  function ashley_decor_setup()
  {
    load_theme_textdomain('ashley-decor', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus(array(
      'menu-1' => __('Primary', 'ashley-decor'),
      'menu-2' => __('Footer Menu', 'ashley-decor'),
    ));

    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    ));

    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
    add_theme_support('responsive-embeds');
    remove_theme_support('block-templates');
  }
endif;
add_action('after_setup_theme', 'ashley_decor_setup');

/**
 * WooCommerce Support
 */
function ashley_decor_add_woocommerce_support()
{
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'ashley_decor_add_woocommerce_support');

/**
 * Enqueue Scripts and Styles
 */
function ashley_decor_scripts()
{
  // Tailwind / Base CSS
  wp_enqueue_style('ashley-decor-style', get_stylesheet_uri(), array(), ASHLEY_DECOR_VERSION);

  // Theme JS (Points to minified file for production)
  wp_enqueue_script('ashley-decor-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), ASHLEY_DECOR_VERSION, true);

  // Localize AJAX & Theme URL for JS + Security Nonce
  wp_localize_script('ashley-decor-script', 'ashleyData', array(
    'themeUrl' => get_template_directory_uri(),
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce'    => wp_create_nonce('ashley-ajax-nonce'),
  ));

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'ashley_decor_scripts');

/**
 * AJAX Update Cart Quantity - Persistent Session Version
 */
function ashley_decor_qty_cart()
{
  // 1. Verify Nonce
  check_ajax_referer('ashley-ajax-nonce', 'security');

  // 2. IMPORTANT: Manually initialize the session if it's missing (Fixes cart_count: 0)
  if (! is_user_logged_in() && ! WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
  }

  // 3. Force cart to load from the database/session
  if (is_null(WC()->cart)) {
    wc_load_cart();
  }

  // Explicitly calculate totals to ensure session synchronization
  WC()->cart->get_cart();

  // 4. Get and sanitize data
  $cart_item_key = isset($_POST['hash']) ? sanitize_text_field($_POST['hash']) : '';
  $quantity      = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

  // 5. Validation Check
  $cart_item = WC()->cart->get_cart_item($cart_item_key);

  if (! $cart_item) {
    wp_send_json_error(array(
      'message'       => 'Invalid cart item key.',
      'received_hash' => $cart_item_key,
      'cart_count'    => WC()->cart->get_cart_contents_count(), // If this is still 0, the session load failed
      'total_items'   => count(WC()->cart->get_cart())
    ));
  }

  // 6. Perform the update
  WC()->cart->set_quantity($cart_item_key, $quantity);

  wp_send_json_success();
}
add_action('wp_ajax_qty_cart', 'ashley_decor_qty_cart');
add_action('wp_ajax_nopriv_qty_cart', 'ashley_decor_qty_cart');

/**
 * Custom Callback for Reviews List
 * Ensures pending reviews show the stars correctly using the width % logic.
 */
function woocommerce_comments($comment, $args, $depth)
{
?>
<li <?php comment_class('review-item'); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>" class="comment_container">
    <div class="comment-text">
      <?php
        $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
        if ($rating > 0) :
          $percentage = ($rating / 5) * 100; ?>
      <div class="star-rating mb-2" title="Rated <?php echo esc_attr($rating); ?> out of 5">
        <span style="width:<?php echo esc_attr($percentage); ?>%"></span>
      </div>
      <?php endif; ?>

      <p class="meta">
        <strong class="woocommerce-review__author"><?php comment_author(); ?></strong>
        <?php if ('0' === $comment->comment_approved) : ?>
        <em class="text-theme-orange block text-xs italic">
          <?php esc_html_e('Your review is awaiting approval', 'woocommerce'); ?>
        </em>
        <?php endif; ?>
        <time class="woocommerce-review__published-date" datetime="<?php echo get_comment_date('c'); ?>">
          <?php echo get_comment_date(wc_date_format()); ?>
        </time>
      </p>

      <div class="description font-paragraph text-theme-grey">
        <?php comment_text(); ?>
      </div>
    </div>
  </div>
</li>
<?php
}

/**
 * Template Tags & Functions
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';

// Disable Default WooCommerce Styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Custom Star Rating HTML (For Display Only)
add_filter('woocommerce_get_star_rating_html', 'ashley_decor_custom_star_rating', 10, 3);
function ashley_decor_custom_star_rating($html, $rating, $count)
{

  $percentage = (floatval($rating) / 5) * 100;

  // If WooCommerce core has already provided a wrapper, 
  // we just want to provide the inner span to avoid nesting.
  if (strpos($html, 'class="star-rating"') !== false) {
    return '<span style="width:' . esc_attr($percentage) . '%"></span>';
  }

  // Otherwise, create the full structure
  $html = '<div class="star-rating" role="img" aria-label="Rated ' . esc_attr($rating) . ' out of 5">';
  $html .= '<span style="width:' . esc_attr($percentage) . '%"></span>';
  $html .= '</div>';

  return $html;
}

// Remove default WooCommerce star output to avoid duplicates
remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);