<?php

namespace sintret\diesel\components;

use Yii;
use yii\base\Component;
use \app\models\User;

class Util extends Component {

    public $beforeBody;
    public $afterBody;
    public $member;
    public $tab = 1;

    const PUBLISH = 1;
    const UNPUBLISH = 0;

    public static $dirSample = '@webroot/xls_sample/';
    public static $dirParsing = '@webroot/xls_parsing/';
    public static $dirParsingRelative = '@web/xls_parsing/';
    public static $dirQrcode = '@webroot/qrcode/';
    public static $dirQrcodeRelative = '@web/qrcode/';
    public static $dirTemp = '@webroot/temp/';
    public static $dirTempRelative = '@web/temp/';

    public function publish() {
        return ['Unpublish', 'Publish'];
    }

    public function publishLabel($int) {
        $array = $this->publish();
        if ($int == 1)
            $class = 'success';
        else
            $class = 'default';

        return '<div class="label label-' . $class . '">' . $array[$int] . '</div>';
    }

    public function say() {
        return 'haiii';
    }

    public function getUserId($id = 0) {
        if ($id)
            return User::find()->where(['id' => $id])->one();
    }

    public function randomString($length = 10, $chars = '', $type = array()) {
        $alphaSmall = 'abcdefghijklmnopqrstuvwxyz';
        $alphaBig = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '0123456789';
        $othr = '`~!@#$%^&*()/*-+_=[{}]|;:",<>.\/?' . "'";
        $characters = "";
        $string = '';
        isset($type['alphaSmall']) ? $type['alphaSmall'] : $type['alphaSmall'] = true;
        isset($type['alphaBig']) ? $type['alphaBig'] : $type['alphaBig'] = true;
        isset($type['num']) ? $type['num'] : $type['num'] = true;
        isset($type['othr']) ? $type['othr'] : $type['othr'] = false;
        isset($type['duplicate']) ? $type['duplicate'] : $type['duplicate'] = true;
        if (strlen(trim($chars)) == 0) {
            $type['alphaSmall'] ? $characters.=$alphaSmall : $characters = $characters;
            $type['alphaBig'] ? $characters.=$alphaBig : $characters = $characters;
            $type['num'] ? $characters.=$num : $characters = $characters;
            $type['othr'] ? $characters.=$othr : $characters = $characters;
        } else
            $characters = str_replace(' ', '', $chars);
        if ($type['duplicate'])
            for (; $length > 0 && strlen($characters) > 0; $length--) {
                $ctr = mt_rand(0, (strlen($characters)) - 1);
                $string.=$characters[$ctr];
            } else
            $string = substr(str_shuffle($characters), 0, $length);
        return $string;
    }

    public function randomCode() {
        $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $return .= $tokens[rand(0, 35)];
            }
            if ($i < 2) {
                $return .= '';
            }
        }
        return $return;
    }

    public function countUser() {
        return User::find()->count();
    }

    public function templateExcel() {
        return ("@webroot/templates/new.xls");
    }

    /**
     * For Custom report translate 0 ke A
     */
    public function excelChar() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
            'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
            'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
            'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
            'EA', 'EB', 'EC', 'EE', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ',
        );
    }

    public function excelNot() {
        return [
            'userUpdate', 'userCreate', 'createDate', 'updateDate', 'image'
        ];
    }

    public function excelParsing($fileExcel) {
//        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;  /* here i added */
//        $cacheEnabled = \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
//        if (!$cacheEnabled) {
//            echo "### WARNING - Sqlite3 not enabled ###" . PHP_EOL;
//        }
        $objPHPExcel = new \PHPExcel();

        //$fileExcel = Yii::getAlias('@webroot/templates/operator.xls');
        $inputFileType = \PHPExcel_IOFactory::identify($fileExcel);

        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);

        $objReader->setReadDataOnly(true);

        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($fileExcel);

        $total_sheets = $objPHPExcel->getSheetCount();

        $allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

                $arraydata[$row - 1][$col] = $value;
            }
        }

        return $arraydata;
    }

    public static function Rp($price) {
        return number_format($price, 0, '.', ',');
    }
    
    public static function PriceComa($price) {
        return number_format($price, 0, ',', '.');
    }

    public static function usernameOne($id) {
        $user = User::find()->select('username')->where(['id' => $id])->one();
        return $user->username;
    }

    public static function years() {
        $return = [];
        for ($i = 2015; $i <= date(Y) + 1; $i++) {
            $return[$i] = $i;
        }

        return $return;
    }

    public static $monthName = [1 => 'January', 'February', 'March', 'April', 'May', 'Juni', 'Juli', 'Augustus', 'September', 'October', 'November', 'December'];

    public static function months() {
        $return = [];
        for ($i = 1; $i <= 12; $i++) {
            $r = $i < 10 ? '0' . $i : $i;
            $return[$r] = self::$monthName[$i];
        }

        return $return;
    }

    public static function cleanFormat($str) {
        $array = ['"', "'"];
        return str_replace($array, "", $str);
    }

    public static function cleanCurrencyDot($val) {
        return str_replace(",", "", $val);
    }

    public static function cleanCurrencyComma($val) {
        return str_replace(",", "", $val);
    }

    public static function buildError($errors = []) {

        $error = '';
        if ($errors) {
            foreach ($errors as $k => $v) {
                $error .= $v[0] . '  ';
            }
            Yii::$app->session->setFlash('error', $error);
        }
    }

    public static function rcopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    self::rcopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function resize_image($file, $w, $h, $crop = FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public static function createJson($filename, $json) {
        $fp = fopen($filename, 'w');
        fwrite($fp, $json);
        fclose($fp);
    }

    /*
     * Encrypted and decrypted
     */

    const METHOD = 'sintret-diesel-basic9';
    CONST HEX2BIN = 'sintretdieselng';

    public static function encrypt($encrypt) {
        $mc_key = self::HEX2BIN;
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $mc_key, trim($encrypt), MCRYPT_MODE_ECB, $iv));
        $encode = base64_encode($passcrypt);
        return $encode;
    }

    public static function decrypt($decrypt) {
        $mc_key = self::HEX2BIN;
        $decoded = base64_decode($decrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $mc_key, trim($decoded), MCRYPT_MODE_ECB, $iv));
        return $decrypted;
    }

    public static function downloadFile($url, $path) {
        $newfname = $path;
        $file = fopen($url, 'rb');
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }

    /* creates a compressed zip file */

    public static function createZip($files = [], $destination = '', $overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
//        if (file_exists($destination) && !$overwrite) {
//            return false;
//        }
        //vars
        $valid_files = [];
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive;
            if ($zip->open($destination, $overwrite ? $zip::OVERWRITE : $zip::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach ($valid_files as $file) {
                $newFile = self::randomString(10) . '.jpg';
                $zip->addFile($file, $newFile);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }

}
