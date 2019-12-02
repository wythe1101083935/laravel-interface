<?php

namespace App\Wythe\Actions;

class Table extends Open
{
	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'table';

	/**
	 * view
	 *
	 * @var
	 */
	protected $view = 'wythe.table';

	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response($params=[])
	{
		$data = 
		[
			'name'=>$this->model->getName(),
			'type'=>$this->name,
			'fields'=>$this->model->getTableFields(),
			'action'=>$this->getArrayActions(),
			'search'=>$this->model->getSearchs(),
			'keywords'=>$this->model->getKeywords(),
			'url'=>'/'.$this->pre.'/'.$this->model->getName().'/list',
			'exportUrl'=>'/'.$this->pre.'/'.$this->model->getName().'/export',
			'orderBy'=>$this->model->getSort(),
		];


		$data['lang'] = 
		[
			'exportLang'=>
			[
				'title'=>trans('index.export.title'),
				'all'=>trans('index.export.all'),
				'select'=>trans('index.export.select'),
				'page'=>trans('index.export.page'),
			],
			'searchLang'=>trans('index.search'),
			'advancedLang'=>trans('index.advanced-search'),
			'confirmLang'=>trans('index.confirm'),
			'resetLang'=>trans('index.reset'),
			'leastOneLang'=>trans('index.select_least_one'),
			'selectOneLang'=>trans('index.select_one'),
			'actionListLang'=>trans('index.action_list'),
			'confirmPromptLang'=>trans('index.confirm_prompt'),
		];
		
		return $this->view($data);	
	}

	/**
	 * 交互模型动作转为数组
	 *
	 * @param  
	 * @return 
	 */
	public function getArrayActions()
	{
		$data = [];

		foreach($this->model->getActions() as $action)
		{
			if($action->getName() != 'table')
			{
				$data[] = $action->toArray();
			}
		}

		return $data;
	}
	
	
}