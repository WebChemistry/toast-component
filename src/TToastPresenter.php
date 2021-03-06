<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

trait TToastPresenter
{

	private IToastComponentFactory $toastComponentFactory;

	final public function injectTToastPresenter(IToastComponentFactory $toastComponentFactory): void
	{
		$this->toastComponentFactory = $toastComponentFactory;
	}

	protected function createComponentToast(): IToastComponent
	{
		return $this->toastComponentFactory->create();
	}

}
