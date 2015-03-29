<?php

class FileStore {
	
	public $localeFilePath = 'langs';
	public $localeFileVarName = 'data';
	public $targetLocaleFilePath;
	
	public function __construct(Locale $locale) {
		$this->locale = $locale;
		$this->setTargetLocaleFilePath();
		$this->mountTargetLocaleFile();
	}
	
	public function setTargetLocaleFilePath() {
		$path = sprintf('%s/%s/%s.php', __DIR__, $this->localeFilePath, $this->locale->target);
		$this->targetLocaleFilePath = $path;
	}
	
	public function mountTargetLocaleFile() {
		if(!is_file($this->targetLocaleFilePath)) {
			$this->createLocaleFile();
		}	
		global ${$this->localeFileVarName};
		include_once $this->targetLocaleFilePath;
	}
		
	function createLocaleFile() {
        $file = fopen($this->targetLocaleFilePath, 'w') 
        or die('Cannot create file:  '.$this->targetLocaleFilePath);
        fclose($file);
    }
		
	function rewriteLocaleFile($data = array()) {
        $array = sprintf('$%s = %s', $this->localeFileVarName, var_export($data, true));
        $str = "<?php\n" . $array . ";\n";
        return file_put_contents($this->targetLocaleFilePath, $str);
    }
    
	public function getFromFileByHash($hash = '') {		
		global $data;
		return isset($data[$hash]) ? $data[$hash] : FALSE;
	}
}
