<?php
    class Formatter extends CFormatter {

        public $numberFormat=array('decimals'=>2, 'decimalSeparator'=>'.', 'thousandSeparator'=>',');
     

        public function formatNumber($value) {
            if($value === null) return null;    // new
            if($value === '') return '';        // new
            return number_format($value, $this->numberFormat['decimals'], $this->numberFormat['decimalSeparator'], $this->numberFormat['thousandSeparator']);
            }
     

        public function unformatNumber($formatted_number) {
            if($formatted_number === null) return null;
            if($formatted_number === '') return '';
            if(is_float($formatted_number)) return $formatted_number; // only 'unformat' if parameter is not float already
     
            $value = str_replace($this->numberFormat['thousandSeparator'], '', $formatted_number);
            $value = str_replace($this->numberFormat['decimalSeparator'], '.', $value);
            return (float) $value;
        }

    }
?>