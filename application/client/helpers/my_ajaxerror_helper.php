<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * print ajax error
 *
 *
 * @access	public
 * @param	string
 * @return	json
 */
if ( ! function_exists('printAjaxError')) {
	function printAjaxError($field =  '', $message = '') {
		$messageArr = array(
		              'success'=> false,
		              'field'=>   $field,
                      'message'=> $message
                      );
        echo json_encode($messageArr);
        exit;
	}
}

// ------------------------------------------------------------------------

/**
 * print ajax success
 *
 *
 * @access	public
 * @param	string
 * @return	json
 */
if ( ! function_exists('printAjaxSuccess')) {
	function printAjaxSuccess($field =  '', $message = '') {
		$messageArr = array(
		              'success' => true,
		              'field'=>   $field,
                      'message'   => $message
                      );
        echo json_encode($messageArr);
        exit;
	}
}

// ------------------------------------------------------------------------

/**
 * print ajax success
 *
 *
 * @access	public
 * @param	array
 * @return	json
 */
if ( ! function_exists('printAjaxData')) {
	function printAjaxData($data) {
		$messageArr = array(
		              'success' => true,
                      'data'   => $data
                      );
        echo json_encode($messageArr);
        exit;
	}
}
/* End of file html_helper.php */
/* Location: ./application/admin/helpers/My_ajaxerror.php */