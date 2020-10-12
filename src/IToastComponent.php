<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;
use Nette\Localization\ITranslator;

interface IToastComponent
{

	public function setFlashesControl(Control $control): void;

	public function setTranslator(ITranslator $translator): void;

	public function setCallbackTranslator(callable $translator): void;

}
