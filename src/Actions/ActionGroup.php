<?php

namespace App\Wythe\Actions;
use App\Wythe\ActionFactory;

class ActionGroup extends Action
{
	/**
	 * name
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * type
	 *
	 * @var string
	 */
	protected $type="group";
	
	/**
	 * container
	 *
	 * @var string
	 */
	protected $container;
	
	/**
	 * init
	 *
	 * @param  
	 * @return 
	 */
	public function __construct($model,$params)
	{
		parent::__construct($model,$params);

		$this->type = 'group';

		foreach ($params['list'] as $params) 
		{
			$params['name'] = $this->name.'.'.$params['name'];
			
			$this->container[] = ActionFactory::make($this->model,$params);
		}
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

		$data['childs'] = [];

		foreach ($this->container as $action) 
		{
			$data['childs'][] = $action->toArray();
		}

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
		return 'Empty';
	}
	
	
	
	
	
	
	
	
}