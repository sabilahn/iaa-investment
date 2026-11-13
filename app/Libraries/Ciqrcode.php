<?php

namespace App\Libraries;

use QRcode;
use QRimage;

/**
 * PHP QR Code porting for CodeIgniter 4
 */
class Ciqrcode
{
    public bool $cacheable = true;
    public string $cachedir;
    public string $errorlog;
    public bool $quality = true;
    public int $size = 1024;

    public function __construct(array $config = [])
    {
        $this->cachedir = WRITEPATH . 'cache' . DIRECTORY_SEPARATOR;
        $this->errorlog = WRITEPATH . 'logs' . DIRECTORY_SEPARATOR;

        $qrDir = __DIR__ . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR;
        include_once $qrDir . 'qrconst.php';
        include_once $qrDir . 'qrtools.php';
        include_once $qrDir . 'qrspec.php';
        include_once $qrDir . 'qrimage.php';
        include_once $qrDir . 'qrinput.php';
        include_once $qrDir . 'qrbitstream.php';
        include_once $qrDir . 'qrsplit.php';
        include_once $qrDir . 'qrrscode.php';
        include_once $qrDir . 'qrmask.php';
        include_once $qrDir . 'qrencode.php';

        $this->initialize($config);
    }

    public function initialize(array $config = []): void
    {
        $this->cacheable = $config['cacheable'] ?? $this->cacheable;
        $this->cachedir  = rtrim($config['cachedir'] ?? $this->cachedir, '/\\') . DIRECTORY_SEPARATOR;
        $this->errorlog  = rtrim($config['errorlog'] ?? $this->errorlog, '/\\') . DIRECTORY_SEPARATOR;
        $this->quality   = $config['quality'] ?? $this->quality;
        $this->size      = $config['size'] ?? $this->size;

        if (! defined('QR_CACHEABLE')) {
            define('QR_CACHEABLE', $this->cacheable);
        }
        if (! defined('QR_CACHE_DIR')) {
            define('QR_CACHE_DIR', $this->cachedir);
        }
        if (! defined('QR_LOG_DIR')) {
            define('QR_LOG_DIR', $this->errorlog);
        }

        if ($this->quality) {
            if (! defined('QR_FIND_BEST_MASK')) {
                define('QR_FIND_BEST_MASK', true);
            }
        } else {
            if (! defined('QR_FIND_BEST_MASK')) {
                define('QR_FIND_BEST_MASK', false);
            }
            if (! defined('QR_DEFAULT_MASK')) {
                define('QR_DEFAULT_MASK', $this->quality);
            }
        }

        if (! defined('QR_FIND_FROM_RANDOM')) {
            define('QR_FIND_FROM_RANDOM', false);
        }
        if (! defined('QR_PNG_MAXIMUM_SIZE')) {
            define('QR_PNG_MAXIMUM_SIZE', $this->size);
        }
    }

    public function generate(array $params = [])
    {
        if (
            isset($params['black'])
            && is_array($params['black'])
            && count($params['black']) === 3
            && array_filter($params['black'], 'is_int') === $params['black']
        ) {
            QRimage::$black = $params['black'];
        }

        if (
            isset($params['white'])
            && is_array($params['white'])
            && count($params['white']) === 3
            && array_filter($params['white'], 'is_int') === $params['white']
        ) {
            QRimage::$white = $params['white'];
        }

        $params['data'] = $params['data'] ?? 'QR Code Library';

        $level = 'L';
        if (isset($params['level']) && in_array($params['level'], ['L', 'M', 'Q', 'H'], true)) {
            $level = $params['level'];
        }

        $size = 4;
        if (isset($params['size'])) {
            $size = min(max((int) $params['size'], 1), 10);
        }

        if (isset($params['savename'])) {
            $dir = dirname($params['savename']);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            QRcode::png($params['data'], $params['savename'], $level, $size, 2);

            return $params['savename'];
        }

        QRcode::png($params['data'], null, $level, $size, 2);

        return null;
    }
}

