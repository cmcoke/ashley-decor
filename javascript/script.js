/**
 * Front-end JavaScript - Unified Quantity, Cart & Review Logic
 */

/* global jQuery, ashleyData */
(function ($) {
	'use strict';

	$(function () {
		console.log('Ashley Decor Script Loaded');

		// --- 1. Drawer Controls ---
		const $drawer = $('#cart-drawer');
		const $overlay = $('#cart-overlay');

		$(document).on('click', '.cart-customlocation', function (e) {
			e.preventDefault();
			$drawer.removeClass('translate-x-full');
			$overlay.removeClass('hidden').addClass('opacity-100');
			$('body').addClass('overflow-hidden');
		});

		const closeCart = function () {
			$drawer.addClass('translate-x-full');
			$overlay.removeClass('opacity-100');
			$('body').removeClass('overflow-hidden');
			setTimeout(() => $overlay.addClass('hidden'), 300);
		};

		$(document).on('click', '#close-cart, #cart-overlay', closeCart);

		// --- 2. AJAX Cart Update ---
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
				},
				success: function (response) {
					$(document.body).trigger('added_to_cart', [
						response.fragments,
						response.cart_hash,
					]);
					$(document.body).trigger('wc_fragment_refresh');
				},
				complete: function () {
					isUpdating = false;
					$('#mini-cart-content').css('opacity', '1');
				},
			});
		}

		// --- 3. Unified Quantity Logic ---
		function initQuantityButtons() {
			$('.quantity').each(function () {
				const $container = $(this);
				if ($container.find('.qty-btn').length > 0) return;

				const $input = $container.find('input.qty');
				if (!$input.length) return;

				$container.find('.screen-reader-text').hide();

				const $mBtn = $(
					'<button type="button" class="qty-btn m-btn p-2 flex items-center justify-center cursor-pointer hover:opacity-70 transition-opacity"><img src="' +
						ashleyData.themeUrl +
						'/images/minus.png" class="w-3 h-3" alt="minus"></button>'
				);
				const $pBtn = $(
					'<button type="button" class="qty-btn p-btn p-2 flex items-center justify-center cursor-pointer hover:opacity-70 transition-opacity"><img src="' +
						ashleyData.themeUrl +
						'/images/plus.png" class="w-3 h-3" alt="plus"></button>'
				);

				$container.prepend($mBtn);
				$container.append($pBtn);
			});
		}

		$(document).on('click', '.remove-item-link', function (e) {
			e.preventDefault();
			const itemKey = $(this).attr('data-item-key');
			updateCartQuantity(itemKey, 0);
		});

		// --- 4. Event Handling ---
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
				if (newVal >= 0) {
					updateCartQuantity(itemKey, newVal);
				}
			} else {
				if (newVal >= 1) {
					$input.val(newVal).trigger('change');
				}
			}
		});

		const cartObserver = new MutationObserver(function () {
			initQuantityButtons();
		});

		const miniCartNode = document.getElementById('cart-drawer');
		if (miniCartNode) {
			cartObserver.observe(miniCartNode, {
				childList: true,
				subtree: true,
			});
		}

		$(document.body).on(
			'updated_wc_div added_to_cart updated_cart_totals',
			initQuantityButtons
		);
		initQuantityButtons();

		// --- 5. Single Product Tabs ---
		const $infoBtn = $('#tab-info-btn');
		const $reviewsBtn = $('#tab-reviews-btn');
		const $infoPanel = $('#info-panel');
		const $reviewsPanel = $('#reviews-panel');

		$(document).on('click', '#tab-info-btn, #tab-reviews-btn', function () {
			const isReviews = $(this).attr('id') === 'tab-reviews-btn';

			$infoBtn
				.toggleClass(
					'text-theme-orange border-b-2 border-theme-orange',
					!isReviews
				)
				.toggleClass('text-theme-grey', isReviews);
			$reviewsBtn
				.toggleClass(
					'text-theme-orange border-b-2 border-theme-orange',
					isReviews
				)
				.toggleClass('text-theme-grey', !isReviews);

			$infoPanel.toggleClass('hidden', isReviews);
			$reviewsPanel.toggleClass('hidden', !isReviews);
		});

		// --- 6. Star Rating Interaction ---
		$(document).on('click', '.stars span a', function (e) {
			// Prevent WC default logic and bubbling
			e.preventDefault();
			e.stopImmediatePropagation();

			const $star = $(this);
			const $container = $star.closest('.stars');

			// Extract numeric value from class (e.g., 'star-4' becomes '4')
			const classList = $star.attr('class').split(/\s+/);
			const starClass = classList.find((cls) => cls.startsWith('star-'));

			if (starClass) {
				const ratingValue = starClass.replace('star-', '');

				// Update hidden WC select field
				$('#rating').val(ratingValue).trigger('change');

				console.log('Review Rating Set to: ' + ratingValue);
			}

			// Visual feedback
			$('.stars span a').removeClass('active');
			$star.addClass('active');
			$container.addClass('has-active');
		});
	});
})(jQuery);
