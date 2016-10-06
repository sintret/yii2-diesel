<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$cl = $generator->modelClass;
$explode = explode("\\", $cl);
$date = strtolower(date('Y-M-d-H:i:s'));
echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use <?= $explode[0];?>\<?= $explode[1];?>\User;
/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
$date = date("YmdHis");
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <div class="page-header">
        <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
    </div>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>    

<?= "<?php \n " ?>
   $contentsCreate = sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.create') ? Html::a('<i class="glyphicon glyphicon-plus"></i>', ['<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/create'], ['type' => 'button', 'title' => 'Add ' . $this->title, 'class' => 'btn bg-green-active', 'id' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>_btn']) . ' ' : '';
    $contentsParsing = sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.parsing') ? Html::a('<i class="fa fa-cloud-upload"></i>', ['<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn  bg-aqua-active']) . ' ' : '';
    $contentsDeleteAll = sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.delete-all') ? Html::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'button', 'title' => 'Delete Selected ' . $this->title, 'class' => 'btn btn-danger', 'id' => 'deleteSelected']) : '';

    $contentsAction = $contentsCreate . $contentsParsing . $contentsDeleteAll;
    $contentsRefresh = Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/index', 'p_reset' => true], ['data-pjax' => 0, 'class' => 'btn bg-purple', 'title' => 'Reset Grid']) . ' ';
    
    $toolbars = [
        ['content' => $contentsAction],
        ['content' => $contentsRefresh],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        '{export}',
    ];
   $templatesButton = '';
    if (sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.view')) {
        $templatesButton .= '{view} ';
    }
    if (sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.update')) {
        $templatesButton .= '{update} ';
    }
    if (sintret\diesel\controllers\Controller::accessTo('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>.delete')) {
        $templatesButton .= '{delete} ';
    }
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . ' Grid</h3>',
        'before' => '<div style="padding-top: 7px;"><em>* You can customize your own personal grid in the right toolbar button.</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        <?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        
        if($name == 'image'){
            
            echo "     ['attribute' => 'image', 'format' => 'html', 'value' => function($data) { return $data->thumb;}], \n";
         
        }  else if($name=='userCreate'){ ?>
             [
            'attribute'=>'userCreate',
            'filter'=>  User::dropdown(),
            'value'=>function ($data){
                return app\components\Util::usernameOne($data->userCreate);
            },
        ],
        <?php } else if($name=='userUpdate'){ ?>
        [
            'attribute'=>'userUpdate',
            'filter'=>  User::dropdown(),
            'value'=>function ($data){
                return app\components\Util::usernameOne($data->userCreate);
            },
        ],
        <?php }  else
            echo "            '" . $name . "',\n";
        
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        $name = $column->name;
        if($name=='image'){ ?>
            ['attribute' => 'image', 'format' => 'html', 'value' => function($data) { return $data->thumb;}],
        <?php }  else if($name=='userCreate'){ ?>
             [
            'attribute'=>'userCreate',
            'filter'=>  User::dropdown(),
            'value'=>function ($data){
                return app\components\Util::usernameOne($data->userCreate);
            },
        ],
        <?php } else if($name=='userUpdate'){ ?>
        [
            'attribute'=>'userUpdate',
            'filter'=>  User::dropdown(),
            'value'=>function ($data){
                return app\components\Util::usernameOne($data->userCreate);
            },
        ],
        <?php }  else         
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        
    }
}
?>
            
            [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'template' => $templatesButton,
        ],
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'checkboxOptions' => [
                'class' => 'simple'
            ],
            //'pageSummary' => true,
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
        ],
    ];
    
    $dynagrid = DynaGrid::begin([
                'id' => 'user-grid',
                'columns' => $columns,
                'theme' => 'panel-default',
                'showPersonalize' => true,
                'storage' => 'db',
                //'maxPageSize' =>500,
                'allowSortSetting' => true,
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'showPageSummary' => true,
                    'pjax' => true,
                    'panel' => $panels,
                    'toolbar' => $toolbars,
                    'export' => [
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                    //'target' => GridView::TARGET_SELF
                    ],
                    'exportConfig' => [
                        'html' => [
                            'filename' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-'.$date,
                        ],
                        'csv' => [
                            'filename' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-'.$date,
                        ],
                        'xls' => [
                            'filename' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-'.$date,
                        ],
                        'txt' => [
                            'filename' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-'.$date,
                        ],
                        'json' => [
                            'filename' => '<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-'.$date,
                        ]
                    ],
                ],
                'options' => ['id' => '<?= StringHelper::basename($generator->modelClass) ?>'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
<?= "?> " ?>
</div>
<?php echo "<?php \n";?>
$this->registerJs('$("#deleteSelected").on("click",function(){
var array = "";
$(".simple").each(function(index){
    if($(this).prop("checked")){
        array += $(this).val()+",";
    }
})
if(array==""){
    alert("No data selected?");
} else {
    if(window.confirm("Are You Sure to delete selected data?")){
        $.ajax({
            type:"POST",
            url:"'.Yii::$app->urlManager->createUrl(['<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>/delete-all']).'",
            data :{pk:array},
            success:function(){
                location.href="";
            }
        });
    }
}
});');?>
