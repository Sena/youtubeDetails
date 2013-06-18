<?php
class youtubeDetails {

	private $key;
	private $data;
	private $embed;
	private $image = array('big' => NULL,
		'small' => NULL);

	public function __construct($url = null) {
		if($url !== NULL) {
			$this->getKey($url);
		}
	}

	public function getImages($url = NULL, $size = 'small'){
		if($url !== NULL) {
			$this->getKey($url);
		}
		$this->image['big'] = 'http://img.youtube.com/vi/' . $this->key . '/0.jpg';
		$this->image['small'] = 'http://i1.ytimg.com/vi/' . $this->key . '/default.jpg';
	    return $this->image;
	}
	public function getEmbed($url = NULL){
		if($url !== NULL) {
			$this->getKey($url);
		}
		return $this->embed = 'http://www.youtube.com/embed/' . $this->key;
	}
	public function getInfor($url = NULL){
		$this->gData($url);
		$this->getImages($url);
		$this->getEmbed($url);

		return array('image' => $this->image,
			'key' => $this->key,
			'embed' => $this->embed,
			'published' => $this->data->published,
			'updated' => $this->data->updated,
			'title' => $this->data->title,
			'author' => array('name' => (string)$this->data->author->name,
				'uri' => (string)$this->data->author->uri));
	}
	private function getKey($url){
	    $url = explode('v=',$url);
	    $url = explode('&',$url[1]);
	    return $this->key = current($url);
	}
	private function gData($url = NULL) {
		if($url !== NULL) {
			$this->getKey($url);
		}
		$curl = curl_init('http://gdata.youtube.com/feeds/api/videos/' . $this->key);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec($curl);
		return $this->data = simplexml_load_string($xml);
	}
}