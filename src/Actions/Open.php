<?php

namespace App\Wythe\Actions;

class Open extends Action
{
	/**
	 * init
	 *
	 * @param  
	 * @return 
	 */
	public function __construct($model,$params)
	{
		parent::__construct($model,$params);

		if(isset($params['type']))
		{
			$this->type = $params['type'];
		}else
		{
			$this->type = 'open';
		}

	}

	/**
	 * response
	 *
	 * @param  
	 * @return 
	 */
	public function response()
	{
		return $this->view($this->openBaseResponse());
	}

	/**
	 * openBaseResponse
	 *
	 * @param  
	 * @return 
	 */
	protected function openBaseResponse()
	{
		return 
		[
			'name'=>$this->model->getName(),
			'type'=>$this->name,
			'url'=>'/'.$this->model->getName().'/'.$this->name,
		];	
	}
	
	
}