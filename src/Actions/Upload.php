<?php

namespace App\Wythe\Actions;

use App\Wythe\Files\UploadTemplate;

class Upload extends Open
{
	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'upload';
	
	/**
	 * view
	 * 
	 * @var 
	 */
	protected $view = 'wythe.upload';
	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response($params=[])
	{
		$data = parent::openBaseResponse();

		$data['fields'] = $this->getFields();

		$data['quotes'] = $this->model->getFormFields();

		$data['uploadUrl'] = '/'.$this->model->getName().'/upload';

		$data['templateUrl'] = '/'.$this->pre.'/'.$this->model->getName().'/upload.template';

		$data['exportUrl'] = '/'.$this->pre.'/'.$this->model->getName().'/upload.tmpexport';

		$data['lang'] = 
		[
			'promptLang'=>trans("index.prompt"),
			'indexLang'=>trans("index.index"),
			'selectFileLang'=>trans('index.selectFile'),
			'startUploadLang'=>trans('index.startUpload'),
			'downloadModelLang'=>trans('index.downloadModel'),
			'statusLang'=>trans('index.status'),
			'successLang'=>trans('index.success'),
			'errorLang'=>trans('index.error'),
			'exportLang'=>trans('index.export.title'),
		];

		return $this->view($data);
	}
	
	/**
	 * getFields
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function getFields()
	{
		$quotes = $this->model->getFormFields();

		$data = [];

		foreach ($quotes as $quote) 
		{
			foreach ($quote['fields'] as $fields) 
			{
				$data[] = $fields;
			}
		}

		return $data;
	}
	
}