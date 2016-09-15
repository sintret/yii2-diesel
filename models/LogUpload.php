<?php

namespace sintret\diesel\models;

use Yii;

/**
 * This is the model class for table "log_upload".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property string $filename
 * @property string $fileori
 * @property string $params
 * @property string $values
 * @property string $warning
 * @property string $keys
 * @property integer $type
 * @property integer $userCreate
 * @property integer $userUpdate
 * @property string $updateDate
 * @property string $createDate
 */
class LogUpload extends \yii\db\ActiveRecord {

    public $type_parsing;

    const ADD_DATA = 1;
    const EDIT_DATA = 2;

    public static $typies_parsing = [1 => 'Add Data', 'Edit Data'];
    public static $typies_label = [1 => 'add_data', 'edit_data'];

    public static function typies_label_xls($type = NULL) {
        if (empty($type))
            $type = 1;

        return self::$typies_label[$type] . '.xlsx';
    }

    public static function typies_label_sample($type = NULL) {
        if (empty($type))
            $type = 1;

        return self::$typies_label[$type] . '_sample.xls';
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'log_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['userId', 'type', 'userCreate', 'userUpdate'], 'integer'],
            [['updateDate', 'createDate', 'params', 'values', 'warning', 'keys', 'title', 'filename', 'fileori'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'title' => 'Title',
            'filename' => 'Filename',
            'fileori' => 'Fileori',
            'params' => 'Params',
            'values' => 'Values',
            'warning' => 'Warning',
            'keys' => 'Keys',
            'type' => 'Type',
            'userCreate' => 'User Create',
            'userUpdate' => 'User Update',
            'updateDate' => 'Update Date',
            'createDate' => 'Create Date',
        ];
    }

}
