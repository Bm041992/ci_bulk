<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model','common');
    }

    public function send_email()
    {
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);

		$email_data= $this->db->select('cron_emails.*, send_email_contents.email_subject, send_email_contents.email_content, contact_emails.email,admins.id as uid, admins.role, admins.email_limit')
							->from('cron_emails')
                            ->join('send_email_contents', 'send_email_contents.id = cron_emails.email_content_id')
                            ->join('contacts', 'contacts.id = cron_emails.contact_id')
                            ->join('contact_emails', 'contact_emails.contact_id = cron_emails.contact_id')
                            ->join('admins', 'admins.id = cron_emails.user_id')
                            ->where('admins.status',1)
                            ->where('cron_emails.is_sent',0)
                            ->order_by('cron_emails.id','asc')
							->get()
							->result();
        
		if(!empty($email_data))
		{
            $smtp_list=$this->common->get_data_array('smtp_configs',array('status'=>1),'','id','asc');
            if(!empty($smtp_list))
            {
                $email_index = 0;
                $smtp_index = 0;
                $emails_per_smtp = 495;
                //$total_emails = count($email_data);

                $sent_today_by_user = [];
                foreach($email_data as $data)
                {
                    if(!empty($data->email))
                    {
                        $user_id = $data->uid;
                        $role = $data->role;
                        $daily_limit = (int) $data->email_limit;

                        if ($role == 2) 
                        {
                            if (!isset($sent_today_by_user[$user_id])) 
                            {
                                $sent_today_by_user[$user_id] = $this->db
                                    ->where('user_id', $user_id)
                                    ->where('is_sent', 1)
                                    ->where('DATE(updated_at)', date('Y-m-d'))
                                    ->count_all_results('cron_emails');
                            }

                            if ($sent_today_by_user[$user_id] >= $daily_limit) 
                            {
                                continue; // skip this sub-admin's email
                            }
                        }

                        if (!isset($smtp_list[$smtp_index])) 
                        {
                            // No more SMTP configs available
                            break;
                        }
                        
                        $current_smtp = $smtp_list[$smtp_index];
                        // echo "<pre>";
                        // print_r($current_smtp);
                        // echo "</pre>";

                        $email_body = "<div>".$data->email_content."</div>"; 
                
                        $email_view=$this->load->view('emails/email_template',array('email_body'=>$email_body),true);

                        $subject = (!empty($data->email_subject)) ? $data->email_subject : 'Leaders Dimension';

                        $mail_send=email_send($data->email,$subject,$email_view, '',$current_smtp);
                        $response = json_decode($mail_send,true);
                        if ($response['status']) 
                        {
                            // Mark as sent
                            $this->db->where('id', $data->id)->update('cron_emails', [
                                'is_sent' => 1,
                                'smtp_config_id' => $current_smtp['id'],
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                            if ($role == 2) 
                            {
                                $sent_today_by_user[$user_id]++;
                            }
                        } 
                        // else
                        // {
                        //     $this->common->insert_data('log_message',array(
                        //         'email'=>$data->email,
                        //         'contact_id'=>$data->contact_id,
                        //         'smtp_config_id'=>$current_smtp['id'],
                        //         'email_content_id'=>$data->email_content_id,
                        //         'error_info'=>$response['error'],
                        //         'created_at'=>date('Y-m-d H:i:s')
                        //     ));
                        // }

                        $this->common->insert_data('email_logs', [
                            'user_id' => $data->uid,
                            'email' => $data->email,
                            'contact_id' => $data->contact_id,
                            'smtp_config_id' => $current_smtp['id'],
                            'type' => 'campaign', // or 'birthday'
                            'email_content_id'=>$data->email_content_id,
                            'status' => $response['status'] ? 'success' : 'failed',
                            'error_info' => $response['status'] ? '' : $response['error'],
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $email_index++;

                        // Switch to next SMTP after 500 emails
                        if ($email_index % $emails_per_smtp === 0) 
                        {
                            $smtp_index++;
                            if (!isset($smtp_list[$smtp_index])) 
                            {
                                break; // No more SMTPs
                            }
                        }
                    }
                }
            }
        }
    }

    public function send_birthday_emails()
    {
        $today = date('m-d');

        $email_data = $this->db->select('contacts.id as contact_id, contacts.name, contacts.birthday, ce.email, admins.id as uid, admins.role')
            ->from('contacts')
            ->join('(SELECT MIN(id) as min_id, contact_id FROM contact_emails GROUP BY contact_id) as first_email', 'first_email.contact_id = contacts.id')
            ->join('contact_emails ce', 'ce.id = first_email.min_id')
            ->join('admins', 'admins.id = contacts.user_id') 
            ->where('DATE_FORMAT(contacts.birthday, "%m-%d") =', $today)
            ->where('admins.status', 1)
            ->get()
            ->result();
        // echo "<pre>";
        // print_r($email_data);
        // echo "</pre>";
        // exit;
        if (!empty($email_data)) 
        {
            $smtp_list = $this->common->get_data_array('smtp_configs', ['status' => 1], '', 'id', 'asc');
            $default_bday_temlate = $this->common->get_data_row('birthday_templates', ['id' => 1]);

            if (!empty($smtp_list)) 
            {
                $email_index = 0;
                $smtp_index = 0;
                $emails_per_smtp = 495;

                foreach ($email_data as $data) 
                {
                    if (empty($data->email)) continue;

                    if (!isset($smtp_list[$smtp_index])) break;

                    $current_smtp = $smtp_list[$smtp_index];

                    $bt_info = $this->common->get_data_row('bt_smtp_contacts', [
                        'contacts_id' => $data->contact_id,
                        'user_id'     => $data->uid   
                    ]);

                    $template_id='';
                    if (!empty($bt_info)) 
                    {
                        $get_template = $this->common->get_data_row('birthday_templates', ['id' => $bt_info->bt_id]);
                        $template = (!empty($get_template)) ? $get_template : '';
                        $template_id=(!empty($get_template)) ? $get_template->id : '';
                    } 
                    else 
                    {
                        $template = (!empty($default_bday_temlate)) ? $default_bday_temlate : '';
                        $template_id=(!empty($default_bday_temlate)) ? $default_bday_temlate->id : '';
                    }

                    if(!empty($template))
                    {
                        $subject = $default_bday_temlate->email_subject;
                        $email_body = $default_bday_temlate->email_content;
                    }
                    else
                    {
                        // Default subject and email body
                        $subject = '🎉 Happy Birthday from Leaders Dimension!';

                        $email_body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; padding: 30px; text-align: center;">
        
                            <h2 style="color: #FF6F61; margin-bottom: 10px;">🎂 Happy Birthday, ' . htmlspecialchars($data->name) . '!</h2>

                            <p style="font-size: 16px; color: #333;">
                                On behalf of the entire <strong>Leaders Dimension</strong> team, we wish you a day filled with joy, success, and memorable moments!
                            </p>

                            <div style="margin: 30px 0;">
                                <img src="'.base_url('assets/images/bday_img.png').'" alt="Birthday Icon" width="100" height="100">
                            </div>

                            <p style="font-size: 16px; color: #333;">
                                May the year ahead bring you growth, happiness, and all the achievements you aim for. Thank you for being a valued part of our journey!
                            </p>

                            <p style="font-size: 16px; margin-top: 30px; color: #777;">— With warm wishes, <br><strong>The Leaders Dimension Team</strong></p>

                        </div>';
                    }

                    $email_view = $this->load->view('emails/email_template', ['email_body' => $email_body], true);

                    $mail_send = email_send($data->email, $subject, $email_view, '', $current_smtp);
                    $response = json_decode($mail_send, true);

                    if ($response['status']) 
                    {
                        // $this->common->insert_data('log_message', [
                        //     'email' => $data->email,
                        //     'contact_id' => $data->contact_id,
                        //     'smtp_config_id' => $current_smtp['id'],
                        //     'error_info' => $response['error'],
                        //     'type' => 'birthday',
                        //     'created_at' => date('Y-m-d H:i:s')
                        // ]);
                        // }
                        // else
                        // {
                        $email_index++;
                    }

                    $this->common->insert_data('email_logs', [
                        'user_id' => $data->uid,
                        'email' => $data->email,
                        'contact_id' => $data->contact_id,
                        'smtp_config_id' => $current_smtp['id'],
                        'type' => 'birthday', // or 'campaign'
                        'birthday_template_id'=>$template_id,
                        'status' => $response['status'] ? 'success' : 'failed',
                        'error_info' => $response['status'] ? '' : $response['error'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    if ($email_index % $emails_per_smtp === 0) 
                    {
                        $smtp_index++;
                        if (!isset($smtp_list[$smtp_index])) break;
                    }
                }
            }
        }
    }

}

?>