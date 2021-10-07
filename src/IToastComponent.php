<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;
use Nette\Localization\ITranslator;

interface IToastComponent
{

	public function setSubject(bool $subject): static;

	public function setTranslator(ITranslator $translator): static;

	public function setCallbackTranslator(callable $translator): static;

}
