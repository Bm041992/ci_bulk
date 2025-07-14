<?php
class Email_cron_model extends CI_Model
{
    public function get_today_smtp_email_count()
    {
        $results = $this->db->select('smtp_config_id, COUNT(*) as sent_count')
            ->from('email_logs')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->where('status', 'success')
            ->group_by('smtp_config_id')
            ->get()
            ->result();

        $smtp_usage = [];
        foreach ($results as $row) {
            $smtp_usage[$row->smtp_config_id] = $row->sent_count;
        }
        return $smtp_usage;
    }

    public function send_email()
    {
        $email_data = $this->db->select('cron_emails.*, send_email_contents.email_subject, send_email_contents.email_content, contact_emails.email, admins.id as uid, admins.role, admins.email_limit')
            ->from('cron_emails')
            ->join('send_email_contents', 'send_email_contents.id = cron_emails.email_content_id')
            ->join('contacts', 'contacts.id = cron_emails.contact_id')
            ->join('contact_emails', 'contact_emails.contact_id = cron_emails.contact_id')
            ->join('admins', 'admins.id = cron_emails.user_id')
            ->where('admins.status', 1)
            ->where('cron_emails.is_sent', 0)
            ->order_by('cron_emails.id', 'asc')
            ->get()
            ->result();

        if (!empty($email_data)) {
            $smtp_list = $this->common->get_data_array('smtp_configs', ['status' => 1], '', 'id', 'asc');
            $smtp_usage = $this->get_today_smtp_email_count();
            $smtp_max_limit = 495;
            $sent_today_by_user = [];

            foreach ($email_data as $data) {
                if (empty($data->email)) continue;

                $user_id = $data->uid;
                $role = $data->role;
                $daily_limit = (int)$data->email_limit;

                if ($role == 2) {
                    if (!isset($sent_today_by_user[$user_id])) {
                        $sent_today_by_user[$user_id] = $this->db
                            ->where('user_id', $user_id)
                            ->where('is_sent', 1)
                            ->where('DATE(updated_at)', date('Y-m-d'))
                            ->count_all_results('cron_emails');
                    }

                    if ($sent_today_by_user[$user_id] >= $daily_limit) continue;
                }

                foreach ($smtp_list as $current_smtp) {
                    $current_smtp_id = $current_smtp['id'];
                    if (!isset($smtp_usage[$current_smtp_id])) $smtp_usage[$current_smtp_id] = 0;

                    if ($smtp_usage[$current_smtp_id] >= $smtp_max_limit) continue;

                    $email_body = "<div>" . $data->email_content . "</div>";
                    $email_view = $this->load->view('emails/email_template', ['email_body' => $email_body], true);
                    $subject = !empty($data->email_subject) ? $data->email_subject : 'Leaders Dimension';

                    $mail_send = email_send($data->email, $subject, $email_view, '', $current_smtp);
                    $response = json_decode($mail_send, true);

                    if ($response['status']) {
                        $this->db->where('id', $data->id)->update('cron_emails', [
                            'is_sent' => 1,
                            'smtp_config_id' => $current_smtp_id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        $smtp_usage[$current_smtp_id]++;
                        if ($role == 2) $sent_today_by_user[$user_id]++;
                    }

                    $this->common->insert_data('email_logs', [
                        'user_id' => $data->uid,
                        'email' => $data->email,
                        'contact_id' => $data->contact_id,
                        'smtp_config_id' => $current_smtp_id,
                        'type' => 'campaign',
                        'email_content_id' => $data->email_content_id,
                        'status' => $response['status'] ? 'success' : 'failed',
                        'error_info' => $response['status'] ? '' : $response['error'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    break; // Exit SMTP loop after sending or skipping due to limit
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

        if (!empty($email_data)) {
            $smtp_list = $this->common->get_data_array('smtp_configs', ['status' => 1], '', 'id', 'asc');
            $smtp_usage = $this->get_today_smtp_email_count();
            $smtp_max_limit = 495;
            $default_template = $this->common->get_data_row('birthday_templates', ['id' => 1]);

            foreach ($email_data as $data) {
                if (empty($data->email)) continue;

                $template = $default_template;
                $bt_info = $this->common->get_data_row('bt_smtp_contacts', ['contacts_id' => $data->contact_id, 'user_id' => $data->uid]);

                if (!empty($bt_info)) {
                    $custom_template = $this->common->get_data_row('birthday_templates', ['id' => $bt_info->bt_id]);
                    if (!empty($custom_template)) $template = $custom_template;
                }

                $current_smtp = null;

                // Override SMTP
                if (!empty($template->smtp_config_id)) {
                    $smtp = $this->common->get_data_row_array('smtp_configs', ['id' => $template->smtp_config_id, 'status' => 1]);
                    if (!empty($smtp)) $current_smtp = $smtp;
                }

                // Use from list if no override
                if (!$current_smtp) {
                    foreach ($smtp_list as $smtp) {
                        if (!isset($smtp_usage[$smtp['id']])) $smtp_usage[$smtp['id']] = 0;
                        if ($smtp_usage[$smtp['id']] < $smtp_max_limit) {
                            $current_smtp = $smtp;
                            break;
                        }
                    }
                }

                if (!$current_smtp) continue; // no SMTP available

                $smtp_id = $current_smtp['id'];
                $subject = $template->email_subject ?? '🎉 Happy Birthday from Leaders Dimension!';
                $email_body = $template->email_content ?? '<div>Happy Birthday!</div>';

                $email_view = $this->load->view('emails/email_template', ['email_body' => $email_body], true);
                $mail_send = email_send($data->email, $subject, $email_view, '', $current_smtp);
                $response = json_decode($mail_send, true);

                if ($response['status']) {
                    $smtp_usage[$smtp_id]++;
                }

                $this->common->insert_data('email_logs', [
                    'user_id' => $data->uid,
                    'email' => $data->email,
                    'contact_id' => $data->contact_id,
                    'smtp_config_id' => $smtp_id,
                    'type' => 'birthday',
                    'birthday_template_id' => $template->id ?? null,
                    'status' => $response['status'] ? 'success' : 'failed',
                    'error_info' => $response['status'] ? '' : $response['error'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
