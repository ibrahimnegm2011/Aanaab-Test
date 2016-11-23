<?php

class ControllerExtensionPaymentBankTransfer extends Controller
{
    public function index()
    {
        $this->load->language('extension/payment/bank_transfer');

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));

        $data['continue'] = $this->url->link('checkout/success');

        return $this->load->view('extension/payment/bank_transfer', $data);
    }

    public function confirm()
    {
        if ($this->session->data['payment_method']['code'] == 'bank_transfer') {
            $this->load->language('extension/payment/bank_transfer');

            $email = ($this->customer->isLogged()) ? $this->customer->getEmail() : $this->session->data['guest']['email'];

            $subject = $this->language->get('text_email_subject');

            $message = $this->language->get('text_email_message');
            $message .= nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));
            $message .= $this->language->get('text_email_message_footer');

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();


            $this->load->model('checkout/order');

            $comment = $this->language->get('text_instruction') . "\n\n";
            $comment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
            $comment .= $this->language->get('text_payment');

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
        }
    }
}