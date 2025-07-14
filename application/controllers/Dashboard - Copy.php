<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
		$this->load->model('Dashboard_model');
    }

    public function index()
    {        
        $data['title']='Dashboard';
		$daily_counts=$this->Dashboard_model->get_daily_email_log_count();
		$monthly_counts=$this->Dashboard_model->get_monthly_email_log_count();

		$data['daily_success_count'] = $daily_counts['success'];
		$data['daily_failed_count'] = $daily_counts['failed'];

		$data['monthly_success_count'] = $monthly_counts['success'];
		$data['monthly_failed_count'] = $monthly_counts['failed'];

        $data['whatsapp_bday_msg'] = $this->Common_model->get_data_row('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')));

		$data['content'] = $this->load->view('dashboard', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function getCountries()
    {
		$data = $this->Common_model->get_data('country');
		header("Content-Type: application/json");
		exit(json_encode($data));
	}

	public function getStates($countryId)
    {
		$data = $this->Common_model->get_data('state', ['country_id'=> $countryId]);
		header("Content-Type: application/json");
		exit(json_encode($data));
	}

	public function getCities($stateId)
    {
		$data = $this->Common_model->get_data('city', ['state_id'=> $stateId]);
		header("Content-Type: application/json");
		exit(json_encode($data));
	}

	public function getLists()
    {
        $data = $row = array();

        $memData = $this->Dashboard_model->get_daily_birthday_contacts_list($_POST);
        $get_whats_app_logs=$this->Dashboard_model->get_whats_app_logs();

        //$sent_msg_ids= (!empty($get_whats_app_logs)) ? array_column($get_whats_app_logs,'contact_id') : array();
        $get_user_exist = $this->Common_model->get_data_row('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')));
        
        $i = $_POST['start'];
        foreach($memData as $contact)
        {
            $message = (!empty($get_user_exist)) ? mb_convert_encoding(str_replace('[Name]',$contact->name,$get_user_exist->message), 'UTF-8', 'UTF-8') : '';
            // $i++;
            // $action='<i class="fa fa-check" style="font-size:16px"></i>';
            // if(!in_array($contact->id,$sent_msg_ids))
            // {
               $action='<a data-popup="tooltip" data-placement="top"  title="Whatsapp" href="https://wa.me/'.$contact->phone_number.'?text=' . urlencode($message).'" class="text-success" ><i class="fa fa-whatsapp" style="font-size:16px"></i></a>
                ';
           //}
            $data[] = array($contact->name, $contact->phone_number,$contact->company,$contact->address, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Dashboard_model->daily_bday_count($_POST),
            "recordsFiltered" => $this->Dashboard_model->daily_bday_count($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
    }

    public function save_message()
    {
        if(!empty($_POST))
        {
            $this->form_validation->set_rules('bday_msg', 'Message', 'required|trim');

            $response['success'] = false;
            $response['msg'] = 'Something went wrong. Please try again.';
            if ($this->form_validation->run()) 
            {
                if($this->session->userdata('user_id') > 0)
                {
                    $get_user_exist = $this->Common_model->get_data_row('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')));
                    $message = trim($this->input->post('bday_msg'));
                    if(!empty($get_user_exist))
                    {
                        if( $this->Common_model->update_by('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')),array('message'=>$message,'updated_at'=>date('Y-m-d H:i:s'))))
                        {
                            $response['success'] = true;
                            $response['msg'] = 'Message updated successfully';
                        }
                    }
                    else
                    {
                        if( $this->Common_model->insert_data('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id'),'message'=>$message,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'))))
                        {
                            $response['success'] = true;
                            $response['msg'] = 'Message added successfully';
                        }
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

    // public function send_whatsapp_msg()
    // {
    //     if(!empty($_POST))
    //     {
    //         $this->form_validation->set_rules('message', 'Message', 'required|trim');
    //         $this->form_validation->set_rules('contact_no', 'Contact Number', 'required|trim');

    //         $response['success'] = false;
    //         $response['msg'] = 'Something went wrong. Please try again.';
    //         if ($this->form_validation->run()) 
    //         {
    //             $message = trim($this->input->post('message'));
    //             $contact_id = base64_decode(trim($this->input->post('contact_id')));
    //             $contact_no = trim($this->input->post('contact_no'));
    //             $get_user_exist = $this->Common_model->get_data_row('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')));
    //             if(!empty($get_user_exist))
    //             {
    //                 $message = $get_user_exist->message;
    //             }

    //             if($this->Dashboard_model->send_whatsapp_msg($contact_no,$message))
    //             {
    //                 $this->Common_model->insert_data('whatsapp_logs',array('user_id'=>$this->session->userdata('user_id'),'contact_id'=>$contact_id,'contact_no'=>$contact_no,'status'=>1,'created_at'=>date('Y-m-d H:i:s'))); 

    //                 $response['success'] = true;
    //                 $response['msg'] = 'Message sent successfully';
    //             }
    //         }
    //         else
    //         {
    //             $response['msg']=validation_errors();
    //         }
    //     }
    //     else
    //     {
    //         redirect(base_url());
    //     }  
    // }
}