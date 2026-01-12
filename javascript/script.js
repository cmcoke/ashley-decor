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

		// ESC key to close drawer
		$(document).on('keydown', function (e) {
			if (e.key === 'Escape') {
				closeCart();
			}
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
					quantity: quantity, // Explicit key:value
					security: ashleyData.nonce,
				},
				success: function (response) {
					if (response.success) {
						// Refresh fragments (updates the mini-cart HTML)
						$(document.body).trigger('wc_fragment_refresh');
					} else {
						console.error('Cart update failed:', response.data);
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

				// Skip if buttons already exist
				if ($container.find('.qty-btn').length > 0) return;

				const $input = $container.find('input.qty');
				if (!$input.length) return;

				$container.find('.screen-reader-text').hide();

				// Build image paths from localized theme URL
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
				// Mini-cart logic (AJAX)
				if (newVal >= 0) {
					updateCartQuantity(itemKey, newVal);
				}
			} else {
				// Single product page logic (Manual Input)
				if (newVal >= 1) {
					$input.val(newVal).trigger('change');
				}
			}
		});

		// Remove item link click
		$(document).on('click', '.remove-item-link', function (e) {
			e.preventDefault();
			const itemKey =
				$(this).attr('data-cart_item_key') ||
				$(this).attr('data-item-key');
			if (itemKey) {
				updateCartQuantity(itemKey, 0);
			}
		});

		// --- 5. Observers & Initialization ---

		// Watch for mini-cart updates to re-initialize buttons
		$(document.body).on(
			'updated_wc_div added_to_cart updated_cart_totals wc_fragments_refreshed',
			function () {
				initQuantityButtons();
			}
		);

		// Mutation Observer as a fallback for the drawer
		const miniCartNode = document.getElementById('cart-drawer');
		if (miniCartNode) {
			const cartObserver = new MutationObserver(initQuantityButtons);
			cartObserver.observe(miniCartNode, {
				childList: true,
				subtree: true,
			});
		}

		// Initial run
		initQuantityButtons();

		// --- 6. Single Product Tabs ---
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

		// --- 7. Star Rating Interaction ---
		$(document).on('click', '.stars a', function (e) {
			const $star = $(this);
			const $wrapper = $star.closest('.comment-form-rating');
			const $select = $wrapper.find('select#rating');

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
