<?php

class ModelExtensionTotalTstPaymentMethod extends Model
{
    public function getTotal($total)
    {
        if ($this->cart->hasShipping()
            && isset($this->session->data['shipping_method'])
            && isset($this->session->data['payment_method']['cost'])
            && $this->session->data['payment_method']['cost'] > 0
        ) {
            $total['totals'][] = array(
                'code' => 'payment_method',
                'title' => $this->session->data['payment_method']['title'],
                'value' => $this->session->data['payment_method']['cost'],
                'sort_order' => $this->config->get('tst_payment_method_sort_order')
            );

            $total['total'] += $this->session->data['tst_payment_method']['cost'];
        }
    }
}