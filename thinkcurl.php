<?php
namespace zhuyl369\thinkcurl;
class thinkcurl {
	/**
	 * 提交数据 
	 * @param  string $url 请求Url
	 * @param  string $method 请求方式
	 * @param  array/string $headers Headers信息 
	 * @param  array/string $params 请求参数
	 * @return 返回的
	 */
	public function request($url, $method, $headers=null, $params=null){
		if (is_array($params)) {
			$requestString = http_build_query($params);
		} else {
			$requestString = $params ? : '';
		}
		if(empty($params)){
			$requestString=null;
		}

		if (empty($headers)) {
			$headers = array('Content-type: text/json'); 
			//$headers = array("Content-type:application/x-www-form-urlencoded");
		} elseif (!is_array($headers)) {
			parse_str($headers,$headers);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		switch ($method){  
			case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, 1);break;  
			case "POST": curl_setopt($ch, CURLOPT_POST, 1);
						 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
			case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
						 curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
			case "DELETE":  curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");   
							curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);break;  
		}

		$response = curl_exec($ch);
		curl_close($ch);
		if (stristr($response, 'HTTP 404') || $response == '') {
			return array('Error' => '请求错误');
		}

		return $response;
	} 
}
?>