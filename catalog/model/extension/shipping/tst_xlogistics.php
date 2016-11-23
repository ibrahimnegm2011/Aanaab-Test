<?php

class ModelExtensionShippingTstXLogistics extends Model
{
    function getQuote($address)
    {
        $this->load->language('extension/shipping/tst_xlogistics');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('tst_xlogistics_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if (!$this->config->get('tst_xlogistics_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $quote_data = array();

            $quote_data['tst_xlogistics'] = array(
                'code' => 'tst_xlogistics.tst_xlogistics',
                'title' => $this->language->get('text_description'),
                'cost' => $this->config->get('tst_xlogistics_cost'),
                'tax_class_id' => 0,
                'text' => $this->currency->format($this->config->get('tst_xlogistics_cost'), $this->session->data['currency'])
            );

            $method_data = array(
                'code' => 'tst_xlogistics',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('tst_xlogistics_sort_order'),
                'error' => false
            );
        }

        return $method_data;
    }
}