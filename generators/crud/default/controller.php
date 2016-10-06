<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

<?php
$isImage = false;
$isUserCreate = false;
$isUserUpdate=false;
$isCreateDate = false;
$isUpdateDate = false;
foreach ($generator->getColumnNames() as $attribute) {
    if($attribute == 'image'){
        $isImage = true;
    }
    if($attribute == 'userCreate'){
        $isUserCreate = true;
    }
    if($attribute == 'userUpdate'){
        $isUserUpdate = true;
    }
    if($attribute == 'createDate'){
        $isCreateDate = true;
    }
    if($attribute == 'updateDate'){
        $isUpdateDate = true;
    }
    
}
if ($isImage) {
    $loadfile = 'loadWithFiles';
} else {
    $loadfile = 'load';
}
?>
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends \sintret\diesel\controllers\Controller <?php echo "\n";?> <?php  // echo StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{

    /**
     * we are need unique name for create json file for sample controller and parsing controller
     */
    public $baseName = "<?= $modelClass;?>";
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $grid = 'grid-' . self::className();
        $reset = Yii::$app->getRequest()->getQueryParam('p_reset');
        if ($reset) {
            \Yii::$app->session->set($grid, "");
        } else {
            $rememberUrl = Yii::$app->session->get($grid);
            $current = Url::current();
            if ($rememberUrl != $current && $rememberUrl) {
                Yii::$app->session->set($grid, "");
                $this->redirect($rememberUrl);
            }
            if (Yii::$app->getRequest()->getQueryParam('_pjax')) {
                \Yii::$app->session->set($grid, "");
                \Yii::$app->session->set($grid, Url::current());
            }
        }
        
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $date = date("Y-m-d H:i:s");
        <?php if($isCreateDate){?>$model->createDate = $date;<?php } echo "\n";?>
        <?php if($isUserCreate){?>$model->userCreate = Yii::$app->user->id;<?php } echo "\n";?>
        <?php if($isUserUpdate){?>$model->userUpdate = Yii::$app->user->id;<?php } echo "\n";?>

        if ($model-><?php echo $loadfile;?>(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to add data!  ');
            return $this->redirect(['index']);
        }
        
        return $this->render('create', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $date = date("Y-m-d H:i:s");
        <?php if($isUserUpdate){?>$model->userUpdate = Yii::$app->user->id;<?php } ?>

        if ($model-><?php echo $loadfile;?>(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to update data!  ');
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
                'model' => $model,
            ]);
        
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();
        Yii::$app->session->setFlash('success', 'Well done! successfully to delete data!  ');

        return $this->redirect(['index']);
    }
    
    public function actionDeleteAll()
    {
        $success = false;
        $explode = explode(",", Yii::$app->request->post('pk'));
        if ($explode)
        foreach ($explode as $v) {
            if ($v){
                $this->findModel($v)->delete();
                $success = true;
            }
        }
        if($success)
            Yii::$app->session->setFlash('success', 'Well done! successfully to delete selected data!  ');

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSample($id = NULL) 
    {
        $model = new <?= $modelClass ?>;
        $models = <?= $modelClass ?>::find()->all();
        $not = \sintret\diesel\components\Util::excelNot();

        if (empty($id)) {
            $not = array_merge($not, ['id']);
            $filename = \sintret\diesel\models\LogUpload::typies_label_xls(1);
        } else {
            $filename = \sintret\diesel\models\LogUpload::typies_label_xls(2);
        }

        foreach ($model->attributeLabels() as $k => $v) {
            if (!in_array($k, $not)) {
                $attributes[$k] = $v;
            }
        }

        $array = [];
        if ($models)
            foreach ($models as $mod) {
                foreach ($attributes as $k => $v) {
                    $array[$k][] = $mod->$k;
                }
            }

        $logSample = $this->baseName . '_' . Yii::$app->user->id;
        
        /*
        / we store json file at "@webroot/xls_sample"
        */
        $jsonName = Yii::getAlias("@webroot/xls_sample/") . $logSample . $filename . '.json';

        Yii::$app->session->set($logSample, $jsonName);

        $json = [
            'filename' => $this->baseName . '_' . $filename,
            'attributes' => $attributes,
            'models' => $array,
            'info' => 'insert start data in row no.4. Please do not remove data in row no.1 & 2'
        ];

        //create file json
       \sintret\diesel\components\Util::createJson($jsonName, json_encode($json));

        return $this->redirect('parsing');
    }
    
    public function actionParsing() 
    {

        /*
         * this script line for handle a sample excel file
         */
        $sampleLog = $this->baseName . '_' . Yii::$app->user->id;
        $session = Yii::$app->session->get($sampleLog);
        if (!empty($session)) {
            return $this->redirect(Url::to(['ajax/sample', 'sessionName' => $sampleLog]));
        }
        /*
         * end a sample
         */

        $type = 1;
        $num = 0;
        $keys = [];
        $fields = [];
        $values = [];
        $attribute = [];
        $jsonName = '';
        $logName = '';
        $jsonNameRelative = '';
        $model = new \sintret\diesel\models\LogUpload;

        $date = date('Ymdhis') . Yii::$app->user->identity->id;

        if (Yii::$app->request->isPost) {
            $model->fileori = \yii\web\UploadedFile::getInstance($model, 'fileori');
            $model->type = $_POST['LogUpload']['type'];

            $type = $model->type;
            $fileLabel = \sintret\diesel\models\LogUpload::$typies_parsing[$type];

            if ($model->validate()) {
                $fileOri = Yii::getAlias("@webroot/uploads/") . $model->fileori->baseName . '.' . $model->fileori->extension;
                $filename = Yii::getAlias("@webroot/uploads/") . $date . $fileLabel . '.' . $model->fileori->extension;
                $model->fileori->saveAs($filename);
            }

            $params = \sintret\diesel\components\Util::excelParsing($filename);
            $model->params = \yii\helpers\Json::encode($params);
            $model->title = 'parsing ' . $this->baseName;
            $model->fileori = $fileOri;
            $model->filename = $filename;
            <?php if($isCreateDate){?>$model->createDate = date('Y-m-d H:i:s');<?php } echo "\n";?>
            <?php if($isUserCreate){?>$model->userCreate = Yii::$app->user->id;<?php } echo "\n";?>
            <?php if($isUserUpdate){?>$model->userUpdate = Yii::$app->user->id;<?php } echo "\n";?>

            if ($params)
                foreach ($params as $k => $v) {
                    foreach ($v as $key => $val) {
                        if ($num == 0) {
                            if($val) {
                                $keys[$key] = $val;
                                $max = $key;
                            }
                        }

                        if ($num == 1) {
                            if($val) {
                                $fields[$key] = $val;
                            }
                        }

                        if ($num >= 3) {
                            $attribute[$keys[$key]] = $val;
                        }
                    }
                    if ($num >= 3)
                        $values[] = $attribute;
                    $num++;
                }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Well done! successfully to Parsing data, see log on log upload menu! Please Waiting for processing indicator if available...  ');


                $json = [
                    'name' => $this->baseName,
                    'type' => $model->type,
                    'keys' => $keys,
                    'fields' => $fields,
                    'datas' => $values,
                ];

                $logName = $this->baseName . '_' . Yii::$app->user->id;
                $jsonName = Yii::getAlias(\sintret\diesel\components\Util::$dirParsing) . $logName . $fileLabel . '.json';
                $jsonNameRelative = Yii::getAlias(\sintret\diesel\components\Util::$dirParsingRelative) . $logName . $fileLabel . '.json';
                //create json file name
                \sintret\diesel\components\Util::createJson($jsonName, json_encode($json));
                
                //save to notification, uncomment for not use this following code
                $notification = new \app\models\Notification();
                $notification->userCreate = Yii::$app->user->id;
                $notification->title = \sintret\diesel\models\LogUpload::$typies_parsing[$model->type] . ' parsing ' . $this->baseName;
                $notification->message = ' parsing ' . $this->baseName . ' ('.\sintret\diesel\models\LogUpload::$typies_parsing[$model->type].')';
                $notification->createDate = date("Y-m-d H:i:s");
                $notification->save();
            }
        }

        return $this->render('parsing', ['model' => $model, 'logName' => $logName, 'jsonName' => $jsonNameRelative, 'type' => $type]);
    }
    
    public function actionParsingLog($type)
    {
        $id = $_POST['id'];

        if ($type == \sintret\diesel\models\LogUpload::ADD_DATA) {

            if ($id) {
                $d = "<span style='color:red'>";
                $d .= "wrong excel format, please click at sample link for add! column of id must be removed";
                $d .= "</span>";

                echo $d;
                exit(0);
            }
            $model = new <?= $modelClass ?>;
            <?php if($isCreateDate){?>$model->createDate = date('Y-m-d H:i:s');<?php } echo "\n";?>
            <?php if($isUserCreate){?>$model->userCreate = Yii::$app->user->id;<?php } echo "\n";?>
            <?php if($isUserUpdate){?>$model->userUpdate = Yii::$app->user->id;<?php } echo "\n";?>
            
        } else {

            if (empty($id)) {
                $d = "<span style='color:red'>";
                $d .= "wrong excel format, please click at sample link for edit! column of id must be availabled";
                $d .= "</span>";

                echo $d;
                exit(0);
            }
            $model = <?= $modelClass ?>::findOne($id);
			if (empty($model->id)) {
                $d = "<span style='color:red'>";
                $d .= "Please check your colum 'id'. Failed to update data...";
                $d .= "</span>";

                echo $d;
                exit(0);
            }
        }

        foreach ($_POST as $k => $v) {
            $model->$k = $v;
        }

        <?php if($isUserUpdate){?>$model->userUpdate = Yii::$app->user->id;<?php } echo "\n";?>

        if ($model->save()) {
            echo "<span style='color:green'>  Success  </span>";
        } else {

            $e = "<span style='color:red'>";
            if ($model->getErrors())
                foreach ($model->getErrors() as $key => $val) {
                    if ($val)
                        foreach ($val as $a => $l) {
                            $e .= $l . "<br>";
                        }
                }
            $e .= "</span>";

            echo $e;
        }
    }
}
