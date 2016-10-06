<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;

// sample path 
$name = '<?= $model->tableName() ?>';
$sampleUrl = $name . '/sample';

$this->title='Parsing / Upload  <?= $model->tableName() ?> excel';
$this->params['breadcrumbs'][] = ['label' => '<?= $model->tableName() ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/parsing.js', ['depends' => [\app\assets\AppAsset::className()]]);

/* @var $this yii\web\View */
/* @var $model app\models\Operator */
/* @var $form yii\widgets\ActiveForm */
<?php echo "?>\n";?>
<div class="sintret-update">

    <div class="page-header">
        <h1>Parsing Excel <?= $model->tableName() ?></h1>
    </div>


    <div class="<?= $model->tableName() ?>-form">


        <div class="<?= $model->tableName() ?>-form">
            <?php echo "<?php\n";?>
            $form = ActiveForm::begin([
                        'type' => ActiveForm::TYPE_HORIZONTAL,
                        'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
            ]);
            <?php echo "?>\n";?>

            <div class="row">
                <div class="col-md-10">
                    <?php echo "<?php";?> 
                        echo $form->field($model, 'type')->dropDownList(\app\models\LogUpload::$typies_parsing, ['data-name' => $name]); 
                    <?php echo "?>\n";?>

                    <?php echo "<?php\n";?>
                        echo $form->field($model, 'fileori')->widget(FileInput::classname(), [
                            'options' => ['accept' => '.xlsx'],
                        ]);
                    <?php echo "?>\n";?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php echo "<?=";?>Html::submitButton('Upload ', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);<?php echo "?>\n";?>
                </div>
            </div>
            <div class="notifications" style="display: none">Please wait, while loading.... <img src="<?php echo "<?=";?> Url::to(['img/loadingAnimation.gif']);<?php echo "?>";?>"></div>
            
            <?php echo "<?php\n";?>
            ActiveForm::end();
            <?php echo "?>\n";?>

        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-10">
            Format Sample : <a id="sample-parsing" data-add="<?php echo "<?php";?> echo Yii::$app->urlManager->createUrl([$sampleUrl]); ?>" data-edit="<?php echo "<?php";?> echo Yii::$app->urlManager->createUrl([$sampleUrl, 'id' => 2]); ?>" href="<?php echo "<?php";?> echo Yii::$app->urlManager->createUrl($sampleUrl); ?>"><?php echo "<?php";?> echo $name.'_'.\sintret\diesel\models\LogUpload::typies_label_sample(1); ?></a>
        </div>
    </div>
    
</div>

<div class="modal fade" id="modalParsing" tabindex="-1" data-url="<?php echo "<?php";?> echo $jsonName; ?>" data-ajax="<?php echo "<?php";?> echo Url::to([$name . '/parsing-log', 'type' => $type]); ?>" role="dialog" aria-labelledby="modalParsingLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title"><?php echo "<?php";?> echo strtoupper($name);?></h4>
                <h5 class="modal-type">Please wait, while loading.... <img src="<?php echo "<?php";?> echo Url::to(['img/loadingAnimation.gif']); ?>"></h5>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php echo "<?php\n";?>
if ($jsonName) {
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/parsing-process.js', ['depends' => [\app\assets\AppAsset::className()]]);
}