<?php

namespace sintret\diesel\controllers;

use Yii;
use yii\web\Controller as WebController;
use yii\helpers\Url;

/**
 * Controller base on all access for dynamic controllers
 */
class Controller extends WebController {

    public $user;

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        $actionId = $action->id;
        $method = strtolower(Yii::$app->controller->id);
        $actionMethod = $method . '.' . $actionId;

        if (in_array($actionMethod, self::fieldsArray())) {
            if ($this->accessMenu($actionMethod))
                return true;
            else {
                if (Yii::$app->user->id) {
                    $this->redirect(Url::to(['webadmin/403']));
                    return false;
                    exit(0);
                } else {
                    $this->redirect(Url::to(['site/login']));
                }
            }
        } else {
            $this->redirect(Url::to(['site/login']));
        }
        parent::beforeAction($action);
    }

    public function accessMenu($name)
    {

        if (Yii::$app->user->id) {
            if (Yii::$app->user->id == -1)
                return true;
            else
                return self::checkAccess($name, Yii::$app->user->identity->roleId);;
        } else
            return false;
    }

    public static function checkAccess($name, $roleId)
    {

        $parts = explode(".", $name);
        return \app\models\Access::find()->where([
                    'roleId' => $roleId,
                    'LOWER(controller)' => strtolower($parts[0]),
                    'LOWER(method)' => strtolower($parts[1])])->exists();
    }

    public static function accessTo($name)
    {
        return self::checkAccess($name, Yii::$app->user->identity->roleId);
    }

    public static function checkManyAccess($array, $roleId)
    {
        $return = 0;
        if ($array)
            foreach ($array as $v) {
                if (self::checkAccess($v, $roleId)) {
                    $return +=1;
                }
            }

        return $return;
    }

    public static function fieldsArray()
    {
        $fields = \app\models\Role::accessFilter();

        $return = [];
        foreach ($fields as $keys => $values) {
            foreach ($values as $k => $v) {
                $return[] = strtolower($keys) . '.' . $v;
            }
        }

        return $return;
    }

    public function accessUser($name)
    {
        if (Yii::$app->user->id) {
            $role = \yii\helpers\Json::decode(Yii::$app->user->identity->roles->params);
        } else
            return false;
    }

}
