<?php

namespace App\Wythe\Actions;

use App\Wythe\Files\ExportTemplate;

class Export extends ListData
{
	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'export';
	
	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response()
	{
		$heads = $this->model->getTableFields();

		$data = $this->get();

		return (new ExportTemplate($heads,$data))->response();		
	}
}