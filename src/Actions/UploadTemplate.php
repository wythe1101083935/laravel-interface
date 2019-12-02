<?php

namespace App\Wythe\Actions;

use App\Wythe\Files\UploadTemplate as UploadTemplateFile;

class UploadTemplate extends Upload
{

	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'upload.template';
	
	/**
	 * createResponse
	 *
	 * @param  
	 * @return 
	 */
	public function response($params=[])
	{
		$template = new UploadTemplateFile($this->getFields());

		return $template->response();
	}
}