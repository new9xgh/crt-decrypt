<?php

namespace frontend\models;


use common\utils\SecureCRTDecipher;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class DecryptForm extends Model
{
    const SCENARIO_DECRYPT_BY_CONFIG = 'decryptByConfig';
    const SCENARIO_DECRYPT_BY_HASH_PASSWORD = 'decryptByHashPassword';

    /**
     * @var UploadedFile
     */
    public $configFile;

    /**
     * @var string
     */
    public $hashPassword;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $password;


    public function rules()
    {
        return [
            [['configFile'], 'file', 'skipOnEmpty' => false, 'maxSize' => 500 * 1024, 'on' => self::SCENARIO_DECRYPT_BY_CONFIG],
            [['hashPassword'], 'required', 'on' => self::SCENARIO_DECRYPT_BY_HASH_PASSWORD],
        ];
    }

    public function attributeLabels()
    {
        return [
            'configFile' => '配置文件',
            'hashPassword' => 'Password密文',
        ];
    }

    public function uploadConfigFile()
    {
        $this->configFile = UploadedFile::getInstance($this, 'configFile');
    }

    public function decryptByConfig()
    {
        $secureCRTDecipher = new SecureCRTDecipher();
        $result = $secureCRTDecipher->decryptByConfigFile($this->configFile->tempName);
        if (!$result) {
            return false;
        }
        $this->password = $result['password'];
        $this->host = $result['host'];
        return true;
    }

    public function decryptByHash()
    {
        $secureCRTDecipher = new SecureCRTDecipher();
        $result = $secureCRTDecipher->decryptByHashPassword($this->hashPassword);
        if (!$result) {
            return false;
        }
        $this->password = $result['password'];
        $this->host = $result['host'];
        return true;
    }
}