<?php

namespace App\Components\FlashMessages;

use App\Components\BaseControl;
use Nette;


class FlashMessagesControl extends BaseControl
{

	public function render()
	{
		$this->template->flashes = $this->parent->template->flashes;
		$this->template->render();
	}

}
