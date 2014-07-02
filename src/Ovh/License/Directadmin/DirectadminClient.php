<?php
/**
 * Copyright 2013 Stéphane Depierrepont (aka Toorop)
 *
 * Authors :
 *  - Stéphane Depierrepont (aka Toorop)
 *  - Florian Jensen (aka flosoft) : https://github.com/flosoft
 *  - Gillardeau Thibaut (aka Thibautg16) 
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

namespace Ovh\License\Directadmin;

use Guzzle\Http\Message\Response;
use Ovh\Common\AbstractClient;
use Ovh\Common\Exception\BadMethodCallException;
use Ovh\License\Directadmin\Exception\DirectadminException;

class DirectadminClient extends AbstractClient
{

    /**
     * Get Orderable Versions of DirectAdmin on an IP -- untested
     *
     * @param string $domain
	 * @param string $ip
     * @return string Json
     * @throws Exception\DirectadminException
     * @throws Exception\DirectadminNotFoundException
	 * @throws Exception\BadMethodCallException
     */
    public function getOrderableVersions($domain,$ip)
    {
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing');
		if (!$ip)
			throw new BadMethodCallException('Parameter $ip is missing');

        try {
            $r = $this->get('license/directadmin/' . $domain .'?'.$ip)->send();
        } catch (\Exception $e) {
            throw new DirectadminException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }


    /**
     * Get properties of Directadmin license
     *
     * @param string $domain
     * @return string Json
     * @throws Exception\DirectadminException
     * @throws Exception\DirectadminNotFoundException
	 * @throws Exception\BatMethodCallException
     */
    public function getProperties($domain)
    {
		if (!$domain)
			throw new BadMethodCallException('Parameter $domain is missing');
        try {
            $r = $this->get('license/directadmin/' . $domain)->send();
        } catch (\Exception $e) {
            throw new DirectadminException($e->getMessage(), $e->getCode(), $e);
        }
        return $r->getBody(true);
    }
	
}