<?php

namespace Wythe\Interface;

use Illumiante\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Wythe\ActionFactory;
use Illuminate\Support\Arr;

class Model
{
	/**
	 * request
	 *
	 * @var string
	 */
	public $request;
	
	/**
	 * mainTable
	 *
	 * @var string
	 */
	protected $mainTable;
	
	/**
	 * idFields
	 *
	 * @var string
	 */
	protected $idFields;
	
	/**
	 * cacheInfo
	 *
	 * @var string
	 */
	protected $cacheInfo;

	/**
	 * query
	 *
	 * @var string
	 */
	protected $query;

	/**
	 * tableView
	 *
	 * @var string
	 */
	protected $parseInfo;
	
	
	/**
	 * 初始化 若没有自定义名称，自动获取交互模块名称 model.name
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function __construct($request,$name)
	{
		$this->request = $request;	
		
		$this->name = $name;

		$this->cacheInfo = Cache::get('wythe_table_fields');
	}

	/**
	 * tableFields
	 *
	 * @param  
	 * @return 
	 */
	public function tableInfo()
	{
		return [
			'fields'=>[],
			'search'=>[],
			'keywords'=>[],
			'actions'=>[]
		];
	}

	/**
	 * select
	 *
	 * @param  
	 * @return 
	 */
	public function select()
	{
		
	}
	
	/**
	 * optionSelect
	 *
	 * @param  
	 * @return 
	 */
	public function optionSelect()
	{
		return \Illuminate\Support\Facades\DB::raw('id,name');
	}
	
	/**
	 * relationSelect
	 *
	 * @param  
	 * @return 
	 */
	public function relationSelect()
	{
		return \Illuminate\Support\Facades\DB::raw('id,name,pid');
	}	

	/**
	 * createFields
	 *
	 * @param  
	 * @return 
	 */
	public function createInfo()
	{
		return [
			[
				'name'=>'',
				'type'=>'',
				'fields'=>'',
			],
		];
	}
	
	/**
	 * updateFields
	 *
	 * @param  
	 * @return 
	 */
	public function updateInfo()
	{
		return [
			'fields'=>[],
		];
	}

	/**
	 * optionsInfo
	 *
	 * @param  
	 * @return 
	 */
	public function urlInfo()
	{
		return [];
	}

	/**
	 * getParseInfo
	 *
	 * @param  
	 * @return 
	 */
	public function getParseInfo($action='table')
	{
		Arr::get($this->parseInfo,$action,function()use($action){

			$parser = $this->makeFieldsParser($action);

			return $parser->parse(call_user_func_array([$this,$action.'Info'],[]));
		});
	}

	/**
	 * makeFieldsParser
	 *
	 * @param  
	 * @return 
	 */
	protected function makeFieldsParser($action)
	{
		switch ($action) {
			case 'table':
				return new \Wythe\FieldsParser\TableParser($this);
			case 'create':
			case 'update':
			case 'block':
				return new \Wythe\FieldsParser\FormParser($this);
			default:
				$class = config('interface.parser.'.$action)
				if($class)
				{
					return new $class($this);
				}
		}
	}
	
	/**
	 * 设置表格页数据模型
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function getModel()
	{
		if(is_null($this->query))
		{
			$this->query = $this->setModel();
		}

		return $this->query;
	}

	/**
	 * setTableModel
	 *
	 * @param  
	 * @return 
	 */
	public function setModel()
	{
		return null;
	}
	
	/**
	 * getUpdateModel
	 *
	 * @param 
	 * @return 
	 */
	public function getUpdateData()
	{
		return $this->getModel()->where($this->mainTable.'.'.$this->idFields,$id)->first();
	}

	/**
	 * 设置表格页默认条件刷选
	 *
	 * @param  
	 * @return 
	 */
	public function where()
	{
		return function($query)
		{
			$this->whereDefault($query,$this->request);
		};
	}

	/**
	 * whereDefault
	 *
	 * @param  
	 * @return 
	 */
	public function whereDefault($query,$request)
	{
		return false;
	}
	
	/**
	 * dataFilter
	 *
	 * @param  
	 * @return 
	 */
	public function tableDataFilter($data)
	{
		return $data;
	}
}