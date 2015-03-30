<?php

class FileStore {

    public static $path = 'langs';
    public static $storeVarName = 'data';
    public static $toPath;

    public static function init() {
        self::setToPath();
        self::mountTarget();
    }

    private static function setToPath() {
        $path = sprintf('%s/%s/%s.php', __DIR__, self::$path, Locale::$to);
        self::$toPath = $path;
    }

    public static function getToPath() {
        return self::$toPath;
    }

    private static function mountTarget() {
        if (!is_file(self::$toPath)) {
            self::create();
        }
        global ${self::$storeVarName};
        include_once self::$toPath;
    }

    private static function create() {
        $file = fopen(self::$toPath, 'w')
                or die('Cannot create file: ' . self::$toPath);
        self::rewrite();
        fclose($file);
    }

    public static function rewrite($data = array()) {
        $array = sprintf('$%s = %s', self::$storeVarName, var_export($data, true));
        $str = "<?php\n" . $array . ";\n";
        return file_put_contents(self::$toPath, $str);
    }
    
    public static function getFrom($text) {
        global ${self::$storeVarName};
        $hash = self::getHashByText($text);
        return isset($data[$hash]) ? $data[$hash] : FALSE;
    }
    
    public static function getTextByHash($hash) {
        global ${self::$storeVarName};
        return isset($data[$hash]) ? $data[$hash] : FALSE;
    }
    
    public static function getAll() {
        global ${self::$storeVarName};
        return ${self::$storeVarName};
    }
    
    public static function getHashByText($text = '') {
        return crc32($text);
    }
}
