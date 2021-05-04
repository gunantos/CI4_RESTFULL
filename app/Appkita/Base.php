<?php

namespace Appkita;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use Config\Services;
use Exception;
abstract class Base extends Controller
{
	/**
	 * @var string|null The model that holding this resource's data
	 */
	protected $modelName;

	/**
	 * @var object|null The model that holding this resource's data
	 */
	protected $model;

    protected $user = null;
	protected $userMdl = null;

	protected $auth = ['TOKEN', 'KEY', 'BASIC', 'DIGEST'];
	protected $https = true;
	protected $format = 'json';
    

	/**
	 * @var string| null this AUTHENTICATION PARAMETER
	 * JWT AUTHENTICATION
	 */
	protected $JWT_KEY = '12MlopSLMnouxbqzK&%';
	protected $JWT_AUD = 'https://app-kita.net';
	protected $JWT_ISS = 'https://app-kita.com';
	protected $JWT_TIMEOUT = 3600;
	protected $JWT_USERNAME = 'email';

	/**
	 * @var string|null AUTH TABLE
	 */
	
	protected $COLOUMN_USERNAME = 'email';
	protected $COLOUMN_PASSWORD = 'password';
	protected $COLOUMN_KEY = 'key';


	protected $API_KEY = 'X-API-KEY';

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		$this->setModel($this->modelName);
		$this->userMdl = new \App\Models\UserModel();
	}

	abstract protected function setAuth($auth);
	abstract protected function getAuth();

	protected function failerror($messages  = 'unauthorization', int $status = 401, string $code = null)
	{
		if (! is_array($messages))
		{
			$messages = ['error' => $messages];
		}

		$response = [
			'status'   => $status,
			'error'    => $code ?? $status,
			'messages' => $messages,
		];
		
		header('Content-Type: application/json', true, 404);
		http_response_code(404);
		die(json_encode($response));
		exit();
	}

	/**
	 * Set or change the model this controller is bound to.
	 * Given either the name or the object, determine the other.
	 *
	 * @param object|string|null $which
	 *
	 * @return void
	 */
	public function setModel($which = null)
	{
		// save what we have been given
		if ($which)
		{
			$this->model     = is_object($which) ? $which : null;
			$this->modelName = is_object($which) ? null : $which;
		}

		// make a model object if needed
		if (empty($this->model) && ! empty($this->modelName))
		{
			if (class_exists($this->modelName))
			{
				$this->model = model($this->modelName);
			}
		}

		// determine model name if needed
		if (! empty($this->model) && empty($this->modelName))
		{
			$this->modelName = get_class($this->model);
		}
	}
}
