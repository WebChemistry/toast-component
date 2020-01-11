<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;

final class ToastComponent extends Control {

	/** @var Control|null */
	protected $control;

	/** @var bool */
	protected $allFlashes = false;

	/** @var callable|null */
	protected $translator;

	public function setFlashesControl(Control $control): void {
		$this->control = $control;
	}

	public function setCallbackTranslator(callable $translator): void {
		$this->translator = $translator;
	}

	protected function setCaptureAllFlashes() {
		$this->allFlashes = true;
	}

	protected function getControl(): Control {
		if (!$this->control) {
			$this->control = $this->lookup(Control::class);
		}

		return $this->control;
	}

	protected function getFlashes(): iterable {
		$presenter = $this->getPresenter();
		if (!$presenter->hasFlashSession()) {
			return;

		} else if ($this->allFlashes) {
			foreach ($presenter->getFlashSession() as $flashes) {
				if ($flashes) {
					foreach ($flashes as $flash) {
						yield [
							'type' => $flash->type,
							'subject' => $this->resolveType($flash->type),
							'message' => $flash->message,
						];
					}
				}
			}

		} else {
			$id = $this->getControl()->getParameterId('flash');
			$session = $presenter->getFlashSession();
			if (!isset($session->$id)) {
				return;
			}

			foreach ((array) $session->$id as $flash) {
				yield [
					'type' => $flash->type,
					'subject' => $this->resolveType($flash->type),
					'message' => $flash->message,
				];
			}

		}
	}

	public function render(): void {
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/toast.latte');

		$template->flashes = $this->getFlashes();

		$template->render();
	}

}
