<?php

namespace App\Wythe\Actions;
use Illuminate\Support\Facades\DB;

class ListData extends RequestAction
{

	/**
	 * orderB
	 *
	 * @var 
	 */
	protected $orderBy=false;
	
	

	/**
	 * name
	 *
	 * @var string
	 */
	protected $name = 'list';
	
	/**
	 * response
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response()
	{
		$data = ['count'=>$this->count(),'data'=>$this->get()];

		return response()->json($data);
	}

	/**
	 * whereSearch
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function whereSearch()
	{
		$searchFields = $this->model->getSearchs();

		if(!$searchFields)
		{
			return function($query){};
		}

		$searchData = isset($this->requestData['where']) ? $this->requestData['where'] : [];

		if(empty($searchData))
		{
			return function($query){};
		}
		return function($query) use ($searchFields,$searchData)
		{
			foreach ($searchFields as $fields) 
			{
				$name = $fields['table'].'.'.$fields['name'];
				if(!isset($searchData[$name]))
				{
					continue;
				}

				$value = $searchData[$name];

				//where时间
				if($fields['type'] == 'datetime')
				{
	                list($start,$end)= explode('~',$value);

	                $query->whereBetween($name,[trim($start),trim($end)]);
	            //where普通字段			
				}else
				{
					$query->where($name,$value);
				}
			} 
		};
  		
	}

	/**
	 * orderBy
	 *
	 * @param  
	 * @return 
	 */
	protected function getOrderBy()
	{
		if($this->orderBy)
		{
			return [$this->orderBy,'asc'];

		}elseif(isset($this->requestData['sort_field']) && $this->requestData['sort_field'])
		{
			$field = $this->requestData['sort_field'];

			$order = isset($this->requestData['sort_order']) ? $this->requestData['sort_order'] : 'desc';
			
			return [$field,$order];

		}else
		{
			return [false,false];
		}
	}
	
	
	/**
	 * setRequest
	 *
	 * @param
	 * @return
	 */
	public function setWhere($key,$values)
	{
	    $this->requestData['keywordsField'] = $key;

		$this->requestData['keywordsValues'] = $values;

		return $this;
	}


	/**
	 * whereKeywords
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function whereKeywords()
	{
	   $fields = $this->model->getKeywords();

	   if(!$fields 
	   	|| !isset($this->requestData['keywordsField'])
	   	|| !$this->requestData['keywordsField']
	   	|| !isset($this->requestData['keywordsValues'])
	   	|| !$this->requestData['keywordsValues']
	   )
	   {
	   		return function($query){};
	   }

	   $keywordsField = $this->requestData['keywordsField'];

	   $arr = $this->filterKeywords($this->requestData['keywordsValues']);

	   return function($query) use ($arr,$fields,$keywordsField)
	   {
	   		foreach ($fields as  $field) 
	   		{
	   			$name = $field['table'].'.'.$field['name'];

	   			if($name == $keywordsField)
	   			{
					$query->whereIn($name,$arr);

					$this->orderBy = DB::raw("INSTR(',".implode(',',$arr).",',CONCAT(',',".$name.",','))");
				   	/*if(count($arr) > 1)
				   	{


				   	}elseif(count($arr) == 1)
				  	{
				  		$query->where($name,'like','%'.$arr[0].'%');
				   	}*/
				   	break;	   				
	   			}
	   		}
	   };
	}
	
	/**
	 * get
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function get()
	{
		list($offset,$limit) = $this->page();


		$data = $this->model->getModel()
		->where($this->model->where())
		->where($this->whereKeywords())
		->where($this->whereSearch())
		->offset($offset)->limit($limit)
		->select($this->model->select());

		list($field,$order) = $this->getOrderBy();

		if($field)
		{
			$data = $data->orderBy($field,$order)->get();
		}else
		{
			$data = $data->get();
		}



		return $this->model->dataFilter($data);
	}

	/**
	 * optionsGet
	 *
	 * @param  
	 * @return 
	 */
	public function optionsGet($isRelation=false)
	{
		$model = $this->model->getModel()->where($this->model->where());

		if($isRelation)
		{
			return $model->select($this->model->relationSelect())->get();
		}else
		{
			return $model->select($this->model->optionSelect())->get();
		}
	}
	
	
	
	/**
	 * count
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function count()
	{
		$count = $this->model->getModel()
		->where($this->model->where())
		->where($this->whereKeywords())
		->where($this->whereSearch())		
		->count();

		return $count;
	}
	
	/**
	 * page
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	protected function page()
	{
		if(isset($this->requestData['page']) && isset($this->requestData['pageSize']))
		{
			$offset = ($this->requestData['page'] -1)*$this->requestData['pageSize'] ? : 0;

			$limit = $this->requestData['pageSize'] ? : 10;

			return [$offset,$limit];
		}

		return [0,1000];
	}
	
    /**
     * 处理keywords字符串
     *
     * @param  string  $keywords
     * @return bool
     */
    protected function filterKeywords($keywords)
    {
        if(! is_array($keywords))
        {
            $keywords = str_replace('，',',',$keywords);

            $keywords = str_replace("</ br>",',',$keywords);

            $keywords = str_replace("%0A",',',$keywords);

            $keywords = str_replace("\n",',',$keywords);

            $arr = explode(',',$keywords);

        }else
        {
            $arr = $keywords;
        }


        $arr = array_filter($arr,function($v)
        {
            return $v;
        });

        return $arr;
    }  	
}