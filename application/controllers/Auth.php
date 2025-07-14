<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{
	public function __construct() 
	{
        parent::__construct();
		
        $this->load->model('Auth_model');
    }
	public function index()
	{
		if (is_user_logged_in())
		{
			redirect(base_url());
		}
		$this->load->view('login');
	}

	public function forgot_password()
	{
		if (is_user_logged_in())
		{
			redirect(base_url());
		}

		if ($this->input->post())
		{
			$email=trim($this->input->post('email'));
			$get_user= $this->Common_model->get_data_row('admins',array('email'=>$email));
			
			if (!empty($get_user))
			{
				if ($get_user->status != 1)
				{
					$this->session->set_flashdata('error_msg','Your account is not active. Please contact to your Administrator.');
				}
				else
				{
					$new_pass_key = app_generate_hash();

					$this->Common_model->update_by('admins',array('id'=>$get_user->id), array('new_pass_key'=>$new_pass_key,'new_pass_key_requested'=>date('Y-m-d H:i:s')));
					
					if ($this->db->affected_rows() > 0)
					{
						$reset_password_link = base_url('auth/reset_password/').str_replace('=','',base64_encode($get_user->id)).'/'.$new_pass_key;

						$email_body = '<p>We received a request to reset your password for your account.</p>';

						$email_body .= '<p>If you made this request, click the button below to reset your password:</p>';

						$email_body .= '<div style="text-align: center; margin: 30px 0;">
							<a href="' . $reset_password_link . '" style="
								background-color: #007bff;
								color: #fff;
								padding: 12px 25px;
								text-decoration: none;
								font-weight: bold;
								border-radius: 5px;
								font-family: Arial, sans-serif;
								display: inline-block;">
								Reset Password
											</a>
										</div>';

						$email_body .= "<p>If the button above doesn't work, copy and paste the following link into your browser:</p>";
						$email_body .= '<p style="word-break: break-all;">
							<a href="' . $reset_password_link . '">' . $reset_password_link . '</a>
						</p>';

						$email_body .= "<p>This link is valid for a limited time. If you didn't request a password reset, you can safely ignore this email — no changes will be made.</p>";

						// Wrap in your standard email template (optional)
						$email_view = $this->load->view('emails/common_template', ['email_body' => $email_body], true);

						// Send the email
						$email_send = email_send($email, 'Reset Your Password', $email_view);
						$sent_email = json_decode($email_send, true);
						if ($sent_email['status'])
						{
							$this->session->set_flashdata('success_msg','Check your email for further instructions for resetting your password.');
						}
						else
						{
							$this->session->set_flashdata('error_msg','Error sending email. Please try again.');
						}
					}
					else
					{
						$this->session->set_flashdata('error_msg','Error setting new password.');
					}
				}
			}
			else
			{
				$this->session->set_flashdata('error_msg','Incorrect email.');
			}

			redirect(base_url('auth/forgot_password'));
		}
		$this->load->view('forgot_password');
	}

	public function is_email_exists()
	{
		$email=$this->input->post('email');
		$data=$this->Auth_model->get_data_row('admins',array('email'=>$email));
		if(!empty($data))
		{
			echo json_encode(true);
		}
		else
		{
			echo json_encode(false);
		}
	}

	public function reset_password($user_id = null, $new_pass_key = '')
	{	
		if (($user_id == null) || ($new_pass_key == ''))
		{
			redirect(base_url('auth'));
		}

		$user_id=base64_decode($user_id);
		if (!$this->Auth_model->can_reset_password($user_id, $new_pass_key))
		{
			$this->session->set_flashdata('error_msg', 'Reset Password key expired.');
			redirect(base_url('auth'));
			exit;
		} 

		if ($this->input->post())
		{
			$reset_pass = $this->Auth_model->reset_password($user_id, $new_pass_key, $this->input->post('password'));

			if (is_array($reset_pass) && isset($reset_pass['expired']))
			{
				$this->session->set_flashdata('error_msg','Reset Password key expired.');
			}
			elseif ($reset_pass)
			{
				$this->session->set_flashdata('success_msg','Your password has been reset. You can login now!');
			}
			else
			{
				$this->session->set_flashdata('error_msg','Something went wrong. Please try again.');
				redirect(base_url($this->uri->uri_string()));
				exit;
			}

			redirect(base_url('auth'));
		}

		$this->load->view('reset_password');
	}

	public function login()
	{
		if (is_user_logged_in())
		{
			redirect(base_url());
		}
		
		if(!empty($this->input->post()))
		{
			$response['success']=false;
			$response['msg']='Something went wrong!';

			$email=$this->input->post('email');
			$password=md5($this->input->post('password'));

			$data=$this->Auth_model->get_data_row('admins',array('email'=>$email,'password'=>$password));
			if(!empty($data))
			{
				if($data->role == 2 && $data->status != 1)
				{
					$response['msg']='Your account is not active. Please contact admin.';
				}
				else
				{
					$this->session->set_userdata('user_id', $data->id);
					$this->session->set_userdata('user_name', $data->name);
					$this->session->set_userdata('user_email', $data->email);
					$this->session->set_userdata('user_role', $data->role);
					$this->session->set_userdata('country', $data->country_id);
					$this->session->set_userdata('state', $data->state_id);
					$this->session->set_userdata('city', $data->city_id);

					$response['success']=true;
					$response['msg']='Logged in successfully! Redirect...';
				}
			}
			else
			{
				$response['msg']='Invalid email or password';
			}

			echo json_encode($response);
			exit;
		}
		else
		{
			redirect(base_url());
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_email');
		$this->session->unset_userdata('user_role');
		$this->session->unset_userdata('country');
		$this->session->unset_userdata('state');
		$this->session->unset_userdata('city');
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
