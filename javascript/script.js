/**
 * Front-end JavaScript - Unified Quantity, Cart & Review Logic
 */

/* global jQuery, ashleyData */
(function ($) {
	'use strict';

	$(function () {
		// --- 1. Drawer Controls ---
		const $drawer = $('#cart-drawer');
		const $overlay = $('#cart-overlay');

		const openCart = function () {
			$drawer.removeClass('translate-x-full');
			$overlay.removeClass('hidden').addClass('opacity-100');
			$('body').addClass('overflow-hidden');
		};

		const closeCart = function () {
			$drawer.addClass('translate-x-full');
			$overlay.removeClass('opacity-100');
			$('body').removeClass('overflow-hidden');
			setTimeout(() => $overlay.addClass('hidden'), 300);
		};

		$(document).on('click', '.cart-customlocation', function (e) {
			e.preventDefault();
			openCart();
		});

		$(document).on('click', '#close-cart, #cart-overlay', closeCart);

		$(document).on('keydown', function (e) {
			if (e.key === 'Escape') closeCart();
		});

		// --- 2. AJAX Cart Update Logic ---
		let isUpdating = false;

		function updateCartQuantity(key, quantity) {
			if (isUpdating) return;
			isUpdating = true;

			$('#mini-cart-content').css('opacity', '0.5');

			$.ajax({
				type: 'POST',
				url: ashleyData.ajax_url,
				data: {
					action: 'qty_cart',
					hash: key,
					quantity: quantity,
					security: ashleyData.nonce,
				},
				success: function (response) {
					// response.fragments contains the updated HTML for the cart icon AND the mini-cart
					if (response && response.fragments) {
						$.each(response.fragments, function (key, value) {
							$(key).replaceWith(value);
						});

						// IMPORTANT: Re-run the button injection after the HTML is replaced
						initQuantityButtons();

						$(document.body).trigger('wc_fragment_refresh');
					}
				},
				complete: function () {
					isUpdating = false;
					$('#mini-cart-content').css('opacity', '1');
				},
			});
		}

		// --- 3. Quantity Button Injection ---
		function initQuantityButtons() {
			$('.quantity').each(function () {
				const $container = $(this);
				if ($container.find('.qty-btn').length > 0) return;

				const $input = $container.find('input.qty');
				if (!$input.length) return;

				$container.find('.screen-reader-text').hide();

				const baseUrl = ashleyData.themeUrl.replace(/\/$/, '');
				const minusImg = baseUrl + '/images/minus.png';
				const plusImg = baseUrl + '/images/plus.png';

				const $mBtn = $(
					'<button type="button" class="qty-btn m-btn"><img src="' +
						minusImg +
						'" alt="minus"></button>'
				);
				const $pBtn = $(
					'<button type="button" class="qty-btn p-btn"><img src="' +
						plusImg +
						'" alt="plus"></button>'
				);

				$container.prepend($mBtn);
				$container.append($pBtn);
			});
		}

		// --- 4. Event Handling ---

		// Quantity Plus/Minus Click
		$(document).on('click', '.qty-btn', function (e) {
			e.preventDefault();
			if (isUpdating) return;

			const $btn = $(this);
			const $container = $btn.closest('.quantity');
			const $input = $container.find('input.qty');
			const $miniCartWrapper = $container.closest('.mini-cart-qty');
			const itemKey = $miniCartWrapper.attr('data-item-key');

			let val = parseInt($input.val()) || 0;
			let newVal = $btn.hasClass('m-btn') ? val - 1 : val + 1;

			if (itemKey) {
				// Mini-cart logic (Quantity 0 will trigger removal in PHP)
				if (newVal >= 0) {
					updateCartQuantity(itemKey, newVal);
				}
			} else {
				// Single product page logic
				if (newVal >= 1) {
					$input.val(newVal).trigger('change');
				}
			}
		});

		// Remove item link click
		$(document).on('click', '.remove-item-link', function (e) {
			e.preventDefault();
			const itemKey = $(this).attr('data-cart_item_key');
			if (itemKey) {
				updateCartQuantity(itemKey, 0);
			}
		});

		// Open drawer on successful Add to Cart
		$(document.body).on('added_to_cart', function () {
			openCart();
		});

		// --- 5. Observers & Initialization ---

		$(document.body).on(
			'updated_wc_div added_to_cart updated_cart_totals wc_fragments_refreshed',
			function () {
				initQuantityButtons();
			}
		);

		initQuantityButtons();

		// --- 6. Tabs & Star Ratings (Remains Same) ---
		$(document).on('click', '#tab-info-btn, #tab-reviews-btn', function () {
			const isReviews = $(this).attr('id') === 'tab-reviews-btn';
			$('#tab-info-btn')
				.toggleClass(
					'text-theme-orange border-b-2 border-theme-orange',
					!isReviews
				)
				.toggleClass('text-theme-grey', isReviews);
			$('#tab-reviews-btn')
				.toggleClass(
					'text-theme-orange border-b-2 border-theme-orange',
					isReviews
				)
				.toggleClass('text-theme-grey', !isReviews);
			$('#info-panel').toggleClass('hidden', isReviews);
			$('#reviews-panel').toggleClass('hidden', !isReviews);
		});

		$(document).on('click', '.stars a', function (e) {
			const $star = $(this);
			const $select = $star
				.closest('.comment-form-rating')
				.find('select#rating');
			const classMatch = $star.attr('class').match(/star-(\d+)/);
			const ratingValue = classMatch
				? classMatch[1]
				: $star.text().trim().charAt(0);

			$select.val(ratingValue).trigger('change');
			$star.addClass('active').prevAll('a').addClass('active');
			$star.nextAll('a').removeClass('active');
			$star.closest('.stars').addClass('selected');
		});
	});
})(jQuery);
