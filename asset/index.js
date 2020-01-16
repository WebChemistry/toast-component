const BASE_TIMEOUT = 4000;

function hideElements(elements, timeout = 0) {
	let counter = 0;
	elements.forEach(el => {
		hideElement(el, timeout + (counter * timeout));
		setTimeout(() => {
			hideElement(el);
		}, timeout + (counter * timeout));

		counter++;
	});
}

function hideElement(el, timeout = 0) {
	if (timeout <= 0) {
		el.remove();
	} else {
		setTimeout(() => {
			el.remove();
		}, timeout);
	}
}

document.addEventListener('click', (e) => {
	if (!e.target) {
		return;
	}
	if (!e.target.matches('.toast__icon__close')) {
		return;
	}

	hideElement(e.target.closest('.toast'), 0);
});

function createElement(title, content, type) {
	const html =
		`<div class="toast toast--${type}">
			<div class="toast__icon">
				<div class="toast__icon__svg"></div>
			</div>
			<div class="toast__content">
				<p class="toast__type">${title}</p>
				<p class="toast__message">${content}</p>
			</div>
			<div class="toast__close">
				<div class="toast__icon__close"></div>
			</div>
		</div>`;

	const template = document.createElement('template');
	template.innerHTML = html;

	return template.content.firstElementChild;
}

hideElements(document.querySelectorAll('.toast__cell > .toast'), BASE_TIMEOUT);

export default function createToast(title, content, type = 'success') {
	const el = createElement(title, content, type);

	document.querySelector('.toast__cell').appendChild(el);
	hideElement(el, BASE_TIMEOUT);
}
