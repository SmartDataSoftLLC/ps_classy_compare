<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class ClassyCompareUpdater {
	private $store_url = 'https://classydevs.com/';
	private $api_data        = array();
	private $currv = '';

	public function __construct($v) {
		$this->currv = $v;
		$this->notify_update();
	}

	private function notify_update() {
		$todate        = Configuration::get('CLCOMPARE_LICENSE_DATE');
		$today = date('Y-m-d');
		if($today != $todate){
			Configuration::updateValue('CLCOMPARE_LICENSE_DATE', $today);
			$this->classy_compare_get_update();
		}else{
			$version = Configuration::get('CLCOMPARE_STABLE');
			if (version_compare($version, $this->currv, '>')) {
				$this->show_notification($version);
			}
		}
	}

	public function activate_license($key){
		$api_params = array(
			'edd_action' => 'activate_license',
			'item_id'    => 129146,
			'license'    => $key,
			'url'        => _PS_BASE_URL_SSL_,
		);

		$url = $this->store_url . '?' . http_build_query($api_params);
		$response = $this->wp_remote_get(
			$url,
			array(
				'timeout' => 20,
				'headers' => '',
				'header'  => false,
				'json'    => true,
			)
		);
		$responsearray = Tools::jsonDecode($response, true);
		if ($responsearray['success'] && $responsearray['license'] == 'valid') {
			Configuration::updateValue('CLCOMPARE_LICENCE', $key);
			return true;
		} else {
			return false;
		}
	}

	private function classy_compare_get_update() {
		$key        = Configuration::get('CLCOMPARE_LICENCE');
		$api_params = array(
			'edd_action' => "get_version",
			'license'    => $key,
			'item_id'    => 129146,
			'version'    => $this->currv,
			'updatc_url'    => $this->get_updatc_url(),
			'url'        => _PS_BASE_URL_SSL_
		);
		$url        = $this->store_url . '?' . http_build_query( $api_params );
		$response = $this->wp_remote_get(
			$url,
			array(
				'timeout' => 20,
				'headers' => '',
				'header'  => false,
				'json'    => true,
			)
		);
		
		$responsearray = Tools::jsonDecode( $response, true );
		
		if (version_compare($responsearray['stable_version'], $this->currv, '>')) {
			Configuration::updateValue('CLCOMPARE_STABLE', $responsearray['stable_version']);
			$sections = '';
			if(isset($responsearray['sections'])){
				$sections = unserialize( $responsearray['sections'] );
				
				if(isset($sections['changelog'])){
					$changelog = trim($sections['changelog']);
					$changelog = strip_tags($changelog);
					$changelog = Tools::jsonDecode( $changelog, true );
					$changelog = Tools::jsonEncode( $changelog );
					Configuration::updateValue('CLCOMPARE_CHANGELOG', $changelog);
				}
			}
			$this->show_notification($responsearray['stable_version']);
		}
	}
	private function wp_remote_get($url, $args = array())
	{
		return $this->getHttpCurl($url, $args);
	}
	public function get_updatc_url(){
		return Configuration::get('PS_SHOP_EMAIL'); 
	}
	private function getHttpCurl($url, $args)
	{
		global $wp_version;
		if (function_exists('curl_init')) {
			$defaults = array(
				'method'      => 'GET',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(
					'Authorization'   => 'Basic ',
					'Content-Type'    => 'application/x-www-form-urlencoded;charset=UTF-8',
					'Accept-Encoding' => 'x-gzip,gzip,deflate',
				),
				'body'        => array(),
				'cookies'     => array(),
				'user-agent'  => 'Prestashop' . $wp_version,
				'header'      => true,
				'sslverify'   => false,
				'json'        => false,
			);

			$args         = array_merge($defaults, $args);
			$curl_timeout = ceil($args['timeout']);
			$curl         = curl_init();
			if ($args['httpversion'] == '1.0') {
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			} else {
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			}
			curl_setopt($curl, CURLOPT_USERAGENT, $args['user-agent']);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $curl_timeout);
			curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'api=true');
			$ssl_verify = $args['sslverify'];
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl_verify);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, ($ssl_verify === true) ? 2 : false);
			$http_headers = array();
			if ($args['header']) {
				curl_setopt($curl, CURLOPT_HEADER, $args['header']);
				foreach ($args['headers'] as $key => $value) {
					$http_headers[] = "{$key}: {$value}";
				}
			}
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
			if (defined('CURLOPT_PROTOCOLS')) { // PHP 5.2.10 / cURL 7.19.4
				curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
			}
			if (is_array($args['body']) || is_object($args['body'])) {
				$args['body'] = http_build_query($args['body']);
			}
			$http_headers[] = 'Content-Length: ' . strlen($args['body']);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($curl);
			if ($args['json']) {
				return $response;
			}
			$header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$responseHeader = substr($response, 0, $header_size);
			$responseBody   = substr($response, $header_size);
			$error          = curl_error($curl);
			$errorcode      = curl_errno($curl);
			$info           = curl_getinfo($curl);
			curl_close($curl);
			$info_as_response            = $info;
			$info_as_response['code']    = $info['http_code'];
			$info_as_response['message'] = 'OK';
			$response                    = array(
				'body'     => $responseBody,
				'headers'  => $responseHeader,
				'info'     => $info,
				'response' => $info_as_response,
				'error'    => $error,
				'errno'    => $errorcode,
			);
			return $response;
		}
		return false;
	}

	private function show_notification( $v) {
		$msg = 'There is a new version of Classy Compare Module is available.';
		?>
<style>
.classy-update-content-area {
    background-color: #fff;
    border: 1px solid #d3d8db;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.classy-update-content-area a {
    text-transform: capitalize !important;
    margin-left: 20px;
}

.classy-update-logo-and-text {
    display: flex;
    align-items: center;
}

.classy-update-logo-and-text img {
    margin-right: 12px;
}

.classy-update-bt {
    background-color: #624de3 !important;
    border-color: #624de3 !important;

}

.classy-update-bt:hover {
    background-color: #fff !important;
    border-color: #624de3 !important;
    color: #624de3 !important;
}

.classy-update-header-text-and-version h4 {
    line-height: 16px;
    font-size: 16px;
    font-weight: 500;
}

.classy-update-header-text-and-version h6 {
    line-height: 6px;
    font-size: 12px;
    font-weight: 600;
    background: #113167;
    display: table;
    padding: 8px;
    color: white;
    border-radius: 3px;
}

.classy-update-content-area {
    position: relative;
}

.classycompare-update-bt {
    background-color: #113167 !important;
    border-color: #113167 !important;

}

.classycompare-update-bt:hover {
    background-color: #fff !important;
    border-color: #113167 !important;
    color: #113167 !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="classy-update-content-area">
            <div class="classy-update-logo-and-text">
                <img src="<?php echo _MODULE_CLCOMPARE_IMAGE_URL_ . '/logo.png'; ?>" width="50" height="50">
                <div class="classy-update-header-text-and-version">
                    <h4 class="update_msg"><?php echo $msg; ?></h4>
                    <div class="update_vsn_wrappper">
                        <h6 class="update_vsn"><?php echo 'Version: ' . $v; ?></h6>
                    </div>
                </div>
            </div>
            <a href="https://classydevs.com/classy-product-comparison-prestashop/?utm_source=to_update&utm_medium=to_update&utm_campaign=to_update&utm_id=to_update&utm_term=to_update&utm_content=to_update"
                target="_blank" id="crazy_update_bt"
                class="btn btn-primary classycompare-update-bt"><?php echo 'Update To <strong>Version ' . $v . '</strong>'; ?></a>
        </div>
    </div>
</div>
<?php
	}

}