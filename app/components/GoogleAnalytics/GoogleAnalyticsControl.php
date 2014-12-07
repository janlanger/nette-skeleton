<?php

namespace App\Components\GoogleAnalytics;

use App\Components\BaseControl;
use Nette;
use Nette\Utils\Strings;


class GoogleAnalyticsControl extends BaseControl
{

	public function render()
	{
		if (func_num_args()) {
			list($acc) = func_get_args();
		}

		$dic = $this->presenter->context;
		if (!$dic->parameters['productionMode']) {
			return;
		}

		$this->template->acc = isset($acc) ? $acc : $dic->parameters['googleAnalytics'];
		list(, $this->template->domain) = Strings::match($dic->httpRequest->url->host, '~([^.]+.[^.]+)$~');
		$this->template->ssl = $dic->httpRequest->isSecured();
		$this->template->render();
	}

}
