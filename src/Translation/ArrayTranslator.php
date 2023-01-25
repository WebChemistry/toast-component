<?php declare(strict_types = 1);

namespace WebChemistry\Toast\Translation;

use Nette\Localization\Translator;

final class ArrayTranslator implements Translator
{

	/** @var string[] */
	private array $mapping;

	/**
	 * @param string[] $mapping
	 */
	public function __construct(array $mapping)
	{
		$this->mapping = $mapping;
	}

	public function translate($message, ...$parameters): string
	{
		return $this->mapping[$message] ?? ucfirst($message);
	}

}
