<?php

namespace App;

use Model;
use Nette;


/**
 * Homepage presenter.
 */
class DefaultPresenter extends BasePresenter
{


	public function renderDefault()
	{
		$this->template->content = 'Hello world';
	}

}
