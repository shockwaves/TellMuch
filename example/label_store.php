<?php

class Label_store extends Label {

    public $table = 'label_store';
    public $fixed;

    function __construct() {
        $this->fixed = [
			'ru' => 1, 'uk' => 1, 'en' => 1
		];
    }

    function get($id = false) {
        $data = array();
        foreach ($this->langs->locales as $country => $locale) {
            $this->db->limit(1);
            $this->db->where(['id' => $id, 'lang' => $locale]);
            $row = $this->db->get($this->table)->row_array();
            $data['version'][$row['lang']] = $row['version'];
            $data['fixed'][$row['lang']] = $row['fixed'];
        }
        return $data;
    }

    function gets($range = 0) {
        parent::$limit *= $this->langs->count;
        parent::$range = $range * $this->langs->count;
        $this->db->select($this->table . '.lang AS `lang`');
        $this->db->select($this->table . '.fixed AS `fixed`');
        $this->db->select($this->table . '.version AS `version`');
        $this->db->join($this->table, 'labels.id = label_store.id');
        $this->db->order_by($this->table . '.id', 'asc');
        return $this->collect();
    }

    function collect() {
        $data = array();
        foreach ($this->crud->gets() as $key => $row)
            $data[$row->lang][$row->id] = $row;
        return $data;
    }

    function add($id = false, $version = array()) {
        if (!$id) return false;
        $data = array();
        foreach ($version as $locale => $text)
            $data[] = array(
                'id' 		=> $id,
                'lang' 		=> strtolower($locale),
                'version' 	=> trim($text),
                'fixed'		=> $this->fixed[$locale]
            );

        return $this->db->insert_batch($this->table, $data);
    }

    function edit($data = array(), $version = array()) {
        foreach ($version as $locale => $text) {		
            $store = [
                'id' 		=> $data['id'],
                'lang' 		=> strtolower($locale),
                'version' 	=> trim($text),
                'fixed'		=> isset($data['fixed'][$locale])
            ];
            $this->db->replace($this->table, $store);
        }
    }
    
    function search($match = '', $range = 0)
    {
		if (empty($match)) return false;
        $this->db->select($this->table . '.id');
        $this->db->where('lang', $this->langs->current);
        $this->db->like('version', $match);
        $result = $this->db->get($this->table, parent::$limit, $range)->result_array();
        
        if (empty($result)) return false;
        $select = [];
        foreach ($result as $item => $row) {
            $select[] = $row['id'];
        }
        return $select;
	}

    function import($data = array(), $locale = false) {
        $store = array();
        include $this->langs->get_path($locale);
        foreach ($data as $item)
            $store[] = array(
                'id' => $item->id,
                'lang' => $locale,
                'version' => $lang[$item->name]
            );
        $this->db->insert_batch($this->table, $store);
    }

    function delete($id = false) {
        if (!$id)
            return FALSE;
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    function reset() {
        $this->db->truncate($this->table);
    }

}
