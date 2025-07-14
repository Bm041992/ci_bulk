<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Sub_admin_model extends CI_Model 
{ 
    public function __construct() 
    { 
        parent::__construct(); 

        $this->table='admins';

        $this->column_order = array('admins.name','admins.email','admins.email_limit','country.name','state.name','city.name','created_at');
        // Set searchable column fields
        $this->column_search = array('admins.name','admins.email','admins.email_limit','country.name','state.name','city.name');
        $this->order  = ['id' => 'desc'];
    } 

    public function getRows($postData)
    {
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1)
        {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
    
        return $query->result();
    }

    public function countFiltered($postData)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData)
    {
        $this->db->select('admins.*,country.name as country_name,state.name as state_name,city.name as city_name');
        $this->db->from($this->table);
        $this->db->join('country', 'country.country_id = admins.country_id', 'left outer');
        $this->db->join('state', 'state.state_id = admins.state_id', 'left outer');
        $this->db->join('city', 'city.city_id = admins.city_id', 'left outer');
        $this->db->where('admins.role <>',1);

        $i = 0;        
        // loop searchable columns 
        foreach($this->column_search as $item)
        {
            // if datatable send POST for search
            if($postData['search']['value'])
            {
                // first loop
                if($i===0)
                {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i)
                {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }                
         
        if(isset($postData['order']))
        {
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
}