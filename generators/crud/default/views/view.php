<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view no-print">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Create') ?>, ['create'], ['class' => 'btn btn-default']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-default']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Print Qrcode') ?>, ['id' => 'print'], ['id' => 'print', 'class' => 'btn btn-default']) ?>

    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php

if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        
        if($name=='image'){
            echo "        [
            'attribute' => 'image',
            'format' => 'image',
            'value' => $model->thumbnailTrue, \n"
                    . "]\n";
           
        } elseif($name=='userCreate'){
            echo "      [
            'attribute' => 'userCreate',
            'format' => 'html',
            'filter' => User::dropdown(),
            'value' => function($data) {
                return $data->userCreateLabel;
            },
        ], \n";
        } elseif($name=='userUpdate'){
            echo "         [
            'attribute' => 'userUpdate',
            'format' => 'html',
            'filter' => User::dropdown(),
            'value' => function($data) {
                return $data->userUpdateLabel;
            },
        ],  \n";
            
        }elseif($name=='createDate'){
            echo "      [
            'attribute' => 'createDate',
            'filterType' => GridView::FILTER_DATE,
            'format' => 'raw',
            'width' => '170px',
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'yyyy-mm-dd']
            ],
        ],    \n";
            
        }elseif($name=='updateDate'){
            echo "   [
            'attribute' => 'updateDate',
            'filterType' => GridView::FILTER_DATE,
            'format' => 'raw',
            'width' => '170px',
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'yyyy-mm-dd']
            ],
        ],";
        }
        
        
        else 
            echo "            '" . $name . "',\n";
        
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        $name = $column->name;
        if($name=='image'){ ?>
            ['attribute' => 'image','format' => 'image','value' => $model->thumbnailTrue],
        <?php } elseif($name=='userCreate'){ ?>
             [
                'attribute' => 'userCreate',
                'value' => Yii::$app->util->getUserId($model->userCreate)->username,
            ],
        <?php } elseif($name=='userUpdate'){ ?>
            [
                'attribute' => 'userUpdate',
                'value' => Yii::$app->util->getUserId($model->userUpdate)->username,
            ],
            
        <?php }elseif($name=='createDate'){ ?>
            [
                'attribute' => 'createDate',
                'value' => $model->createDate,
            ],
            
        <?php }elseif($name=='description'){ ?>
            'description:html',
        <?php }elseif($name=='updateDate'){ ?>
            [
                'attribute' => 'updateDate',
                'value' => $model->updateDate,
            ],
                
        <?php } else 
        
            //echo "            '" . $format . "',\n";
        
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        
    }
}
?>
        ]]) ;?>

</div>

<img id="imgqr" style="display: none" alt="Embedded Image" src="http://sintret.com/site/qrcode?text=<?php echo "<?php ";?> echo $model->id; ?>&size=300&font-size=16&label=<?php echo "<?php ";?> echo $this->title   ; ?>" />
<?php  echo "<?php ";?> $this->registerJs('$("#print").on("click", function(event ){
    $("#imgqr").show();
    event.preventDefault();
window.print();
});'); ?>