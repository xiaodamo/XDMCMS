<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Validation extends CI_Validation {


	function My_validation()
    {
        parent::CI_Validation();
    }

    // --------------------------------------------------------------------

    /**
	 * 检查日期格式
	 *
	 *
	 */	
	function valid_datetime($str)
	{
		$flag1 = ( ! preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/ix", $str));
		$flag2 = ( ! preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\s?[0-9]{2}:[0-9]{2}:[0-9]{2}$/ix", $str)) ;
		if($flag1 && $flag2){
			return FALSE;
		}else{
			return TRUE;
		}
	}

    // --------------------------------------------------------------------

    /**
	 * 无符号数值
	 *
	 *
	 */	
	function unsigned_numeric($str)
	{
		return (bool)preg_match( '/^[0-9]*\.?[0-9]+$/', $str);
	}

    // --------------------------------------------------------------------

    /**
	 * 不超过最大数
	 *
	 *
	 */	
	function max_num($num, $max)
	{
		if (preg_match("/[^0-9]/", $num) || preg_match("/[^0-9\.]/", $max))
		{
			return FALSE;
		}		
		return ((float)$num > (float)$max) ? FALSE : TRUE;
	}

}
