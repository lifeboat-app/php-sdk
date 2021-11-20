<?php

namespace Lifeboat\Tests;

use Lifeboat\CurlResponse;
use Lifeboat\Exceptions\InvalidArgumentException;
use Lifeboat\Utils\ArrayLib;
use Lifeboat\Utils\Curl;
use Lifeboat\Utils\URL;
use Lifeboat\Utils\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilsTest
 * @package Lifeboat\Tests
 */
class UtilsTest extends TestCase {

    /**
     * @test
     * @covers \Lifeboat\Utils\ArrayLib::is_associative
     */
    public function test_is_associative_array()
    {
        $this->assertEquals(true, ArrayLib::is_associative(['a' => 1]));
        $this->assertEquals(false, ArrayLib::is_associative([1,2,3]));
        $this->assertEquals(false, ArrayLib::is_associative([]));

        try {
            ArrayLib::is_associative(new \stdClass());
            $this->fail('ArrayLib::is_associative should not accept non array parameters');
        } catch (\TypeError $e) {}

        try {
            ArrayLib::is_associative('123');
            $this->fail('ArrayLib::is_associative should not accept non array parameters');
        } catch (\TypeError $e) {}
    }

    /**
     * @test
     * @covers \Lifeboat\Utils\URL::is_absolute_url
     */
    public function test_is_absolute()
    {
        $this->assertFalse(URL::is_absolute_url('/'));
        $this->assertFalse(URL::is_absolute_url('/123'));
        $this->assertFalse(URL::is_absolute_url('/123?a'));
        $this->assertFalse(URL::is_absolute_url('local.test/123?a'));
        $this->assertFalse(URL::is_absolute_url('/#'));

        $this->assertTrue(URL::is_absolute_url('://local.test'));
        $this->assertTrue(URL::is_absolute_url('https://local.test'));
        $this->assertTrue(URL::is_absolute_url('https://local.test/'));
        $this->assertTrue(URL::is_absolute_url('https://local.test?a'));
        $this->assertTrue(URL::is_absolute_url('https://local.test/?a'));
        $this->assertTrue(URL::is_absolute_url('https://local.test/#'));
        $this->assertTrue(URL::is_absolute_url('https://local.test#'));
        $this->assertTrue(URL::is_absolute_url('https://user:pass@local.test#'));
    }

    /**
     * @test
     * @covers \Lifeboat\Utils\URL::setGetVar
     */
    public function test_set_get_var()
    {
        $rel_path = '/path';
        $this->assertEquals($rel_path . '?var=1', URL::setGetVar('var', 1, $rel_path));

        $rel_path_t = '/path/';
        $this->assertEquals($rel_path_t . '?var=1', URL::setGetVar('var', 1, $rel_path_t));

        $absolute = 'https://test.com';
        $this->assertEquals($absolute . '?var=1', URL::setGetVar('var', 1, $absolute));

        $absolute_t = 'https://test.com/';
        $this->assertEquals($absolute_t . '?var=1', URL::setGetVar('var', 1, $absolute_t));
    }

    /**
     * @test
     * @covers \Lifeboat\Utils\Utils::create_random_string
     */
    public function test_create_random_string()
    {
        // Test the length of the generated string
        $this->assertTrue(strlen(Utils::create_random_string()) === 24);

        // Test that only the supplied characters are used
        $this->assertTrue(intval(Utils::create_random_string(2, '123456789')) > 0);

        // Test the randomness
        $generated = [];
        while (count($generated) < 100000) $generated[] = Utils::create_random_string();
        $count  = count($generated);
        $unique = count(array_unique($generated));
        $random = 100 - ((100 / $count) * $unique);

        // Less than 2% repeat
        $this->assertTrue($random < 2);
    }

    /**
     * @test
     * @covers \Lifeboat\Utils\Curl::__construct
     * @covers \Lifeboat\Utils\Curl::getURL
     * @covers \Lifeboat\Utils\Curl::setURL
     * @covers \Lifeboat\Utils\Curl::addDataParam
     * @covers \Lifeboat\Utils\Curl::getDataParams
     * @covers \Lifeboat\Utils\Curl::addHeader
     * @covers \Lifeboat\Utils\Curl::getHeaders
     * @covers \Lifeboat\Utils\Curl::removeHeader
     * @covers \Lifeboat\Utils\Curl::isFileUpload
     * @covers \Lifeboat\Utils\Curl::setIsFileUpload
     * @covers \Lifeboat\Utils\Curl::removeDataParam
     */
    public function test_curl_construct()
    {
        $params     = ['a' => 1];
        $headers    = ['header' => 'value'];
        $curl       = new Curl('/url', $params, $headers);

        // Remove the default headers
        $curl->removeHeader('Content-Type');
        $curl->removeHeader('X-Requested-By');

        $this->assertEquals('/url', $curl->getURL());
        $this->assertEquals($headers, $curl->getHeaders());
        $this->assertEquals($params, $curl->getDataParams());

        $curl->removeDataParam('a');
        $this->assertEquals([], $curl->getDataParams());

        $this->assertFalse($curl->isFileUpload());
        $curl->setIsFileUpload(true);
        $this->assertTrue($curl->isFileUpload());

        $headers_set = $curl->getHeaders();
        if (!array_key_exists('Content-Type', $headers_set) ||
            $headers_set['Content-Type'] !== 'multipart/form-data') {
            $this->fail('Curl::setIsFileUpload() did not set the correct headers');
        }

        try {
            new Curl('/url', '123', 'abc');
            $this->fail('Curl::__construct parameters 2 and 3 should of type array only');
        } catch (\TypeError $e) {}
    }

    /**
     * @test
     * @covers \Lifeboat\Utils\Curl::setMethod
     * @covers \Lifeboat\Utils\Curl::getMethod
     */
    public function test_set_curl_method()
    {
        $curl = new Curl('/');

        $this->assertEquals('GET', $curl->getMethod());

        // Set method should be case insensitive
        $curl->setMethod('pOsT');
        $this->assertEquals('POST', $curl->getMethod());

        try {
            $curl->setMethod('GET');
            $curl->setMethod('POST');
            $curl->setMethod('PUT');
            $curl->setMethod('DELETE');
        } catch (InvalidArgumentException $e) {
            $this->fail('Curl::setMethod should accept GET,POST,PUT,DELETE');
        }

        try {
            $curl->setMethod('X');
            $this->fail('Curl::setMethod should only accept valid HTTP methods');
        } catch (InvalidArgumentException $e) {}
    }

    /**
     * @test
     *
     * @covers \Lifeboat\CurlResponse::__construct
     * @covers \Lifeboat\CurlResponse::getRaw
     * @covers \Lifeboat\CurlResponse::getHTTPCode
     * @covers \Lifeboat\CurlResponse::isValid
     * @covers \Lifeboat\CurlResponse::isJSON
     * @covers \Lifeboat\CurlResponse::getError
     * @covers \Lifeboat\CurlResponse::getErrors
     */
    public function test_curl_response()
    {
        // Non-JSON
        $response = new CurlResponse(204, 'No Content');
        $this->assertEquals('No Content', $response->getRaw());
        $this->assertEquals(204, $response->getHTTPCode());
        $this->assertTrue($response->isValid());
        $this->assertFalse($response->isJSON());

        // JSON
        $json = new CurlResponse(400, '{"errors":[{"error":"xxx"}]}');
        $this->assertFalse($json->isValid());
        $this->assertTrue($json->isJSON());
        $this->assertEquals([['error' => 'xxx']], $json->getErrors());
        $this->assertEquals(['errors' => [['error' => 'xxx']]], $json->getJSON());
        $this->assertEquals('xxx', $json->getError());
    }
}
