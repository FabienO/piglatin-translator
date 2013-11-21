<?php

function pre_r($array = array()) {
    if(PHP_SAPI != 'cli') {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    } else {
        print_r($array);
    }
}