<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Common_model extends CI_Model 
{ 
    public function __construct() 
    { 
        parent::__construct(); 
    } 

    public function get_data($table, $where=[],$select='',$order_by_k='',$order_by='')
    {
        $select_col= ($select) ? ($select) : '*';

            $this->db->select($select_col);
            $this->db->from($table);
            if(!empty($where))
            {
                foreach ($where as $column => $parameter) 
                {
                    $this->db->where($column , $parameter);  
                }
            } 
			if($order_by_k!='' && $order_by!='')
			{
				$this->db->order_by($order_by_k." ".$order_by);
			}
        return $this->db->get()->result();
    }

    function get_data_row($tbl,$where=array(),$order_by_k='',$order_by='',$select_col='')
	{
		$select_column= (!empty($select_col)) ? $select_col : '*';

		 		$this->db->select($select_column);
				$this->db->from($tbl);
				$this->db->where($where);
				if($order_by_k!='' && $order_by!='')
				{
					$this->db->order_by($order_by_k." ".$order_by);
				}
		$query=	$this->db->get();
		return $query->row();
	}

    function get_data_row_array($tbl,$where=array(),$order_by_k='',$order_by='',$select_col='')
	{
		$select_column= (!empty($select_col)) ? $select_col : '*';

		 		$this->db->select($select_column);
				$this->db->from($tbl);
				$this->db->where($where);
				if($order_by_k!='' && $order_by!='')
				{
					$this->db->order_by($order_by_k." ".$order_by);
				}
		$query=	$this->db->get();
		return $query->row_array();
	}

    function insert_data($tbl,$data)
	{
		$query=	$this->db->insert($tbl,$data);
		return $this->db->insert_id();
	}

	function update_by($tbl, $where, $data) 
	{
		$this->db->where($where);
		return $this->db->update($tbl, $data);
	}

	function delete_data($tbl,$where)
	{
		$this->db->where($where);
		$this->db->delete($tbl);
		return true;
	}

    function multiple_delete($tbl,$where_in)
	{
		foreach($where_in as $k=>$v)
		{
			$this->db->where_in($k, $v);
			$this->db->delete($tbl);
		}
		return true;
	}

	public function get_contacts()
	{
					$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
					$this->db->select('contacts.*, ce.email');
					$this->db->from('contacts');
                    $this->db->where('contacts.user_id', $this->session->userdata('user_id'));
					$this->db->join('(SELECT contact_id, email 
									FROM contact_emails 
									WHERE id IN (
										SELECT MIN(id) 
										FROM contact_emails 
										GROUP BY contact_id
									)
									) AS ce', 'ce.contact_id = contacts.id', 'left');
					$this->db->order_by('ce.email', 'ASC');
		$query = $this->db->get();
		
		return $query->result();
	}

    public function get_bday_contacts($assign_template_contact_ids=[])
	{
					$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
					$this->db->select('contacts.*, ce.email');
					$this->db->from('contacts');
                    $this->db->where('contacts.user_id', $this->session->userdata('user_id'));

                    if(!empty($assign_template_contact_ids))
                    {
                        $this->db->where_not_in('contacts.id', $assign_template_contact_ids);
                    }
					$this->db->join('(SELECT contact_id, email 
									FROM contact_emails 
									WHERE id IN (
										SELECT MIN(id) 
										FROM contact_emails 
										GROUP BY contact_id
									)
									) AS ce', 'ce.contact_id = contacts.id', 'left');
					$this->db->order_by('ce.email', 'ASC');
		$query = $this->db->get();
		
		return $query->result();
	}

	public function get_data_array($table, $where=[],$select='',$order_by_k='',$order_by='')
    {
        $select_col= ($select) ? ($select) : '*';

            $this->db->select($select_col);
            $this->db->from($table);
            if(!empty($where))
            {
                foreach ($where as $column => $parameter) 
                {
                    $this->db->where($column , $parameter);  
                }
            } 
			if($order_by_k!='' && $order_by!='')
			{
				$this->db->order_by($order_by_k." ".$order_by);
			}
        return $this->db->get()->result_array();
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

	public function countAll()
    {
        $this->db->from('email_logs');
        return $this->db->count_all_results();
    }

    private function _get_datatables_query($postData)
    {
        // Define columns
        $this->column_order = [
            'email_logs.email',
            'contacts.name',
            'smtp_configs.smtp_username',
            'send_email_contents.email_subject'
        ];

        $this->column_search = [
            'email_logs.email',
            'contacts.name',
            'smtp_configs.smtp_username',
            'send_email_contents.email_subject',
            'email_logs.error_info'
        ];

        $this->order = ['email_logs.id' => 'desc'];

        // Select with joins
        $this->db->select('
            email_logs.*, 
            smtp_configs.smtp_username, 
            contacts.name AS contact_name, 
            send_email_contents.email_subject, 
            birthday_templates.email_subject AS birthday_email_subject
        ');
        $this->db->from('email_logs');
        $this->db->join('smtp_configs', 'smtp_configs.id = email_logs.smtp_config_id', 'left');
        $this->db->join('contacts', 'contacts.id = email_logs.contact_id', 'left');
        $this->db->join('send_email_contents', 'send_email_contents.id = email_logs.email_content_id', 'left');
        $this->db->join('birthday_templates', 'birthday_templates.id = email_logs.birthday_template_id', 'left');
        $this->db->where('email_logs.status', 'failed'); // filter failed emails only

        // Apply search
        if (!empty($postData['search']['value'])) {
            $search_value = $postData['search']['value'];
            $this->db->group_start(); // Start grouping for ORs
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $search_value);
            }
            $this->db->group_end(); // End grouping
        }

        // Apply ordering
        if (!empty($postData['order'])) {
            $order_col_index = $postData['order'][0]['column'];
            $order_dir = $postData['order'][0]['dir'];

            if (isset($this->column_order[$order_col_index])) {
                $this->db->order_by($this->column_order[$order_col_index], $order_dir);
            }
        } else {
            foreach ($this->order as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }
    }

    // private function _get_datatables_query($postData)
    // {
    //     // Define orderable and searchable columns
    //     $this->column_order = array('log_message.email', 'contacts.name', 'smtp_configs.smtp_username', 'send_email_contents.email_subject');
    //     $this->column_search = array('log_message.email', 'contacts.name', 'smtp_configs.smtp_username', 'send_email_contents.email_subject', 'log_message.error_info');
    //     $this->order = array('log_message.id' => 'desc');

    //     // Select fields
    //     $this->db->select('log_message.*, smtp_configs.smtp_username, contacts.name AS contact_name, send_email_contents.email_subject');
    //     $this->db->from('log_message');
    //     $this->db->join('smtp_configs', 'smtp_configs.id = log_message.smtp_config_id', 'left');
    //     $this->db->join('contacts', 'contacts.id = log_message.contact_id', 'left');
    //     $this->db->join('send_email_contents', 'send_email_contents.id = log_message.email_content_id', 'left');

    //     // Search filter
    //     $i = 0;
    //     foreach ($this->column_search as $item) {
    //         if (!empty($postData['search']['value'])) {
    //             if ($i === 0) {
    //                 $this->db->group_start();
    //                 $this->db->like($item, $postData['search']['value']);
    //             } else {
    //                 $this->db->or_like($item, $postData['search']['value']);
    //             }
    //             if (count($this->column_search) - 1 == $i) {
    //                 $this->db->group_end();
    //             }
    //         }
    //         $i++;
    //     }

    //     // Order by
    //     if (isset($postData['order'])) {
    //         $colIdx = $postData['order']['0']['column'];
    //         $orderDir = $postData['order']['0']['dir'];
    //         if (isset($this->column_order[$colIdx])) {
    //             $this->db->order_by($this->column_order[$colIdx], $orderDir);
    //         }
    //     } else if (!empty($this->order)) {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    // }
} 