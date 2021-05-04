<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class RestController extends ResourceController
{
	use ResponseTrait;
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	protected $user = null;
	protected $userMdl = null;

	protected $auth = true;
	protected $https = true;
	protected $format = 'json';
	protected $auth_type = ['JWT', 'KEY', 'BASIC', 'DIGGEST'];
	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */

	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		if ($this->https && !$request->isSecure() && ENVIRONMENT == 'production') {
			force_https();
		}
		$this->userMdl = new \App\Models\UserModel();
		$this->setResponseFormat($this->format);
		$this->init($request, $response);
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
	}

	private function init($request, $response) {
		if ($this->auth) {
			 $authenticationHeader = $request->getServer('HTTP_AUTHORIZATION');
			 try {
				helper('jwt');
				$encodedToken = getJWTFromRequest($authenticationHeader);
				if ($encodedToken == false) {
					return $this->failUnauthorized('Unauthorization');
				}
				validateJWTFromRequest($encodedToken);
				return $request;
			} catch (Exception $e) {
				return Services::response()
					->setJSON(
						[
							'status'=> 401,
							'error' => $e->getMessage()
						]
					)
					->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);

			}
		}
	}
}
