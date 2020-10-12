<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;
use Nette\Localization\ITranslator;

final class ToastComponent extends Control implements IToastComponent
{

	private ?Control $control;

	private bool $allFlashes = false;

	/** @var callable|null */
	private $translator;

	private bool $subject = false;

	public function setFlashesControl(Control $control): void
	{
		$this->control = $control;
	}

	public function setSubject(bool $subject): void
	{
		$this->subject = $subject;
	}

	public function setTranslator(ITranslator $translator): void
	{
		$this->translator = [$translator, 'translate'];
	}

	public function setCallbackTranslator(callable $translator): void
	{
		$this->translator = $translator;
	}

	private function setCaptureAllFlashes()
	{
		$this->allFlashes = true;
	}

	private function getControl(): Control
	{
		if (!$this->control) {
			$this->control = $this->lookup(Control::class);
		}

		return $this->control;
	}

	private function getFlashes(): iterable
	{
		$presenter = $this->getPresenter();
		if (!$presenter->hasFlashSession()) {
			return;
		} elseif ($this->allFlashes) {
			$collection = $presenter->getFlashSession();
		} else {
			$id = $this->getControl()->getParameterId('flash');
			$session = $presenter->getFlashSession();
			if (!isset($session->$id)) {
				return;
			}

			$collection = [(array) $session->$id];
		}

		foreach ($collection as $flashes) {
			if (!$flashes) {
				continue;
			}

			foreach ($flashes as $flash) {
				yield [
					'type' => $flash->type,
					'subject' => $this->subject ? $this->resolveType($flash->type) : null,
					'message' => $flash->message,
				];
			}
		}
	}

	public function render(): void
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/toast.latte');

		$template->flashes = $this->getFlashes();

		$template->render();
	}

	private function resolveType(string $type): string
	{
		if (!$this->translator) {
			return ucfirst($type);
		}

		return ($this->translator)($type);
	}

}
