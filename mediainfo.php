<?php

/**
 * FFMPEG MediaInfo PHP Class
 * --
 * MediaInfo is an easy-to-use static PHP class, for extracting data from audio and video files, 
 * the FFMpeg program must be installed on the sevrera or computer for the class to work.
 * --
 * Author: UberCHEL
 * version: v1.0.5
 */

 namespace uberchel;
 

 class MediaInfo {


    /**
     * var read/write return data Object of Array
     */
    private static $state = [
        'isObject' => true
    ];

    /**
     * Local variable media info data
     */
    private static $result = [
        'streams' => [
            'video' => [],
            'audios' => [],
            'pictures' => [],
            'subtitles' => []
        ]
    ];

    /**
     * Local variable metadata keys
     */
    private static $env = [
        'DURATION' => 'duration',
        'NUMBER_OF_BYTES' => 'size',
        '_STATISTICS_WRITING_APP' => 'writeApp'
    ];

    /**
     * Check is Object
     * 
     * @return Boolean
     */
    public static function isObject() {
        return self::$state['isObject'];
    }

    /**
     * A function for setting the data output as an object or not
     * 
     * @property {boolean} $ok
     * @return void
     */
    public static function asObject(bool $ok = true) {
        self::$state['isObject'] = $ok;
    }

    /**
     * Static Function a the Get All info media file
     * 
     * @property {string} $name
     * @property {array} $arguments
     * @return object or array
     */
    public static function __callStatic(string $name, array $arguments = []) {
        // check arguments
        if (!empty($arguments)) {
            self::Get($arguments[0]);
        }

        // prepared $name, make string a lower and deletes first 3 chars
        $name = lcfirst(mb_substr($name, 3, strlen($name), 'UTF-8'));

        // We check the presence of the key in the array with the name of the variable
        if (array_key_exists($name, self::$result)) {
            $result = self::$result[$name];

            // We check the array or not and return either a string or an object.
            return self::transformData($result);
        } 

        // In the streams block, we check for the presence of a key in the array by the variable name.
        elseif (array_key_exists($name, self::$result['streams'])) {
            $result = self::$result['streams'][$name];

            // We check the array or not and return either a string or an object.
            return self::transformData($result);
        }

        return null;
    }

    /**
     * Checking for the existence of a method value
     * 
     * @property {string} $value
     * @property {int} $num
     * @return boolean
     */
    public static function Has(string $value, int $num = null) {

        $arr = explode('.', $value);
        if ($arr && count($arr) === 2) {

            // check array keys is empty
            $num = $num ? $num : $arr[0];
            if (empty(self::$result['streams'][$num])) {
                return false;
            }

            // Get data of Streams
            $data = self::$result['streams'][$num];
            unset($num);

            // checking is stream or streams
            if (array_key_exists(0, $data)) {
                foreach ($data AS $item) {
                    return array_key_exists($arr[1], $item);
                }
            } 
            else

            // check is key in stream
            return array_key_exists($arr[1], $data);
        } 

        // check is key
        return array_key_exists($value, self::$result);
    }

    /**
     * Static Function a the Get All info media file
     * 
     * @property {string} $file
     * @property {boolean} $toObject
     * @return Object or Array
     */
    public static function Get(string $file = '', bool $toObject = true) {
        // check file on empty
        if (empty(self::$result['duration']) && !$file) return null;
        elseif ($file) {

            // Loading mediainfo
            self::Load($file, $toObject);
        }

        // return mediainfo data
        return self::transformData(self::$result);
    }

    /**
     * Static Function a the Get All info media file
     * 
     * @property {string} $file
     * @property {boolean} $toObject
     * @return void
     */
    public static function Load (string $file = '', bool $toObject = true) {
        // check file on empty
        if (empty($file)) return null;

        // execute ffmpeg application
        $source = self::FFMpegExec($file);

        // state type
        self::asObject($toObject);

        // parsing general info (Duration, bitrate, start time)
        if (preg_match('/Duration:\s+?(\d+:\d+:\d+)[^\n,]+, start:\s+?(\d+)[^\n,]+, bitrate:\s+?([0-9.]+\sKb\/s)\b/iS', $source, $general)) {
            self::$result['start'] = $general[2];
            self::$result['bitrate'] = $general[3];
            self::$result['duration'] = $general[1];
        }

        // parsing general info (title, encoder, created file)
        self::$result['title'] = preg_match('/title\s+: ([^\n]+)\b/iS', $source, $var) ? $var[1] : null;
        self::$result['encoder'] = preg_match('/encoder\s+: ([^\n]+)\b/iS', $source, $var) ? $var[1] : null;
        self::$result['created'] = preg_match('/creation_time\s+: ([0-9-\:T]+)\b/i', $source, $var) ? $var[1] : '00:00:00';

        // parsing streams data
        preg_match_all('/Stream #\d+:\d+(?:\([^\)]+\))?:[^\n]*(?:\n\s+.*?(?=\n\s*Stream #|\n\s*$|\Z))/s', $source, $streams);
        unset($var);

        // parsing of streams dat for cycle
        foreach ($streams[0] as $item) {
            $stream = [];

            if (preg_match('/Stream #(\d+:\d+)(?:\(([^\)]+)\))?: ([^\n]+)/', $item, $main)) {
                $stream['id'] = $main[1];
                $stream['language'] = isset($main[2]) ? $main[2] : null;
                $stream['type_info'] = trim($main[3]);

                // parse stream videos section
                if (stripos($stream['type_info'], 'Video:') === 0) {
                    $stream['type'] = stripos($stream['type_info'], 'attached pic') ? 'pictures' : 'video';
                    if (preg_match('/Video: ([^,\s]+).*?,\s(\d+x\d+).*?([0-9.]+ fps)/', $stream['type_info'], $video)) {
                        $stream['codec'] = $video[1];
                        $stream['resolution'] = $video[2];
                        list($width, $height) = explode('x', $video[2]);
                        $stream['width'] = $width;
                        $stream['height'] = $height;
                        $stream['fps'] = $video[3];
                        $stream['title'] = self::$result['title'];

                        // adding videoCodec of videos id 0
                        if (empty(self::$result['audioCodec'])) {
                            self::$result['videoCodec'] = $video[1];
                        }
                    }
                    
                    // parsing bytrate
                    if (preg_match('/BPS\s+: (\d+)/mi', $item, $bpsMatch)) {
                        $stream['bitrate'] = self::FormatBytRate($bpsMatch[1]);
                    } 
                    elseif (preg_match('/Video:.*,\s(\d+\sKb\/s),/iS', $stream['type_info'], $bitrate)) {
                        $stream['bitrate'] = $bitrate[1];
                    }
                } 

                // parse stream audio section
                elseif (stripos($stream['type_info'], 'Audio:') === 0) {
                    $stream['type'] = 'audios';

                    // parsing audio streaming
                    if (preg_match('/Audio: ([^,\s]+),.*?(\d+\sHz),\s?([^,\s]+).*?(\d+ kb\/s)/', $stream['type_info'], $audio)) {
                        $stream['codec'] = $audio[1];
                        $stream['rates'] = $audio[2];
                        $stream['channels'] = $audio[3];
                        $stream['bitrate'] = $audio[4];

                        // adding audioCodec of audios id 0
                        if (empty(self::$result['audioCodec'])) {
                           self::$result['audioCodec'] = $audio[1];
                        }
                    }
                } 

                // parse stream subtitles section
                elseif (stripos($stream['type_info'], 'Subtitle:') === 0) {
                    $stream['type'] = 'subtitles';
                }
            }

            // parse metadata section
            if(preg_match_all('/^\s+([^:]+):\s+(.+)$/m', $item, $meta, PREG_SET_ORDER)) {
                $metadata = [];
                foreach ($meta as $match) {
                    $key = trim($match[1]);
                    $value = trim($match[2]);

                    // parsing metadata for title for streams
                    if ($key === 'Metadata' && preg_match('/(?:title|filename|bps)\s+?:\s+(.+)?/is', $value, $a)) {
                        $stream['title'] = $a[1];
                    } 
                    else {

                        // parsing duration for current metadata stream
                        if (array_key_exists($key, self::$env) && $key === 'DURATION') {
                            $pos = strpos($value, '.');
                            $stream['duration'] = $pos > 0 ? mb_substr($value, 0, strpos($value, '.'), 'UTF-8') : $value;
                            unset($pos);
                        }

                        // getting metadata value
                        elseif (array_key_exists($key, self::$env)) {
                            $stream[self::$env[$key]] = is_numeric($value) ? self::FormatSize($value) : $value;
                        }
                    }
                }
            }

            // added streams data in array
            if (isset($stream['type'])) {
                $type = $stream['type'];
                unset($stream['type']);

                // There is usually only one video stream, so we don't include the array.
                if ($type === 'video') {
                    self::$result['streams'][$type] = $stream;
                }
                // Adding the rest of the streams as an array
                else {
                    self::$result['streams'][$type][] = $stream;
                }
                
                // FileSize
                self::$result['size'] = self::FormatSize(fileSize($file));

                // remove variable
                unset($type);
            }
        }
    }

    /**
     * Static Function Executable ffmpeg
     * 
     * @property {string} $file
     * @return strings
     */
    private static function FFMpegExec (string $file) {

        // auto destruction class
        register_shutdown_function(['MediaInfo', 'destruct']);

        // check for exec
        if (self::exec_disabled()) {
            throw new RuntimeException('Exec is disabled on server', 334561);
        }

        // Exec ffmpeg proccess
        exec ("ffmpeg -i {$file} 2>&1", $result);

        // check is success of output data
        if (strpos($result[0], 'ffmpeg') === 0) {
            return implode(PHP_EOL, $result);
        } 
        else {
            throw new RuntimeException('FFmpeg is not installed on host server', 334561);
        }
    }

    /**
     * check is Data on transform Object or String or array
     * 
     * @property {mixed} $data
     * @return mixed
     */
    private static function transformData ($data) {
        // check of is empty
        if (!isset($data)) return '';

        // check of is array
        if (self::isObject() && is_array($data)) {
            // return Object
            return (object) $data;
        } else {
            // return string or array
            return $data;
        }
    }

    /**
     * check is exec disabled
     * 
     * @return boolean
     */
    private static function exec_disabled() {
        //get php ini section disable_functions
        $disabled = explode(',', ini_get('disable_functions'));
        return in_array('exec', $disabled);
    }

    /**
     * bytes format for kylobytes/s
     * 
     * @property {float} $size
     * @return string
     */
    private static function FormatBytRate(float $size) {
        // format bytes to kylobytes/s
        $size = floor($size /= 999);
        // return formatted size
        return strval(round($size, 2) . ' ' . 'Kb/s');
     }

    /**
     * bytes formatte for size
     * 
     * @property {float} $size
     * @return string
     */
    private static function FormatSize(float $size) {
        $mod = 1024;
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
        // return formatted size
        return strval(round($size, 2) . ' ' . ['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$i]);
     }

 }