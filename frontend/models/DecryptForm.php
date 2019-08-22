<?php

namespace frontend\models;


use common\utils\SecureCRTDecipher;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class DecryptForm extends Model
{
    const SCENARIO_DECRYPT_BY_CONF = 'decryptByConfig';
    const SCENARIO_DECRYPT_BY_HASH = 'decryptByHash';

    /**
     * @var UploadedFile
     */
    public $configFile;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $password;

    public $hashVersions = [
        SecureCRTDecipher::VERSION1 => '加密版本V1',
        SecureCRTDecipher::VERSION2 => '加密版本V2',
    ];

    public function rules()
    {
        return [
            [['configFile'], 'file', 'skipOnEmpty' => false, 'maxSize' => 500 * 1024, 'on' => self::SCENARIO_DECRYPT_BY_CONF],
            [['hash', 'version'], 'required', 'on' => self::SCENARIO_DECRYPT_BY_HASH],
        ];
    }

    public function attributeLabels()
    {
        return [
            'configFile' => '配置文件',
            'hash' => 'Hash密文',
            'version' => '加密版本',
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
        $result = $secureCRTDecipher->decryptByHash($this->hash, $this->version);
        if (!$result) {
            return false;
        }
        $this->password = $result['password'];
        $this->host = $result['host'];
        return true;
    }
}