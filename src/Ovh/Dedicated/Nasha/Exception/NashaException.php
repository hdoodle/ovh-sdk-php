<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://www.apache.org/licenses/LICENSE-2.0.txt
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

// cloned from vrack 2014-06-29

namespace Ovh\Dedicated\Nasha\Exception;

use Ovh\Common\Exception\InvalidResourceException;
use Ovh\Common\Exception\InvalidSignatureException;

use Guzzle\Http\Message\Response; // for debugging only
use Guzzle\Http\Message\Request;

class ServerException extends \RuntimeException
{
	public function __construct($message = '', $code = 0, $prev)
	{

		$request = $prev->getRequest();
		$response = $prev->getResponse();
		$statusCode = $response->getStatusCode();
		switch ($statusCode) {
			case 404 :
				// Bad Method or Ressource not available
				if (stristr((string)$response->getBody(), 'The object') && stristr((string)$response->getBody(), 'does not exist'))
					throw new InvalidResourceException('Ressource ' . $request->getMethod() . ' ' . $request->getResource() . ' does not exist', 404);

				// Task does not exists
				if ($response->getReasonPhrase() == "The requested object (Tasks) does not exist") {
					$d = explode("/", $request->getPath());
					$taskId = $d[5];
					throw new TaskDoesNotExistsException('There is no task with ID : ' . $taskId . '. for nasha' . $this->getNasna($request->getPath()), 404);
				} else throw $prev;


			case 400 :
				// Bad signature
				if ($response->getReasonPhrase() == "Bad Request - Invalid signature")
					throw new InvalidSignatureException('The request signature is not valid.', 400);

				else throw $prev;


			default :
				throw $prev;
		}

	}

	/**
	 * Return domain from path
	 *
	 * @param string $path
	 * @return string domain
	 */
	private function getDomain($path)
	{
		$d = explode("/", $path);
		return $d[3];
	}


	public function debug()
	{
		$r = new Response();
		var_dump($r->getReasonPhrase());
		$req = new Request();
		$req->getClient();
	}

}
