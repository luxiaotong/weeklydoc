<?php

class mail {

	public static function send($to, $path)
	{
		$filename = self::get_basename($path);
		list($subject, ) = explode('.', $filename);
		$conf_dir = $_SERVER[ 'DOCUMENT_ROOT'] . '/conf/application.ini';
		$config = new Yaf_Config_Ini($conf_dir,'account');
                
		$mail = new PHPMailer_phpmailer();
                $mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
                $mail->IsSMTP();                            // 设定使用SMTP服务
                $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
                $mail->SMTPSecure = "ssl";                  // SMTP 安全协议
                $mail->Host       = "smtp.qq.com";       // SMTP 服务器
                $mail->Port       = 465;                    // SMTP服务器的端口号
                $mail->Username   = $config->mail->get("params")->username;;  // SMTP服务器用户名
                $mail->Password   = $config->mail->params->password;        // SMTP服务器密码
                $mail->SetFrom('admin@weeklydoc.cn', 'Weekly No Reply');    // 设置发件人地址和名称
                //$mail->AddReplyTo("邮件回复人地址","邮件回复人名称"); 
                                                            // 设置邮件回复人地址和名称
                $mail->Subject    = "=?UTF-8?B?" . base64_encode($subject) . "?=";      // 设置邮件标题
                $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
                                                            // 可选项，向下兼容考虑
                $mail->MsgHTML('见附件');                         // 设置邮件内容
				list($to_name, ) = explode('@', $to);
                $mail->AddAddress($to, $to_name);
                //$mail->SMTPDebug = 1;
		
                $mail->AddAttachment($path, $filename); // 附件 
                if(!$mail->Send()) {
                    return 0;
                } else {
                    return 1;
		}
	}

    public static function prepareAttachment($path) {

        $rn = "\r\n";

        if (file_exists($path)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $ftype = finfo_file($finfo, $path);
            $file = fopen($path, "r");
            $attachment = fread($file, filesize($path));
            $attachment = chunk_split(base64_encode($attachment));
            fclose($file);

            $msg = 'Content-Type: \'' . $ftype . '\'; name="' . self::get_basename($path) . '"' . $rn;
//            $msg .= "Content-Transfer-Encoding: base64" . $rn;
            $msg .= 'Content-ID: <' . self::get_basename($path) . '>' . $rn;
//            $msg .= 'X-Attachment-Id: ebf7a33f5a2ffca7_0.1' . $rn;
            $msg .= $rn . $attachment . $rn . $rn;

            return $msg;
        } else {
            return false;
        }
    }

    public static function sendMail($to, $subject, $content, $path = '', $cc = '', $bcc = '', $_headers = false) {

        $rn = "\r\n";
        $boundary = md5(rand());
        $boundary_content = md5(rand());

// Headers
        $headers = 'From: Weekly No Reply <weeklydoc@sina.com>' . $rn;
        $headers .= 'Mime-Version: 1.0' . $rn;
        $headers .= 'Content-Type: multipart/related;boundary=' . $boundary . $rn;

        //adresses cc and ci
        if ($cc != '') {
            $headers .= 'Cc: ' . $cc . $rn;
        }
        if ($bcc != '') {
            $headers .= 'Bcc: ' . $cc . $rn;
        }
        $headers .= $rn;

// Message Body
        $msg = $rn . '--' . $boundary . $rn;
        $msg.= "Content-Type: multipart/alternative;" . $rn;
        $msg.= " boundary=\"$boundary_content\"" . $rn;

//Body Mode text
        $msg.= $rn . "--" . $boundary_content . $rn;
        $msg .= 'Content-Type: text/plain; charset=UTF-8' . $rn;
        $msg .= strip_tags($content) . $rn;

//Body Mode Html       
        $msg.= $rn . "--" . $boundary_content . $rn;
        $msg .= 'Content-Type: text/html; charset=UTF-8' . $rn;
        $msg .= 'Content-Transfer-Encoding: quoted-printable' . $rn;
	/*if ($_headers) {
            $msg .= $rn . '<img src=3D"cid:template-H.PNG" />' . $rn;
        }*/
        //equal sign are email special characters. =3D is the = sign
//        $msg .= $rn . nl2br(str_replace("=", "=3D", $content)) . $rn;
        $msg .= $rn . $content . $rn;
        /*if ($_headers) {
            $msg .= $rn . '<img src=3D"cid:template-F.PNG" />' . $rn;
        }*/
        $msg .= $rn . '--' . $boundary_content . '--' . $rn;

//if attachement
        if ($path != '' && file_exists($path)) {
            $conAttached = self::prepareAttachment($path);
            if ($conAttached !== false) {
                $msg .= $rn . '--' . $boundary . $rn;
                $msg .= $conAttached;
            }
        }
       
//other attachement : here used on HTML body for picture headers/footers
	/*
        if ($_headers) {
            $imgHead = dirname(__FILE__) . '/../../../../modules/notification/ressources/img/template-H.PNG';
            $conAttached = self::prepareAttachment($imgHead);
            if ($conAttached !== false) {
                $msg .= $rn . '--' . $boundary . $rn;
                $msg .= $conAttached;
            }
            $imgFoot = dirname(__FILE__) . '/../../../../modules/notification/ressources/img/template-F.PNG';
            $conAttached = self::prepareAttachment($imgFoot);
            if ($conAttached !== false) {
                $msg .= $rn . '--' . $boundary . $rn;
                $msg .= $conAttached;
            }
        }
	*/

// Fin
        $msg .= $rn . '--' . $boundary . '--' . $rn;
	var_dump($msg, $headers);
// Function mail()
        mail($to, $subject, $msg, $headers);
    }

	static public function get_basename($filename)
	{  
		return preg_replace('/^.+[\\\\\\/]/', '', $filename);  
	} 

}

