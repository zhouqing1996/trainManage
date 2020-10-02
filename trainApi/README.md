<p align="center">
    <h1 align="center">会务管理系统api后台说明2018-07-25</h1>
    <br>
</p>

## 1.项目说明
基于 yii2 的会议系统管理系统，restful接口程序，按标准写接口和说明文档

## 2.安装说明
```
1、git clone将项目克隆下来
2、进入项目文件夹，执行composer install --ignore-platform-reqs  (忽略版本问题)
3、执行php init初始化项目，选择development
4、修改common\config\main-loacal.conf中的数据库连接信息

附：
composer安装
https://docs.phpcomposer.com/00-intro.html
```

## 3.项目结构

```
common                   全局
    config/              全局配置文件夹
    mail/                不造
    models/              全局模型，用于存储与表相关的model，用gii生成
    tests/               全局测试    
console                  控制台入口
    config/              控制台配置文件（不管）
    controllers/         控制台控制器（不管）
    migrations/          控制台数据库迁移相关（不管）
    models/              控制台模型（不管）
    runtime/             控制台运行文件夹（不管）
backend                  后台api文件夹
    assets/              后台静态文件夹（基本不用）
    config/              后台配置文件夹
    controllers/         后台控制器（不主要）
    module/              模块（主要，按模块划分）
        member/          成员模块(exp)
            controllers/ 模块控制器文件夹
            Module.php   模块入口       
    models/              后台模型，在表的基础上实现业务逻辑的模型类放在此处
    runtime/             后台运行环境
    tests/               后台测试    
    views/               后台试图（不管）
    web/                 后台入口
frontend                 前台
    assets/              前台静态文件夹（基本不用）
    config/              前台配置文件夹
    controllers/         前台控制器
    models/              前台模型，在表的基础上实现业务逻辑的模型类放在此处
    runtime/             前台运行环境
    tests/               前台测试    
    views/               前台试图（不管）
    web/                 前台入口
vendor/                  第三方&核心库文件夹
environments/            环境配置相关（测试环境和正式环境）
doc                      说明文档目录，api开发人员填写，前端开发人员查看
```
## 4.数据库说明
链接远程数据库，数据库命名以"模型名_表名"命名，如member_user_info（没网可以从远程copy一份在本地）
```  
远程数据库地址：139.224.54.245
账号:cm
密码:cm
数据库名：club
``` 
## 5.API编写说明

#### 1、yii2链接数据库的4中方法
``` 
1．yii::db->creatCommand("sql语句，带参数")->-bindValue("查询参数")>queryAll(queryOne)，返回字符型数组

2．ActiveRecord查询ActiveQuery，类表示为表，对象表示为一条记录，属性为对象；之前可以桥连其他的where或者其他方法
如Post::findOne()->delete()或者Post->where()->find()->all()等
   关联表查询 设置get表名的方法,hasOne(其他表，关联字段)，hasMany；gii可以自动生成，但是要在数据库建立表之间的关系
   实质是特殊的QueryBuilder

3．QueryBuilder;  $query->select()->from(）->order()->where()->join()->,参数为键值对

4．AR的querysql（sql语句），返回型号为对象

``` 
#### 2、api restful的几种书写风格
``` 
1.controller继承ActiveController（按表对应的控制器），调用ActiveController类中书写好的api接口（增删查改），使用时需要配置Url美化规则；controller要直接调用继承自ActiveRecord（如：public $modelClass = 'backend\models\Test'，模型为按表对应的模型）

----（见backend\module\member\controllers\ListController)

好处：继承之后直接实现了api的常规增删查改的方法，快

若想要修改原api方法，则需要重写actions方法，在actions方法中unset掉，然后重写想要的actionXXX方法

若想在其中自定义方法api，则要在url美化规则设置extraPatterns，如：
'extraPatterns'=>[
     'POST search' => 'search'
]

2.直接继承Controller，只需要配置Url美化规则，使得控制满足restful风格

Gii可以生成这类控制器(curl生成)，正常gii生成的控制改为api，需要修改behavior的过滤器和返回值的类型

----（见backend\module\member\controllers\IndexController）
``` 
#### 3、查询数据库的方法写哪？
``` 
查询数据库的方式上述4中，只是实现的位置不一样
1．将逻辑写在model中，该model可以是继承AR类，则在AR类中写，model类只调用，也可以为普通的model类，在普通类中写

2．写在控制器中，可以直接用查询数据库，可以调用Model类的方法
``` 
#### 4、普通model和AR类的区别

1．AR类是也是的model，只是实现了更多的数据库操作的方法，例如实现更新前后的操作，可以有Gii读取数据表自动生成(model生成)；

2．普通的model类似于java中的类，主要用于接收和处理数据，当然可以实现业务逻辑

3．所以AR类是做接近数据库操作的地方，其次是Model,最后是控制器

#### 5、传入赋值的过程
``` 
1．$this->load()，一般用于对普通model的赋值
将POST传入的表单值赋予一个model,在model中对传入的参数进行验证(传统的只止步于用POST取值)
注意：load有两个参数，自定义表单（即自己POST传入）要load成功通常得传第2个参数为空字符串
参考：http://www.kkh86.com/it/yii2-adv/guide-mistake-load-failure.html

2．$this->validate会验证rule，从而实现数据验证
``` 

#### 6、其他
``` 
1．数据辅助工具：arrayhelper，提供很多数组处理操作
2．数据提供者：DataProvider，可以用于做分页   https://www.yiibai.com/yii2/yii_data_providers.html
3．类赋值取值，getter和setter实现对私有变量的保护，或者对于类赋值时进行统一变换操作

常规restful设置参考：
https://www.yiichina.com/tutorial/1606

常规gii使用参考：
https://www.yiichina.com/doc/guide/2.0/start-gii
``` 
#### 7、模块设置
``` 
采用gii生成模块,如backend\module\member\Module
main.conf修改配置
    'modules' => [
        'advertise' => [
            'class' => 'backend\module\advertise\Module',
        ],
    ]
添加路由
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['member/index','member/list']
    ],
``` 
#### 7、授权验证
``` 
yii项目源码登入验证授权部分讲解（魏曦的视频有讲）

授权验证成功或会返回个access_token

授权验证主要写在behaviors中，设置QueryParamAuth过滤器，实现验证过滤,除了登入，其他请求都在在url上带上access_token

可以看controller/sitecontroller

(过滤器：控制器类的过滤器默认应用到该类的 所有 动作)
参考:
https://blog.csdn.net/lhorse003/article/details/62215672
https://blog.csdn.net/u012979009/article/details/52136672
https://blog.csdn.net/lhorse003/article/details/62215690
http://www.manks.top/yii2-restful-api.html

``` 
#### 8、API返回格式
包括两种方式，返回字符串和返回数组
1.return "111",用于做只返回状况的api
{
    "code": 200,
    "data": "null",
    "message": "1111"
}

2.return array("data"=>"欢迎到来","msg"=>"22313")  ,用于做返回状况和数据的api
{
    "code": 200,
    "data": "欢迎到来",
    "message": "22313"
}

<p style="color:red">参考member模块下控制器代码，和model模型代码</p>



