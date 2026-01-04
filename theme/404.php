<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ashley'_decor
 */

get_header();
?>

<section id="primary">
  <main id="main">

    <div>
      <header class="page-header">
        <h1 class="page-title font-heading text-theme-black"><?php esc_html_e('Page Not Found', 'ashley-decor'); ?></h1>
      </header><!-- .page-header -->

      <div <?php ashley_decor_content_class('page-content'); ?>>
        <p class="font-paragraph text-theme-grey">
          <?php esc_html_e('This page could not be found. It might have been removed or renamed, or it may never have existed.', 'ashley-decor'); ?>
        </p>
        <?php get_search_form(); ?>
      </div><!-- .page-content -->
    </div>

  </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();