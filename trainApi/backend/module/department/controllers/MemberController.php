<?php
namespace backend\module\department\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\VwTeacherInfo;
use common\models\TeacherInfo;
use common\models\MajorInfo;
use common\models\ClassInfo;

use common\models\VwMajorInfo;
use common\models\StudentInfo;
use common\models\Users;

class MemberController extends Controller
{
  public function actionIndex()
  {
    return "师生管理首页";
  }

  public function actionGetteacherdata()
  {
   $request = \Yii::$app->request;
   $query = VwTeacherInfo::find()
   ->select('*')
   ->andWhere(['status' => 1])
   ->orderBy('id DESC')
   ->all();

   $query1 = VwTeacherInfo::find()
   ->select('*')
   ->orderBy('id DESC')
   ->andWhere(['status' => 1]);

   $countQuery = clone $query1;
   $totalCount = $countQuery->count();
   $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
   $models = $query1->offset($pages->offset)
      ->limit($pages->limit)
      ->all();   
   $pageNum = ceil($totalCount/8);
   return array("data"=>[$models,$pageNum],"msg"=>"success");
 }

 public function actionGetstudentdata()
 {
   $request = \Yii::$app->request;
   $query = (new Query())
    ->select('*')
    ->from('vw_student_info')
    ->andWhere(['status' => 1])
    ->orderBy('id DESC')
    ->all();

   $query1 = (new Query())
    ->select('*')
    ->from('vw_student_info')
    ->orderBy('id DESC')
    ->andWhere(['status' => 1]);

   $countQuery = clone $query1;
   $totalCount = $countQuery->count();
   $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
   $models = $query1->offset($pages->offset)
   ->limit($pages->limit)
   ->all();   
   $pageNum = ceil($totalCount/8);
   return array("data"=>[$models,$pageNum],"msg"=>"success");
 }

 public function actionGetamemberdata()
 {

   $request = \Yii::$app->request;
   $majorId = $request->post('majorId');
   $query = (new Query())
   ->select('*')
   ->from('vw_teacher_info')
   ->andWhere(['status' => 1])
   ->andWhere(['majorId' => $majorId])
   ->all();

   $query1 = (new Query())
   ->select('*')
   ->from('vw_teacher_info')
   ->andWhere(['status' => 1])
   ->andWhere(['majorId' => $majorId]);

   $countQuery = clone $query1;
   $totalCount = $countQuery->count();
   $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
   $models = $query1->offset($pages->offset)
   ->limit($pages->limit)
   ->all();   
   $pageNum = ceil($totalCount/8);
   return array("data"=>[$models,$pageNum],"msg"=>"success");
 }

 public function actionGetastudent()
 {

   $request = \Yii::$app->request;
   $majorId = $request->post('majorId');
   $query = (new Query())
   ->select('*')
   ->from('vw_student_info')
   ->andWhere(['status' => 1])
   ->andWhere(['majorId' => $majorId])
   ->all();

   $query1 = (new Query())
   ->select('*')
   ->from('vw_student_info')
   ->andWhere(['status' => 1])
   ->andWhere(['majorId' => $majorId]);

   $countQuery = clone $query1;
   $totalCount = $countQuery->count();
   $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
   $models = $query1->offset($pages->offset)
   ->limit($pages->limit)
   ->all();   
   $pageNum = ceil($totalCount/8);
   return array("data"=>[$models,$pageNum],"msg"=>"success");
 }


 public function actionGetmajordata()
 {

   $request = \Yii::$app->request;
   $query = (new Query())
   ->select('*')
   ->from('major_info')
   ->andWhere(['status' => 1])
   ->all();
   return array("data"=>[$query],"msg"=>"success");
 }

 public function actionGetclassdata()
 {
   $request = \Yii::$app->request;
   $query = (new Query())
    ->select('*')
    ->from('vw_class_info')
    ->andWhere(['status' => 1])
    ->all();
   return array("data"=>$query,"msg"=>"success");
 }

    //插入数据，不存在相同数据则插入
  public function actionInsertteacher()
  {
    $request = \Yii::$app->request;
    $name = $request->post('name');
    $job_num = $request->post('job_num');
    $identify = $request->post('identify');
    $sex = $request->post('sex');
    $phone = $request->post('phone');
    $email = $request->post('email');
    $post = $request->post('post');
    $rank = $request->post('rank');
    $major = $request->post('major');
    
    $result = TeacherInfo::find()
      ->andWhere(['job_num'=>$job_num])
      ->andWhere(['status'=>1])
      ->one();
    
    if ($result == null) {
      $result1 = \Yii::$app->db->createCommand()->insert('teacher_info',
        array(
          'teacherName'=>$name, 
          'identity'=>$identify,
          'job_num'=>$job_num,
          'sex'=>$sex,
          'rank'=>$rank,
          'post'=>$post,
          'contactPhone'=>$phone,
          'email'=>$email,
          'status'=>1,
          'majorId'=>$major
          )
        )->execute();

      if ($result1 == 1){ 
        $password_hash=Yii::$app->security->generatePasswordHash(123456);
        $user = \Yii::$app->db->createCommand()->insert('users',
          array(
            'username'=>$job_num,   
            'password_hash'=>$password_hash,
            'status'=>1
            )
          )->execute();
        if($user == 1){
          $power = \Yii::$app->db->createCommand()->insert('sys_power',
            array(
                'username'=>$job_num,  
                'super'=>2,
                'userkind'=>1, 
                'major'=>1,
                'practice'=>1,
                'requirement'=>1,
                'tracking'=>1,
                'apprenticeship'=>1,
                'majorId'=>$major,
                'status'=>1)
            )->execute();
          if ($power == 1) {
              return array("data"=>$power,"msg"=>"添加成功");
          }else{
              return false;
          }
        }else{
          return "添加权限失败";
        }
      }else{ 
        return "教师添加失败";
      }
    }else{ 
      return array("data"=>$result,"msg"=>"数据不为空");
    }
}

public function actionInsertstudent()
{
  $request = \Yii::$app->request;
  $studentName = $request->post('studentName');
  $studentSno = $request->post('studentSno');
  $studentIdentify = $request->post('studentIdentify');
  $studentSex = $request->post('studentSex');
  $studentBorndate = $request->post('studentBorndate');
  $studentClass = $request->post('studentClass');
  $studentPhone = $request->post('studentPhone');
  $studentEmail = $request->post('studentEmail');

  $password_hash=Yii::$app->security->generatePasswordHash(123456);

  $result = StudentInfo::find()->andWhere(['sno'=>$studentSno])->andWhere(['status'=>1])->one();
  if ($result == null) {
    $temp=\Yii::$app->db->createCommand()->insert('student_info',
      array(
        'stuName'=>$studentName, 
        'sno'=>$studentSno,
        'identity'=>$studentIdentify,
        'sex'=>$studentSex,
        'bornDate'=>$studentBorndate,
        'classId'=>$studentClass,
        'contactPhone'=>$studentPhone,
        'email'=>$studentEmail,
        'status'=>1,
        )
      )->execute();

    if($temp==1){  
      $user=\Yii::$app->db->createCommand()->insert('users',
        array(
          'username'=>$studentSno,   
          'password_hash'=>$password_hash,
          'kind'=>3,
          'status'=>1
          )
        )->execute();
      if($user==1){ 
        return array("data"=>$temp,"msg"=>"添加成功");}
      else{ 
        return "添加失败";
      }
    }
    else{ 
      return "学生添加失败";
    }
 }
 else
  { return array("data"=>$_data,"msg"=>"数据不为空");}

}

  //删除数据，根据专业Id来删除数据
  public function actionDeleteteacher()
  {
    $request = \Yii::$app->request;
    $id = $request->post('id');
    $job_num = $request->post('job_num');
    
    $result1 = \Yii::$app->db->createCommand()
      ->update('teacher_info',['status'=>0],'id=:id',
      [':id' => $id])->execute();
    
    $result2 = \Yii::$app->db->createCommand()
      ->update('users',['status'=>0],'username=:job_num',
      [':job_num' => $job_num])->execute();

    if($result1 & $result2){
      return $result2;
    }else{
      return "删除失败";
    }
  }

  public function actionDeletestudent()
  {
    $request = \Yii::$app->request;
    $studentId = $request->post('studentId');
    $sno = $request->post('sno');
    $result1 = \Yii::$app->db->createCommand()
      ->update('student_info',['status'=>0],'id=:studentId',[':studentId' => $studentId])->execute();
    $result2 = \Yii::$app->db->createCommand()->update('users',['status'=>0],'username=:sno',[':sno' => $sno])->execute();

    if($result1 & $result2){
      return $result2;
    }else{
      return "删除失败";
    }
  }

//获取编辑数据
public function actionGeteditstudent()
{
  $request = \Yii::$app->request;
  $studentId = $request->post('studentId');
  $result = StudentInfo::find()->where(['id' => $studentId])->one();
  if ($result==null) {
    return "failure";
  } else {
//$teacherInfo = TeacherInfo::find()->where(['majorId' => $majorId])->all();
    return array("data"=>[$result],"msg"=>"success");
  }
}
  public function actionGeteditteacher()
  {
    $request = \Yii::$app->request;
    $id = $request->post('id');
    $result = TeacherInfo::find()->where(['id' => $id])->one();
    if ($result==null) {
      return "failure";
    } else {
      return array("data"=>$result,"msg"=>"success");
    }
  }

  //修改数据
  public function actionAlterstudent()
  {
    $request = \Yii::$app->request;
    $studentId = $request->post('studentId');
    $studentName = $request->post('studentName');
    $studentIdentify = $request->post('studentIdentify');
    $studentSex = $request->post('studentSex');
    $studentBorndate = $request->post('studentBorndate');
    $studentClass = $request->post('studentClass');
    $studentPhone = $request->post('studentPhone');
    $studentEmail = $request->post('studentEmail');

    $result1 = \Yii::$app->db->createCommand()->update('student_info',
      [
        'stuName'=>$studentName, 
        'identity'=>$studentIdentify,
        'sex'=>$studentSex,
        'bornDate'=>$studentBorndate,
        'classId'=>$studentClass,
        'contactPhone'=>$studentPhone,
        'email'=>$studentEmail],'id=:studentId',[':studentId' => $studentId])->execute();

    if($result1 == 1){
      return array("data"=>$result1,"msg"=>"success");
    }else{ 
      return array("data"=>$result1,"msg"=>"failure");
    }
  }

  public function actionAlterteacher()
  {
    $request = \Yii::$app->request;
    $teacherId = $request->post('teacherId');
    $teacherName = $request->post('teacherName');
    $teacherIdentify = $request->post('teacherIdentify');
    $teacherSex = $request->post('teacherSex');
    $teacherPhone = $request->post('teacherPhone');
    $teacherEmail = $request->post('teacherEmail');
    $teacherPost = $request->post('teacherPost');
    $teacherRank = $request->post('teacherRank');
    $teacherMajor = $request->post('teacherMajor');

    $result1 = \Yii::$app->db->createCommand()->update('teacher_info',
      [ 
        'teacherName'=>$teacherName, 
        'identity'=>$teacherIdentify,
        // 'job_num'=>$teacherUser,
        'sex'=>$teacherSex,
        'rank'=>$teacherRank,
        'post'=>$teacherPost,
        'contactPhone'=>$teacherPhone,
        'email'=>$teacherEmail,
        'majorId'=>$teacherMajor,],'id=:teacherId',[':teacherId' => $teacherId])->execute();
    
    if($result1 == 1){
      return array("data"=>$result1,"msg"=>"success");
    }else{ 
      return array("data"=>$result1,"msg"=>"failure");
    }
  }


  public function actionQueryteacher()
  {
    $request = \Yii::$app->request;
    $teacherName = $request->get('teacherName');
    $query = (new Query())
      ->select('*')
      ->andWhere([$teacherName=='null'?'not like':'like','teacherName',$teacherName=='null'?'null':$teacherName])
      ->from('vw_teacher_info')
      ->andWhere(['status' => 1])
      ->orderBy('id DESC')
      ->all();

    //$query用来分页
    $query1 = (new Query())
      ->select('*')
      ->andWhere([$teacherName=='null'?'not like':'like','teacherName',$teacherName=='null'?'null':$teacherName])
      ->from('vw_teacher_info')
      ->orderBy('id DESC')
      ->andWhere(['status' => 1]);
    $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
    return array("data"=>[$models,$pageNum],"msg"=>"success");
  }

  public function actionQuerystudent()
  {
    $request = \Yii::$app->request;
    $studentName = $request->get('studentName');
    $query = (new Query())
      ->select('*')
      ->from('vw_student_info')
      ->andWhere([$studentName=='null'?'not like':'like','stuName',$studentName=='null'?'null':$studentName])
      ->andWhere(['status' => 1])
      ->orderBy('id DESC')
      ->all();

    $query1 = (new Query())
      ->select('*')
      ->from('vw_student_info')
      ->andWhere([$studentName=='null'?'not like':'like','stuName',$studentName=='null'?'null':$studentName])
      ->andWhere(['status' => 1])
      ->orderBy('id DESC');
    $countQuery = clone $query1;
      $totalCount = $countQuery->count();
      $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
      $models = $query1->offset($pages->offset)
          ->limit($pages->limit)
          ->all();   
      $pageNum = ceil($totalCount/8);
    return array("data"=>[$models,$pageNum],"msg"=>"success");
  }

  public function actionImportexcel(){
      $request = \Yii::$app->request->post("data");
      $request=json_decode($request,true);
      for($i=0;$i<count($request);$i++){
          $job_num=isset($request[$i]['job_num'])?$request[$i]['job_num']:"";
          $major=isset($request[$i]['major'])?$request[$i]['major']:"";
          $majorInfo=MajorInfo::find()->andWhere(['major'=>$major])->one();
          $majorId=$majorInfo['id'];
          $teacherName=isset($request[$i]['teacherName'])?$request[$i]['teacherName']:"";
          $identity=isset($request[$i]['identity'])?$request[$i]['identity']:"";
          $sex=isset($request[$i]['sex'])?$request[$i]['sex']:"";
          if ($sex == '男') {
            $sex = 1;
          }else{
            $sex = 2;
          }
          $rank=isset($request[$i]['rank'])?$request[$i]['rank']:"";
          $post=isset($request[$i]['post'])?$request[$i]['post']:"";
          $contactPhone=isset($request[$i]['contactPhone'])?$request[$i]['contactPhone']:"";
          $email=isset($request[$i]['email'])?$request[$i]['email']:"";
          $_user=TeacherInfo::find()
            ->andWhere(['job_num'=>$job_num])
            ->andWhere(['status'=>1])
            ->one();
          if ( $_user == null) {
              $teacher_insert=\Yii::$app->db->createCommand()->insert('teacher_info',
                       [
                           'job_num'=>$job_num,
                           'majorId'=>$majorId,
                           'teacherName'=>$teacherName,
                           'identity'=>$identity,
                           'sex'=>$sex,
                           'rank'=>$rank,
                           'post'=>$post,
                           'contactPhone'=>$contactPhone,
                           'email'=>$email,
                           'status'=>1
                       ])->execute();
              if ($teacher_insert==1) {
                $password_hash=\Yii::$app->security->generatePasswordHash(123456);
                $users_insert=\Yii::$app->db->createCommand()->insert('users',
                       [
                           'username'=>$job_num,
                           'password_hash'=>$password_hash,
                           'status'=>1
                       ])->execute();
                if ($users_insert==1) {
                  $power = \Yii::$app->db->createCommand()->insert('sys_power',
                    array(
                        'username'=>$job_num,  
                        'super'=>2,
                        'userkind'=>1, 
                        'major'=>1,
                        'practice'=>1,
                        'requirement'=>1,
                        'tracking'=>1,
                        'apprenticeship'=>1,
                        'majorId'=>$major,
                        'status'=>1)
                    )->execute();
                }else{
                  return false2;
                }
              }else{
                return false1;
              }
          } else {
              $teacher_update=\Yii::$app->db->createCommand()->update('teacher_info',
                [
                  'majorId'=>$majorId,
                  'teacherName'=>$teacherName,
                  'identity'=>$identity,
                  'sex'=>$sex,
                  'rank'=>$rank,
                  'post'=>$post,
                  'contactPhone'=>$contactPhone,
                  'email'=>$email,
                  'status'=>1],'job_num=:job_num',[':job_num' => $job_num])->execute();
              $password_hash=\Yii::$app->security->generatePasswordHash(123456);
              $users_update=\Yii::$app->db->createCommand()->update('users',
                [
                   'username'=>$job_num,
                   'password_hash'=>$password_hash,
                   'status'=>1],'username=:username',[':username' => $job_num
                ])->execute();
              $power = \Yii::$app->db->createCommand()->update('sys_power',
                [
                  'super'=>2,
                  'userkind'=>1, 
                  'major'=>1,
                  'company'=>0,
                  'practice'=>1,
                  'requirement'=>1,
                  'tracking'=>1,
                  'apprenticeship'=>1,
                  'majorId'=>$major,
                  'status'=>1],'username=:username',[':username' => $job_num
                ])->execute();
          } 
      }
      return $power;
  }
  public function actionImportexcel2(){
    $request = \Yii::$app->request->post("data");
    $request=json_decode($request,true);
    for($i=0;$i<count($request);$i++){
      $sno=isset($request[$i]['sno'])?$request[$i]['sno']:"";
      $major=isset($request[$i]['major'])?$request[$i]['major']:"";
      $grade=isset($request[$i]['grade'])?$request[$i]['grade']:"";
      $class=isset($request[$i]['class'])?$request[$i]['class']:"";
      $majorInfo=MajorInfo::find()->andWhere(['major'=>$major])->one();
      if ($majorInfo) {
        $majorId=$majorInfo['id'];
        $classInfo=ClassInfo::find()
          ->andWhere(['majorId'=>$majorId])
          ->andWhere(['className'=>$class])
          ->andWhere(['grade'=>$grade])
          ->andWhere(['status'=>1])
          ->one();
        if ($classInfo) {
          $classId=$classInfo['id'];
          $stuName=isset($request[$i]['stuName'])?$request[$i]['stuName']:"";
          $identity=isset($request[$i]['identity'])?$request[$i]['identity']:"";
          $bornDate=isset($request[$i]['bornDate'])?$request[$i]['bornDate']:"";
          $sex=isset($request[$i]['sex'])?$request[$i]['sex']:"";
          if ($sex=='男'){
            $sex = 1;
          }else{
            $sex = 2;
          }
          $contactPhone=isset($request[$i]['contactPhone'])?$request[$i]['contactPhone']:"";
          $email=isset($request[$i]['email'])?$request[$i]['email']:"";
          $_user=StudentInfo::find()
            ->andWhere(['sno'=>$sno])
            ->andWhere(['status'=>1])
            ->one();
          if ($_user==null) {
              $teacher_insert=\Yii::$app->db->createCommand()->insert('student_info',
                       [
                           'stuName'=>$stuName,
                           'sno'=>$sno,
                           'identity'=>$identity,
                           'sex'=>$sex,
                           'classId'=>$classId,
                           'bornDate'=>$bornDate,
                           'contactPhone'=>$contactPhone,
                           'email'=>$email,
                           'status'=>1
                       ])->execute();
              $password_hash=Yii::$app->security->generatePasswordHash(123456);
              $users_insert=\Yii::$app->db->createCommand()->insert('users',
                       [
                           'kind'=>3,
                           'username'=>$sno,
                           'password_hash'=>$password_hash,
                       ])->execute();
          }else{
            $teacher_update=\Yii::$app->db->createCommand()->update('student_info',
            [
              'stuName'=>$stuName,
              'identity'=>$identity,
              'sex'=>$sex,
              'classId'=>$classId,
              'bornDate'=>$bornDate,
              'contactPhone'=>$contactPhone,
              'email'=>$email,
              'status'=>1],'sno=:sno',[':sno'=>$sno])->execute();
            $password_hash=\Yii::$app->security->generatePasswordHash(123456);
            $users_update=\Yii::$app->db->createCommand()->update('users',
              [
                'kind'=>3,
                'password_hash'=>$password_hash,
                'status'=>1],'username=:username',[':username' => $sno
              ])->execute();
          }
        }
      } 
    }
    return '学生信息导入成功';
  }


}