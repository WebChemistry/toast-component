<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;
use Nette\Localization\Translator;
use Nette\Utils\Helpers;

final class ToastComponent extends Control implements IToastComponent
{

	/** @var (callable(mixed, mixed...): string)|null */
	private $translator;

	private bool $subject = false;

	/** @var array{type: string, subject: string|null, message: string}[] */
	private array $flashes;

	public function __construct(
		private Control $control,
		?Translator $translator = null,
	)
	{
		if ($translator) {
			$this->setTranslator($translator);
		}

		$this->getFlashes(); // autostart session
	}

	public function setSubject(bool $subject): static
	{
		$this->subject = $subject;

		return $this;
	}

	public function setTranslator(Translator $translator): static
	{
		$this->translator = [$translator, 'translate'];

		return $this;
	}

	/**
	 * @param callable(mixed, mixed...): string $translator
	 */
	public function setCallbackTranslator(callable $translator): static
	{
		$this->translator = $translator;

		return $this;
	}

	/**
	 * @return array{type: string, subject: string|null, message: string}[]
	 */
	private function getFlashes(): array
	{
		if (!isset($this->flashes)) {
			$id = $this->control->getParameterId('flash');
			$session = $this->control->getPresenter()->getFlashSession();
			$flashes = $session->get($id);
			$this->flashes = [];

			if (is_array($flashes)) {
				foreach ($flashes as $flash) {
					$this->flashes[] = [
						'type' => $flash->type,
						'subject' => $this->subject ? $this->resolveType($flash->type) : null,
						'message' => $flash->message,
					];
				}
			}
		}

		return $this->flashes;
	}

	public function requestRedraw(): void
	{
		$this->redrawControl();
	}

	public function requestPayload(): void
	{
		$this->redrawControl();
		$this->snippetMode = true;

		Helpers::capture(function (): void {
			$this->render();
		});
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
