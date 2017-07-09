<?php

namespace Socialvine\Pulse\Providers;

class TwitterProvider extends \Socialvine\Pulse\Providers\AbstractProvider {

    protected $config;
	private $twitter;

    function __construct($config) {
		$this->config = $config;
		
		$this->twitter = new TwitterAPIExchange([
			    'oauth_access_token' => $this->config['access_token'],
			    'oauth_access_token_secret' => $this->config['access_token_secret'],
			    'consumer_key' => $this->config['api_key'],
			    'consumer_secret' => $this->config['api_secret']
			]);
	}
	
	public function followers($id) {

		$url = 'https://api.twitter.com/1.1/users/show.json';
		$getfield = '?screen_name='.$id;
		$requestMethod = 'GET';

		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest())->followers_count;
	}

	public function find($id) {

		$url = 'https://api.twitter.com/1.1/users/show.json';
		$getfield = '?screen_name='.$id;
		$requestMethod = 'GET';

		
		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest());
	}

	public function search($params) {

		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$getfield = $this->prepGetParams($params);;
		$requestMethod = 'GET';
		
		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest());
	}

	private function prepGetParams($params) {
		$queryString = "?";
		$queryString .= "&q=".$params['term'];
		return $queryString;
	}

}