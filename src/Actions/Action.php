<?php

namespace App\Wythe\Actions;

use App\Wythe\ActionFactory;

class Action
{
	/**
	 * Url pre
	 *
	 * @var string
	 */
	protected $pre='wythe';
	
	/**
	 * 动作名称 唯一 用作后台判断动作
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * 动作标题 根据当前语言自动获取
	 *
	 * @var string
	 */
	protected $title;
	
	/**
	 * 动作图标 定义类设置
	 *
	 * @var string
	 */
	protected $icon;

	/**
	 * 动作的界面类型 用于前端判读动作的类型
	 * @var
	 * open | open_with_one | submit | submit_with_one | open_content_with_one | open_content
	 */
	protected $type;

	/**
	 * 动作的返回类型，DEBUG
	 * 
	 * @var 
	 */
	protected $returnType='request';

	/**
	 * 动作所属的交互模型
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * url
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * view
	 *
	 * @var
	 */
	protected $view='wythe.form';
	

	/**
	 * requestData
	 *
	 * @var string
	 */
	protected $requestData;
	
	/**
	 * 初始化，获取交互模型和动作名称
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function __construct($model,$params)
	{
		$this->model = $model;
		
		$this->name = $params['name'];

		if(isset($params['url']))
		{
			$this->url = $params['url'];
		}

		if(isset($params['returnType']))
		{
			$this->returnType = $params['returnType'];
		}

		$this->title = trans('action.'.$this->name.'.title');
		
		$this->pre = config('userInterface.pre') ? : 'wythe';

        if($model->request->jsonData)
        {
            $this->requestData = json_decode($model->request->jsonData,true);
        }else
        {
            $this->requestData = $model->request->all();
        }

	}
	
	/**
	 * 动作转为数组
	 *
	 * @param  
	 * @return 
	 */
	public function toArray()
	{ 
		return [
			'name'=>$this->name,
			'title'=>$this->title,
			'icon'=>$this->icon,
			'type'=>$this->type,
			'url'=>$this->url ?: '/'.$this->pre.'/'.$this->model->getName().'/'.$this->name,
			'returnType'=>$this->returnType
		];
	}

	/**
	 * view
	 *
	 * @param  
	 * @return 
	 */
	public function view($data)
	{
		return view($this->view,['data'=>$data]);
	}

	/**
	 * 获取动作名称
	 *
	 * @param  
	 * @return String
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * 获取动作类型
	 *
	 * @param  
	 * @return String
	 */
	public function getType()
	{
		return $this->type;
	}

}