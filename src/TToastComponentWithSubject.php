<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

trait TToastComponentWithSubject
{

	private IToastComponentFactory $toastComponentFactory;

	final public function injectTToastComponent(IToastComponentFactory $toastComponentFactory): void
	{
		$this->toastComponentFactory = $toastComponentFactory;

		$this->onRender[] = function (): void {
			$this['toast']; // autostart session
		};
	}

	public function getToastComponent(): IToastComponent
	{
		return $this['toast'];
	}

	protected function createComponentToast(): IToastComponent
	{
		return $this->toastComponentFactory->create($this)
				->setSubject(true);
	}

}
