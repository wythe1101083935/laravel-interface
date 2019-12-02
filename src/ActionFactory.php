<?php

namespace Wythe\Interface;

class ActionFacotry
{
	/**
	 * make
	 *
	 * @param  
	 * @return 
	 */
	public static function make($model,$params)
	{
		$action = self::makeWithName($model,$params['name']);

		if($action !== false)
		{
			return $action;
		}

		if(isset($params['list']) && !empty($params['list']))
		{
			$params['type'] = 'group';
		}

		if(isset($params['fields']) && !empty($params['fields']))
		{
			$params['type'] = 'open_content_request';
		}

		if(isset($params['type']))
		{
			switch ($params['type']) 
			{
				case 'open':
				case 'open_with_one':
					return new \App\Wythe\Actions\Open($model,$params);
				case 'group':
					return new \App\Wythe\Actions\ActionGroup($model,$params);
				case 'open_content_request':
					return new \App\Wythe\Actions\OpenContentRequest($model,$params);
			}		
		}

		return new \App\Wythe\Actions\RequestAction($model,$params);
	}

	/**
	 * makeWithName
	 *
	 * @param  
	 * @return 
	 */
	public static function makeWithName($model,$name)
	{
		switch ($name) 
		{
			case 'create':
				return new \App\Wythe\Actions\Create($model,['name'=>$name]);
			case 'update':
				return new \App\Wythe\Actions\Update($model,['name'=>$name]);
			case 'upload':
				return new \App\Wythe\Actions\Upload($model,['name'=>$name]);
			case 'upload.template':
				return new \App\Wythe\Actions\UploadTemplate($model,['name'=>$name]);
			case 'upload.tmpexport':
				return new \App\Wythe\Actions\UploadTmpDataExport($model,['name'=>$name]);
			case 'table':
				return new \App\Wythe\Actions\Table($model,['name'=>$name]);
			case 'list':
				return new \App\Wythe\Actions\ListData($model,['name'=>$name]);
			case 'export':
				return new \App\Wythe\Actions\Export($model,['name'=>$name]);
			default:
				return false;
		}
	}

}