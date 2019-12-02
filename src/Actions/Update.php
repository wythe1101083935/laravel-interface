<?php

namespace App\Wythe\Actions;

class Update extends Create
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

		$this->type = 'open_with_one';
	}
	
	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response($params=[])
	{
		$this->id = $params['id'];
		
		$data = 
		[
			'name'=>$this->model->getName(),
			'type'=>$this->name,
			'url'=>'/'.$this->model->getName().'/'.$this->name,
		];

		$data['quotes'] = $this->getFieldsAndDefault();

		$data['quotes'] = $this->getValues($data['quotes']);

		if(!empty($this->relation))
		{
			$data['relation'] = $this->relation;
		}

		$data['lang'] = 
		[
			'submitLang'=>trans('index.submit'),
			'resetLang'=>trans('index.reset'),
			'selectFileLang'=>trans('index.selectFile'),
		];

		$data['id'] = $this->id;

		return $this->view($data);
	}
	
	/**
	 * getDefault
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function getValues($quotes)
	{
		$data = $this->model->getUpdateData($this->id);

		foreach ($quotes as &$quote) 
		{
			$quote['value'] = [];

			if($quote['type'] == 'form')
			{
				foreach ($quote['fields'] as &$fields) 
				{
					$quote['value'][$fields['name']] = isset($data[$fields['name']]) ? $data[$fields['name']] : '';
				}
			}

			if($quote['type'] == 'table')
			{
				$quote['value'] = $this->model->getTableModel($this->id)->get()->toArray();

				foreach ($quote['value'] as &$value) 
				{
					if(isset($value['pivot']))
					{
						$value += $value['pivot'];
					}
				}
			}
		}

		return $quotes;
	}

	
}