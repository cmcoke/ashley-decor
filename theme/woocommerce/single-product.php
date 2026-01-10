<?php

/**
 * The Template for displaying all single products
 *
 * @package ashley_decor
 */

get_header();

// Start the Loop
while (have_posts()) : the_post();
  global $product; // Access the WooCommerce product object
?>

<main class="single-product-main">
  <div class="wrapper">

    <div class="col-start-1 col-end-11 lg:col-start-1 lg:col-end-6">
      <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('full', [
            'class' => 'w-full h-full object-cover shadow-sm'
          ]); ?>
      <?php else : ?>
      <img src="<?php echo esc_url(wc_placeholder_img_src('full')); ?>" alt="Placeholder"
        class="w-full h-auto object-cover">
      <?php endif; ?>
    </div>

    <div class="col-start-1 col-end-11 lg:col-start-6 lg:col-end-10 place-self-center my-11 lg:my-0 lg:pl-12">

      <div class="px-4">
        <a href="<?php echo esc_url(site_url()); ?>" class="relative font-paragraph text-theme-grey transition-colors duration-300 hover:text-theme-orange 
                          after:content-[''] after:absolute after:left-0 after:-bottom-0.5 after:h-[2px] after:bg-theme-orange 
                          after:w-0 hover:after:w-full after:transition-all after:duration-300">
          Back to products
        </a>
      </div>

      <div class="px-4 flex flex-col gap-4">
        <h1 class="font-heading uppercase mt-8 text-[2.5rem] leading-tight text-theme-black mb-0">
          <?php the_title(); ?>
        </h1>

        <div class="flex gap-3 items-center">
          <?php if ($product->get_rating_count() > 0) : ?>
          <div class="product-rating-wrapper">
            <?php echo wc_get_rating_html($product->get_average_rating()); ?>
          </div>
          <span class="font-paragraph text-sm text-gray-500">
            (<?php echo $product->get_review_count(); ?> Reviews)
          </span>
          <?php else : ?>
          <span class="font-paragraph text-sm italic text-gray-400">No reviews yet</span>
          <?php endif; ?>
        </div>

        <div class="font-heading text-theme-orange text-3xl font-semibold">
          <?php echo $product->get_price_html(); ?>
        </div>

        <div class="font-paragraph text-theme-grey leading-relaxed max-w-md">
          <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
        </div>

        <div class="mt-10 product-actions-container">
          <?php
            /**
             * Hook: woocommerce_template_single_add_to_cart
             * Handles quantity input, variation selection, and the "Add to Cart" button.
             */
            woocommerce_template_single_add_to_cart();
            ?>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 text-xs uppercase tracking-widest text-gray-400 font-paragraph">
          <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>'); ?>
          <span class="block mt-1">SKU: <?php echo ($sku = $product->get_sku()) ? $sku : 'N/A'; ?></span>
        </div>

      </div>
    </div>
  </div>

  <div class="wrapper lg:my-[10rem]">

    <div class="px-4 col-start-1 col-end-11 lg:col-start-2 lg:col-end-10">
      <div class="flex gap-10">
        <button type="button" id="tab-info-btn"
          class="tab-btn font-paragraph text-theme-orange text-[clamp(1.125rem,1rem_+_0.5vw,1.5rem)] border-b-2 border-theme-orange pb-2 transition-all cursor-pointer">
          Product Information
        </button>
        <button type="button" id="tab-reviews-btn"
          class="tab-btn font-paragraph text-theme-grey text-[clamp(1.125rem,1rem_+_0.5vw,1.5rem)] pb-2 hover:text-theme-orange transition-all cursor-pointer">
          Reviews (<?php echo $product->get_review_count(); ?>)
        </button>
      </div>
      <hr class="border-t border-gray-200">
    </div>

    <div class="px-4 col-start-1 col-end-11 lg:col-start-2 lg:col-end-10 mt-12">

      <div id="info-panel" class="tab-panel active">
        <div class="font-paragraph text-theme-grey prose prose-neutral max-w-none leading-relaxed">
          <?php the_content(); ?>
        </div>
      </div>

      <div id="reviews-panel" class="tab-panel hidden">
        <div class="reviews-container">
          <?php
            if (comments_open()) {
              comments_template();
            } else {
              echo '<p class="font-paragraph italic">Reviews are currently closed.</p>';
            }
            ?>
        </div>
      </div>

    </div>
  </div>
</main>

<?php
endwhile; // End of the loop.

get_footer();
?>