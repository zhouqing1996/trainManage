<?php

namespace backend\module\probation\controllers;
use Yii;
use yii\web\Controller;
use backend\models\practice\ExportModel;

/**
 * Default controller for the `finance` module
 */
class ExportController extends Controller
{
    //导出实习总结
    public function actionSumword()
    {
        $stu = Yii::$app->request->post("stu");
        $summary = Yii::$app->request->post("summary");

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('基本信息', array('size' => 16, 'bold' => true));
        $section->addText("姓名：{$stu['stuName']}    学号：{$stu['sno']}    专业：{$stu['major']}", array('size' => 12));
        $section->addText('');
        $section->addText('实习总结', array('size' => 16, 'bold' => true));
        $table = $section->addTable();
        for ($a = 1; $a <= count($summary); $a++) {
            $table->addRow();
            $table->addCell(1750)->addText("第{$a}次总结",array('size' => 12, 'bold' => true));
            $table->addRow();
            $table->addCell(1750)->addText("标题");
            $table->addCell(7000)->addText($summary[$a-1]['subject']);
            $table->addRow();
            $table->addCell(1750)->addText("内容");
            $table->addCell(7000)->addText($summary[$a-1]['content']);
            $table->addRow();
            $table->addCell(1750)->addText("时间");
            $table->addCell(7000)->addText($summary[$a-1]['date']);
            $table->addRow();
            $table->addCell(1750)->addText("教师评价");
            $table->addCell(7000)->addText($summary[$a-1]['teacherEvaluate']);
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(iconv("utf-8","gb2312","../exportFile/{$stu['stuName']}实习总结.docx"));
        return array("msg"=>"导出实习总结","data"=>"/exportFile/{$stu['stuName']}实习总结.docx");
    }

}

?>
