<?php

function is_active_controller($controller)
{
	$CI = &get_instance();

	if ($CI->router->fetch_class() == $controller)
	{
		return TRUE;
	}

	return FALSE;
}

function is_user_logged_in()
{
	$CI = &get_instance();
	if ($CI->session->userdata('user_id'))
	{
		return TRUE;
	}
	return FALSE;
}

function get_loggedin_user_id()
{
	$CI = &get_instance();
	return $CI->session->userdata('user_id');
}

function app_generate_hash()
{
	return md5(rand().microtime().time().uniqid());
}

function generateRandomPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&';
    $password = '';
    $max_index = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $max_index)];
    }

    return $password;
}

if ( ! function_exists('get_country')) {  
	function get_country($country_id) {
		$CI =& get_instance();

        return $CI->Common_model->get_data_row('country', ['country_id'=> $country_id]);
	}
}

if ( ! function_exists('get_state')) {  
	function get_state($state_id) {
		$CI =& get_instance();

        return $CI->Common_model->get_data_row('state', ['state_id'=> $state_id]);
	}
}

if ( ! function_exists('get_city')) {  
	function get_city($city_id) {
		$CI =& get_instance();

        return $CI->Common_model->get_data_row('city', ['city_id'=> $city_id,'city_status'=>1]);
	}
}

if ( ! function_exists('get_states')) {  
	function get_states($country_id) {
		$CI =& get_instance();

        return $CI->Common_model->get_data('state', ['country_id'=> $country_id]);
	}
}

if ( ! function_exists('get_cities')) 
{  
	function get_cities($state_id) 
	{
		$CI =& get_instance();

        return $CI->Common_model->get_data('city', ['state_id'=> $state_id,'city_status'=>1]);
	}
}

function email_send($email,$subject="Leaders Dimension",$body='',$attachment="",$smtp_config="") 
{
	$ci = & get_instance();

    $ci->load->library('Phpmailer_lib');
    
    // PHPMailer object
    $mail = $ci->phpmailer_lib->load();
   
    $mail->IsSMTP(); // enable SMTP
	//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; // or 587
	$mail->IsHTML(true);
	if($smtp_config == '')
	{
		$mail->Username = "smt9296@gmail.com";
		$mail->Password = "dmtcotnpgnfgfjrt";
		$mail->SetFrom("smt9296@gmail.com");
	}
	else
	{
		$mail->Username = $smtp_config['smtp_username'];
		$mail->Password = base64_decode($smtp_config['app_password']);
		$mail->SetFrom($smtp_config['smtp_username']);
	}
	
	$mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($email);
    // $mail->SMTPDebug = 2;
    // $mail->Debugoutput = 'html';
	// if(!$mail->Send()) 
	// {
	//     echo "Mailer Error: " . $mail->ErrorInfo;
	//  	return false;
	// } 
	// else 
	// {
	//  	return true;
	// }
	if ($mail->Send()) 
	{
		return json_encode(["status" => true]);
	} 
	else 
	{
		return json_encode(["status" => false, "error" => "Mailer Error: " . $mail->ErrorInfo]);
	}
}
?>