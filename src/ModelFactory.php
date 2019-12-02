<?php

namespace Wythe\Interface;

class ModelFacotry
{
	/**
	 * make
	 *
	 * @param  
	 * @return 
	 */
	public static function make($request,$alias)
	{
		$class = config('userInterface.model.'.$alias);

		try
		{
			$model =  new $class($request,$alias);	
		}catch(\Throwable $e)
		{
			throw new \Exception($alias.'Not Exists');
		}

		return $model;
	}	
}