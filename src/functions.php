<?php
namespace WVHttp;

/**
 * getKeys from text response
 *
 * @param string $response Full response body
 *
 * @return array
 */
function getKeys(string $reponse) : array
{
	preg_match_all('/(.*)[:,=]=.*/', $response, $matches);
	return $matches[1];
}

/**
 * Runs regex on wvhttp string output and returns match
 *
 * @param string $key Desired key from response body
 * @param string $response Full response body
 *
 * @return string
 */
function getValue(string $key, string $response) : string
{
	preg_match('/^('.$key.')[:,=]=(.*)$/', $response, $match);
	return $match[2];
}
