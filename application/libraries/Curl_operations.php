<?php

class Curl_operations extends CI_Loader
{

    /**
     *
     * Sends a curl request with POST method
     *
     * @param string $url request url
     * @param array $data request parameters
     * @return array
     */
    public function postRequestCurl($url, $data = array()) {
        $datatoSend = $this->encodeJson($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($datatoSend),
            'Content-Type: application/json'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datatoSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        try {
            $strData = curl_exec($ch);
        } catch (Exception $e) {
            
        }

        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = $this->decodeJson($strData);
        curl_close($ch);
        return $result; //*/
    }

    /**
     *
     * converts an array to json string
     *
     * @param array $input input array
     * @return string
     */
    protected function encodeJson($input) {
        $encodedString = '';
        if (is_array($input) && count($input) > 0) {
            $encodedString = json_encode($input);
        }
        return $encodedString;
    }

    /**
     *
     * converts json string to array
     *
     * @param string $input input json string
     * @return array
     */
    protected function decodeJson($input) {
        $outputArray = array();
        if ($input != '') {
            $outputArray = json_decode($input, true);
        }
        return $outputArray;
    }
	
	public function sendEmail($to, $subject, $message, $CC)
	{
		if($to!="" && $subject!="" && $message!=""){

			//ini_set('SMTP','e2ksmtp01.e2k.ad.ge.com');
			ini_set('SMTP','e2ksmtp01.e2k.ad.ge.com');
			ini_set('smtp_port','25');

			$header = "From: SCTM \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";
			$header .= "CC: ".$CC." \r\n";
			if(mail($to, $subject, $message, $header)) {
				//return true;
			} else {
				//return false;
			}
		}
		
		return true;
	}

}
?>