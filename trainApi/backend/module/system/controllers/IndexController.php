<?php

namespace backend\module\system\controllers;

use yii\web\Controller;
use backend\models\system\FuncModel;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `system` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "系统设置首页";
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionNav()
    {
        $model = new FuncModel();
        $navList=$model->getFuncList();
        $temp="";
        if ($navList) {
            //对象转数组不收字段过滤器的影响
            $data = ArrayHelper::toArray($navList, [
                'common\models\SysFunction' => [
                    'funcId',
                    'name',
                    'type',
                    'icon',
                    'url',
                ],
            ]);
            foreach ($data as $key => $value) {
                if($value['type']==1){
                    $temp=$key;
                    $data[$temp]['url']=substr($value['url'],1);
                    $data[$temp]['list']=[];
                }
                else{
                    unset($data[$key]);
                    array_push($data[$temp]['list'],$value);
                }
            }
            return array('data' =>$data ,'msg' => "返回菜单" );
        } else {
            return "操作失败";
        }
    }
}
