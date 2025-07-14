<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_admins extends MY_Controller 
{
	public function __construct() 
	{
        parent::__construct();
        if(!(isset($_SESSION['user_role']) && $_SESSION['user_role']==1)) 
		{
            $this->session->set_flashdata('error_msg','You do not have permission to access this page.');
            redirect(base_url());
        }
        $this->load->model('Common_model','common');
        $this->load->model('Sub_admin_model');
    }

    public function index()
    {
        $data['title'] = 'Sub Admin List';
        
		$data['content'] = $this->load->view('sub_admins/index', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function add()
    {
        $data['title'] = 'Add Sub Admin';
        $data['sub_admin_id'] ='';
        $data['sub_admin_dtl'] ='';
		$data['content'] = $this->load->view('sub_admins/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function edit($id='')
    {
        $data['title'] = 'Edit Sub Admin';
        if($id != '' && base64_decode($id) > 0)
        {
            $data['sub_admin_id'] =base64_decode($id);
            $data['sub_admin_dtl'] = $this->common->get_data_row('admins',array('id'=>base64_decode($id)));
            if(empty($data['sub_admin_dtl']))
            {
                $this->session->set_flashdata('error_msg','Record not found. Something went wrong. Please try again.');
                redirect(base_url('sub_admins'));
            }
        }
        else
        {
            redirect(base_url('sub_admins'));
        }
		$data['content'] = $this->load->view('sub_admins/create', $data, TRUE);
		$this->load->view('layouts/index', $data);
    }

    public function save_sub_admin()
    {
        if(!empty($_POST))
        {
            if(isset($_POST['sub_admin_id']) && !empty($_POST['sub_admin_id']) && base64_decode($_POST['sub_admin_id']) > 0)
            {
                $id = base64_decode($_POST['sub_admin_id']);
                // Update - exclude current row from unique check
                $this->form_validation->set_rules(
                    'email',
                    'Email',
                    'required|trim|valid_email|callback_unique_email[' . $id . ']'
                );
            } 
            else 
            {
                $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[admins.email]', ['is_unique' => 'This email is already registered.']);
            }
            $this->form_validation->set_rules('email_limit', 'Email Limit', 'required|trim');
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
                $sub_admin_id=base64_decode($this->input->post('sub_admin_id'));
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

                $password=generateRandomPassword();

                $name=ucwords(trim($this->input->post('username')));
                $email=trim($this->input->post('email'));
                $email_limit=trim($this->input->post('email_limit'));
                $country=trim($this->input->post('country'));
                $state=trim($this->input->post('state'));
                $city=trim($this->input->post('city'));

                $data=array(
                    'name'=> $name, 
                    'email'=>$email,  
                    'password'=> md5($password), 
                    'role'=> 2,
                    'email_limit'=> $email_limit,
                    'country_id'=>$country,
                    'state_id'=>$state,
                    'city_id'=>$city,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                );

                if(isset($_POST['sub_admin_id']) && !empty($_POST['sub_admin_id']) && base64_decode($_POST['sub_admin_id']) > 0)
                {
                    $data['status'] = (array_key_exists('status', $_POST) && trim($this->input->post('status')) == 1) ? 1 : 0;
            
                    unset($data['created_at']);
                    if($this->common->update_by('admins',array('id'=>base64_decode($_POST['sub_admin_id'])),$data))
                    {
                        $response['success'] = true;
                        $response['msg'] = 'Sub Admin details updated successfully.';
                    }
                }
                else
                {
                    if($this->common->insert_data('admins',$data))
                    {
                        $get_country=$this->common->get_data_row('country',array('country_id'=>$country));
                        $get_state=$this->common->get_data_row('state',array('state_id'=>$state));
                        $get_city=$this->common->get_data_row('city',array('city_id'=>$city));

                        $country_name= (!empty($get_country)) ? $get_country->name : '';
                        $state_name= (!empty($get_state)) ? $get_state->name : '';
                        $city_name= (!empty($get_city)) ? $get_city->name : '';

                        $email_body='<p>You are registered as a Sub Admin in Leaders Dimension.</p>';

                        $email_body.='<p>Below are the details of your account:</p>';

                        $email_body.='<table>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>Name:</strong></td>';
                        $email_body.='<td>'.$name.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>Email:</strong></td>';
                        $email_body.='<td>'.$email.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>Password:</strong></td>';
                        $email_body.='<td>'.$password.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>Email Limit:</strong></td>';
                        $email_body.='<td>'.$email_limit.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>Country:</strong></td>';
                        $email_body.='<td>'.$country_name.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>State:</strong></td>';
                        $email_body.='<td>'.$state_name.'</td>';
                        $email_body.='</tr>';
                        $email_body.='<tr>';
                        $email_body.='<td><strong>City:</strong></td>';
                        $email_body.='<td>'.$city_name.'</td>';
                        $email_body.='</tr>';
                        $email_body.='</table>';
                        $email_body .= '<br><div style="text-align: center; margin-top: 20px;">';
                        $email_body .= '<a href="' . base_url('auth') . '" style="
                            background-color: #28a745;
                            color: white;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            font-weight: bold;
                            display: inline-block;
                        ">
                            Login
                        </a>';
                        $email_body .= '</div>';

                        $email_view=$this->load->view('emails/common_template',array('email_body'=>$email_body),true);
										
						email_send($email,'Leaders Dimension',$email_view);

                        $response['success'] = true;
                        $response['msg'] = 'Sub Admin added successfully.';
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
        $this->db->where('email', $email);
        $this->db->where('id !=', $id); // Exclude current row
        $query = $this->db->get('admins');

        if ($query->num_rows() > 0) 
        {
            $this->form_validation->set_message('unique_email', 'This email is already registered.');
            return false;
        }

        return true;
    }

    public function getLists()
    {
        $data = $row = array();

        $memData = $this->Sub_admin_model->getRows($_POST);
        
        $i = $_POST['start'];
        foreach($memData as $sub_admin_dtl)
        {
            //$i++;
            $switch = '<input type="checkbox" class="sub-admin-status-switch" 
                  data-id="' .str_replace('=','',base64_encode($sub_admin_dtl->id)) . '" 
                  data-on-text="Yes" 
                  data-off-text="No" 
                  ' . (($sub_admin_dtl->status == 1) ? 'checked' : '') . '>';

            $action='<a href="'.base_url().'sub_admins/edit/'.str_replace('=','',base64_encode($sub_admin_dtl->id)).'" data-popup="tooltip" data-placement="top"  title="Edit"  class="text-info"><i class="icon-pencil7"></i></a>&nbsp;&nbsp;<a data-popup="tooltip" data-placement="top"  title="Delete" href="javascript:void(0);" class="text-danger delete_sub_admin" data-id="'.str_replace('=','',base64_encode($sub_admin_dtl->id)).'"><i class=" icon-trash"></i></a>
             ';
            $data[] = array($sub_admin_dtl->name, $sub_admin_dtl->email, $sub_admin_dtl->email_limit, $switch,$sub_admin_dtl->country_name, $sub_admin_dtl->state_name, $sub_admin_dtl->city_name, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Sub_admin_model->countFiltered($_POST),
            "recordsFiltered" => $this->Sub_admin_model->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
        exit;
    }

    public function update_status()
    {
        $sub_admin_id = base64_decode($this->input->post('sub_admin_id'));

        $get_data=$this->common->get_data_row('admins',array('id'=>$sub_admin_id));

        $response['success']=false;
        $response['msg']='Something went wrong. Please try again.';
        if(!empty($get_data))
        {
            $status = $this->input->post('status');

            if($this->common->update_by('admins',array('id'=>$sub_admin_id),['status' => $status]))
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


    public function delete_sub_admin()
    {
        $sub_admin_id = base64_decode($this->input->post('sub_admin_id'));

        $get_data=$this->common->get_data_row('admins',array('id'=>$sub_admin_id));

        $response['success']=false;
        $response['msg']='Something went wrong. Please try again.';
        if(!empty($get_data))
        {
            $deleted = $this->common->delete_data('admins',array("id"=>$sub_admin_id));
            if ($deleted) 
            {    
               $response['success']=true;
               $response['msg']= 'Sub Admin deleted successfully';
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