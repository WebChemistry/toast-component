<?php declare(strict_types = 1);

namespace WebChemistry\Toast\DI;

use Nette\DI\CompilerExtension;
use Nette\Localization\ITranslator;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use WebChemistry\Toast\IToastComponentFactory;
use WebChemistry\Toast\ToastComponent;
use WebChemistry\Toast\Translation\ArrayTranslator;

final class ToastExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'types' => Expect::arrayOf('string'),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$toast = $builder->addFactoryDefinition($this->prefix('toastComponentFactory'))
			->setImplement(IToastComponentFactory::class)
			->getResultDefinition()
				->setFactory(ToastComponent::class);

		if ($config->types) {
			$translator = $builder->addDefinition($this->prefix('translator'))
				->setAutowired(false)
				->setType(ITranslator::class)
				->setFactory(ArrayTranslator::class, [$config->types]);

			$toast->addSetup('setTranslator', [$translator]);
		}
	}

}
