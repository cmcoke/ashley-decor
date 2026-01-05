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
  <div class="col-start-1 col-end-2 place-self-center">
    <a href="<?php echo esc_url(site_url()); ?>">
      <img src="<?php echo get_theme_file_uri('images/logo.webp') ?>" alt="logo" class="max-w-[5rem] h-auto">
    </a>
  </div>
  <div class="col-start-10 col-end-11 place-self-center">
    <img src="<?php echo get_theme_file_uri('images/shopping-bag.webp') ?>" alt="shopping bag"
      class="max-w-[1.5rem] h-auto cursor-pointer">
  </div>
</header>