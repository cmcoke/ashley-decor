<?php

/**
 * ashley' decor functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ashley'_decor
 */

if (! defined('ASHLEY_DECOR_VERSION')) {
  /*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
  define('ASHLEY_DECOR_VERSION', '0.1.0');
}

if (! defined('ASHLEY_DECOR_TYPOGRAPHY_CLASSES')) {
  /*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `ashley_decor_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
  define(
    'ASHLEY_DECOR_TYPOGRAPHY_CLASSES',
    'prose prose-neutral max-w-none prose-a:text-primary'
  );
}

if (! function_exists('ashley_decor_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function ashley_decor_setup()
  {
    /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ashley' decor, use a find and replace
		 * to change 'ashley-decor' to the name of your theme in all the template files.
		 */
    load_theme_textdomain('ashley-decor', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
    add_theme_support('title-tag');

    /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(
      array(
        'menu-1' => __('Primary', 'ashley-decor'),
        'menu-2' => __('Footer Menu', 'ashley-decor'),
      )
    );

    /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
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

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Enqueue editor styles.
    add_editor_style('style-editor.css');

    // Add support for responsive embedded content.
    add_theme_support('responsive-embeds');

    // Remove support for block templates.
    remove_theme_support('block-templates');
  }
endif;
add_action('after_setup_theme', 'ashley_decor_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
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
  wp_enqueue_script('ashley-decor-script', get_template_directory_uri() . '/js/script.min.js', array(), ASHLEY_DECOR_VERSION, true);

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
      array(
        'wp-blocks',
        'wp-edit-post',
      ),
      ASHLEY_DECOR_VERSION,
      true
    );
    wp_add_inline_script('ashley-decor-editor', "tailwindTypographyClasses = '" . esc_attr(ASHLEY_DECOR_TYPOGRAPHY_CLASSES) . "'.split(' ');", 'before');
  }
}
add_action('enqueue_block_assets', 'ashley_decor_enqueue_block_editor_script');

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function ashley_decor_tinymce_add_class($settings)
{
  $settings['body_class'] = ASHLEY_DECOR_TYPOGRAPHY_CLASSES;
  return $settings;
}
add_filter('tiny_mce_before_init', 'ashley_decor_tinymce_add_class');

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function ashley_decor_modify_heading_levels($args, $block_type)
{
  if ('core/heading' !== $block_type) {
    return $args;
  }

  // Remove <h1>, <h5> and <h6>.
  $args['attributes']['levelOptions']['default'] = array(2, 3, 4);

  return $args;
}
add_filter('register_block_type_args', 'ashley_decor_modify_heading_levels', 10, 2);

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Woocommerce theme support
 */
function ashley_decor_add_woocommerce_support()
{
  add_theme_support('woocommerce');
  // This allows WooCommerce to use your custom single-product.php
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'ashley_decor_add_woocommerce_support');

/**
 * Show cart contents / total Ajax
 */
function woocommerce_header_add_to_cart_fragment($fragments)
{
  ob_start();
  $count = WC()->cart->get_cart_contents_count();
?>
  <a class="cart-customlocation block" href="<?php echo esc_url(wc_get_cart_url()); ?>">
    <div class="relative inline-block">
      <img src="<?php echo get_theme_file_uri('images/shopping-bag.webp') ?>" alt="shopping bag"
        class="max-w-[1.5rem] h-auto cursor-pointer">
      <?php if ($count > 0) : ?>
        <span
          class="absolute -bottom-1 -right-1 bg-black text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none min-w-[18px] h-[18px] flex items-center justify-center border-2 border-white">
          <?php echo $count; ?>
        </span>
      <?php endif; ?>
    </div>
  </a>
  <?php
  $fragments['a.cart-customlocation'] = ob_get_clean();

  // ADD THIS: Update the mini-cart drawer content
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

// Customizing the WooCommerce Add to Cart button class
add_filter('woocommerce_loop_add_to_cart_link', 'add_class_to_cart_button', 10, 2);
function add_class_to_cart_button($html, $product)
{
  // You can inject Tailwind classes here if needed, 
  // but for single-product pages, CSS is often easier:
  return $html;
}


/**
 * Customize the quantity input to include Plus/Minus buttons
 */
add_filter('woocommerce_quantity_input_get_main_form_wrapper_classes', function ($classes) {
  return array_merge($classes, array('flex', 'items-center', 'gap-4'));
});

// Note: If you prefer to keep it simple, the CSS above will make the 
// standard input look great. If you want the clickable +/- images, 
// let me know and I can provide the JavaScript to inject them!