<?php

namespace App\Wythe\Actions;

use App\Wythe\Files\ExportTemplate;

class UploadTmpDataExport extends Upload
{
	/**
	 * response
	 *
	 * @param  
	 * @return 
	 */
	public function response($params=[])
	{
		$head = $this->getFields();

		array_unshift($head,['name'=>'msg','title'=>trans('index.prompt'),'table'=>'prompt']);

		array_unshift($head,['name'=>'status','title'=>trans('index.status'),'table'=>'prompt']);

		$data = $this->requestData;

		return (new ExportTemplate($head,$data,true))->response();
	}
}