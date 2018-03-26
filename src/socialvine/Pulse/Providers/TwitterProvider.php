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
    
    public function friends($id) {

		$url = 'https://api.twitter.com/1.1/friends/ids.json';
		$getfield = '?screen_name='.$id;
		$requestMethod = 'GET';

		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest())->followers_count;
	}

    public function likes($id) {
        return $this->friends($id);
    }
	
	public function followers($id) {

		$url = 'https://api.twitter.com/1.1/users/id.json';
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

	public function getRateLimitStatus() {
		$url = 'https://api.twitter.com/1.1/application/rate_limit_status.json';
		$getfield = '';//?resources=statuses';
		$requestMethod = 'GET';

		
		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest());

	}
	public function timeline($params) {
        // $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		// $requestMethod = 'GET';

		// $getfield = '?screen_name=twitterapi&count=2';

		// // $twitter = new TwitterAPIExchange($settings);
		// $response =  $this->twitter->setGetfield($getfield)
		// 	->buildOauth($url, $requestMethod)
		// 	->performRequest();

		// print_r(json_decode($response));
		// die;

        $params['screen_name'] = $params['from'];
		unset($params['from']);
		// $params['q'] = '%22'.$params['q'].'%22' . " -filter:retweets filter:links";
		$query = [];
		foreach($params as $key => $value) {
            array_push($query, $key."=".$value);
		}
        $query = implode('&', $query);
        // die;
        // die;

		// print_r($params);die;
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?'.$query;
		$requestMethod = 'GET';

		// echo $getfield;die;
		
		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest());
    }
    
	public function search($params) {

		// $url = 'https://api.twitter.com/1.1/search/tweets.json';
		// $requestMethod = 'GET';

		// $getfield = '?q=test&geocode=37.781157,-122.398720,1mi&count=100';

		// // $twitter = new TwitterAPIExchange($settings);
		// $response =  $this->twitter->setGetfield($getfield)
		// 	->buildOauth($url, $requestMethod)
		// 	->performRequest();

		// print_r(json_decode($response));
		// die;



		// print_r($params);die;
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$getfield = '?'.$this->prepGetParams($params);;
		$requestMethod = 'GET';

		// echo $getfield;die;
		
		return json_decode($this->twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest());
	}

	private function prepGetParams($params) {
		// print_r($params);die;
		
		$params['q'] = $params['term'] . " -filter:retweets filter:links";
		unset($params['term']);
		// $params['q'] = '%22'.$params['q'].'%22' . " -filter:retweets filter:links";
		$query = [];
		foreach($params as $key => $value) {
			array_push($query, $key."=".$value);
		}
		return implode('&', $query);
		// echo http_build_query($params);die;// $queryString .= "&q=".$params['term'];
		// return $queryString;
	}

}