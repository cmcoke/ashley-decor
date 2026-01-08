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

document.addEventListener('DOMContentLoaded', function () {
	const cartBtn = document.querySelector('.cart-customlocation');
	const closeBtn = document.getElementById('close-cart');
	const drawer = document.getElementById('cart-drawer');
	const overlay = document.getElementById('cart-overlay');

	function openCart(e) {
		if (e) e.preventDefault();
		drawer.classList.remove('translate-x-full');
		overlay.classList.remove('hidden');
		document.body.classList.add('overflow-hidden');
		setTimeout(() => overlay.classList.add('opacity-100'), 10);
	}

	function closeCart() {
		drawer.classList.add('translate-x-full');
		overlay.classList.remove('opacity-100');
		document.body.classList.remove('overflow-hidden');
		setTimeout(() => overlay.classList.add('hidden'), 300);
	}

	// Click Events
	cartBtn.addEventListener('click', openCart);
	closeBtn.addEventListener('click', closeCart);
	overlay.addEventListener('click', closeCart);

	// Keyboard Event
	window.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeCart();
		}
	});
});

document.addEventListener('DOMContentLoaded', function () {
	// Target WooCommerce quantity wrappers
	const qtyContainers = document.querySelectorAll('.quantity');

	qtyContainers.forEach((container) => {
		const input = container.querySelector('input.qty');

		// Create Minus Button
		const minusBtn = document.createElement('button');
		minusBtn.type = 'button';
		minusBtn.innerHTML = '–'; // Or use your <img> tag here
		minusBtn.className =
			'px-3 py-2 text-xl hover:text-theme-orange transition-colors';

		// Create Plus Button
		const plusBtn = document.createElement('button');
		plusBtn.type = 'button';
		plusBtn.innerHTML = '+'; // Or use your <img> tag here
		plusBtn.className =
			'px-3 py-2 text-xl hover:text-theme-orange transition-colors';

		// Add functionality
		minusBtn.onclick = () => {
			if (input.value > 1) input.value = parseInt(input.value) - 1;
		};
		plusBtn.onclick = () => {
			input.value = parseInt(input.value) + 1;
		};

		// Insert buttons into DOM
		container.insertBefore(minusBtn, input);
		container.appendChild(plusBtn);
	});
});
