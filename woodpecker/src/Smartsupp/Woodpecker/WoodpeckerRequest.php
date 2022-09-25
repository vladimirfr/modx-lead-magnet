<?php

namespace Smartsupp\Woodpecker;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class WoodpeckerRequest
{

	// Endpoint starts here
	private $baseUrl = "https://api.woodpecker.co/";

	// version
	private $version = 'v1';

	// api key
	private $key;


	public function __construct($key = null, $version = null)
	{
		if (!$key) {
			throw new \Exception("You need an API key");
		}
		if ($version) {
			$this->version = $version;
		}
		$this->key = $key;

	}


	/**
	 * Post
	 *
	 * @param string url Endpoint
	 * @param array data
	 * @return ResponseInterface
	 */
	public function post($url, array $data = [])
	{
		$data = $this->sanitizeBools($data);
		return $this->getClient()->post($url, ['json' => $data]);
	}


	/**
	 * Put
	 *
	 * @param string url Endpoint
	 * @param array data
	 * @return ResponseInterface
	 */
	public function put($url, array $data = [])
	{
		$data = $this->sanitizeBools($data);
		return $this->getClient()->put($url, ['json' => $data]);
	}


	/**
	 * Get
	 *
	 * @param string url Endpoint
	 * @param array data
	 * @return ResponseInterface
	 */
	public function get($url, array $data = [])
	{
		$data = $this->sanitizeBools($data);
		return $this->getClient()->get($url, $data);
	}


	/**
	 * Delete
	 *
	 * @param string url Endpoint
	 * @return ResponseInterface
	 */
	public function delete($url)
	{
		return $this->getClient()->delete($url);
	}


	/**
	 * @return Client
	 */
	private function getClient()
	{
		return new Client([
			'base_uri' => $this->baseUrl . 'rest/' . $this->version . '/',
			'headers' => [
				'Authorization' => $this->key,
			],
		]);
	}


	private function sanitizeBools(array $data = [])
	{
		foreach ($data as $key => $value) {
			if ($value === true) {
				$data[$key] = "true";
			} elseif ($value === false) {
				$data[$key] = "false";
			}
		}
		return $data;
	}

}
