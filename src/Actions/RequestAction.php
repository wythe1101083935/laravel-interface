<?php

namespace App\Wythe\Actions;

class RequestAction extends Action
{

	/**
	 * init
	 *
	 * @param  
	 * @return 
	 */
	public function __construct($model,$params=[])
	{
		parent::__construct($model,$params);

		if(isset($params['type']))
		{
			$this->type = $params['type'];
		}else
		{
			$this->type = 'request';
		}

	}

	/**
	 * toArray
	 *
	 * @param  
	 * @return 
	 */
	public function toArray()
	{
		$data = parent::toArray();

		$data['url'] = '/'.$this->model->getName().'/'.$this->name;

		return $data;
	}
	
	

	/**
	 * response
	 *
	 * @param  
	 * @return 
	 */
	public function response()
	{
		throw new \Exception(trans('index.system-error'));
	}
	
	
	

	
}