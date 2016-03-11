<?php

namespace League\Flysystem\MediaHaven;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use League\Flysystem\Util;
use Psr\Http\Message\ResponseInterface;

class MediaHavenAdapter extends AbstractAdapter
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var
     */
    private $client;

    /**
     * MediaHavenAdapter constructor.
     * @param string $apiUrl
     * @param string $userName
     * @param string $password
     */
    public function __construct($apiUrl, $userName, $password)
    {
        $this->apiUrl = $apiUrl;
        $this->userName= $userName;
        $this->password = $password;

        $this->client = new Client();
    }

    /**
     * Returns the StatusCode of a blank request.
     *
     * @return int
     */
    public function getServiceStatus()
    {
        $response = $this->search(array(
            'startIndex' => 0,
            'nrOfResults' => 1
        ));

        return $response->getStatusCode();
    }

    public function isServiceResponsive()
    {
        try {
            $this->search(array(
                'startIndex' => 0,
                'nrOfResults' => 1
            ));

            return true;
        } catch (ConnectException $ex) {
            return false;
        }
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        // TODO: Implement write() method.
    }

    /**
     * Write a new file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        // TODO: Implement writeStream() method.
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        // TODO: Implement update() method.
    }

    /**
     * Update a file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {
        // TODO: Implement rename() method.
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {
        // TODO: Implement copy() method.
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return array|bool|null
     */
    public function has($path)
    {
        // TODO: Implement has() method.
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function read($path)
    {
        $response = $this->getItem($path);

        return (array)json_decode($response->getBody());
    }

    /**
     * Read a file as a stream.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function readStream($path)
    {
        $response = $this->getItem($path);

        /** @var \stdClass $result */
        $result =  json_decode($response->getBody());

        return $result->previewImagePath;
    }

    /**
     * List contents of a directory.
     *
     * @param string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        $response = $this->search();

        /** @var \stdClass $result */
        $result = (string)$response->getBody();
        $result = json_decode($result);

        $keys = array_map(function($mediaData) {
            return $mediaData->mediaObjectId;
        }, $result->mediaDataList);

        return $keys;
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }

    /**
     * @param string $key
     * @return ResponseInterface
     */
    private function getItem($key)
    {
        $requestUrl = '/mediahaven-rest-api/resources/media' . '/' . $key;

        return $this->client->request('GET', $this->apiUrl . $requestUrl, array(
            'auth' => array(
                $this->userName,
                $this->password,
            ),
        ));
    }

    /**
     * @param array $queryParams
     * @return ResponseInterface
     */
    private function search(array $queryParams = array())
    {
        $requestUrl = '/mediahaven-rest-api/resources/media';

        return $this->client->request('GET', $this->apiUrl . $requestUrl, array(
            'query' => $queryParams,
            'auth' => array(
                $this->userName,
                $this->password,
            ),
        ));
    }
}
