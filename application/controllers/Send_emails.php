<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_emails extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
        $this->load->model('Common_model','common');
        $this->load->model('EmailContent_model','ec_model');
    }

    public function index()
    {        
        $data=[];
        $data['title'] = 'Send Emails';
            
		$data['content'] = $this->load->view('send_email/index', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function get_email_contents()
    {
        $postData = $this->input->post();
        $data = array();
        $emailData = $this->ec_model->getRows($postData);
        $i = $postData['start'];

        foreach ($emailData as $item) 
        {
            $schedule_date= (!in_array($item->schedule_date, array('1970-01-01','0000-00-00',null,''))) ? date('d M Y',strtotime($item->schedule_date)) : '';
            $data[] = array(
                $schedule_date,
                htmlspecialchars($item->email_subject),
                word_limiter(strip_tags($item->email_content), 15), // short preview
                $item->created_at,
                '<a href="'.base_url('email-content/view/'.str_replace('=','',base64_encode($item->id))).'" class="text-info"><i class=" icon-eye"></i></a>&nbsp;&nbsp;<a data-popup="tooltip" data-placement="top"  title="Delete" href="javascript:void(0);" class="text-danger delete_email_content" data-id="'.str_replace('=','',base64_encode($item->id)).'"><i class=" icon-trash"></i></a>'
            );
        }

        $output = array(
            "draw" => intval($postData['draw']),
            "recordsTotal" => $this->ec_model->countFiltered($postData),
            "recordsFiltered" => $this->ec_model->countFiltered($postData),
            "data" => $data,
        );

        echo json_encode($output);
        exit;
    }

    public function view($id)
    {
        $data['title'] = 'View Content';
        $data['email_content'] = $this->ec_model->getById(base64_decode($id));

        if (!$data['email_content']) {
            show_404(); // If not found
        }

        $data['content'] = $this->load->view('send_email/view', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function add()
    {        
        $data=[];
        $data['title'] = 'Send Emails';
        $data['contacts'] = $this->common->get_contacts();
            
		$data['content'] = $this->load->view('send_email/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function send()
    {
        if(!empty($_POST))
        {
            $this->form_validation->set_rules('schedule_date', 'Schedule Date', 'required|trim');
            $this->form_validation->set_rules('email_subject', 'Subject', 'required|trim');
            $this->form_validation->set_rules('email_message', 'Message', 'required|trim');
            //$this->form_validation->set_rules('email_to[]', 'Contacts', 'required|trim');
            $this->form_validation->set_rules('email_to[]', 'Contacts', 'callback__at_least_one_email_required');
            $this->form_validation->set_rules('manual_emails', 'Manual Emails', 'trim');

            $response['success'] = false;
            $response['msg'] = 'Something went wrong. Please try again.';
            if ($this->form_validation->run()) 
            {
                $schedule_date = date('Y-m-d', strtotime($this->input->post('schedule_date')));
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message', false);
                $email_to = $this->input->post('email_to');

                $insert_id = $this->common->insert_data('send_email_contents',array('user_id'=>$this->session->userdata('user_id'),'schedule_date'=>$schedule_date,'email_subject'=>$email_subject,'email_content'=>$email_message,'created_at'=>date('Y-m-d H:i:s')));

                if($insert_id)
                {
                    $contact_emails=[];
                    if(!empty($email_to))
                    {
                        $contact_emails= $this->db->select('email')
                                    ->where_in('contact_id', $email_to)
                                    ->get('contact_emails')
                                    ->result_array();
                        if(!empty($contact_emails))
                        {
                            $contact_emails= array_column($contact_emails,'email');
                        }
                        
                        foreach($email_to as $contact_id)
                        {
                            $this->common->insert_data('cron_emails',array('user_id'=>$this->session->userdata('user_id'),'contact_id'=>$contact_id,'email_content_id'=>$insert_id,'updated_at'=>date('Y-m-d H:i:s')));
                        }

                        if($this->session->userdata('user_role') == 2)
                        {
                            if(array_key_exists('send_to_admin_contacts', $_POST) && $_POST['send_to_admin_contacts'] == '1')
                            {
                                if($this->session->userdata('country') > 0 && $this->session->userdata('state') > 0 && $this->session->userdata('city') > 0)
                                {
                                    $get_admin_contacts=$this->common->get_data('contacts',array('user_id'=>1,'country'=>$this->session->userdata('country'),'state'=>$this->session->userdata('state'),'city'=>$this->session->userdata('city')),'id');

                                    if(!empty($get_admin_contacts))
                                    {
                                        foreach($get_admin_contacts as $contacts)
                                        {
                                            $this->common->insert_data('cron_emails',array('user_id'=>$this->session->userdata('user_id'),'contact_id'=>$contacts->id,'email_content_id'=>$insert_id,'updated_at'=>date('Y-m-d H:i:s')));
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $manual_emails=explode(',',$this->input->post('manual_emails'));
                    $batch_data = [];
                    $counter = 0;
                    $added_emails = [];
                    if(!empty($manual_emails))
                    {
                        foreach($manual_emails as $manual_email)
                        {
                            $manual_email=trim($manual_email);
                            if (filter_var($manual_email, FILTER_VALIDATE_EMAIL) && !in_array($manual_email, $contact_emails) && !in_array($manual_email, $added_emails)) 
                            {
                                $batch_data[] = [
                                    'user_id' => $this->session->userdata('user_id'),
                                    'email' => $manual_email,
                                    'email_content_id' => $insert_id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ];

                                $added_emails[] = $manual_email;
                                $counter++;
                            }

                            if ($counter == 100) 
                            {
                                $this->db->insert_batch('cron_manual_emails', $batch_data);
                                $batch_data = []; // clear for next batch
                                $counter = 0;
                            }
                        }
                        $added_emails = [];
                    }
                    
                    if (!empty($batch_data)) 
                    {
                        $this->db->insert_batch('cron_manual_emails', $batch_data);
                    }
                    $response['success'] = true;
                    $response['msg'] = 'Content added successfully.';
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

    public function _at_least_one_email_required($value)
    {
        $manual_emails = $this->input->post('manual_emails');
        $email_to = $this->input->post('email_to'); // this will be an array

        if ((empty($email_to) || count($email_to) === 0) && empty(trim($manual_emails))) 
        {
            $this->form_validation->set_message('_at_least_one_email_required', 'Please select at least one contact or enter a manual email.');
            return false;
        }

        return true;
    }

    public function delete_email_content()
	{
		if(!empty($_POST))
		{
			$response['success'] = false;
			$response['msg'] = 'Something went wrong. Please try again.';

			$ec_id=base64_decode($this->input->post('ec_id'));

			if($ec_id > 0)
			{
				$get_contact = $this->common->get_data_row('send_email_contents',array('id'=>$ec_id));
				if(!empty($get_contact))
				{
					if($this->common->delete_data('send_email_contents',array('id'=>$ec_id)))
					{
                        $this->common->delete_data('cron_emails',array('email_content_id'=>$ec_id));

                        $this->common->delete_data('log_message',array('email_content_id'=>$ec_id));
                        
						$response['success'] = true;
						$response['msg'] = 'Content deleted successfully.';
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

    // public function logs()
    // {
    //     $data=[];
    //     $data['title'] = 'Send Emails Logs';
            
	// 	$data['content'] = $this->load->view('send_email/logs', $data, TRUE);
	// 	$this->load->view('layouts/index', $data);
    // }

    public function get_error_log_lists()
    {
        $data = $row = array();

        $logData = $this->common->getRows($_POST);
       
       // $i = $_POST['start'];
        foreach($logData as $log)
        {
            $email_subject='';
            if($log->email_content_id > 0)
            {
                $email_subject=$log->email_subject;
            }
            else  if($log->birthday_template_id > 0)
            {
                $email_subject=$log->birthday_email_subject;
            }
            // $i++;
            $data[] = array(
               // $i,
                $log->email,
                $log->contact_name ?? '-',
                $log->smtp_username ?? '-',
                $email_subject,
                $log->error_info,
                $log->created_at
            );
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->common->countFiltered($_POST),
            "recordsFiltered" => $this->common->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
        exit;
    }
}