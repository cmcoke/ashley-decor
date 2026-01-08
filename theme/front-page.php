<?php get_header(); ?>

<main>

  <!-- hero -->
  <div class="wrapper min-h-[calc(100vh_-_100px)]">

    <div class="col-start-1 sm:col-start-2 col-end-11 sm:col-end-10 place-self-center">

      <div class="text-center">

        <h1
          class="font-heading text-[clamp(2.938rem,2.4559rem_+_2.4107vw,6.313rem)] text-theme-black leading-[2.813rem] px-[1rem] mb-[clamp(2.5rem,2.1429rem_+_1.7857vw,5rem)]">
          The Wooden</h1>

        <a href="#" class="font-heading text-[1.125rem] relative inline-block pb-3
         after:content-[''] after:absolute after:left-0 after:bottom-0
         after:w-full after:h-[2px] after:bg-current hover:text-theme-orange transition-colors duration-300">
          Shop Now
        </a>

      </div>

      <div>
        <img src="<?php echo get_theme_file_uri('images/hero.webp') ?>" alt="main product">
      </div>

    </div>

  </div>

  <!-- products -->
  <?php
  // 1. Setup the Product Query
  $args = array(
    'post_type'      => 'product',
    'posts_per_page' => 9,
    'orderby'        => 'menu_order', // Look at the manual order number
    'order'          => 'ASC',        // Start from 1 and go up
  );
  $loop = new WP_Query($args);
  $counter = 1;

  if ($loop->have_posts()) :
    while ($loop->have_posts()) : $loop->the_post();
      global $product;

      // 2. Open a new wrapper every 3 products
      if ($counter == 1 || $counter == 4 || $counter == 7) {
        echo '<div class="wrapper min-h-[1272px]">';
      }

      // 3. Define the specific Tailwind Grid classes based on the product position
      $grid_classes = '';

      // Item 1 & 4: Large on Left
      if ($counter == 1 || $counter == 4) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-1 lg:col-end-6 row-start-1 row-end-2 lg:row-start-1 lg:row-end-3';
      }
      // Item 2 & 5: Small Top Right
      elseif ($counter == 2 || $counter == 5) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-6 lg:col-end-11 row-start-2 row-end-3 lg:row-start-1 lg:row-end-2';
      }
      // Item 3 & 6: Small Bottom Right
      elseif ($counter == 3 || $counter == 6) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-6 lg:col-end-11 row-start-3 row-end-4 lg:row-start-2 lg:row-end-3';
      }
      // Item 7: Large on Right (The flip happens here)
      elseif ($counter == 7) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-6 lg:col-end-11 row-start-1 row-end-2 lg:row-start-1 lg:row-end-3';
      }
      // Item 8: Small Top Left
      elseif ($counter == 8) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-1 lg:col-end-6 row-start-2 row-end-3 lg:row-start-1 lg:row-end-2';
      }
      // Item 9: Small Bottom Left
      elseif ($counter == 9) {
        $grid_classes = 'col-start-1 col-end-11 lg:col-start-1 lg:col-end-6 row-start-3 row-end-4 lg:row-start-2 lg:row-end-3';
      }
  ?>

      <div class="<?php echo $grid_classes; ?>">
        <a href="<?php the_permalink(); ?>" class="relative block w-full h-full">
          <?php
          if (has_post_thumbnail()) {
            the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']);
          }
          ?>
          <div class="absolute bottom-3 left-4">
            <h5 class="font-heading font-bold uppercase text-[14px] tracking-[.2em]"><?php the_title(); ?></h5>
            <span class="font-heading text-[14px] font-light text-theme-orange block">
              <?php echo $product->get_price_html(); ?>
            </span>
          </div>
        </a>
      </div>

  <?php
      // 4. Close the wrapper every 3 products
      if ($counter == 3 || $counter == 6 || $counter == 9) {
        echo '</div>';
      }

      $counter++;
    endwhile;
    wp_reset_postdata();
  endif;
  ?>

  <!-- newsletter -->
  <div class="wrapper py-[10rem]">

    <div class="col-start-1 col-end-11 lg:col-start-2 lg:col-end-10 place-self-center">
      <p
        class="font-paragraph text-center uppercase font-bold tracking-[9.1px] text-[clamp(0.875rem,0.8571rem_+_0.0893vw,1rem)]">
        Enjoy 15% off</p>
      <h2
        class="text-[clamp(1.25rem,1.1607rem_+_0.4464vw,1.875rem)] font-heading text-center uppercase tracking-[7px] mt-5 mb-14">
        Subscribe to our newsletter.</h2>

      <form action="" class="px-3 lg:px-0 relative max-w-2xl">
        <div class="relative">
          <!-- Input -->
          <input id="email" type="email" placeholder=" " class="peer w-full bg-transparent
             border-0 border-b border-gray-400
             py-2 pr-10
             focus:border-black focus:outline-none" />

          <!-- Floating label -->
          <label for="email" class="absolute left-0 top-2
             text-gray-500 text-sm
             transition-all duration-200
             peer-placeholder-shown:top-2
             peer-placeholder-shown:text-base
             peer-placeholder-shown:text-gray-400
             peer-focus:-top-3
             peer-focus:text-sm
             peer-focus:text-theme-orange">
            Enter your email address
          </label>

          <!-- Animated underline -->
          <span class="pointer-events-none absolute left-0 bottom-0
             h-[2px] w-full bg-theme-orange
             scale-x-0 origin-left
             transition-transform duration-300
             peer-focus:scale-x-100"></span>

          <!-- Submit button (arrow) -->
          <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2
             p-1
             focus:outline-none cursor-pointer" aria-label="Submit email">
            <img src="<?php echo get_theme_file_uri('images/right-arrow.webp'); ?>" alt="submit button" class="w-6 h-6">
          </button>
        </div>
      </form>


    </div>

  </div>

</main>


<?php get_footer(); ?>