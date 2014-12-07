<?php


namespace App\Utils;

use Brabijan\Images\ImagePipe;
use Nette\Application\Application;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

class TemplateFactory implements ITemplateFactory
{

	/** @var \Nette\Application\Application */
	private $application;
	/** @var NTemplateFactory */
	private $templateFactory;
	/**
	 * @var ImagePipe
	 */
	private $imagePipe;

	public function __construct(NTemplateFactory $templateFactory, Application $application, ImagePipe $imagePipe)
	{
		$this->application = $application;
		$this->templateFactory = $templateFactory;
		$this->imagePipe = $imagePipe;
	}


	public function createTemplate(Control $control = NULL)
	{
		if ($control === NULL) {
			$control = $this->application->presenter;
		}

		$template = $this->templateFactory->createTemplate($control);
		$template->_imagePipe = $this->imagePipe;
		$template->locale = isset($control->getPresenter()->locale) ? $control->getPresenter()->locale : NULL;

		$template->addFilter('file', function ($path) {
			return $this->imagePipe->getPath() . '/' . $path;
		});
		$template->addFilter('mangleEmail', function ($mail) {
			$parts = str_split($mail);
			$str = '';
			foreach ($parts as $item) {
				$str .= '&#' . ord($item) . ";";
			}

			return $str;
		});

		return $template;
	}

} 