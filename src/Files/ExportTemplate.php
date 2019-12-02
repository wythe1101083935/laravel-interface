<?php

namespace App\Wythe\Files;

class ExportTemplate extends TmpExcel
{
	/**
	 * 创建文件内容
	 *
	 * @param  
	 * @return 
	 */
	protected function createContent()
	{
        $colsIndex = [];

        $i = 1;

        foreach ($this->head as $key => $val) 
        {
            $this->sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

            if($this->useFields)
            {
                $this->sheet->setCellValueByColumnAndRow($i,1,$val['table'].'__'.$val['name']);
            }else
            {
                $this->sheet->setCellValueByColumnAndRow($i,1,$val['title']);
            }

            $this->sheet->getStyleByColumnAndRow($i,1)->getFont()->setBold(true)->setSize(12)->setName('微软雅黑');

            $colsIndex[$val['table'].'__'.$val['name']] = $i;

            $i++;
        }

        if($this->data instanceof \Illuminate\Support\Collection)
        {
          $data = $this->data->values()->all();
        }else
        {
          $data = $this->data;
        }

        $j = 1;
        foreach ($data as $key => $val) 
        {
            if(method_exists($val, 'toArray'))
            {
                $val = $val->toArray();
            }else
            {
                $val = (array)$val;
            }
            
            $val['wythe__index'] = $j;

            $j++;
            foreach($val as $field => $value)
            {
                if(isset($colsIndex[$field]))
                {
                    $this->sheet->setCellValueByColumnAndRow($colsIndex[$field],$key+2,trim(strip_tags($value)),\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                }
            }
        }		
	}

	/**
	 * file
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function content()
	{
		return $this->getContent();
	}
	
	/**
	 * header
	 *
	 * @param  string  $varName
	 * @return bool
	 */
	public function header()
	{
		return $this->getHeader();
	}
}