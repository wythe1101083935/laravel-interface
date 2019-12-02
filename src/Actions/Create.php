<?php

namespace App\Wythe\Actions;

class Create extends Open
{
	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'create';
	
	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response($params=[])
	{
		$data = $this->openBaseResponse();

		$data['id'] = $params['id'];
		
		$data['quotes'] = $this->getFieldsAndDefault();

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

		return $this->view($data);	
	}
	
	/**
	 * getDefault
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function getFieldsAndDefault()
	{
		$quotes = $this->model->getFormFields();

		foreach ($quotes as &$quote) 
		{
			if($quote['type'] == 'form')
			{
				foreach ($quote['fields'] as &$fields) 
				{
					$quote['default'][$fields['name']] = $fields['default'];
				}
			}

			if($quote['type'] == 'table')
			{
				$quote['default'][0] = collect($quote['fields'])->pluck('default','name');
			}
		}

		return $quotes;
	}
	

	
}