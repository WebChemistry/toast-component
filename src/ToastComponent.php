<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\IPresenter;
use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;

final class ToastComponent extends Control implements IToastComponent
{

	/** @var callable|null */
	private $translator;

	private bool $subject = false;

	public function __construct(
		private Control $control,
	)
	{
	}

	public function setSubject(bool $subject): static
	{
		$this->subject = $subject;

		return $this;
	}

	public function setTranslator(ITranslator $translator): static
	{
		$this->translator = [$translator, 'translate'];

		return $this;
	}

	public function setCallbackTranslator(callable $translator): static
	{
		$this->translator = $translator;

		return $this;
	}

	private function getFlashes(): iterable
	{
		$id = $this->control->getParameterId('flash');
		$session = $this->control->getPresenter()->getFlashSession();

		if (!isset($session->$id)) {
			return;
		}

		foreach ($session->$id as $flash) {
			yield [
				'type' => $flash->type,
				'subject' => $this->subject ? $this->resolveType($flash->type) : null,
				'message' => $flash->message,
			];
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
