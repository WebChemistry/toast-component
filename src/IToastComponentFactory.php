<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

use Nette\Application\UI\Control;

interface IToastComponentFactory
{

	public function create(Control $control): IToastComponent;

}
