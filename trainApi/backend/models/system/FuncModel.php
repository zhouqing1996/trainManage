<?php
namespace backend\models\system;

use Yii;
use yii\base\Model;
use common\models\SysFunction;
/**
 * Login form
 */
class FuncModel extends Model{
	/**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function getFuncList()
    {
		// $users= User::model()->findAll( $criteria );
		// 返回字段可以用select规定，model(AR)下的rule方法用于反正model输入的，场景可以规定哪些方法验证哪些字段
		// AR下的fields可以用于规定哪些数据是否要返回,同时支持在从数据库查询之后进行二次操作，然后返回结果到Model中
		// 即：AR的fiels先控制输出，然后select在上基础后进行输出，若select返回的字段在fields中没有，则不显示,若select不完全输出fields，则未select的为Null
		// 
    	return SysFunction::find()->select(['funcId','name','url','type','icon'])->orderBy([
		    'funcId' => SORT_ASC,
		])->all();
    }

}