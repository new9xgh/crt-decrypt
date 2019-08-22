<?php

namespace common\utils;

use Yii;

class SecureCRTDecipher
{
    /**
     * 哈希值版本
     */
    const VERSION1 = 1;
    const VERSION2 = 2;

    /**
     * 通过配置文件解密
     * @param $filePath
     * @return array|bool
     */
    public function decryptByConfigFile($filePath)
    {
        $content = file_get_contents($filePath);
        $parseData = $this->parseConfigContent($content);
        $result = $this->decryptByHash($parseData['hash'], $parseData['version']);
        if (!$result) {
            return false;
        }
        $result['host'] = $parseData['host'];
        return $result;
    }

    /**
     * 通过hash密文解密
     * @param $hash
     * @param $version
     * @return array|bool
     */
    public function decryptByHash($hash, $version)
    {
        $host = '';
        $command = $this->buildCommand($hash, $version);
        $password = $this->executeCommand($command);
        if (!$password) {
            return false;
        }
        return ['host' => $host, 'password' => $password, 'version' => $version];
    }

    /**
     * 解析配置文件内容
     * @param $content
     * @return array
     */
    private function parseConfigContent($content)
    {
        $version = self::VERSION2;
        $hash = '';
        $host = '';

        // hash
        preg_match('/"Password.*?"=(.+)/i', $content, $passwordMatches);
        if ($passwordMatches && count($passwordMatches) == 2) {
            $passwordData = explode(':', $passwordMatches[1]);
            if (count($passwordData) == 2) {
                $hash = trim($passwordData[1]);
                $version = $passwordData[0] === '02' ? self::VERSION2 : self::VERSION1;
            } else if (count($passwordData) == 1) {
                $hash = substr(trim($passwordData[0]), 1);
                $version = self::VERSION1;
            }
        }

        // host
        preg_match('/"Hostname"=(.+)/i', $content, $hostMatches);
        if ($hostMatches && count($hostMatches) == 2) {
            $host = trim($hostMatches[1]);
        }

        $result = ['version' => $version, 'host' => $host, 'hash' => $hash];
        return $result;
    }

    /**
     * 构造命令
     * @param $hash
     * @param $version
     * @return string
     */
    private function buildCommand($hash, $version)
    {

        $script = dirname(Yii::getAlias("@app")) . '/scripts/SecureCRTCipher.py';
        $version = $version == self::VERSION2 ? '-v2' : '';
        $command = Yii::$app->params['python'] . " {$script} dec {$version} {$hash} 2>&1";
        return $command;
    }

    /**
     * 执行命令
     * @param $command
     * @return bool|string
     */
    private function executeCommand($command)
    {
        $result = exec($command, $output, $ret);
        if ($ret != 0) {
            return false;
        }
        return $result;
    }
}