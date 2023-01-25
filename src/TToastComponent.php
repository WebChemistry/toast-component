<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

trait TToastComponent
{

	private IToastComponentFactory $toastComponentFactory;

	final public function injectTToastPresenter(IToastComponentFactory $toastComponentFactory): void
	{
		$this->toastComponentFactory = $toastComponentFactory;

		$this->onRender[] = function (): void {
			$this['toast']; // autostart session
		};
	}

	protected function createComponentToast(): IToastComponent
	{
		return $this->toastComponentFactory->create($this);
	}

}
