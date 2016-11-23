<?php

class ModelExtensionPaymentTstCODXLogistics extends Model
{
    public function getMethod($address, $total)
    {
        if ($this->session->data['shipping_method']['code'] != 'tst_xlogistics.tst_xlogistics') {
            return array();
        }

        $this->load->language('extension/payment/tst_cod_xlogistics');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('tst_cod_xlogistics_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('tst_cod_xlogistics_total') > 0 && $this->config->get('tst_cod_xlogistics_total') > $total) {
            $status = false;
        } elseif (!$this->cart->hasShipping()) {
            $status = false;
        } elseif (!$this->config->get('tst_cod_xlogistics_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => 'tst_cod_xlogistics',
                'title' => $this->language->get('text_title'),
                'terms' => $this->currency->format($this->config->get('tst_cod_xlogistics_fee'), $this->session->data['currency']),
                'sort_order' => $this->config->get('tst_cod_xlogistics_sort_order'),
                'cost' => $this->config->get('tst_cod_xlogistics_fee')
            );
        }

        return $method_data;
    }
}
