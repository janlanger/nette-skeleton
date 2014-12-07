<?php


namespace App\Components\FlashMessages;


use Kdyby\Translation\Translator;
use Nette\Utils\Html;

trait TTranslatedFlashMessages
{

	/**
	 * @var Translator
	 */
	private $t;

	public function flashMessage($message, $type = 'info')
	{
		return parent::flashMessage($this->translateFlash($message), $type);
	}

	private function translateFlash($message, $count = NULL, $params = [])
	{
		return $this->t->translate($message, $count, $params);
	}

	public function flashSuccess($message)
	{
		return $this->flashMessage($message, 'success');
	}

	public function flashError($message)
	{
		return $this->flashMessage($message, 'danger');
	}

	public function flashParametrized($message, $type, $count = NULL, $parameters = [])
	{
		$flash = $this->translateFlash($message, $count, $parameters);
		$flash = Html::el('span')->setHtml($flash); //return as Html because it may contain tags
		return parent::flashMessage($flash, $type);
	}

	public function injectTranslator(Translator $translator)
	{
		$this->t = $translator;
	}

} 