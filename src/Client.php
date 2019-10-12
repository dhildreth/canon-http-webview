<?php

namespace WVHttp;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
	/** @var array Default request options */
	protected $config;

	/** @var GuzzleClient Guzzle client object */
	protected $client;

	/**
     * Clients accept an array of constructor parameters.
     *
     * Here's an example of creating a client using a base_uri and an array of
     * default request options to apply to each request:
     *
     *     $client = new Client([
     *         'base_uri'        => 'http://www.foo.com/1.0/',
     *         'timeout'         => 20,
	 *         'auth'            => [
	 *             'username',
	 *             'password'
     *         ]
     *     ]);
     *
	 * @param array $config Client configuration settings.
	 *
	 * @see \GuzzleHttp\RequestOptions for a list of available request options.
	 */
	public function __construct(array $config = [])
	{
		$this->configureDefaults($config);

		$this->client = new GuzzleClient($this->config);
	}

	/**
	 * Returns current session ID.
	 *
	 * @return string
	 */
	public function getSessionId() {
		return $this->config['sessionId'];
	}

	public function open(array $params = []) : string
    {
		$request = $this->getRequest('open.cgi', $params);
		$body = (string) $request->getBody();

		// Find the session information and return
		preg_match('/s:=(.*)/', $body, $session);
		$this->config['sessionId'] = $session[1];

        return $body;
    }

	public function close() : string
	{
		$request = $this->getRequest('close.cgi', [], true);
		$body = (string) $request->getBody();

		if(trim($body) == "OK.") {
			$this->config['sessionId'] = null;
		}

		return $body;
	}

	public function claim() : string
	{
		$request = $this->getRequest('claim.cgi', [], true);
		return (string) $request->getBody();
	}

	public function yield() : string
	{
		$request = $this->getRequest('yield.cgi', [], true);
		return (string) $request->getBody();
	}

	public function session(array $params = []) : string
	{
		$request = $this->getRequest('session.cgi', $params, true);
		return (string) $request->getBody();
	}

	public function image($filename, array $params = [], $path = '.') : ResponseInterface
	{
		$filepath = $path . DIRECTORY_SEPARATOR . $filename;
		return $this->getRequest('image.cgi', $params, true, $filepath);
	}

	public function control(array $params = []) : string
	{
		$request = $this->getRequest('control.cgi', $params, true);
		return (string) $request->getBody();
	}

	public function info(array $params = []) : string
	{
		$request = $this->getRequest('info.cgi', $params, true);
		return (string) $request->getBody();
	}

	/**
	* Configures the default options for a client.
	*
	* @param array $config
	*/
	private function configureDefaults(array $config)
	{
		$defaults = [
			'url_path' => '/-wvhttp-01-/',
			'sessionId' => null,
		];
		$this->config = $config + $defaults;
	}

	/**
	 * Sets up and returns the Guzzle client request
	 *
	 * @param string $endpoint The path after url_path
	 * @param array $params URL parameters
	 * @param boolean $usesSession Specify if endpoint requires session
	 * @param boolean $filename Specify filename to sink
	 *
	 * @return RequestInterface
	 */
	private function getRequest($endpoint, array $params = [], $usesSession = false, $filename = false) : Response
	{
		if ($usesSession) {
			$params = $params + ['s' => $this->config['sessionId']];
		}

		$url = $this->config['url_path'] . $endpoint;

		if (!$filename) {
			return $this->client->request('GET', $url, ['query' => $params]);
		}
		else {
			return $this->client->request('GET', $url, ['query' => $params, 'sink' => $filename]);
		}
	}

}
