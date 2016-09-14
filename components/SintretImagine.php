<?php

namespace sintret\diesel\components;

use yii\imagine\Image;

class SintretImagine extends Image {

    public static $driver = [self::DRIVER_GD2, self::DRIVER_GMAGICK, self::DRIVER_IMAGICK];

}
