<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('data_br_para_en')) {

    function data_br_para_en($data)
    {
        $array_data = explode('/', $data);

        return $array_data[2] . '-' . $array_data[1] . '-' . $array_data[0];
    }
}

if (!function_exists('data_en_para_br')) {

    function data_en_para_br($data)
    {
        $array_data = explode('-', $data);

        return $array_data[2] . '/' . $array_data[1] . '/' . $array_data[0];
    }
}
