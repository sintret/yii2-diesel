<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
    
    <?php 
    $isImage = false;
    $viewRule = "";
    foreach ($labels as $name => $label) {
        if($name=='image'){
            $isImage = true;
            $viewRule .= ", \n";
            $viewRule .= "\n            [['image'], 'file', 'extensions' => 'jpg,png,gif'],";
        }
    }
    ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . $viewRule."\n        "?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
        
    <?php if ($isImage) { ?>
    
    /**
     * @inheritdoc
     * This case using image upload and store with easyly.
     */
     
    public static $imagePath = '@webroot/images/<?= strtolower($className) ?>/';
    
    public function getImageTrue()
    {
        if($this->image){
            return Yii::getAlias($this->image);
        }
    }
    
    public function getThumbnailTrue()
    {
        if ($this->image) {
            $name = \yii\helpers\StringHelper::basename($this->image);
            $dir = \yii\helpers\StringHelper::dirname($this->image);
            return Yii::getAlias($dir . '/thumb/' . $name);
        }
    }
    
    public function getThumb()
    {
        if ($this->thumbnailTrue) {
            return \yii\helpers\Html::img($this->thumbnailTrue, ['width' => '100px']);
        }
    }
    
    public function behaviors()
    {
        return [
            'image' => [
            'class' => \sintret\diesel\components\CropBehavior::className(),
            'paths' => self::$imagePath . '{id}/',
            'width'=>200,
            ]
        ];
    }
    <?php } ?>
}
