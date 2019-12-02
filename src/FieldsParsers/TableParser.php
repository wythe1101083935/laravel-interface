<?php

namespace Wythe\Interface\FieldsParsers;

class TableParser extends FieldsParser
{	

	/**
	 * parse
	 *
	 * @param  
	 * @return 
	 */
	public function parse($data)
	{
		$this->parseInfo['fields'] = $this->parseFields($data['fields']);

		$this->parseInfo['search'] = $this->parseSeachFields($data['search']);

		$this->parseInfo['keywords'] = $this->parseKeywordsFields($data['keywords']);

		$this->parseInfo['actions'] = $this->parseActions($data['actions']);
	}
	
	/**
	 * parseFields
	 *
	 * @param  
	 * @return 
	 */
	protected function parseFields($fieldsList)
	{
		$fieldsInfo = [];

		foreach ($fieldsList as $key => $value) 
		{
			if(is_numeric($key))
			{
				$fieldsInfo[] = $this->getFields($value);
			}else{
				$fieldsInfo[] = $this->getFields($key,$value);
			}
		}

		return $fieldsInfo;
	}
}
