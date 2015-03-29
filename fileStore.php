<?php

class FileStore {

    public static $path = 'langs';
    public static $storeVarName = 'data';
    public static $targetPath;

    public static function init($locale = '') {
        self::setTargetPath($locale);
        self::mountTarget();
    }

    private static function setTargetPath($locale = '') {
        $path = sprintf('%s/%s/%s.php', __DIR__, self::$path, Locale::$target);
        self::$targetPath = $path;
    }

    public static function getTargetPath() {
        return self::$targetPath;
    }

    private static function mountTarget() {
        if (!is_file(self::$targetPath)) {
            self::createPath();
        }
        global ${self::$storeVarName};
        include_once self::$targetPath;
    }

    private static function createPath() {
        $file = fopen(self::$targetPath, 'w')
                or die('Cannot create file:  ' . self::$targetPath);
        fclose($file);
    }

    private static function rewritePath($data = array()) {
        $array = sprintf('$%s = %s', self::$storeVarName, var_export($data, true));
        $str = "<?php\n" . $array . ";\n";
        return file_put_contents(self::$targetPath, $str);
    }

    public static function getByHash($hash = '') {
        global ${self::$storeVarName};
        return isset($data[$hash]) ? $data[$hash] : FALSE;
    }

}
