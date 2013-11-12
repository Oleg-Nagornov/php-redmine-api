<?php

namespace Redmine\Api;

use Redmine\Client;

/**
 * Abstract class for Api classes
 *
 * @author Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
abstract class AbstractApi
{
    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    protected function get($path)
    {
        return $this->client->get($path);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $data)
    {
        return $this->client->post($path, $data);
    }

    /**
     * {@inheritDoc}
     */
    protected function put($path, $data)
    {
        return $this->client->put($path, $data);
    }

    /**
     * {@inheritDoc}
     */
    protected function delete($path)
    {
        return $this->client->delete($path);
    }

    /**
     * Checks if the variable passed is not null
     *
     * @param mixed $var Variable to be checked
     *
     * @return bool
     */
    protected function isNotNull($var)
    {
        return !is_null($var);
    }

    /**
     * Retrieves all the elements of a given endpoint (even if the
     * total number of elements is greater than 100)
     *
     * @param  string $endpoint API end point
     * @param  array  $params   optional parameters to be passed to the api (offset, limit, ...)
     * @return array  elements found
     */
    protected function retrieveAll($endpoint, array $params = array())
    {
        if (empty($params)) {
            return $this->get($endpoint);
        }
        $defaults = array(
            'limit'  => 25,
            'offset' => 0,
        );
        $params = array_filter(array_merge($defaults, $params));

        $ret = array();

        while ($limit > 0) {
            if ($limit > 100) {
                $_limit = 100;
                $limit -= 100;
            } else {
                $_limit = $limit;
                $limit = 0;
            }
            $params = array(
                'limit'  => $_limit,
                'offset' => $offset
            );
            $ret = array_merge($ret, $this->get($endpoint . '?' . http_build_query($params)));
            $offset += $_limit;
        }

        return $ret;
    }
}
