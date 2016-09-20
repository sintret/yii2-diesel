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

    </p>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <?php

            $num = 3;
            $tableSchema = $generator->getTableSchema();
            $count = count($tableSchema->columns);
        foreach ($tableSchema->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            $name = $column->name;
                        
            if($name=="image"){
                $val = 'Html::img($model->thumbnailTrue)';
            } else if ($name == "userCreate"){
                $val = 'Yii::$app->util->getUserId($model->userCreate)->username';
            } else if ($name == "userUpdate"){
                $val = 'Yii::$app->util->getUserId($model->userUpdate)->username';
            } else {
                $val = '$model->'.$name;
            }
            
            ?>
            <?php if($num %2==1) echo "<tr>\n";?><th><?php echo Inflector::camel2words(StringHelper::basename($name));?></th><td><?= "<?= " ?><?php echo $val ; ?>?></td><?php echo "\n";?><?php if($num %2==0 || $num == ($count+2)) echo "</tr> \n";?>
        <?php $num++;}?>
        </tbody>
    </table>
    <p>&nbsp;</p>
</div>