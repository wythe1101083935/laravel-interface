<?php

namespace Wythe\Interface\FieldsParsers;
use Illuminate\Support\Arr;

class FieldsParser
{
	/**
	 * model
	 *
	 * @var string
	 */
	protected $model;
	
	/**
	 * parseInfo
	 *
	 * @var string
	 */
	protected $parseInfo;
	
	/**
	 * __construct
	 *
	 * @param  
	 * @return 
	 */
	public function __construct($model)
	{
		$this->model = $model;
	}
	
	/**
	 * defaultTableFields
	 *
	 * @param  
	 * @return 
	 */
	protected function getFields($fieldsName,$info=[])
	{
		$fieldstable = Arr::get($info,'table',$this->mainTable);

		$fieldsInfo = $this->cacheInfo->where('table',$fieldsTable)->where('name',$fieldsName)->first();

		$fieldsInfo['title'] = Arr::get($info,'title',trans($table.'.fields.'.$fieldsName));

		$fieldsInfo['remarks'] = Arr::get($info,'remarks',trans($table.'.remarks.'.$fieldsName))

		if(is_array($fieldsOptions) && !empty($fieldsInfo['options']))
		{
			foreach ($$fieldsInfo['options'] as &$option) 
			{
				$option['name'] = trans($fieldsTable.'.options.'.$fieldsName.'.'.$option['id']);
			}
		}

		$fields['sort'] = Arr::get($info,'sort',false);

		$fields['link'] = Arr::get($info,'link',false);

		if(Arr::get($this->urlInfo(),$fieldsTable.'.'.$fieldsName,false))
		{
			$fieldsInfo['type'] = 'select';
			$fieldsInfo['options'] = $this->getOptionsFromFromModel();
		}

		return $fieldsInfo;
	}
}