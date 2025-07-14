<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Dashboard_model extends CI_Model 
{ 
    public function __construct() 
    { 
        parent::__construct(); 

        $this->table='contacts';

        $this->column_order = array( 'contacts.name', 'contacts.company', 'contacts.address');
        // Set searchable column fields
        $this->column_search = array('contacts.name', 'contacts.company', 'contacts.address');
        $this->order  = ['contacts.id' => 'desc'];
    } 

    public function get_daily_birthday_contacts_list($postData)
    {
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1)
        {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
    
        return $query->result();
    }

    public function daily_bday_count($postData)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData)
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $this->db->select('contacts.id,contacts.name,contacts.company,contacts.address, cp.phone_number');
        $this->db->from('contacts');
        $this->db->join('(SELECT contact_id, phone_number FROM contact_phones WHERE id IN (SELECT MIN(id) FROM contact_phones GROUP BY contact_id)) cp', 'cp.contact_id = contacts.id', 'inner');
        $this->db->where('contacts.user_id', $this->session->userdata('user_id'));
        $this->db->where('DATE_FORMAT(contacts.birthday, "%m-%d") =', date('m-d'));
        
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

    public function get_daily_email_log_count()
    {
        $today = date('Y-m-d');

        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $daily_stats = $this->db->select("status, COUNT(*) as total")
                                ->from('email_logs')
                                ->where('DATE(created_at)', $today)
                                ->group_by('status')
                                ->get()
                                ->result();
       
        $counts = ['success' => 0, 'failed' => 0];
        foreach ($daily_stats as $row) 
        {
            $counts[$row->status] = $row->total;
        }
        return $counts;
    }

    public function get_monthly_email_log_count()
    {
        $month = date('Y-m'); // e.g., "2025-07"

        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $monthly_stats = $this->db->select("status, COUNT(*) as total")
                                ->from('email_logs')
                                ->where("DATE_FORMAT(created_at, '%Y-%m') = ", $month)
                                ->group_by('status')
                                ->get()
                                ->result();

        $counts = ['success' => 0, 'failed' => 0];
        foreach ($monthly_stats as $row) 
        {
            $counts[$row->status] = $row->total;
        }
        return $counts;
    }

    public function get_whats_app_logs()
    {
        $today = date('Y-m-d');

        $daily_stats = $this->db->select("contact_id")
                                ->from('whatsapp_logs')
                                ->where('DATE(created_at)', $today)
                                ->where('user_id', $this->session->userdata('user_id'))
                                ->where('status',1)
                                ->get()
                                ->result();
       
        return $daily_stats;
    }
    
}