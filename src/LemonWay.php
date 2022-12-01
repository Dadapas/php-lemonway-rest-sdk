<?php

namespace LemonWay;

use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;

class LemonWay
{

	protected $client;

	protected $config;

	protected $cache;

	protected $isProd = false;

	public function __construct(CacheInterface $cache, LemonWayConfig $config)
	{
		$this->client = new Client();
		$this->cache = $cache;
		$this->config = $config;
	}

	public function getEndPoint()
	{

	}

	public function getAccessToken()
	{
		
		return $this->cache->get('LemonWayToken', function(){

			$response = $this->client->request('GET', $this->getEndPoint().'/oauth/api/v1/oauth/token', [
				'form_params'	=> [
					'Grant_type'	=> 'client_credentials'
				],
				'headers'	=> [
					'Accept' => 'application/json;charset=UTF-8',
					'Authorization'	=> 'basic '. $this->config->basicAuth,
				]
			]);

			$dataString = $response->getBody();

			$data = json_decode($dataString, true);

			$this->cache->set('LemonWayToken', $data['access_token'], $data['expires_in']);

			return $data['access_token'];
		});
	}
}
