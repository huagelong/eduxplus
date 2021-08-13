<?php
namespace App\Bundle\AppBundle\Lib\Service;
use App\Bundle\AppBundle\Lib\Base\BaseService;
/**
 * [AesSecurity aes加密，支持PHP7+]
 * 算法模式：ECB
 * 密钥长度：128
 * 补码方式：PKCS7Padding
 * 解密串编码方式：base64/十六进制
 */
class AesService extends BaseService
{
    /**
     * [encrypt aes加密]
     * @param    [type]                   $input [要加密的数据]
     * @return   [type]                          [加密后的数据]
     */
    public static function encrypt($input)
    {
        $key = $this->getConfig("secret");
        $key = self::_sha1prng($key);
        $iv = '';
        $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA, $iv);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * [decrypt aes解密]
     * @param    [type]                   $sStr [要解密的数据]
     * @return   [type]                         [解密后的数据]
     */
    public static function decrypt($sStr)
    {
        $sKey = $this->getConfig("secret");
        $sKey = self::_sha1prng($sKey);
        $iv = '';
        $decrypted = openssl_decrypt(base64_decode($sStr), 'AES-128-ECB', $sKey, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    /**
     * SHA1PRNG算法
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    private static function _sha1prng($key)
    {
        return substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
    }
}
