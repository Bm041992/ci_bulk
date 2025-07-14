<?php

class Auth_model extends CI_Model
{
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

	public function can_reset_password($user_id, $new_pass_key)
	{
		$this->db->where('id', $user_id);
		$this->db->where('new_pass_key', $new_pass_key);
		$user = $this->db->get('admins')->row();
		
		if ($user)
		{
			$timestamp_now_minus_1_hour = time() - (60 * 60);
			$new_pass_key_requested     = strtotime($user->new_pass_key_requested);
			
			if ($timestamp_now_minus_1_hour > $new_pass_key_requested)
			{
				return false;
			}

			return true;
		}

		return false;
	}

	public function reset_password($user_id, $new_pass_key, $password)
	{
		if (!$this->can_reset_password($user_id, $new_pass_key))
		{
			return ['expired' => true];
		}

		$this->db->where('id', $user_id);
		$this->db->where('new_pass_key', $new_pass_key);
		$this->db->update('admins', ['password' => md5($password)]);

		if ($this->db->affected_rows() > 0)
		{
			$this->db->set('new_pass_key', null);
			$this->db->set('new_pass_key_requested', null);
			$this->db->set('last_password_change', date('Y-m-d H:i:s'));
			$this->db->where('id', $user_id);
			$this->db->where('new_pass_key', $new_pass_key);
			$this->db->update('admins');

			return true;
		}

		return false;
	}
}