<?php

namespace sintret\diesel\models;

use Yii;

/**
 * This is the model class for table "tbl_dynagrid".
 *
 * @property string $id
 * @property string $filter_id
 * @property string $sort_id
 * @property string $data
 */
class TblDynagrid extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dynagrid';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'data'], 'required'],
            [['data'], 'string'],
            [['id', 'filter_id', 'sort_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'filter_id' => 'Filter ID',
            'sort_id' => 'Sort ID',
            'data' => 'Data',
        ];
    }

    public static function pageMe($table = NULL) {

        $page = 0;
        $userId = Yii::$app->user->id;

        if ($table) {
            $id = $table . $userId . '_' . $userId;
        }

        $model = static::find()->where(['id' => $id])->one();
        if ($model->id) {
            $data = $model->data;
            $encode = json_decode($data, true);
            $page = $encode['page'];
        }

        return $page;
    }

    public static function buildGrid($userId) {

        $affix = $userId . '_' . $userId;

        $return['Location' . $affix] = '{"page":"50","theme":"panel-primary","keys":["7b6b4a62","25412442","3d65576e","fc0227ba","f237cb47"],"filter":"","sort":""}';
        $return['Klas' . $affix] = '{"page":"50","theme":"panel-primary","keys":["7b6b4a62","25412442","78f67f03"],"filter":"","sort":""}';
        //$return['Asset' . $affix] = '{"page":"50","theme":"panel-primary","keys":["7b6b4a62","25412442","fc0227ba","acf6ecd6","f237cb47","4a16cdfb"],"filter":"","sort":""}';
        $return['Asset' . $affix] = '{"page":"50","theme":"panel-primary","keys":["7b6b4a62","25412442","d33fb1f4","fc0227ba","acf6ecd6","4a16cdfb"],"filter":"","sort":""}';
        $return['SparePart' . $affix] = '{"page":"50","theme":"panel-danger","keys":["7b6b4a62","25412442","acf6ecd6","fc0227ba","9a4a2ba4","99bd34fe","999f019a","fb233ea5","245192a2","4a16cdfb"],"filter":"","sort":""}';
        $return['SubClass' . $affix] = '{"page":"50","theme":"panel-primary","keys":["7b6b4a62","25412442","42bfd9ea"],"filter":"","sort":""}';
        $return['Unit' . $affix] = '{"page":"50","theme":"panel-default","keys":["7b6b4a62","25412442","fc0227ba"],"filter":"","sort":""}';
        //$return['WorkRequest' . $affix] = '{"page":"50","theme":"panel-success","keys":["7b6b4a62","03239679","e170572e","ec489d1d","6f4cf3ff","4a16cdfb","c1fac919","d17677bd","8bc445cc"],"filter":"","sort":""}';
        $return['WorkRequest' . $affix] = '{"page":"50","theme":"panel-success","keys":["7b6b4a62","fc0227ba","03239679","e170572e","6f4cf3ff","4a16cdfb","c1fac919","d17677bd","8bc445cc"],"filter":"","sort":""}';
        
        return $return;
    }

    public static function buildMe($userId) {
        $array = self::buildGrid($userId);

        foreach ($array as $k => $v) {

            $model = static::findOne($k);
            if ($model->id) {
                $model->data = $v;
                $model->save();
            } else {
                $model = new TblDynagrid();
                $model->id = $k;
                $model->data = $v;
                $model->save();
            }
        }
    }

    public static function builds() {
        $users = User::find()->all();
        if ($users)
            foreach ($users as $user) {
                self::buildMe($user->id);
            }
    }

}
