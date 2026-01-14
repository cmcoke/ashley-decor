<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ashley'_decor
 */

?>

<header class="h-[100px] wrapper items-center">

  <!-- logo -->
  <div class="col-start-2 col-end-3 lg:col-start-1 lg:col-end-2 place-self-center">
    <a href="<?php echo esc_url(site_url()); ?>">
      <img src="<?php echo get_theme_file_uri('images/logo.webp') ?>" alt="logo" class="max-w-[5rem] h-auto">
    </a>
  </div>

  <!-- cart -->
  <div class="col-start-9 col-end-10 lg:col-start-10 lg:col-end-11 place-self-center">
    <a class="cart-customlocation block" href="<?php echo esc_url(wc_get_cart_url()); ?>"
      title="<?php _e('View your shopping cart', 'ashley_decor'); ?>">
      <div class="relative inline-block">
        <img src="<?php echo get_theme_file_uri('images/shopping-bag.webp') ?>" alt="shopping bag"
          class="max-w-[1.5rem] h-auto cursor-pointer">

        <?php
        $count = WC()->cart->get_cart_contents_count();
        if ($count > 0) :
          // Logic: if count is greater than 10, show 10+, otherwise show the number
          $display_count = ($count > 10) ? '10+' : $count;
        ?>
        <span
          class="absolute -bottom-3 -right-3 bg-theme-orange text-white text-[10px] font-bold px-1 py-0.5 rounded-full leading-none min-w-[22px] h-[22px] flex items-center justify-center border-2 border-white">
          <?php echo $display_count; ?>
        </span>
        <?php endif; ?>
      </div>
    </a>
  </div>

  <!-- slide out cart -->
  <div id="cart-overlay"
    class="fixed inset-0 backdrop-blur-sm bg-white/10 z-[60] hidden transition-opacity duration-300 opacity-0"></div>

  <div id="cart-drawer"
    class="fixed top-0 right-0 h-full w-[350px] bg-white z-[70] shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold uppercase"><?php _e('Your Bag', 'ashley_decor'); ?></h2>
      <button id="close-cart" class="text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>

    <div id="mini-cart-content" class="widget_shopping_cart_content">
      <?php woocommerce_mini_cart(); ?>
    </div>
  </div>
</header>