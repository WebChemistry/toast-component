<?php declare(strict_types = 1);

namespace WebChemistry\Toast;

interface IToastComponentFactory
{

	public function create(): IToastComponent;

}
