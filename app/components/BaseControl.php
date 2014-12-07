<?php

namespace App\Components;

use Kdyby\Autowired\AutowireComponentFactories;
use Nette;


abstract class BaseControl extends Nette\Application\UI\Control
{
	use AutowireComponentFactories;

	/**
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * @param string $class
	 * @return Nette\Templating\ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		/** @var \Nette\Templating\FileTemplate|\stdClass $template */
		$template = parent::createTemplate($class);
		//$template->registerHelperLoader('App\TemplateHelpers::loader');

		if ($file = $this->getTemplateDefaultFile()) {
			$template->setFile($file);
		}

		return $template;
	}


	/**
	 * Derives template path from class name.
	 *
	 * @return null|string
	 */
	protected function getTemplateDefaultFile()
	{
		$refl = $this->getReflection()->getMethod('render')->getDeclaringClass();
		$file = dirname($refl->getFileName()) . '/' . lcfirst($refl->getShortName()) . '.latte';

		return file_exists($file) ? $file : NULL;
	}
}
