<?php
declare(strict_types=1);
namespace Inelo\Numco;
use Inelo\Numco\ArrayDelta;

class Numco {
    public static function compress (array $array): string {
        $arrayDelta = ArrayDelta::getDelta($array);
        $deflated = gzcompress(implode(',', $arrayDelta));
        return base64_encode($deflated);
    }

    public static function decompress (string $data): array {
        $inflatedData =  gzuncompress(base64_decode($data));
        $arrayDelta = [];
        if (!empty($inflatedData)) {
            $arrayDelta = explode(',', $inflatedData);
        }
        return ArrayDelta::getValues($arrayDelta);
    }

    public static function compressUrlSafe (array $array): string
    {
        $arrayDelta = ArrayDelta::getDelta($array);
        $deflated   = gzcompress(implode(',', $arrayDelta));
        return base64url_encode($deflated);
    }

    public static function decompressUrlSafe(string $data): array
    {
        $inflatedData = gzuncompress(base64url_decode($data));
        $arrayDelta   = [];
        if (!empty($inflatedData))
        {
            $arrayDelta = explode(',', $inflatedData);
        }
        return ArrayDelta::getValues($arrayDelta);
    }


}

/**
 * Encode data to Base64URL
 * @param string $data
 * @return boolean|string
 */
function base64url_encode(string $data)
{
    // First of all you should encode $data to Base64 string
    $b64 = base64_encode($data);

    // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
    if ($b64 === false) {
        return false;
    }

    // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
    $url = strtr($b64, '+/', '-_');

    // Remove padding character from the end of line and return the Base64URL result
    return rtrim($url, '=');
}

/**
 * Decode data from Base64URL
 * @param string $data
 * @param boolean $strict
 * @return boolean|string
 */
function base64url_decode(string $data, bool $strict = false)
{
    // Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
    $b64 = strtr($data, '-_', '+/');

    // Decode Base64 string and return the original data
    return base64_decode($b64, $strict);
}