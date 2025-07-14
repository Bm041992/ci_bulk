<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller
{
	/**
	 * Constructor for the class
	 */
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Common_model','common');
	}

	public function index()
    {
		redirect(base_url('profile/edit'));
	}

	public function edit()
	{
		$id = get_loggedin_user_id();

		if ($id)
		{
            $data['title']='Edit Profile';
			$data['countries'] = $this->common->get_data('country');
			$data['user']    = $this->common->get_data_row('admins',array('id'=>$id));
			$data['content'] = $this->load->view('profile/edit', $data, TRUE);
			$this->load->view('layouts/index', $data);
		}

		if ($this->input->post())
		{
			$username=ucwords(trim($this->input->post('username')));
			$data = array('name' => $username);

			$update = $this->common->update_by('admins',array('id'=>$id), $data);

			if ($update)
			{
				$this->session->set_userdata('user_name', $username);
                $this->session->set_flashdata('success_msg', 'Profile updated successfully');
			}
            else
            {
                $this->session->set_flashdata('error_msg', 'Profile not updated. Something went wrong');
            }
            redirect(base_url('profile/edit'));
		}
	}

	public function edit_password()
	{
		$id           = get_loggedin_user_id();
		$data['user'] = $this->common->get_data_row('admins',array('id'=>$id));

		if ($this->input->post())
		{
			$data = array('password' => md5($this->input->post('new_password')));

			$update = $this->common->update_by('admins',array('id'=>$id), $data);

			if ($update)
			{
				$this->session->set_flashdata('success_msg', 'Password updated successfully');
			}
            else
            {
                $this->session->set_flashdata('error_msg', 'Password not updated. Something went wrong');
            }

            redirect(base_url('profile/edit'));
		}
	}

	public function check_user_oldpassword()
    {
		$old_password = $this->input->post('old_password');
        $id           = get_loggedin_user_id();
        
        $response = false;
        if($id) 
        {            
            $get_admin_byid=$this->common->get_data_row('admins',array("id"=>$id));
         
            if(!empty($get_admin_byid))
            {
                if(md5($old_password) == $get_admin_byid->password)
                {
                    $response = true;
                } 
            } 
        } 
        echo json_encode($response);
        exit;
	}
}
?>