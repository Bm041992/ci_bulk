<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
        $this->load->model('Common_model','common');
        $this->load->model('Contact_model');
    }

    public function index()
    {
        $data['title'] = 'Contacts';
        
		$data['content'] = $this->load->view('contacts/index', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function add()
    {
        $data['title'] = 'Add Contact';
        $data['contact_id'] ='';
        $data['contact_dtl'] ='';
        $data['contact_emails'] ='';
         $data['contact_phones'] ='';
		$data['content'] = $this->load->view('contacts/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function edit($id='')
    {
        $data['title'] = 'Edit Contact';
        if($id != '' && base64_decode($id) > 0)
        {
            $data['contact_id'] =base64_decode($id);
            $data['contact_dtl'] = $this->common->get_data_row('contacts',array('id'=>base64_decode($id)));
            if(empty($data['contact_dtl']))
            {
                $this->session->set_flashdata('error_msg','Record not found. Something went wrong. Please try again.');
                redirect(base_url('contacts'));
            }
            $data['contact_emails'] = $this->common->get_data('contact_emails',array('contact_id'=>base64_decode($id)),'','id','asc');
            $data['contact_phones'] = $this->common->get_data('contact_phones',array('contact_id'=>base64_decode($id)),'','id','asc');
        }
        else
        {
            redirect(base_url('contacts'));
        }
		$data['content'] = $this->load->view('contacts/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function save_contact()
    {
        if(!empty($_POST))
        {
            $this->form_validation->set_rules('contact_name', 'Name', 'required|trim');
            $this->form_validation->set_rules('birthday', 'Birth Date', 'required|trim');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required|trim');
            $this->form_validation->set_rules('job_title', 'Job title', 'required|trim');
            $this->form_validation->set_rules('contact_address', 'Address', 'required|trim');
            $this->form_validation->set_rules('country', 'Country', 'required|trim');
            $this->form_validation->set_rules('state', 'State', 'required|trim');

            $city_select = $this->input->post('city'); // From city dropdown
        	$city_manual = trim($this->input->post('city_manual')); 
			if ($city_select == 'other') 
            {
				$this->form_validation->set_rules('city_manual', 'Manual City', 'trim|required|max_length[100]');
			} 
            elseif($city_select == '') 
            {
				$this->form_validation->set_rules('city', 'City', 'trim|required');
			}

            $this->form_validation->set_error_delimiters('', '<br>');

            $response['success'] = false;
            $response['msg'] = 'Something went wrong. Please try again.';
            if ($this->form_validation->run()) 
            {
                $user_id=$this->session->userdata('user_id');
                $contact_id=base64_decode($this->input->post('contact_id'));
                $country= trim($this->input->post('country'));
                $state= trim($this->input->post('state'));
                $city ='';
				if(trim($this->input->post('city'))=='other' && $this->input->post('city_manual') != "") 
                {
					$get_city=$this->common->get_data_row('city',array('state_id' => $state,'name' => trim($city_manual)));
					if(!empty($get_city))
					{
						$city = $get_city->city_id;
					}
					else
					{
						$citydata['state_id'] =$state;
						$citydata['name'] = trim($this->input->post('city_manual'));
						$insert_id=$this->common->insert_data('city',$citydata);
						if($insert_id)
						{
							$city = $insert_id;
						}
					}
				} 
                elseif(trim($this->input->post('city')) != 'other') 
                {
					$city = trim($this->input->post('city'));
				}

                $data=array(
                    'user_id'=>$user_id,
                    'name'=>ucwords($this->input->post('contact_name')), 
                    'birthday'=> date('Y-m-d',strtotime($this->input->post('birthday'))), 
                    'company'=>ucwords($this->input->post('company_name')), 
                    'job_title'=>ucwords($this->input->post('job_title')), 
                    'address'=>trim($this->input->post('contact_address')), 
                    'note'=>trim($this->input->post('contact_notes')),
                    'country'=>$country,
                    'state'=>$state,
                    'city'=>$city
                );

                $insert='';
                $update='';
                if($contact_id > 0)
                {
                    if($this->common->update_by('contacts',array('id'=>$contact_id),$data))
                    {
                        $response['success'] = true;
                        $response['msg'] = 'Contact detail updated successfully.';
                        $update=1;
                    }
                }
                else
                {
                    $contact_id=$this->common->insert_data('contacts',$data);
                    if($contact_id > 0)
                    {
                        $response['success'] = true;
                        $response['msg'] = 'Contact detail added successfully.';
                        $insert=1;
                    }
                }

                if(!empty($insert) || !empty($update))
                {
                    if(array_key_exists('contact_email',$_POST))
                    {
                        $this->common->delete_data('contact_emails',array('contact_id'=>$contact_id));
                        foreach($_POST['contact_email'] as $key => $value)
                        {
                            if($value)
                            {
                                $data=array(
                                    'contact_id'=>$contact_id,
                                    'email_type'=>ucfirst($_POST['contact_email_type'][$key]),
                                    'email'=>$value
                                );
                                $this->common->insert_data('contact_emails',$data);
                            }
                        }
                    }

                    if(array_key_exists('contact_type',$_POST))
                    {
                        $this->common->delete_data('contact_phones',array('contact_id'=>$contact_id));
                        foreach($_POST['contact_type'] as $key => $value)
                        {
                            if(isset($_POST['contact_number'][$key]) && !empty($_POST['contact_number'][$key]))
                            {
                                $data=array(
                                    'contact_id'=>$contact_id,
                                    'phone_type'=>ucfirst($value),
                                    'phone_number'=>$_POST['contact_number'][$key]
                                );
                                $this->common->insert_data('contact_phones',$data);
                            }
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

    public function getLists()
    {
        $data = $row = array();

        $memData = $this->Contact_model->getRows($_POST);
        
        //$i = $_POST['start'];
        foreach($memData as $contact_dtl)
        {
            //$i++;
            
            $action='<a href="'.base_url().'contacts/edit/'.str_replace('=','',base64_encode($contact_dtl->id)).'" data-popup="tooltip" data-placement="top"  title="Edit"  class="text-info"><i class="icon-pencil7"></i></a>&nbsp;&nbsp;<a data-popup="tooltip" data-placement="top"  title="Delete" href="javascript:void(0);" class="text-danger delete_contact" data-id="'.str_replace('=','',base64_encode($contact_dtl->id)).'"><i class=" icon-trash"></i></a>
             ';
            
            $birthday= (!in_array($contact_dtl->birthday, array('1970-01-01','0000-00-00',null,''))) ? date('d M Y',strtotime($contact_dtl->birthday)) : '';
            $data[] = array($contact_dtl->name,$birthday,$contact_dtl->company,$contact_dtl->job_title,$contact_dtl->address,date('d M Y H:i',strtotime($contact_dtl->created_at)), $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Contact_model->countFiltered($_POST),
            "recordsFiltered" => $this->Contact_model->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
        exit;
    }

    public function delete_contact()
	{
		if(!empty($_POST))
		{
			$response['success'] = false;
			$response['msg'] = 'Something went wrong. Please try again.';

			$contact_id=base64_decode($this->input->post('contact_id'));

			if($contact_id > 0)
			{
				$get_contact = $this->common->get_data_row('contacts',array('id'=>$contact_id));
				if(!empty($get_contact))
				{
					if($this->common->delete_data('contacts',array('id'=>$contact_id)))
					{
                        $this->common->delete_data('contact_emails',array('contact_id'=>$contact_id));

                        $this->common->delete_data('contact_phones',array('contact_id'=>$contact_id));
                        
						$response['success'] = true;
						$response['msg'] = 'Contact deleted successfully.';
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

    public function import_contacts()
    {
        $data['title'] = 'Import Contact';
        
		$data['content'] = $this->load->view('contacts/import_contact', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function import_contacts_csv()
    {
        // ini_set('max_execution_time', 600);

        $response['success'] = false;
        $response['msg'] = 'Something went wrong. Please try again.';

        if (isset($_FILES['contacts_file']) && !empty($_FILES['contacts_file']['tmp_name']))
        {
            $filename=time().rand(1,999);
            $config['upload_path'] = './assets/csv/';
            $config['allowed_types'] = 'csv';
            $config['file_name'] = $filename;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('contacts_file'))
            {
                $response['msg'] =$this->upload->display_errors();

                echo json_encode($response);
                exit;
            }
            else
            {
                $data = array('upload_data' => $this->upload->data());
                $file_Name = $data['upload_data']['full_path'];

                $csv = array_map('str_getcsv', file($file_Name));
                $header = $csv[0];
                unset($csv[0]); // remove header

                $column_map = array_flip($header); // 'Given Name' => 1, etc.

                $required = ['Name', 'Given Name', 'Family Name'];
                foreach ($required as $req) {
                    if (!in_array($req, $header)) {
                        $response['msg'] = "Missing required column: $req";
                        echo json_encode($response);
                        unlink($file_Name);
                        exit;
                    }
                }

                //$this->db->trans_start();
                $row_no = 1;
                foreach ($csv as $row) 
                {
                    $row_no++;
                    if (empty(array_filter($row))) continue;

                    $this->db->trans_start();

                    $has_phone = false;
                    $has_email = false;

                    foreach ($header as $index => $column_name) {
                        if (strpos($column_name, 'Phone') !== false && strpos($column_name, 'Value') !== false) {
                            $value = trim($row[$index]);
                            if (!empty($value)) {
                                // if (strpos($value, 'E+') !== false) {
                                //     $response['msg'] = "Invalid phone number format (scientific notation) in column: $column_name (Row: $row_no)";
                                //     echo json_encode($response);
                                //     unlink($file_Name);
                                //     exit;
                                // }
                                $has_phone = true;
                                break;
                            }
                        }
                    }

                    foreach ($header as $index => $column_name) {
                        if (strpos($column_name, 'E-mail') !== false && strpos($column_name, 'Value') !== false) {
                            if (!empty(trim($row[$index]))) {
                                $has_email = true;
                                break;
                            }
                        }
                    }

                    if (!$has_phone || !$has_email) {
                        $this->db->trans_rollback();
                        $response['msg'] = "Each contact must have at least one phone and one email. (Row: $row_no)";
                        echo json_encode($response);
                        unlink($file_Name);
                        exit;
                    }

                    // $given_name  = trim($row[$column_map['Given Name']]);
                    // $family_name = trim($row[$column_map['Family Name']]);

                    $contact_data = [
                        'user_id' => $this->session->userdata('user_id'),
                        'name'        => trim($row[$column_map['Name']]),
                        //'given_name'  => $given_name,
                        //'family_name' => $family_name,
                        'birthday'    => isset($column_map['Birthday']) && !empty($row[$column_map['Birthday']]) && !in_array(trim($row[$column_map['Birthday']]), array('1970-01-01','0000-00-00',null,'')) ? date('Y-m-d', strtotime(trim($row[$column_map['Birthday']]))) : null,
                        'note'       => isset($column_map['Notes']) ? trim($row[$column_map['Notes']]) : ''
                    ];

                    $this->db->insert('contacts', $contact_data);
                    $contact_id = $this->db->insert_id();
                    if($contact_id > 0)
                    {
                        // Phones
                        foreach ($header as $index => $column_name) {
                            if (strpos($column_name, 'Phone') !== false && strpos($column_name, 'Value') !== false) {
                                $type_column = str_replace('Value', 'Type', $column_name);
                                $type_index = array_search($type_column, $header);

                                $phone_type = $type_index !== false ? trim($row[$type_index]) : '';
                                $phone_value = trim($row[$index]);

                                if (!empty($phone_value)) {
                                    if (strpos($phone_value, 'E+') !== false) {
                                        // $response['msg'] = "Invalid phone number format (scientific notation) in column: $column_name (Row: $row_no)";
                                        // echo json_encode($response);
                                        // unlink($file_Name);
                                        // exit;
                                        if (preg_match('/[eE]\\+?\\d+$/', $phone_value)) {
                                            // Convert scientific to regular string
                                            $phone_value = number_format((float)$phone_value, 0, '', '');
                                        }
                                    }
                                    // Step 1: Remove extension (x or X)
                                    $main_part = explode('x', strtolower($phone_value))[0];

                                    // Step 2: Allow only +, -, spaces, digits
                                    //$cleaned_number = preg_replace('/[^0-9+\-\s]/', '', trim($main_part));
                                    $cleaned_number = preg_replace('/[^0-9+\-\s\(\)]/', '', trim($main_part));
                                    // Step 3: Validate phone number with country code (1–3 digit code + 7–15 digits)
                                    // if (!preg_match('/^\+\d{1,3}[\s\-]?\d{7,15}$/', $cleaned_number)) {
                                    //     $response['msg'] = "Invalid phone number format in column: $column_name (Row: $row_no). Only international format allowed (e.g., +91 9876543210)";
                                    //     echo json_encode($response);
                                    //     unlink($file_Name);
                                    //     exit;
                                    // }

                                    $this->db->insert('contact_phones', [
                                        'contact_id'   => $contact_id,
                                        'phone_type' => $phone_type,
                                        'phone_number' => $cleaned_number,
                                    ]);
                                }
                            }
                        }

                        // Emails
                        foreach ($header as $index => $column_name) {
                            if (strpos($column_name, 'E-mail') !== false && strpos($column_name, 'Value') !== false) {
                                $type_column = str_replace('Value', 'Type', $column_name);
                                $type_index = array_search($type_column, $header);

                                $email_type = $type_index !== false ? trim($row[$type_index]) : '';
                                $email_value = trim($row[$index]);

                                if (!empty($email_value)) {
                                    if (!filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
                                        $this->db->trans_rollback();
                                        $response['msg'] = "Invalid email format in column: $column_name (Row: $row_no)";
                                        echo json_encode($response);
                                        unlink($file_Name);
                                        exit;
                                    }

                                    $this->db->insert('contact_emails', [
                                        'contact_id'  => $contact_id,
                                        'email_type' => $email_type,
                                        'email' => $email_value,
                                    ]);
                                }
                            }
                        }
                    }
                    $this->db->trans_complete();
                }

                // $this->db->trans_complete(); // Will commit only if no rollback occurred

                // if ($this->db->trans_status() === FALSE) {
                //     $response['msg'] = "CSV import failed.";
                //     echo json_encode($response);
                //     exit;
                // }
                unlink($file_Name);
                $response['success'] = true;
                $response['msg'] = 'Contacts imported successfully.';
            }
        }
        
        echo json_encode($response);
        exit;
    }
}