<?php

namespace App\Helper;

class Backend
{
    public static function badgeProductStatus($productStatus)
    {
        $resultHTML = "";
        if($productStatus == "1"){
            $resultHTML = "<span class='badge badge-success'>In stock</span>";
        } elseif($productStatus == "0"){
            $resultHTML = "<span class='badge badge-danger'>Out of stock</span>";
        }
        return $resultHTML;
    }
}
?>