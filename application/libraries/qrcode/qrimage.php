<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
 
    define('QR_IMAGE', true);

    class QRimage {
        
        public static $black = array(255,255,255);
        public static $white = array(0,0,0);

        //----------------------------------------------------------------------
        public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE) 
        {
            if (!function_exists('ImageCreate')) {
                $pngData = self::pngFallback($frame, $pixelPerPoint, $outerFrame);
                if ($filename === false) {
                    Header("Content-type: image/png");
                    echo $pngData;
                } else {
                    if ($saveandprint === TRUE) {
                        file_put_contents($filename, $pngData);
                        header("Content-type: image/png");
                        echo $pngData;
                    } else {
                        file_put_contents($filename, $pngData);
                    }
                }
                return;
            }

            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/png");
                ImagePng($image);
            } else {
                if($saveandprint===TRUE){
                    ImagePng($image, $filename);
                    header("Content-type: image/png");
                    ImagePng($image);
                }else{
                    ImagePng($image, $filename);
                }
            }
            
            if (PHP_VERSION_ID < 80000) {
                ImageDestroy($image);
            }
        }
    
        //----------------------------------------------------------------------
        public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85) 
        {
            $image = self::image($frame, $pixelPerPoint, $outerFrame);
            
            if ($filename === false) {
                Header("Content-type: image/jpeg");
                ImageJpeg($image, null, $q);
            } else {
                ImageJpeg($image, $filename, $q);            
            }
            
            if (PHP_VERSION_ID < 80000) {
                ImageDestroy($image);
            }
        }
    
        //----------------------------------------------------------------------
        private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4) 
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;
            
            $base_image =ImageCreate($imgW, $imgH);
            
            $col[0] = ImageColorAllocate($base_image,QRImage::$black[0],QRImage::$black[1],QRImage::$black[2]);
            $col[1] = ImageColorAllocate($base_image,QRImage::$white[0],QRImage::$white[1],QRImage::$white[2]);

            imagefill($base_image, 0, 0, $col[0]);

            for($y=0; $y<$h; $y++) {
                for($x=0; $x<$w; $x++) {
                    if ($frame[$y][$x] == '1') {
                        ImageSetPixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
                    }
                }
            }
            
            $target_image =ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
            ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);
            if (PHP_VERSION_ID < 80000) {
                ImageDestroy($base_image);
            }
            
            return $target_image;
        }

        private static function pngChunk($type, $data)
        {
            return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        }

        private static function pngFallback($frame, $pixelPerPoint = 4, $outerFrame = 4)
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            $gridW = $w + 2 * $outerFrame;
            $gridH = $h + 2 * $outerFrame;
            $imgW = $gridW * $pixelPerPoint;
            $imgH = $gridH * $pixelPerPoint;

            $plte = chr(QRimage::$black[0]) . chr(QRimage::$black[1]) . chr(QRimage::$black[2])
                  . chr(QRimage::$white[0]) . chr(QRimage::$white[1]) . chr(QRimage::$white[2]);

            $rawData = '';
            for ($y = 0; $y < $gridH; $y++) {
                $rowBytes = "\x00";
                for ($x = 0; $x < $gridW; $x++) {
                    $bit = ($y >= $outerFrame && $y < $outerFrame + $h && $x >= $outerFrame && $x < $outerFrame + $w && $frame[$y - $outerFrame][$x - $outerFrame] == '1') ? "\x01" : "\x00";
                    $rowBytes .= str_repeat($bit, $pixelPerPoint);
                }
                $rawData .= str_repeat($rowBytes, $pixelPerPoint);
            }

            $ihdr = pack('NNCCCCC', $imgW, $imgH, 8, 3, 0, 0, 0);
            return "\x89PNG\r\n\x1a\n"
                . self::pngChunk('IHDR', $ihdr)
                . self::pngChunk('PLTE', $plte)
                . self::pngChunk('IDAT', gzcompress($rawData, 6))
                . self::pngChunk('IEND', '');
        }
    }