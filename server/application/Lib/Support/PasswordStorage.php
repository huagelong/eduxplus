<?php
/**
 *  hash 散列加密解密
 */
namespace Lib\Support;

class PasswordStorage
{
    public static function verify_password($password, $hash){
        $hashStr = base64_decode($hash, true);
        $hashStr = substr($hashStr, 1);
//        echo bin2hex($hashStr)."\r\n";
        $salt_raw = substr($hashStr, 0, 16);
//        echo bin2hex($salt_raw)."\r\n";
        $iterations = 1000;
        $key_raw = self::pbkdf2(
            'sha1',
            $password,
            $salt_raw,
            $iterations,
            32,
            true
        );
//        echo bin2hex($key_raw)."\r\n";
        return $hashStr == ($salt_raw.$key_raw);
    }

    /**
     * Hash a password with PBKDF2
     *
     * @param string $password
     * @return string
     */
    public static function create_hash($password)
    {
        $slatByteLenth = 16;
        if (!\is_string($password)) {
            throw new \Exception(
                "create_hash(): Expected a string"
            );
        }
        $salt_raw = \random_bytes($slatByteLenth);

        $keyByte =  self::pbkdf2(
            'sha1',
            $password,
            $salt_raw,
            1000,
            32,
            true
        );
        $bytes = random_bytes(1).$salt_raw.$keyByte;
        return base64_encode($bytes);
    }

    /*
     * PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
     * $algorithm - The hash algorithm to use. Recommended: SHA256
     * $password - The password.
     * $salt - A salt that is unique to the password.
     * $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
     * $key_length - The length of the derived key in bytes.
     * $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
     * Returns: A $key_length-byte key derived from the password and salt.
     *
     * Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
     *
     * This implementation of PBKDF2 was originally created by https://defuse.ca
     * With improvements by http://www.variations-of-shadow.com
     */
    public static function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
        // Type checks:
        if (!\is_string($algorithm)) {
            throw new \Exception(
                "pbkdf2(): algorithm must be a string"
            );
        }
        if (!\is_string($password)) {
            throw new \Exception(
                "pbkdf2(): password must be a string"
            );
        }
        if (!\is_string($salt)) {
            throw new \Exception(
                "pbkdf2(): salt must be a string"
            );
        }
        // Coerce strings to integers with no information loss or overflow
        $count += 0;
        $key_length += 0;
        $algorithm = \strtolower($algorithm);
        if (!\in_array($algorithm, \hash_algos(), true)) {
            throw new \Exception(
                "Invalid or unsupported hash algorithm."
            );
        }
        // Whitelist, or we could end up with people using CRC32.
        $ok_algorithms = array(
            "sha1", "sha224", "sha256", "sha384", "sha512",
            "ripemd160", "ripemd256", "ripemd320", "whirlpool"
        );
        if (!\in_array($algorithm, $ok_algorithms, true)) {
            throw new \Exception(
                "Algorithm is not a secure cryptographic hash function."
            );
        }
        if ($count <= 0 || $key_length <= 0) {
            throw new \Exception(
                "Invalid PBKDF2 parameters."
            );
        }

        if (\function_exists("hash_pbkdf2")) {
            // The output length is in NIBBLES (4-bits) if $raw_output is false!
            if (!$raw_output) {
                $key_length = $key_length * 2;
            }
            return \hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
        }

        $hash_length = self::ourStrlen(\hash($algorithm, "", true));
        $block_count = \ceil($key_length / $hash_length);

        $output = "";
        for($i = 1; $i <= $block_count; $i++) {
            // $i encoded as 4 bytes, big endian.
            $last = $salt . \pack("N", $i);
            // first iteration
            $last = $xorsum = \hash_hmac($algorithm, $last, $password, true);
            // perform the other $count - 1 iterations
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = \hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }

        if($raw_output) {
            return self::ourSubstr($output, 0, $key_length);
        } else {
            return \bin2hex(self::ourSubstr($output, 0, $key_length));
        }
    }
    /*
     * We need these strlen() and substr() functions because when
     * 'mbstring.func_overload' is set in php.ini, the standard strlen() and
     * substr() are replaced by mb_strlen() and mb_substr().
     */
    /**
     * Calculate the length of a string
     *
     * @param string $str
     * @return int
     */
    private static function ourStrlen($str)
    {
        static $exists = null;
        if ($exists === null) {
            $exists = \function_exists('mb_strlen');
        }

        if (!\is_string($str)) {
            throw new \Exception(
                "ourStrlen() expects a string"
            );
        }

        if ($exists) {
            $length = \mb_strlen($str, '8bit');
            if ($length === false) {
                throw new \Exception();
            }
            return $length;
        } else {
            return \strlen($str);
        }
    }
    /**
     * Substring
     *
     * @param string $str
     * @param int $start
     * @param int $length
     * @return string
     */
    private static function ourSubstr($str, $start, $length = null)
    {
        static $exists = null;
        if ($exists === null) {
            $exists = \function_exists('mb_substr');
        }
        // Type validation:
        if (!\is_string($str)) {
            throw new \Exception(
                "ourSubstr() expects a string"
            );
        }

        if ($exists) {
            // mb_substr($str, 0, NULL, '8bit') returns an empty string on PHP
            // 5.3, so we have to find the length ourselves.
            if (!isset($length)) {
                if ($start >= 0) {
                    $length = self::ourStrlen($str) - $start;
                } else {
                    $length = -$start;
                }
            }
            return \mb_substr($str, $start, $length, '8bit');
        }
        // Unlike mb_substr(), substr() doesn't accept NULL for length
        if (isset($length)) {
            return \substr($str, $start, $length);
        } else {
            return \substr($str, $start);
        }
    }
}