<?php

namespace App\Http\Controllers;

use Validator;
use App\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    function changeTaxInclusion(Request $request){
        $config = Config::find('1');
        if($request->checkboxstatus == "true"){
            $config->tax_inclusion = 1;
        }else if($request->checkboxstatus == "false"){
            $config->tax_inclusion = 0;
        }
        $config->save();
    }
    function getConfigData(){
        $config = Config::find('1');
        $output = array(
            'tax_rate' => $config->tax_rate,
            'tax_inclusion' => $config->tax_inclusion,
            'discount_fixed' => $config->discount_fixed,
            'discount_percent' => $config->discount_percent,
            'discount_type' => $config->discount_type,
        );
        echo \json_encode($output);
    }
    function taxRate(Request $request){
        $config = Config::find('1');
        $config->tax_rate = $request->tax_rate;
        $config->save();
    }
    function discountTypeChange(Request $request){
        $config = Config::find('1');
        if($request->checkboxstatus == "true"){
            $config->discount_type = 1;
            echo ($config->discount_percent);
        }else if($request->checkboxstatus == "false"){
            $config->discount_type = 0;
            echo ($config->discount_fixed);
        }
        $config->save();
    }
    function discountRate(Request $request){
        $config = Config::find('1');
        if($request->discount_type == "true"){
            $config->discount_percent = $request->discount_rate;
        }else{
            $config->discount_fixed = $request->discount_rate;
        }
        $config->save();
    }
}
