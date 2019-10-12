<?php
namespace WVHttp;

use Psr\Http\Message\ResponseInterface;

/**
 * Client interface.
 */
interface ClientInterface
{
    // Using Ver. 2.0 of HTTP WebView Protocol Specifications
    const VERSION = '2.0';

    /**
     * Open a session.
     *
     * @param array $params URL parameters
     *
     * @return string
     */
     public function open(array $params = []) : string;

     /**
      * Close and delete a session.
      *
      * @return string
      */
     public function close() : string;

     /**
      * Request camera control privileges.
      *
      * @return string
      */
     public function claim() : string;

     /**
      * Release camera control privileges.
      *
      * @return string
      */
     public function yield() : string;

     /**
      * Retrieves or changes session-specific attributes.
      *
      * @param array $params URL parameters
      *
      * @return string
      */
     public function session(array $params = []) : string;

     /**
      * Requests a JPEG still image.
      *
      * @param string $filename
      * @param array $params URL parameters
      * @param string $path
      *
      * @return ResponseInterface
      */
     public function image($filename, array $params = [], $path = '.') : ResponseInterface;

     /**
      * Controls the camera and external output terminal.
      *
      * @param array $params URL parameters
      *
      * @return string
      */
     public function control(array $params = []) : string;

     /**
      * Retrieves various types of information.
      *
      * @param array $params URL parameters
      *
      * @return string
      */
     public function info(array $params = []) : string;

}
