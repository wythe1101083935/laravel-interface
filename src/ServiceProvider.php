<?php

namespace Wythe\Interface;

use Illuminate\Support\ServiceProvider;
class ServiceProvider extends BaseServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred
	 *
	 * @var string
	 */
	protected $defer = false;

	/**
	 * register
	 *
	 * @param  
	 * @return 
	 */
	public function reigster()
	{
		$this->mergeConfigFrom($this->configPath(),'interface');

		$this->app->singleton(ModelFactory::class,function($app)
		{
			return new ModelFactory;
		});

		$this->app->singleton(ActionFactory::class,function($app)
		{
			return new ActionFactory;
		});
	}

	/**
	 * configPath
	 *
	 * @param  
	 * @return 
	 */
	protected function configPath()
	{
		return __DIR__ . '/../config/interface.php';
	}
	
	
	
	
	
	
}