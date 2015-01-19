<?php

function addslashes_array($string, $force = 0, $strip = FALSE) {
    if(!get_magic_quotes_gpc() || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = addslashes_array($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

function trim_array($params)
{
	if ( !empty($params) && is_array($params) ) {
		foreach($params as $key => $value) {
			if ( trim($value) === '' ) {
				unset($params[$key]);
			}
		}
	}
	return $params;
}

/* End of file request */
