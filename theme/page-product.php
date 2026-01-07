<?php get_header() ?>

<div class="wrapper">

  <div class="col-start-1 col-end-11 lg:col-start-1 lg:col-end-6">
    <img src="<?php echo get_theme_file_uri('images/product_1.webp') ?>" alt="" class="w-full h-full object-cover">
  </div>

  <div class="col-start-1 col-end-11 lg:col-start-6 lg:col-end-10 place-self-center my-11 lg:my-0">

    <div class="px-4">
      <a href="<?php echo esc_url(site_url()); ?>" class="relative font-paragraph text-theme-grey
           transition-colors duration-300
           hover:text-theme-orange
           after:content-[''] after:absolute after:left-0 after:-bottom-0.5
           after:h-[2px] after:bg-theme-orange
           after:w-0 hover:after:w-full
           after:transition-all after:duration-300">
        Back to products
      </a>
    </div>

    <div class="px-4 flex flex-col gap-3">

      <h1 class="font-heading uppercase mt-[2rem] text-[2rem] mb-0">Cacti</h1>

      <div class="flex gap-2 items-center mt-[-1rem]">
        <img src="<?php echo get_theme_file_uri('images/stars.png') ?>" alt="stars" class="max-w-[5rem]">
        <span class="font-paragraph">12 Reviews</span>
      </div>


      <span class="font-heading text-theme-orange">$20.00</span>

      <p class="font-paragraph">Suspendisse fringilla, libero ut tincidunt rutrum, sapien nunc porttitor sem, ac dapibus
        lorem justo in diam.
        Nulla vitae nulla id lectus euismod finibus eleifend id velit. Nullam purus diam, vehicula sed posuere et,
        pharetra sit amet massa. Sed rhoncus nisi ac auctor molestie. Nunc ultricies a nulla quis luctus. Mauris vel
        lectus ultrices, ultricies libero non, suscipit velit. Ut porttitor nisl ut nunc interdum, ut eleifend lacus
        venenatis. Donec ut turpis vitae lorem dictum imperdiet.</p>

      <!-- quantity & add to cart button -->
      <div class="mt-11 inline-flex border rounded-xl overflow-hidden w-fit justify-self-start">

        <!-- quantity -->
        <div class="flex items-center px-4 py-3 gap-4">
          <button class="p-2">
            <img src="<?php echo get_theme_file_uri('images/minus.png') ?>" class="w-4 h-4" alt="minus">
          </button>

          <span class="text-xl font-medium select-none">01</span>

          <button class="p-2">
            <img src="<?php echo get_theme_file_uri('images/plus.png') ?>" class="w-4 h-4" alt="plus">
          </button>
        </div>

        <!-- add to cart -->
        <button
          class="bg-theme-black hover:bg-theme-orange duration-300 transition-colors text-white px-8 py-3 font-medium w-fit">
          Add to Cart
        </button>

      </div>


    </div>

  </div>

</div>

<div class="wrapper lg:my-[11rem]">

  <div class="px-4 col-start-1 col-end-11 lg:col-start-2 lg:col-end-10">
    <div class="flex gap-8 xl:gap-[6rem]">
      <h2 class="font-paragraph text-theme-orange text-[clamp(1.125rem,1.0187rem_+_0.3402vw,1.563rem)]">Product
        Information</h2>
      <h2 class="font-paragraph text-theme-grey text-[clamp(1.125rem,1.0187rem_+_0.3402vw,1.563rem)]">Reviews</h2>
    </div>
    <hr class="mt-11 border-t-2 border-solid border-gray-300">
  </div>

  <div class="px-4 col-start-1 col-end-11 lg:col-start-2 lg:col-end-10 mt-11">

    <p class="font-paragraph text-theme-grey">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id nisl nulla. Suspendisse in neque vestibulum,
      eleifend massa quis, volutpat turpis. Cras nisl neque, dapibus non dictum at, cursus at augue. Phasellus consequat
      tristique leo eu finibus. Cras egestas neque vel elit semper luctus. Sed sem metus, venenatis et tellus eget,
      porttitor pharetra dui. Suspendisse lobortis tellus vel ipsum imperdiet vulputate sed commodo dui. Phasellus
      suscipit fermentum lacus, vitae condimentum risus aliquam eu. Suspendisse augue turpis, lacinia sit amet lorem
      quis, volutpat euismod dolor. Sed mattis, nibh sed posuere accumsan, massa est tincidunt augue, eu feugiat tellus
      velit vitae leo.
    </p>
    <br>
    <p class="font-paragraph text-theme-grey">
      Cras commodo viverra pretium. Suspendisse et faucibus augue, ac malesuada odio. Duis ac egestas dolor. Donec
      tempor purus eget purus aliquam placerat. Etiam vitae nulla sed neque elementum dignissim. In id mauris feugiat,
      vehicula nunc quis, laoreet ex. Quisque imperdiet aliquam sagittis. Praesent rutrum nisi at imperdiet vulputate.
      Donec porta, risus vel dapibus ultrices, mi massa laoreet justo, sit amet luctus risus tortor vitae libero. Orci
      varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Quisque sit amet libero
      bibendum, ultricies ligula sed, pharetra sapien. Sed semper bibendum tincidunt. Aenean condimentum augue sit amet
      ante tincidunt, et ultricies ligula mollis. Morbi at semper nulla.
    </p>
    <br>
    <p class="font-paragraph text-theme-grey">
      Ut lacinia sem eget tincidunt bibendum. Aenean eu nunc nec orci hendrerit consectetur. Fusce porta mauris vitae
      metus consequat auctor. Maecenas interdum dictum eros sed laoreet. Praesent aliquet leo turpis, quis viverra
      libero aliquet in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
      Nunc volutpat sit amet erat id molestie. Mauris non lectus quis nisl mattis accumsan id porta est.
    </p>

  </div>

</div>