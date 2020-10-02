<?php

namespace backend\module\app\controllers;

use yii\web\Controller;

/**
 * Default controller for the `home` module
 */
class NoticeController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionGetdata()
    {
        return "首页";
    }

    
}
