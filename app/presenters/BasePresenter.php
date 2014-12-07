<?php

namespace App;

use App\Components;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette;


/**
 * Base presenter for all application presenters.
 *
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	use AutowireComponentFactories;
	use AutowireProperties;
	//use Components\FlashMessages\TTranslatedFlashMessages;

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->redrawControl('flashMessages');
		$this->template->useFullAssets = $this->context->parameters['useFullAssets'];
	}


	protected function createComponentFlashMessages()
	{
		return new Components\FlashMessages\FlashMessagesControl();
	}


	protected function createComponentGoogleAnalytics()
	{
		return new Components\GoogleAnalytics\GoogleAnalyticsControl();
	}


}
