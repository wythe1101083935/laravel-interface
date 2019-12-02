<?php

namespace App\Wythe\Files;

use Illuminate\Support\Facades\Storage;

class TmpExcel
{
	/**
	 * 临时文件路径
	 *
	 * @var string
	 */
	protected $path;
	
    /**
     * 工作簿
     *
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    protected $excel;
    
    /**
     * 表 \PhpOffice\PhpSpreadsheet\Spreadsheet
     *
     * @var string
     */
    protected $sheet;
    
    /**
     * 表内容
     *
     * @var string
     */
    protected $content;

    /**
     * 临时文件名
     *
     * @var string
     */
    protected $fileName;

	/**
	 * 表头字段 
	 *
	 * @var Array
	 */
	protected $head;
	
	/**
	 * 表body数据
	 *
	 * @var Array
	 */
	protected $data;

	/**
	 * useFields
	 *
	 * @var string
	 */
	protected $useFields=false;
	
	
	/**
	 * init
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function __construct($head,$data=[],$useFields=false)
	{
		ini_set('memory_limit', '200M');

		$this->head = $head;

		$this->data = $data;

		$this->useFields = $useFields;
		//创建工作簿
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		//设置工作表
		$this->excel->setActiveSheetIndex(0);
		//获取工作表
		$this->sheet = $this->excel->getActiveSheet();
		//设置表名
		$this->sheet->setTitle('sheet1');
		//创建表内容
		$this->createContent();
	}

	/**
	 * 创建表内容
	 *
	 * @param  
	 * @return 
	 */
	protected function createContent(){}

	/**
	 * 设置文件随机名称
	 *
	 * @param  
	 * @return 
	 */
	protected function setFileName()
	{
        $name = date('YmdHis').rand(0,10000);   
        
        $this->fileName = $name;
        
        $this->path = storage_path('app/public/'.$name.'.xlsx');		
	}

    /**
     * getContent
     *
     * @param  
     * @return 
     */
    public function getContent()
    {
        if(is_null($this->content))
        {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
            
            $this->setFileName();

            $writer->save($this->path);

            $this->content = file_get_contents(storage_path('app/public/'.$this->fileName.'.xlsx'));  

            Storage::delete('public/'.$this->fileName.'.xlsx');      
        }

        return $this->content;
    }
    
    /**
     * 此文件 http返回头
     *
     * @param  
     * @return 
     */
    public function getHeader()
    {
        return 
        [
            "Cache-Control" => "max-age=0",
            "Content-Description" => "File Transfer",
            'Content-disposition' =>  'attachment; filename=' . $this->fileName.'.xlsx',
            "Content-Type" => "application/zip",
            "Content-Transfer-Encoding" => "binary",
            //'Content-Length' =>  filesize($name)
        ];
    }

	/**
	 * 返回
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function response()
	{
        return response($this->getContent(),200,$this->getHeader());
	}
}