<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Localization\Translator;

interface IToastComponent
{

	public function setSubject(bool $subject): static;

	public function setTranslator(Translator $translator): static;

	public function setCallbackTranslator(callable $translator): static;

}
