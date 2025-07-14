<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Smtp_config extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
        if(isset($_SESSION['user_role']) && $_SESSION['user_role']!=1) 
        {
            $this->session->set_flashdata('error_msg','You do not have permission to access this page.');
            redirect(base_url());
        }
        $this->load->model('Common_model','common');
        $this->load->model('Smtp_model');
    }

    public function index()
    {
        $data['title'] = 'SMTP Config';
        
		$data['content'] = $this->load->view('smtp_config/index', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function add()
    {
        $data['title'] = 'Add SMTP Config';
        $data['smtp_dtl'] ='';
        $data['sub_admins'] =$this->common->get_data('admins',array('role'=>2));
        
		$data['content'] = $this->load->view('smtp_config/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function edit($id='')
    {
        $data['title'] = 'Edit SMTP Config';
        if($id != '' && base64_decode($id) > 0)
        {
            $data['smtp_dtl'] = $this->common->get_data_row('smtp_configs',array('id'=>base64_decode($id)));
            $data['sub_admins'] =$this->common->get_data('admins',array('role'=>2));
            if(empty($data['smtp_dtl']))
            {
                $this->session->set_flashdata('error_msg','Record not found. Something went wrong. Please try again.');
                redirect(base_url('smtp_config'));
            }
        }
        else
        {
            redirect(base_url('smtp_config'));
        }
		$data['content'] = $this->load->view('smtp_config/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function save_smtp_settings()
    {
        if(!empty($_POST))
        {
            if(isset($_POST['smtp_config_id']) && !empty($_POST['smtp_config_id']) && base64_decode($_POST['smtp_config_id']) > 0)
            {
                $id = base64_decode($_POST['smtp_config_id']);
                // Update - exclude current row from unique check
                $this->form_validation->set_rules(
                    'email',
                    'Email',
                    'required|trim|valid_email|callback_unique_email[' . $id . ']'
                );
            } 
            else 
            {
                $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[smtp_configs.smtp_username]', ['is_unique' => 'This email is already registered.']);
            }
            $this->form_validation->set_rules('app_password', 'App Password', 'required|trim|min_length[16]|max_length[16]');
            $this->form_validation->set_error_delimiters('', '<br>');

            $response['success'] = false;
            $response['msg'] = 'Something went wrong. Please try again.';
            if ($this->form_validation->run()) 
            {
                $data=array(
                    'smtp_username'=>$this->input->post('email'), 
                    'app_password'=> str_replace('=','',base64_encode(trim($this->input->post('app_password')))), 
                    'status'=> (trim($this->input->post('status')) == 1) ? 1 : 0,
                    'admin_id'=> (array_key_exists('sub_admin_id',$_POST) && trim($this->input->post('sub_admin_id')) > 0) ? trim($this->input->post('sub_admin_id')) : $this->session->userdata('user_id'),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );
                if(isset($_POST['smtp_config_id']) && !empty($_POST['smtp_config_id']) && base64_decode($_POST['smtp_config_id']) > 0)
                {
                    $smtp_id=base64_decode($_POST['smtp_config_id']);
                    unset($data['created_at']);
                    $get_smtp_data=$this->common->get_data_row('smtp_configs',array('id'=>$smtp_id));

                    if(!empty($get_smtp_data))
                    {
                        if($this->common->update_by('smtp_configs',array('id'=>$smtp_id),$data))
                        {
                            if(array_key_exists('sub_admin_id',$_POST))
                            {
                                if($get_smtp_data->admin_id != trim($this->input->post('sub_admin_id')))
                                {
                                    $this->common->update_by('birthday_templates',array('smtp_config_id'=>$smtp_id),array('smtp_config_id'=>''));
                                }
                            }
                            else if($get_smtp_data->admin_id > 0)
                            {
                                $this->common->update_by('birthday_templates',array('smtp_config_id'=>$smtp_id),array('smtp_config_id'=>''));
                            }

                            $response['success'] = true;
                            $response['msg'] = 'SMTP Configuration updated successfully.';
                        }
                    }
                }
                else
                {
                    if($this->common->insert_data('smtp_configs',$data))
                    {
                        $response['success'] = true;
                        $response['msg'] = 'SMTP Configuration added successfully.';
                    }
                }
            }
            else
            {
                $response['msg']=validation_errors();
            }

            echo json_encode($response);
            exit;
        }
        else
        {
            redirect(base_url());
        }
    }

    public function unique_email($email, $id)
    {
        $this->db->where('smtp_username', $email);
        $this->db->where('id !=', $id); // Exclude current row
        $query = $this->db->get('smtp_configs');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('unique_email', 'This email is already registered.');
            return false;
        }

        return true;
    }

    public function getLists()
    {
        $data = $row = array();

        $memData = $this->Smtp_model->getRows($_POST);
        
        $i = $_POST['start'];
        foreach($memData as $smtp_dtl)
        {
            $i++;
            $switch = '<input type="checkbox" class="smtp-status-switch" 
                  data-id="' .str_replace('=','',base64_encode($smtp_dtl->id)) . '" 
                  data-on-text="Yes" 
                  data-off-text="No" 
                  ' . (($smtp_dtl->status == 1) ? 'checked' : '') . '>';

            $action='<a href="'.base_url().'smtp_config/edit/'.str_replace('=','',base64_encode($smtp_dtl->id)).'" data-popup="tooltip" data-placement="top"  title="Edit"  class="text-info"><i class="icon-pencil7"></i></a>&nbsp;&nbsp;<a data-popup="tooltip" data-placement="top"  title="Delete" href="javascript:void(0);" class="text-danger delete_smtp_config" data-id="'.str_replace('=','',base64_encode($smtp_dtl->id)).'"><i class=" icon-trash"></i></a>
             ';
            $data[] = array($i, $smtp_dtl->smtp_username,$switch, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Smtp_model->countFiltered($_POST),
            "recordsFiltered" => $this->Smtp_model->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
    }

    public function update_status()
    {
        $smtp_config_id = base64_decode($this->input->post('smtp_config_id'));

        $get_data=$this->common->get_data_row('smtp_configs',array('id'=>$smtp_config_id));

        $response['success']=false;
        $response['msg']='Something went wrong. Please try again.';
        if(!empty($get_data))
        {
            $status = $this->input->post('status');

            if($this->common->update_by('smtp_configs',array('id'=>$smtp_config_id),['status' => $status]))
            {
                $response['success']=true;
                $response['msg']='Status changes successfully';
            }
        } 
        else 
        {
            $response['msg']="Record not found. Something went wrong.";
        }
        echo json_encode($response);
        exit;
    }


    public function delete_smtp_config()
    {
        $smtp_config_id = base64_decode($this->input->post('smtp_config_id'));

        $get_data=$this->common->get_data_row('smtp_configs',array('id'=>$smtp_config_id));

        $response['success']=false;
        $response['msg']='Something went wrong. Please try again.';
        if(!empty($get_data))
        {
            $deleted = $this->common->delete_data('smtp_configs',array("id"=>$smtp_config_id));
            if ($deleted) 
            {    
               $response['success']=true;
               $response['msg']= 'Configuration deleted successfully';
            } 
        } 
        else 
        {
            $response['success']=false;
            $response['msg']="Record not found. Something went wrong.";
        }
        echo json_encode($response);
        exit;
    }
}