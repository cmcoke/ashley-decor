<?php
defined('ABSPATH') || exit;

global $product;

if (! comments_open()) {
  return;
}
?>
<div id="reviews" class="woocommerce-Reviews">
  <div id="comments" class="mx-0">
    <h2 class="woocommerce-Reviews-title font-heading text-2xl uppercase mb-6">
      <?php
      $count = $product->get_review_count();
      if ($count > 0) {
        echo esc_html(sprintf(_n('%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce'), $count, get_the_title()));
      } else {
        esc_html_e('Reviews', 'woocommerce');
      }
      ?>
    </h2>

    <?php if (have_comments()) : ?>
    <ol class="commentlist flex flex-col gap-8 mb-12">
      <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
    </ol>
    <?php
      if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        echo '<nav class="woocommerce-pagination">';
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
        echo '</nav>';
      endif;
      ?>
    <?php else : ?>
    <p class="woocommerce-noreviews text-gray-500 italic mb-8">
      <?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?></p>
    <?php endif; ?>
  </div>

  <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
  <div id="review_form_wrapper" class="bg-gray-50 p-8 rounded-lg">
    <div id="review_form">
      <?php
        $commenter    = wp_get_current_commenter();
        $comment_form = array(
          'title_reply'          => have_comments() ? esc_html__('Add a review', 'woocommerce') : esc_html__('Be the first to review', 'woocommerce') . ' &ldquo;' . get_the_title() . '&rdquo;',
          'title_reply_to'       => esc_html__('Leave a Reply to %s', 'woocommerce'),
          'title_reply_before'   => '<span id="reply-title" class="comment-reply-title font-heading text-xl uppercase block mb-4">',
          'title_reply_after'    => '</span>',
          'comment_notes_after'  => '',
          'label_submit'         => esc_html__('Submit Review', 'woocommerce'),
          'logged_in_as'         => '',
          'comment_field'        => '',
          'class_submit'         => 'submit bg-theme-black hover:bg-theme-orange text-white font-heading uppercase px-8 py-3 rounded-md transition-colors duration-300 cursor-pointer border-none',
          'submit_field'         => '<p class="form-submit mt-4">%1$s %2$s</p>',
        );

        $name_email_required = (bool) get_option('require_name_email', 1);
        $fields              = array(
          'author' => array(
            'label'    => __('Name', 'woocommerce'),
            'type'     => 'text',
            'value'    => $commenter['comment_author'],
            'required' => $name_email_required,
          ),
          'email'  => array(
            'label'    => __('Email', 'woocommerce'),
            'type'     => 'email',
            'value'    => $commenter['comment_author_email'],
            'required' => $name_email_required,
          ),
        );

        $comment_form['fields'] = array();

        foreach ($fields as $key => $field) {
          $field_html  = '<p class="comment-form-' . esc_attr($key) . ' mb-4">';
          $field_html .= '<label class="block text-sm font-bold uppercase mb-1" for="' . esc_attr($key) . '">' . esc_html($field['label']) . ($field['required'] ? ' <span class="required">*</span>' : '') . '</label>';
          $field_html .= '<input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' class="w-full border-black border p-3 rounded-md focus:ring-theme-orange focus:border-theme-orange" /></p>';

          $comment_form['fields'][$key] = $field_html;
        }

        // --- STAR RATING FIELD ---
        if (wc_review_ratings_enabled()) {
          $comment_form['comment_field'] = '<div class="comment-form-rating mb-6">
                        <label for="rating" class="block text-sm font-bold uppercase mb-2">' . esc_html__('Your rating', 'woocommerce') . (wc_review_ratings_required() ? ' <span class="required">*</span>' : '') . '</label>
                        <div class="stars-wrapper p-3 bg-white border border-black rounded-md inline-block">
                            <p class="stars">
                                <span>
                                    <a class="star-5" href="#">5</a>
                                    <a class="star-4" href="#">4</a>
                                    <a class="star-3" href="#">3</a>
                                    <a class="star-2" href="#">2</a>
                                    <a class="star-1" href="#">1</a>
                                </span>
                            </p>
                            <select name="rating" id="rating" required style="display:none;">
                                <option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>
                                <option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>
                                <option value="4">' . esc_html__('Good', 'woocommerce') . '</option>
                                <option value="3">' . esc_html__('Average', 'woocommerce') . '</option>
                                <option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>
                                <option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>
                            </select>
                        </div>
                    </div>';
        }

        // --- REVIEW TEXTAREA ---
        $comment_form['comment_field'] .= '<p class="comment-form-comment mb-6">
                    <label for="comment" class="block text-sm font-bold uppercase mb-2">' . esc_html__('Your review', 'woocommerce') . ' <span class="required">*</span></label>
                    <textarea id="comment" name="comment" cols="45" rows="8" required class="w-full border-black border p-3 rounded-md focus:ring-theme-orange focus:border-theme-orange"></textarea>
                </p>';

        comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
        ?>
    </div>
  </div>
  <?php else : ?>
  <p class="woocommerce-verification-required text-gray-500 italic">
    <?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?>
  </p>
  <?php endif; ?>

  <div class="clear"></div>
</div>