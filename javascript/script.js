/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

/**
 * Front-end JavaScript - Unified Quantity & Cart Logic
 */

/* global jQuery, ashleyData */
(function ($) {
	'use strict';

	$(function () {
		console.log('Ashley Decor Script Loaded');

		// --- 1. Drawer Controls --- (Keep your existing code here)
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
		function updateCartQuantity(key, quantity) {
			$.ajax({
				type: 'POST',
				url: ashleyData.ajax_url,
				data: {
					action: 'qty_cart',
					hash: key,
					quantity: quantity,
				},
				success: function (response) {
					// Triggering this ensures subtotals update
					$(document.body).trigger('added_to_cart', [response]);
					$(document.body).trigger('wc_fragment_refresh');
				},
			});
		}

		// --- 3. Unified Quantity Logic ---
		function initQuantityButtons() {
			$('.quantity').each(function () {
				const $container = $(this);

				// If buttons already exist, don't add them again
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

		// --- 4. Event Handling ---

		// Delegated click handler for buttons
		$(document).on('click', '.qty-btn', function (e) {
			e.preventDefault();
			const $btn = $(this);
			const $container = $btn.closest('.quantity');
			const $input = $container.find('input.qty');
			const $miniCartWrapper = $container.closest('.mini-cart-qty');
			const itemKey = $miniCartWrapper.length
				? $miniCartWrapper.attr('data-item-key')
				: null;

			let val = parseInt($input.val()) || 0;

			if ($btn.hasClass('m-btn')) {
				let newVal = val - 1;
				if (itemKey) {
					if (newVal >= 0) {
						$input.val(newVal);
						updateCartQuantity(itemKey, newVal);
					}
				} else if (newVal >= 1) {
					$input.val(newVal).trigger('change');
				}
			} else {
				let newVal = val + 1;
				$input.val(newVal);
				if (itemKey) {
					updateCartQuantity(itemKey, newVal);
				} else {
					$input.trigger('change');
				}
			}
		});

		// WATCHDOG: This observes the cart drawer for any HTML changes
		// and re-runs the button initialization immediately.
		const cartObserver = new MutationObserver(function (mutations) {
			initQuantityButtons();
		});

		const miniCartNode = document.getElementById('cart-drawer');
		if (miniCartNode) {
			cartObserver.observe(miniCartNode, {
				childList: true,
				subtree: true,
			});
		}

		// Standard WooCommerce events as backup
		$(document.body).on(
			'updated_wc_div added_to_cart updated_cart_totals',
			initQuantityButtons
		);

		initQuantityButtons();
	});
})(jQuery);
