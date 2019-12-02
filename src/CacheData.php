<?php

namespace App\Wythe\Middleware;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Model\ErpTracker;
use Closure;

class CacheData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $this->cacheTableField();

        return $next($request);
    }

    /**
     * 缓存表字段
     *
     * @param  string  $varName
     * @return bool
     */
    protected function cacheTableField()
    {
        if(!Cache::has('wythe_table_fields'))
        {
            Cache::rememberForever('wythe_table_fields',function()
            {
                $fields = DB::table('information_schema.COLUMNS')->where('TABLE_SCHEMA',config('database.connections.mysql.database'))->get();
                $cacheFields = collect([]);
                foreach ($fields as $field) 
                {
                    $cacheFields->push($this->getFields($field));
                }
                return $cacheFields;
            });
        }
    }

    /**
     * getFields
     *
     * @param  string  $varName
     * @return bool
     */
    protected function getFields($field)
    {  
    	$data['name'] = $field->COLUMN_NAME;

   		$data['table'] = $field->TABLE_NAME;

    	$data['default'] = $field->COLUMN_DEFAULT;
   		
    	if(in_array($field->COLUMN_NAME,['remarks','address','consignee_address','shipper_address','strategy','note','notes','rule']))
    	{
    		$data['type'] = 'textarea';

    		$data['width'] = 200;
    	}elseif(in_array($field->DATA_TYPE,['timestamp','datetime']))
    	{
    		$data['type'] = 'datetime';

    		$data['width'] = 180;
    	}elseif($field->DATA_TYPE == 'enum')
    	{
    		$data['type'] = 'select';

    		$data['wdith'] = 120;

            $options = ltrim($field->COLUMN_TYPE,'enum(');

            $options = rtrim($options,')');

            $options = explode(',',str_replace("'",'',$options));

            $data['options'] = [];

            foreach ($options as $optionName) 
            {
                $data['options'][] = ['id'=>$optionName];
            }
    	}elseif(strpos($field->COLUMN_NAME,'file') !== false || strpos($field->COLUMN_NAME,'image') !== false)
    	{
    		$data['type'] = 'file';

    		$data['width'] = 120;
    	}else
        {
            $data['type'] = 'text';

            $data['width'] = 160;
        }

    	return $data;
    }
}