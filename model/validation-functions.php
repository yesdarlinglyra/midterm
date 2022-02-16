<?php

function validName($name)
{
    return strlen($name) >= 1;
}

function validOptions($userOptions)
{
    //Store the valid options
    $options = getOptions();

    //Check each selected condiment
    foreach($userOptions as $option) {
        if (!in_array($option, $options)) {
            return false;
        }
    }
    return true;
}