<?php
namespace frontend\module\wechart\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
use frontend\models\wechart\club\MeetNotices;
use frontend\models\wechart\club\MeetArrange;
use frontend\models\wechart\club\MeetMaterial;
use frontend\models\wechart\club\MeetInteract;
use frontend\models\wechart\club\MeetList;
use frontend\models\wechart\club\MeetAttender;
use frontend\models\wechart\club\MeetStatics;
/**
 * Site controller
 */
class ClubController extends Controller
{                                                                                                     
    /**
     * {@inheritdoc}
     * 若要开启access_token，则打开下authenticator
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                // 'actions' => [
                //     'logout' => ['get'],
                // ],
            ],
            //设置了access_token验证的，需要在请求url上带上access_token
            // 'authenticator' => [ 
            //     'class' => QueryParamAuth::className() ,
            //     'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
            // ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['http://phpitem.grouptong.top'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers'=>['x-requested-with','content-type']
                ],
            ], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return array("data"=>"欢迎到来--会议的模块","msg"=>"233333");
    }

    public function actionGetNotices()
    {

        $model = new MeetNotices(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $notices=$model->getNotices();
            if ($notices) {
                return array("msg"=>"获取成功","data"=>$notices);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionGetIntercat()
    {
        $model = new MeetInteract(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $interact=$model->getIntercat();
            if ($interact) {
                return array("msg"=>"获取成功","data"=>$interact);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionGetMaterial()
    {
        $model = new MeetMaterial(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $material=$model->getMaterial();
            if ($material) {
                return array("msg"=>"获取成功","data"=>$material);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionGetMeetList()
    {
        $model = new MeetList(); 
        $access_token=Yii::$app->request->get('access_token');
        if ($model->load(Yii::$app->request->post(),"")) {
            $meetList=$model->getMeetReportList($access_token);
            if ($meetList) {
                return array("msg"=>"获取成功","data"=>$meetList);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionGetAttenderList()
    {
        $model = new MeetAttender(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $attenderList=$model->getSignList();
            if ($attenderList) {
                return array("msg"=>"获取成功","data"=>$attenderList);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionGetStaticsData()
    {
        $model = new MeetStatics(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $statics=$model->getStatics();
            if ($statics) {
                return array("msg"=>"获取成功","data"=>$statics);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }
    public function actionLoadMaterial()
    {
        $model = new MeetMaterial(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $material=$model->loadMaterial();
            if ($material) {
                return array("msg"=>"设置成功","data"=>$material);
            } else {
                return "设置失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionDoInteract()
    {
        $model = new MeetInteract(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $interact=$model->doInteract();
            if ($interact) {
                return array("msg"=>"设置成功","data"=>$interact);
            } else {
                return "设置失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }

    public function actionSign()
    {
        $model = new MeetAttender(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $interact=$model->setSign();
            if ($interact) {
                return array("msg"=>"签到成功","data"=>$interact);
            } else {
                return "签到失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }
}
