<?php

function get_cost($original_cost)
{
    $whith_iva =  ($original_cost)*2.0;
    $final_cost = $whith_iva;

    if ($whith_iva < 100) {
        $final_cost = $whith_iva;
    } elseif ($whith_iva < 300) {
        $final_cost = $whith_iva * 0.75;
    } elseif ($whith_iva < 600) {
        $final_cost = $whith_iva * 0.725;
    } elseif ($whith_iva < 1000) {
        $final_cost = $whith_iva * 0.70;
    } elseif ($whith_iva < 1500) {
        $final_cost = $whith_iva * 0.675;
    } else {
        $final_cost = $whith_iva * 0.65;
    }
    $final_cost = $final_cost*1.21;
    return $final_cost;
}



    function get_cost_without_iva($original_cost) {
        $whith_iva =  ($original_cost)*2.0;
        $final_cost = $whith_iva;
        
        if ($whith_iva < 100) {
            $final_cost = $whith_iva;
        } elseif ($whith_iva < 300) {
            $final_cost = $whith_iva * 0.75;
        } elseif ($whith_iva < 600) {
            $final_cost = $whith_iva * 0.725;
        } elseif ($whith_iva < 1000) {
            $final_cost = $whith_iva * 0.70;
        } elseif ($whith_iva < 1500) {
            $final_cost = $whith_iva * 0.675;
        } else {
            $final_cost = $whith_iva * 0.65;
        }
        return $final_cost;
    }
