<?php

class Label extends Crud {

    public $table = 'labels';

    function __construct() {
        $this->load->model('admin/label_store', 'store');
    }

    function get($id = false) {
        $data = (is_numeric($id)) ? parent::get($id) : parent::get_by_name($id);
        $store = $this->store->get($data->id);
        $data->version = $store['version'];
        $data->fixed = $store['fixed'];
        return $data;
    }

    function gets($range = 0) {
        $this->db->from($this->table);
        $this->db->select($this->table . '.id');
        $this->db->select($this->table . '.name');
        $this->db->select($this->table . '.default');
        $this->db->order_by($this->table . '.' . 'id', 'desc');
        return $this->store->gets($range);
    }

    function gets_where_in($find = array(), $key = 'name', $range = 0) {
        $this->db->where_in($this->table . '.' . $key, $find);
        return $this->gets($range);
    }

    function add() {
		$data = $this->input->post($this->table);
        if (!$data['name']) return;
        $name = strtolower($data['name']);
        $version = $data['version'];
        $data = [
            'name' => trim($name),
            'default' => trim($version['ru']),
        ];
        $id = parent::insert($data);
        $this->store->add($id, $version);
        $this->export();
    }

    function edit() {
		$data = [];
		$data['id'] = $this->input->post('id');
        $data['name'] = $this->input->post('name');
        parse_str($_POST['fixed'], $data['fixed']);
        parse_str($_POST['version'], $data['version']);
        return $this->save($data);       
    }
    
    function save($data = false)
    {
		$data = ($data) ? $data : $this->input->post($this->table); 
		$version = $this->translate($data['version'], $data['fixed']); 	
        $data['name'] = strtolower(trim($data['name']));
        $data['default'] = trim($version['ru']);	
        $this->store->edit($data, $version);
        unset($data['version'], $data['fixed']);
        parent::update($data['id'], $data);
        $this->export();
        return $version;
	}
    
    function translate($version = [], $fixed = [], $from = 'ru')
    {
		$this->load->library('translate');
		$data = (!empty($fixed)) ? array_diff_key($version, $fixed) : $version;	
		$translate = $this->translate->gets($data[$from], $from, $data);
		return $translate + $version;
	}

    function delete($id) {
        if (!$id OR empty($id))
            return;
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        $this->store->delete($id);
    }

    function search($match = '', $range = 0) {
        $store = $this->store->search($match, $range);
        parent::$count = count($store);
        return $this->gets_where_in($store, 'id', $range);
    }

    function map() {
        $data = [];
        $path = realpath(APPPATH . 'views');
        $resourse = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($resourse as $dest => $object) 
        {
            $name = basename($dest, '.php');
            $path = substr(strstr(realpath($dest), 'views/'), strlen('views/'));
            if (!is_file($dest) || pathinfo($dest, PATHINFO_EXTENSION) != 'php')
                continue;
            $location = (dirname($path) == '.') ? $name : dirname($path) . '/' . $name;
            preg_match_all("/lang\('(.*?)'/", file_get_contents($dest), $data[$location]);
            unset($data[$location][0]);
            if (empty($data[$location][1]) OR empty($data[$location])) {
                unset($data[$location]); 
                continue;
            }
            $data[$location] = array_unique($data[$location][1]);
        }
        ksort($data);
        return $data;
    }

    function map_explode($data = array()) {
        $result = [];
        foreach ($data as $link => $item) {
            $name = strtok($link, '/');
            $suffix = substr($link, (strpos($link, '/') ? strpos($link, '/') + 1 : 0), strlen($link));
            ($name == $suffix) ? $result[$name] = $item : $result[$name][$suffix] = $item;
        }
        return $result;
    }
    
    function export() {
        $data = array();
        foreach (parent::get_list() as $row)
            $data[$row->id] = $row->name;
        $store = array();
        parent::$limit = false;
         
        foreach (parent::gets('label_store') as $row)
            $store[$row->lang][$data[$row->id]] = $row->version;
                   
        foreach ($store as $locale => $data)
            $this->rewrite($data, $locale);
    }

    function import() {
        $this->import_default();
        $data = parent::get_list();
        foreach ($this->langs->locales as $index => $locale)
            $this->store->import($data, $locale);
    }

    private function import_default() {
        $locale = 'ru';
        $data = array();
        include $this->get_path($locale);
        foreach ($lang as $key => $value)
            $data[] = array('name' => $key, 'default' => $value);
        $this->db->insert_batch($this->table, $data);
    }

    function get_path($locale = '') {
        if ('' === $locale)
            $locale = $this->langs->current;
        return APPPATH . 'language/' . $locale . '/service_lang.php';
    }

    function rewrite($data = array(), $locale = '') {
        $array = '$lang = ' . var_export($data, true);
        $str = "<?php\n" . $array . ";\n";
        return file_put_contents($this->get_path($locale), $str);
    }

    function reset() {
        $this->db->truncate($this->table);
        $this->store->reset();
    }
    
}
