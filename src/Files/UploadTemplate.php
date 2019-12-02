<?php

namespace App\Wythe\Files;

class UploadTemplate extends TmpExcel
{
    /**
     * 创建上传模板
     *
     * @param  string  $varName
     * @return bool
     */
    protected function createContent()
    {
        foreach ($this->head as $key => $val) 
        {
            //列宽自动
            $this->sheet->getColumnDimensionByColumn($key+1)->setAutoSize(true);
            //设置字段名
            $this->sheet->setCellValueByColumnAndRow($key+1,1,$val['table'].'__'.$val['name']);
            //设置字段标题
            $this->sheet->setCellValueByColumnAndRow($key+1,2,$val['title']);
            //设置字段说明
            $this->sheet->setCellValueByColumnAndRow($key+1,3,$val['remarks']);
            //字段标题样式
            $this->sheet->getStyleByColumnAndRow($key+1,2)->getFont()->setBold(true)->setName('微软雅黑')->setSize(12);
            //字段说明字体设置
            $this->sheet->getStyleByColumnAndRow($key+1,3)->getFont()->setName('微软雅黑')->setSize(11);
            //字段说明颜色设置
            $this->sheet->getStyleByColumnAndRow($key+1,3)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        }
    }
    
    
}