<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Birthday_templates extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
        
        $this->load->model('Common_model','common');
        $this->load->model('BT_model');
    }

    public function index()
    {        
        $data=[];
        $data['title'] = 'Birthday Templates';
            
		$data['content'] = $this->load->view('birthday_templates/index', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function get_list()
    {
        $postData = $this->input->post();
        $data = $row = array();
        $emailData = $this->BT_model->getRows($postData);
        $i = $postData['start'];

        foreach ($emailData as $item) 
        {
            //$i++;
            if($item->id == 1)
            {
                $data[] = array(
                    htmlspecialchars($item->email_subject),
                    word_limiter(strip_tags($item->email_content), 15), // short preview
                    $item->created_at,
                    '<a href="'.base_url('bt-content/view/'.str_replace('=','',base64_encode($item->id))).'" class="text-success"><i class=" icon-eye"></i></a>&nbsp;&nbsp;<a href="'.base_url().'birthday_templates/edit/'.str_replace('=','',base64_encode($item->id)).'" data-popup="tooltip" data-placement="top"  title="Edit"  class="text-info"><i class="icon-pencil7"></i></a>'
                );
            }
            else
            {
                $data[] = array(
                // $i,
                    htmlspecialchars($item->email_subject),
                    word_limiter(strip_tags($item->email_content), 15), // short preview
                    $item->created_at,
                    '<a href="'.base_url('bt-content/view/'.str_replace('=','',base64_encode($item->id))).'" class="text-success"><i class=" icon-eye"></i></a>&nbsp;&nbsp;<a href="'.base_url().'birthday_templates/edit/'.str_replace('=','',base64_encode($item->id)).'" data-popup="tooltip" data-placement="top"  title="Edit"  class="text-info"><i class="icon-pencil7"></i></a>&nbsp;&nbsp;<a data-popup="tooltip" data-placement="top"  title="Delete" href="javascript:void(0);" class="text-danger delete_bt" data-id="'.str_replace('=','',base64_encode($item->id)).'"><i class=" icon-trash"></i></a>'
                );
            }
        }

        $output = array(
            "draw" => intval($postData['draw']),
            "recordsTotal" => $this->BT_model->countFiltered($postData),
            "recordsFiltered" => $this->BT_model->countFiltered($postData),
            "data" => $data,
        );

        echo json_encode($output);
        exit;
    }

    public function view($id)
    {
        $data['title'] = 'View Birthday Template';
        $data['email_content'] = $this->BT_model->getById(base64_decode($id));

        if (!$data['email_content']) 
        {
            show_404(); // If not found
        }

        $data['content'] = $this->load->view('birthday_templates/view', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function add()
    {        
        $data=[];
        $data['title'] = 'Add Birthday Template';
        $data['smtp_configs'] = $this->common->get_data('smtp_configs',array('admin_id'=>$this->session->userdata('user_id'),'status'=>1));

        $assign_template_contacts = $this->common->get_data('bt_smtp_contacts',array('user_id'=>$this->session->userdata('user_id')),'contacts_id');

        $assign_template_contact_ids = (!empty($assign_template_contacts)) ? array_column($assign_template_contacts, 'contacts_id') : [];
        $data['contacts'] = $this->common->get_bday_contacts($assign_template_contact_ids);
            
		$data['content'] = $this->load->view('birthday_templates/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function edit($id='')
    {
        $data['title'] = 'Edit Birthday Template';
        if($id != '' && base64_decode($id) > 0)
        {
            $data['bt_detail'] = $this->common->get_data_row('birthday_templates',array('id'=>base64_decode($id)));
            $bt_contacts = $this->common->get_data('bt_smtp_contacts',array('bt_id'=>base64_decode($id)),'contacts_id');
            $data['bt_contacts'] = (!empty($bt_contacts)) ? array_column($bt_contacts, 'contacts_id') : [];    
            $data['smtp_configs'] = $this->common->get_data('smtp_configs',array('admin_id'=>$this->session->userdata('user_id'),'status'=>1));
           
            $assign_template_contacts = $this->common->get_data('bt_smtp_contacts',array('user_id'=>$this->session->userdata('user_id'),'bt_id <>'=>base64_decode($id)),'contacts_id');

            $assign_template_contact_ids = (!empty($assign_template_contacts)) ? array_column($assign_template_contacts, 'contacts_id') : [];
            $data['contacts'] = $this->common->get_bday_contacts($assign_template_contact_ids);

            if(empty($data['bt_detail']))
            {
                $this->session->set_flashdata('error_msg','Record not found. Something went wrong. Please try again.');
                redirect(base_url('birthday_templates'));
            }
        }
        else
        {
            redirect(base_url('birthday_templates'));
        }
		$data['content'] = $this->load->view('birthday_templates/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function save_template()
    {
        if(!empty($_POST))
        {
            $this->form_validation->set_rules('email_subject', 'Subject', 'required|trim');
            $this->form_validation->set_rules('email_message', 'Message', 'required|trim');
            // $this->form_validation->set_rules('email_to[]', 'Contacts', 'required|trim');

            $response['success'] = false;
            $response['msg'] = 'Something went wrong. Please try again.';
            if ($this->form_validation->run()) 
            {
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message', false);
                $email_to = $this->input->post('email_to');

                $template_id= ($this->input->post('template_id') != '' && base64_decode($this->input->post('template_id')) > 0) ? base64_decode($this->input->post('template_id')) : '';

                $data=array(
                    'user_id'=>$this->session->userdata('user_id'),
                    'email_subject'=>$email_subject,
                    'smtp_config_id'=>$this->input->post('smtp_config'),
                    'email_content'=>$email_message,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                $action='';

                if($template_id > 0)
                {
                    unset($data['user_id']);
                    unset($data['created_at']);
                    if($this->common->update_by('birthday_templates', array('user_id'=>$this->session->userdata('user_id'),'id'=>$template_id), $data))
                    {
                        $action='updated';
                    }
                }
                else
                {
                    $template_id = $this->common->insert_data('birthday_templates',$data);
                    if($template_id > 0)
                    {
                        $action='added';
                    }
                }

                if($action!='' && $template_id > 0)
                {
                    if(!empty($email_to))
                    {
                        $this->common->delete_data('bt_smtp_contacts', array('bt_id'=>$template_id));
                        foreach($email_to as $key => $value)
                        {
                            $data=array(
                                'user_id'=>$this->session->userdata('user_id'),
                                'bt_id'=>$template_id,
                                'contacts_id'=>$value
                            );
                            $this->common->insert_data('bt_smtp_contacts', $data);
                        }
                    }
                    $response['success'] = true;
                    $response['msg'] = ($action == 'added') ? 'Template added successfully.' : 'Template updated successfully.';
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

    public function delete_template()
	{
		if(!empty($_POST))
		{
			$response['success'] = false;
			$response['msg'] = 'Something went wrong. Please try again.';

			$template_id=base64_decode($this->input->post('template_id'));

			if($template_id > 0)
			{
				$get_template = $this->common->get_data_row('birthday_templates',array('id'=>$template_id));
				if(!empty($get_template))
				{
					if($this->common->delete_data('birthday_templates',array('id'=>$template_id)))
					{
                        // $this->common->delete_data('cron_emails',array('email_content_id'=>$ec_id));

                        // $this->common->delete_data('log_message',array('email_content_id'=>$ec_id));
                        
						$response['success'] = true;
						$response['msg'] = 'Template deleted successfully.';
					}
				}
			}

			echo json_encode($response);
        	exit;
		}
		else 
		{
			redirect(base_url());
		}
	}
}