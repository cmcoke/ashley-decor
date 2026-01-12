<?php

/**
 * Display single product reviews (and the review form)
 *
 * @package ashley_decor
 */

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
  return;
}
?>

<div id="reviews" class="woocommerce-Reviews max-w-4xl  py-10">
  <div id="comments" class="mb-16">
    <h2
      class="woocommerce-Reviews-title font-heading text-2xl tracking-widest uppercase mb-8 pb-4 border-b border-gray-100">
      <?php
      $count = $product->get_review_count();
      if ($count && wc_review_ratings_enabled()) {
        $reviews_title = sprintf(esc_html(_n('%1$s review', '%1$s reviews', $count, 'woocommerce')), esc_html($count));
        echo apply_filters('woocommerce_reviews_title', $reviews_title, $count, $product);
      } else {
        esc_html_e('Reviews', 'woocommerce');
      }
      ?>
    </h2>

    <?php if (have_comments()) : ?>
    <ol class="commentlist flex flex-col gap-10">
      <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
    <nav class="woocommerce-pagination mt-10">
      <?php
          paginate_comments_links(
            apply_filters(
              'woocommerce_comment_pagination_args',
              array(
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'type'      => 'list',
              )
            )
          );
          ?>
    </nav>
    <?php endif; ?>
    <?php else : ?>
    <p class="woocommerce-noreviews text-theme-grey font-paragraph italic mb-8">
      <?php esc_html_e('There are no reviews yet. Be the first to share your thoughts.', 'woocommerce'); ?>
    </p>
    <?php endif; ?>
  </div>

  <div id="review_form_wrapper" class="bg-gray-50 p-6 rounded-xl border border-gray-100">
    <div id="review_form">
      <?php
      $commenter    = wp_get_current_commenter();
      $comment_form = array(
        'title_reply'          => have_comments() ? esc_html__('Write a Review', 'woocommerce') : sprintf(esc_html__('Be the first to review &ldquo;%s&rdquo;', 'woocommerce'), get_the_title()),
        'title_reply_to'       => esc_html__('Leave a Reply to %s', 'woocommerce'),
        'title_reply_before'    => '<span id="reply-title" class="comment-reply-title block font-heading text-2xl tracking-widest uppercase mb-8">',
        'title_reply_after'     => '</span>',
        'comment_notes_after'  => '',
        'label_submit'         => esc_html__('Submit Review', 'woocommerce'),
        'logged_in_as'         => '',
        'comment_field'        => '',
        'class_submit'         => 'submit bg-theme-black hover:bg-theme-orange text-white px-10 py-4 rounded-md transition-all duration-300 cursor-pointer uppercase tracking-[0.2em] text-sm font-bold inline-block',
        'submit_field'         => '<div class="form-submit mt-6">%1$s %2$s</div>',
      );

      // Responsive Author/Email Fields (Grid)
      $name_email_fields = array(
        'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">' .
          '<div class="comment-form-author">' .
          '<label for="author" class="block text-xs font-bold uppercase tracking-widest mb-2">' . esc_html__('Name', 'woocommerce') . ' <span class="required">*</span></label> ' .
          '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" required class="w-full border-gray-300 border p-3 rounded-md focus:border-theme-orange focus:ring-1 focus:ring-theme-orange outline-none transition-all" /></div>',
        'email'  => '<div class="comment-form-email">' .
          '<label for="email" class="block text-xs font-bold uppercase tracking-widest mb-2">' . esc_html__('Email', 'woocommerce') . ' <span class="required">*</span></label> ' .
          '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required class="w-full border-gray-300 border p-3 rounded-md focus:border-theme-orange focus:ring-1 focus:ring-theme-orange outline-none transition-all" /></div>' .
          '</div>',
      );

      $comment_form['fields'] = $name_email_fields;

      // Rating Stars Field
      if (wc_review_ratings_enabled()) {
        $comment_form['comment_field'] = '<div class="comment-form-rating mb-8 pb-6 border-b border-gray-200">
                    <label for="rating" class="block text-xs font-bold uppercase tracking-widest mb-4">' . esc_html__('Your Rating', 'woocommerce') . ' <span class="required">*</span></label>
                    <select name="rating" id="rating" required style="display:none;">
                        <option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>
                        <option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>
                        <option value="4">' . esc_html__('Good', 'woocommerce') . '</option>
                        <option value="3">' . esc_html__('Average', 'woocommerce') . '</option>
                        <option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>
                        <option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>
                    </select>
                </div>';
      }

      // Review Textarea
      $comment_form['comment_field'] .= '<div class="comment-form-comment mb-6">
                <label for="comment" class="block text-xs font-bold uppercase tracking-widest mb-2">' . esc_html__('Review Details', 'woocommerce') . ' <span class="required">*</span></label>
                <textarea id="comment" name="comment" cols="45" rows="6" required class="w-full border-gray-300 border p-3 rounded-md focus:border-theme-orange focus:ring-1 focus:ring-theme-orange outline-none transition-all resize-none"></textarea>
            </div>';

      comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
      ?>
    </div>
  </div>
  <div class="clear"></div>
</div>