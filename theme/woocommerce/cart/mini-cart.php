<?php

/**
 * Mini-cart
 */
if (! defined('ABSPATH')) {
  exit;
}

do_action('woocommerce_before_mini_cart'); ?>

<?php if (! WC()->cart->is_empty()) : ?>

<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
  <?php
    do_action('woocommerce_before_mini_cart_contents');

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
      $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

      if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
        $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
        $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink() : '', $cart_item, $cart_item_key);
    ?>
  <li
    class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?> relative mb-8 pb-4 border-b border-gray-100 last:border-0">

    <?php
          echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
            '<a href="%s" class="remove-item-link" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
            esc_url(wc_get_cart_remove_url($cart_item_key)),
            esc_attr__('Remove this item', 'woocommerce'),
            esc_attr($product_id),
            esc_attr($cart_item_key),
            esc_attr($_product->get_sku())
          ), $cart_item_key);
          ?>

    <div class="flex gap-4">
      <?php if (empty($product_permalink)) : ?>
      <div class="w-20 h-24 flex-shrink-0 bg-gray-50 rounded-md overflow-hidden">
        <?php echo $thumbnail; ?>
      </div>
      <?php else : ?>
      <a href="<?php echo esc_url($product_permalink); ?>"
        class="w-20 h-24 flex-shrink-0 bg-gray-50 rounded-md overflow-hidden block">
        <?php echo $thumbnail; ?>
      </a>
      <?php endif; ?>

      <div class="flex-1 flex flex-col justify-between">
        <div>
          <?php if (empty($product_permalink)) : ?>
          <span class="block font-heading font-bold text-gray-900 mb-1"><?php echo $product_name; ?></span>
          <?php else : ?>
          <a href="<?php echo esc_url($product_permalink); ?>"
            class="block font-heading font-bold text-gray-900 mb-1 hover:text-theme-orange transition-colors">
            <?php echo $product_name; ?>
          </a>
          <?php endif; ?>
          <div class="text-sm text-gray-600">
            <?php echo $product_price; ?>
          </div>
        </div>

        <div class="mini-cart-qty mt-3" data-item-key="<?php echo esc_attr($cart_item_key); ?>">
          <?php
                if ($_product->is_sold_individually()) {
                  echo '<span class="text-xs uppercase tracking-widest text-gray-400">Qty: 1</span>';
                } else {
                  echo woocommerce_quantity_input(array(
                    'input_name'   => "cart[{$cart_item_key}][qty]",
                    'input_value'  => $cart_item['quantity'],
                    'max_value'    => $_product->get_max_purchase_quantity(),
                    'min_value'    => '0',
                    'product_name' => $_product->get_name(),
                  ), $_product, false);
                }
                ?>
        </div>
      </div>
    </div>
  </li>
  <?php
      }
    }

    do_action('woocommerce_after_mini_cart_contents');
    ?>
</ul>

<p class="woocommerce-mini-cart__total total border-t border-gray-100 pt-4 mt-4 text-lg font-bold flex justify-between">
  <?php do_action('woocommerce_widget_shopping_cart_total'); ?>
</p>

<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

<div class="woocommerce-mini-cart__buttons buttons flex flex-col gap-2 mt-6">
  <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
</div>

<?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

<?php else : ?>

<div class="empty-cart-wrapper flex flex-col items-center justify-center py-12 text-center">
  <div class="bg-gray-50 rounded-full p-6 mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
      stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
  </div>
  <p class="text-lg font-medium text-gray-900 mb-6">
    <?php esc_html_e('Your bag is currently empty.', 'ashley-decor'); ?>
  </p>
  <a href="<?php echo esc_url(home_url('/')); ?>"
    class="bg-black text-white px-8 py-3 rounded-md uppercase text-sm font-bold hover:bg-theme-orange transition-colors duration-300 inline-block">
    <?php esc_html_e('Shop Our Collection', 'ashley-decor'); ?>
  </a>
</div>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>