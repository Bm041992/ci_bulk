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
        
        $get_user_exist = $this->Common_model->get_data_row('bday_whats_app_msg',array('user_id'=>$this->session->userdata('user_id')));
        
        $i = $_POST['start'];
        foreach($memData as $contact)
        {
            $message = (!empty($get_user_exist)) ? mb_convert_encoding(str_replace('[Name]',$contact->name,$get_user_exist->message), 'UTF-8', 'UTF-8') : '';
            
            $action='<a data-popup="tooltip" data-placement="top"  title="Whatsapp" href="https://wa.me/'.$contact->phone_number.'?text=' . urlencode($message).'" class="text-success" ><i class="fa fa-whatsapp" style="font-size:16px"></i></a>
                ';
           
            $data[] = array($contact->user_name,$contact->email,$contact->name, $contact->phone_number,$contact->company,$contact->address, $action);
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
}