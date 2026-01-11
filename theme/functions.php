<?php

/**
 * Ashley' Decor functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ashley_decor
 */

if (! defined('ASHLEY_DECOR_VERSION')) {
  define('ASHLEY_DECOR_VERSION', '0.1.2');
}

if (! defined('ASHLEY_DECOR_TYPOGRAPHY_CLASSES')) {
  define(
    'ASHLEY_DECOR_TYPOGRAPHY_CLASSES',
    'prose prose-neutral max-w-none prose-a:text-primary'
  );
}

if (! function_exists('ashley_decor_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   */
  function ashley_decor_setup()
  {
    load_theme_textdomain('ashley-decor', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus(
      array(
        'menu-1' => __('Primary', 'ashley-decor'),
        'menu-2' => __('Footer Menu', 'ashley-decor'),
      )
    );

    add_theme_support(
      'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
      )
    );

    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
    add_theme_support('responsive-embeds');
    remove_theme_support('block-templates');
  }
endif;
add_action('after_setup_theme', 'ashley_decor_setup');

/**
 * Register widget area.
 */
function ashley_decor_widgets_init()
{
  register_sidebar(
    array(
      'name'          => __('Footer', 'ashley-decor'),
      'id'            => 'sidebar-1',
      'description'   => __('Add widgets here to appear in your footer.', 'ashley-decor'),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    )
  );
}
add_action('widgets_init', 'ashley_decor_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function ashley_decor_scripts()
{
  wp_enqueue_style('ashley-decor-style', get_stylesheet_uri(), array(), ASHLEY_DECOR_VERSION);
  wp_enqueue_script('ashley-decor-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), ASHLEY_DECOR_VERSION, true);

  // Correct way to load the WooCommerce Review JS logic handles star selection
  if (is_product()) {
    wp_enqueue_script('reviews');
  }

  wp_localize_script('ashley-decor-script', 'ashleyData', array(
    'themeUrl' => get_template_directory_uri(),
    'ajax_url' => admin_url('admin-ajax.php'),
  ));

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'ashley_decor_scripts');

/**
 * Enqueue the block editor script.
 */
function ashley_decor_enqueue_block_editor_script()
{
  $current_screen = function_exists('get_current_screen') ? get_current_screen() : null;

  if (
    $current_screen &&
    $current_screen->is_block_editor() &&
    'widgets' !== $current_screen->id
  ) {
    wp_enqueue_script(
      'ashley-decor-editor',
      get_template_directory_uri() . '/js/block-editor.min.js',
      array('wp-blocks', 'wp-edit-post'),
      ASHLEY_DECOR_VERSION,
      true
    );
    wp_add_inline_script('ashley-decor-editor', "tailwindTypographyClasses = '" . esc_attr(ASHLEY_DECOR_TYPOGRAPHY_CLASSES) . "'.split(' ');", 'before');
  }
}
add_action('enqueue_block_assets', 'ashley_decor_enqueue_block_editor_script');

/**
 * Add the Tailwind Typography classes to TinyMCE.
 */
function ashley_decor_tinymce_add_class($settings)
{
  $settings['body_class'] = ASHLEY_DECOR_TYPOGRAPHY_CLASSES;
  return $settings;
}
add_filter('tiny_mce_before_init', 'ashley_decor_tinymce_add_class');

/**
 * Template Tag Requirements
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';

/**
 * WooCommerce theme support
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
 * Disable default WooCommerce styles for Tailwind control
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * AJAX Shopping Bag Fragments
 */
function woocommerce_header_add_to_cart_fragment($fragments)
{
  // 1. Update Badge Count
  ob_start();
  $count = WC()->cart->get_cart_contents_count();
  $display_count = ($count > 10) ? '10+' : $count;
?>
<a class="cart-customlocation block" href="<?php echo esc_url(wc_get_cart_url()); ?>">
  <div class="relative inline-block">
    <img src="<?php echo get_theme_file_uri('images/shopping-bag.webp') ?>" alt="shopping bag"
      class="max-w-[1.5rem] h-auto cursor-pointer">
    <?php if ($count > 0) : ?>
    <span
      class="absolute -bottom-1 -right-1 bg-theme-orange text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none min-w-[18px] h-[18px] flex items-center justify-center border-2 border-white">
      <?php echo $display_count; ?>
    </span>
    <?php endif; ?>
  </div>
</a>
<?php
  $fragments['a.cart-customlocation'] = ob_get_clean();

  // 2. Update Mini-Cart Content
  ob_start();
  ?>
<div id="mini-cart-content" class="widget_shopping_cart_content">
  <?php woocommerce_mini_cart(); ?>
</div>
<?php
  $fragments['#mini-cart-content'] = ob_get_clean();

  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

/**
 * Mini-Cart Quantity Input Filter
 */
add_filter('woocommerce_widget_cart_item_quantity', 'ashley_decor_mini_cart_quantity_input', 10, 3);
function ashley_decor_mini_cart_quantity_input($html, $cart_item, $cart_item_key)
{
  $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

  if ($_product && $_product->is_visible()) {
    $price = $_product->get_price();
    $currency_symbol = get_woocommerce_currency_symbol();
    $input = woocommerce_quantity_input(array(
      'input_value' => $cart_item['quantity'],
      'max_value'   => $_product->get_max_purchase_quantity(),
      'min_value'   => '0', // Allow zero for removal
    ), $_product, false);

    // We manually add the remove link back in here
    $remove_link = sprintf(
      '<a href="%s" class="remove-item-link text-xs text-red-500 underline mt-2 inline-block font-paragraph" aria-label="Remove this item" data-product_id="%s" data-item-key="%s">Remove</a>',
      esc_url(wc_get_cart_remove_url($cart_item_key)),
      esc_attr($_product->get_id()),
      esc_attr($cart_item_key)
    );

    return sprintf(
      '<div class="mini-cart-item-meta mt-1">
                <span class="mini-cart-item-price block text-sm text-gray-600 mb-2 font-heading">Price: %s%s</span>
                <div class="mini-cart-qty font-heading" data-item-key="%s">%s</div>
                %s
            </div>',
      $currency_symbol,
      number_format($price, 2),
      $cart_item_key,
      $input,
      $remove_link
    );
  }
  return $html;
}

/**
 * AJAX Cart Quantity Handler
 */
add_action('wp_ajax_qty_cart', 'ashley_decor_ajax_qty_cart');
add_action('wp_ajax_nopriv_qty_cart', 'ashley_decor_ajax_qty_cart');

function ashley_decor_ajax_qty_cart()
{
  if (!isset($_POST['hash']) || !isset($_POST['quantity'])) {
    wp_send_json_error('Missing data');
  }

  $cart_item_key = sanitize_text_field($_POST['hash']);
  $quantity = intval($_POST['quantity']);

  if ($quantity <= 0) {
    WC()->cart->remove_cart_item($cart_item_key);
  } else {
    WC()->cart->set_quantity($cart_item_key, $quantity);
  }

  // Force recalculation
  WC()->cart->calculate_totals();

  // Explicitly return the refreshed fragments
  WC_AJAX::get_refreshed_fragments();
  wp_die();
}

/**
 * Enable Product Reviews
 */
add_filter('comments_open', 'ashley_decor_enable_reviews', 10, 2);
function ashley_decor_enable_reviews($open, $post_id)
{
  return (get_post_type($post_id) == 'product') ? true : $open;
}

/**
 * Custom Star Rating HTML (Aligned with finalized CSS)
 */
add_filter('woocommerce_get_star_rating_html', 'ashley_decor_custom_star_rating', 10, 3);
function ashley_decor_custom_star_rating($html, $rating, $count)
{
  $percentage = ($rating / 5) * 100;

  // Aligns with the .star-rating block in your CSS
  $html = '<div class="star-rating" title="Rated ' . esc_attr($rating) . ' out of 5">';
  $html .= '<span style="width:' . esc_attr($percentage) . '%"></span>';
  $html .= '</div>';

  return $html;
}

/**
 * Custom Callback for Product Reviews (Comment List)
 * This handles how reviews look AFTER they are submitted and approved.
 */
function woocommerce_comments($comment, $args, $depth)
{
?>
<li <?php comment_class('review-item'); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>" class="comment_container">
    <div class="comment-text">
      <?php
        // 1. Get the rating directly from the database
        $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));

        // 2. Output the HTML directly. 
        // DO NOT use wc_get_rating_html($rating) here, as it causes the 10-star bug.
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
 * Prevent WooCommerce from injecting duplicate stars into the comment form
 */
add_filter('woocommerce_product_review_comment_form_args', 'ashley_decor_remove_default_stars', 20);

function ashley_decor_remove_default_stars($args)
{
  // We already added the 'Your rating' stars manually in single-product-reviews.php
  // Setting this to false prevents the duplicate injection.
  $args['format'] = '';
  return $args;
}

// Remove the default WooCommerce star output from the review list 
// so only our custom callback handles it.
remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);