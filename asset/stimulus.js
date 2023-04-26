import { Controller } from "@hotwired/stimulus";

/**
 * @property {Number} delayValue
 */
export default class ToastStimulus extends Controller {

	static values = {
		delay: {
			type: Number,
			default: 4000,
		}
	};

	static targets = ['message', 'container'];

	messages = [];

	connect() {
		this.messages = [];
	}

	messageTargetConnected(element) {
		this.messages.push(new ToastMessageElement(element, this.delayValue));
	}

	messageTargetDisconnected(element) {
		const index = this.messages.findIndex(message => message.element === element);

		if (index === -1) {
			return;
		}

		this.messages[index].clear();
		this.messages.splice(index, 1);
	}

	/**
	 * @param {PointerEvent} event
	 */
	close(event) {
		const target = event.currentTarget;
		/** @type {HTMLElement|null} */
		const message = target.closest(`[data-${this.context.identifier}-target="message"]`);

		if (message) {
			message.remove();
		}
	}

	static createMessage(type, title, content) {
		const container = document.querySelector('[data-controller="toast"] [data-toast-target="container"]');

		if (!container) {
			console.warn('No toast container found.');

			return;
		}

		const element = createElement(type, title, content);
		container.appendChild(element);
	}

}

function createElement(type, title, content) {
	const html =
		`<div class="toast toast--${type}" data-toast-target="message">
			<div class="toast__icon">
				<div class="toast__icon__svg"></div>
			</div>
			<div class="toast__content">
				<p class="toast__type">${title}</p>
				<p class="toast__message">${content}</p>
			</div>
			<div class="toast__close" data-action="click->toast#close">
				<div class="toast__icon__close"></div>
			</div>
		</div>`;

	const template = document.createElement('template');
	template.innerHTML = html;

	return template.content.firstElementChild;
}

class ToastMessageElement {

	/** @type {HTMLElement} */
	element;

	/** @type {Number} */
	delay;

	/** @type {Number|null} */
	timeout;

	/**
	 * @param {HTMLElement} element
	 * @param {Number} delay
	 */
	constructor(element, delay) {
		this.element = element;
		this.delay = delay;
		this.timeout = setTimeout(() => {
			this.element.remove();

			this.timeout = null;
		}, delay);
	}

	clear() {
		if (this.timeout === null) {
			return;
		}

		clearInterval(this.timeout);

		this.timeout = null;
	}

}
