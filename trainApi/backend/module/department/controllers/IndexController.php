<?php

namespace backend\module\department\controllers;

use yii\web\Controller;

/**
 * Default controller for the `finance` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "专业管理首页";
    }
}
