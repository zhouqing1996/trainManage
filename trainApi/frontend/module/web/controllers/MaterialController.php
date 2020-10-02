<?php

namespace frontend\module\web\controllers;
use Yii;
use yii\web\Controller;
use common\models\MeetArrangeList;
use common\models\ExpertUserInfo;
use backend\models\meetlist\MeetMaterial;
use common\models\ModuleScene;
use common\models\VwModuleSceneMaterial;
use common\models\MeetList;
/**
 * Default controller for the `finance` module
 */
class MaterialController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "报告人上传资料";
    }

    /**
     * @introduce 报告人上传文件
     * @author ZeroC
     * @date      2018-11-06
     * @data=array('meetId','arrangeId','username'=>'用户手机号','filename');
     * $_FILE[];
     * @return    [type]     [description]
     */
    public function actionUploadmaterial()
    {
        $data=Yii::$app->request->post();
        /*从会议id和会场id获取报告人信息，检验该用户是否是报告人*/
        $arrangeInfo=MeetArrangeList::findOne(['meetId'=>$data['meetId'],'id'=>$data['arrangeId']]);
        if(empty($arrangeInfo)){
            return array("msg"=>"400","data"=>"会议或会场不存在");
        }
        $expertInfo=ExpertUserInfo::findOne(['USER'=>$data['username']]);
        if(empty($expertInfo)){
            return array("msg"=>"401","data"=>"您没有上传权限");
        }
        $trans=Yii::$app->db->beginTransaction();
        try{
            $menu=str_replace('\\','/',dirname(Yii::$app->BasePath));
            $upload_path =$menu.'/'.@backend."/Uploads/material/";
            $web_path ="/backend/Uploads/material/";

            if(!is_dir($upload_path.date("Y-m-d"))){
                mkdir($upload_path.date("Y-m-d"));
            }
            // return array("msg"=>'410',"data"=>$_FILES['file']);

            $filename=$_FILES['file']['name'];
            $fileArr = explode('.',$filename);
            $tempName=date("Y-m-d").'/'.date("YmdHis").rand(1,100).".".$fileArr[1];
            $upload_path=$upload_path.$tempName;
            $fileType=$_FILES["file"]["type"];
            $fileSize=$_FILES["file"]["size"];

            /*文件接收处理*/
            if(!move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path)){
                $trans->rollBack();
                throw new \Exception("404-上传失败");
            }

            $materialData['meetId']=$data['meetId'];
            $materialData['content']=$web_path.$tempName;
            $materialData['author']=$data['username'];
            $materialData['authorId']=$data['username'];
            $materialData['reportId']=$data['arrangeId'];
            $materialData['title']=$data['title'];
            $materialData['format']=$fileType;
            $materialData['size']=$fileSize;
            /*数据库写入*/
            $model= new ModuleScene();
            $model->meetId=$materialData['meetId'];
            $model->content=$materialData['content'];
            $model->author=$materialData['author'];
            $model->authorId=$materialData['authorId'];
            $model->reportId=$materialData['reportId'];
            $model->title=$materialData['title'];
            $model->format=$materialData['format'];
            $model->size=$materialData['size'];
            $model->time=date('Y-m-d H:i:s', time());
            $model->kind=4;
            $model->status=1;

            if (!$model->save()) {
                throw new \Exception("402-上传失败");
            }
            $trans->commit();
            throw new \Exception("200-上传成功");
        }catch(\Exception $e){
            $exception=explode('-',$e->getMessage());
            if(isset($exception[1])){
                return array("msg"=>$exception[0],"data"=>$exception[1]);
            }
            return array("msg"=>'410',"data"=>$exception[0]);
        }
    }

    /**
     * @introduce 删除文件
     * @author ZeroC
     * @date      2018-11-06
     * @data=array('materialId','username')
     * @return    [type]     [description]
     */
    public function actionDealmaterial(){
        $data=Yii::$app->request->post();
        $materialInfo=ModuleScene::findOne(['id'=>$data['materialId'],'kind'=>4,'authorId'=>$data['username']]);
        if($data["kind"]==1){
            $materialInfo->isOpen='1';
            if($materialInfo->save())
                return array("msg"=>'200',"data"=>'设置成功');
            else
                return array("msg"=>'401',"data"=>'设置失败');
        }
        if($data["kind"]==2){
            $materialInfo->isOpen='0';
            $materialInfo->save();
            if($materialInfo->save())
                return array("msg"=>'200',"data"=>'设置成功');
            else
               return array("msg"=>'401',"data"=>'设置失败');
        }

        if(empty($materialInfo)){
            return array("msg"=>'400',"data"=>'该文件不存在');
        }
        $materialPath=str_replace('\\','/',dirname(Yii::$app->BasePath)).$materialInfo['content'];
        if(file_exists($materialPath)){
            if(!unlink($materialPath)){
                return array("msg"=>'401',"data"=>'删除失败');
            }
            $materialInfo->delete();
            return array("msg"=>'200',"data"=>'删除成功');
        }
        $materialInfo->delete();
        return array("msg"=>'200',"data"=>'删除成功');
    }

    /**
     * @introduce 修改上传的文件
     * @author ZeroC
     * @date      2018-11-06
     * @data=array('materialId','username','filename')
     * @_FILE[]
     * @return    [type]     [description]
     */
    public function actionModifymaterial(){
        $data=Yii::$app->request->post();
        $materialInfo=ModuleScene::findOne(['id'=>$data['materialId'],'kind'=>4,'authorId'=>$data['username']]);
        if(empty($materialInfo)){
            return array("msg"=>'400',"data"=>'该文件不存在');
        }
        $materialPath=str_replace('\\','/',dirname(Yii::$app->BasePath)).$materialInfo['content'];
        if(file_exists($materialPath)){
            unlink($materialPath);
        }
        $trans=Yii::$app->db->beginTransaction();
        try{
            $menu=str_replace('\\','/',dirname(Yii::$app->BasePath));
            $upload_path =$menu.'/'.@backend."/Uploads/material/";
            $web_path ="/backend/Uploads/material/";

            if(!is_dir($upload_path.date("Y-m-d"))){
                mkdir($upload_path.date("Y-m-d"));
            }
            
            $filename=$data['filename'];
            $fileArr = explode('.',$filename);
            $tempName=date("Y-m-d").'/'.date("YmdHis").rand(1,100).".".$fileArr[1];
            $upload_path=$upload_path.$tempName;

            $materialInfo->content=$web_path.$tempName;
            if(!$materialInfo->save()){
                throw new \Exception("401-修改失败");
            }
            /*文件接收处理*/
            if(!move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path)){
                $trans->rollBack();
                throw new \Exception("402-修改失败");
            }
            $trans->commit();
            throw new \Exception("200-修改成功");
        }catch(\Exception $e){
            $exception=explode('-',$e->getMessage());
            if(isset($exception[1])){
                return array("msg"=>$exception[0],"data"=>$exception[1]);
            }
            return array("msg"=>'410',"data"=>$exception[0]);
        }
    }

    /**
     * @introduce 获取会议文件
     * @author ZeroC
     * @date      2018-11-06
     * @data=array('meetId','arrangeId') 当是查看自己的文件时，需传入username
     * @return    [type]     [description]
     */
    public function actionGetmaterial(){
        $data=Yii::$app->request->post();
        $condition=[];

        if(count($data)==0)
            return array("msg"=>0,"data"=>[]);

        if(isset($data['meetId']))
            $condition['meetId']=$data['meetId'];

        if(isset($data['arrangeId']))
            $condition['reportId']=$data['arrangeId'];

        if(isset($data['username'])){
            $condition['authorId']=$data['username'];
        }
        $query=VwModuleSceneMaterial::find()->where($condition);
        $countQuery = clone $query;
        $page = isset($data['meetId'])?$data['meetId']:1;
        $count = $countQuery->count();
        $total = ceil($count/10);
        $models = $query->offset(($page-1)*10)
            ->limit(10)
            ->all();

        return array("msg"=>count($models),"data"=>$models);
    }

    public function actionGetMeetList(){
        $meetlist=MeetList::find()->where(["delFlag"=>1])->all();
        return array("msg"=>"success","data"=>$meetlist);
    }

    public function actionGetReportList(){
        $data=Yii::$app->request->post();
        if(!isset($data["meetId"]))
            return array("msg"=>"success","data"=>[]);

        $meetId=$data["meetId"];
        $reportlist=MeetArrangeList::find()->where(["meetId"=>$meetId,"status"=>1])->all();
        return array("msg"=>"success","data"=>$reportlist);
    }
}
