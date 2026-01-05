<?php get_header(); ?>

<main>

  <!-- hero -->
  <div class="min-h-[calc(100vh_-_100px)] wrapper">

    <div class="col-start-1 sm:col-start-2 col-end-11 sm:col-end-10 place-self-center">

      <div class="text-center">

        <h1
          class="font-heading text-[clamp(2.938rem,2.4559rem_+_2.4107vw,6.313rem)] text-theme-black leading-[2.813rem] px-[1rem] mb-[clamp(2.5rem,2.1429rem_+_1.7857vw,5rem)]">
          The Wooden</h1>

        <a href="#" class="font-heading text-[1.125rem] relative inline-block pb-3
         after:content-[''] after:absolute after:left-0 after:bottom-0
         after:w-full after:h-[2px] after:bg-current hover:text-red-500 transition-colors duration-300">
          Shop Now
        </a>

      </div>

      <div>
        <img src="<?php echo get_theme_file_uri('images/hero.webp') ?>" alt="main product">
      </div>

    </div>

  </div>

  <div class="bg-[#261447] text-white min-h-[1272px]">
    products layout 1
  </div>

  <div class="bg-[#7BC950] text-white min-h-[1272px]">
    products layout 2
  </div>

  <div class="bg-[#261447] text-white min-h-[1272px]">
    products layout 1
  </div>

  <div class="bg-[#F97068] text-white min-h-[636px]">
    newsletter
  </div>

</main>


<?php get_footer(); ?>