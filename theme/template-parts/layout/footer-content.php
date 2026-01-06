<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ashley'_decor
 */

?>

<footer class="wrapper lg:items-center pb-11">

  <div class="col-start-1 col-end-11 lg:col-start-2 lg:col-end-5">
    <h5 class="font-paragraph text-gray-400 text-center mb-3 text-xl">Accepted Payments</h5>
    <img src="<? echo get_theme_file_uri('/images/cards.webp') ?>" alt="payment cards" class="block m-auto">
  </div>

  <div class="col-start-1 col-end-11 lg:col-start-5 lg:col-end-8 my-5">
    <a href="<?php echo esc_url(site_url()); ?>">
      <img src="<?php echo get_theme_file_uri('images/logo.webp') ?>" alt="logo"
        class="max-w-[10rem] h-auto block m-auto">
    </a>
  </div>

  <div class="col-start-1 col-end-11 lg:col-start-8 lg:col-end-10">
    <h5 class="font-paragraph text-gray-400 text-center mb-3 text-xl">Follow Us</h5>
    <ul class="flex gap-1 justify-center">
      <li>
        <a href="">
          <img src="<? echo get_theme_file_uri('/images/facebook.webp') ?>" alt="facebook" class="block m-auto">
        </a>
      </li>
      <li>
        <a href="">
          <img src="<? echo get_theme_file_uri('/images/twitter.webp') ?>" alt="twitter" class="block m-auto">
        </a>
      </li>
      <li>
        <a href="">
          <img src="<? echo get_theme_file_uri('/images/youtube.webp') ?>" alt="youtube" class="block m-auto">
        </a>
      </li>
      <li>
        <a href="">
          <img src="<? echo get_theme_file_uri('/images/pinterest.webp') ?>" alt="pinterest" class="block m-auto">
        </a>
      </li>
    </ul>
  </div>



</footer>