<?php

namespace App\Wythe\Actions;

class OpenContentRequest extends RequestAction
{
	/**
	 * 动作前置填入数据
	 *
	 * @var string
	 */
	protected $fields;

	/**
	 * init
	 *
	 * @param  
	 * @return 
	 */
	public function __construct($model,$params)
	{
		parent::__construct($model,$params);

		$this->type = 'open_content_request';

		$this->fields = $this->model->getFields($params['fields']);
	}

	/**
	 * view
	 *
	 * @param  
	 * @return 
	 */
	public function toArray()
	{
		$data = parent::toArray();

		$data['fields'] = $this->fields;

		$data['requestUrl'] = '/'.$this->model->getName().'/'.$this->name;

		return $data;
	}

	public function response()
	{
		new \Exception(trans('index.system-error'));
	}	
	
}