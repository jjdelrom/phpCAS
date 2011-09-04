<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../source/CAS/ProxyChain/AllowedList.php';


/**
 * Test class for verifying the operation of the proxy-chains validation system
 *
 *
 * Generated by PHPUnit on 2010-09-07 at 13:33:53.
 */
class ProxyChainsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var CAS_Client
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new CAS_ProxyChain_AllowedList;
		$this->list_size_0 = array();
		$this->list_size_1 = array(
			'https://service1.example.com/rest',
		);
		$this->list_size_2 = array(
			'https://service1.example.com/rest',
			'http://service2.example.com/my/path',
		);
		$this->list_size_3 = array(
			'https://service1.example.com/rest',
			'http://service2.example.com/my/path',
			'http://service3.example.com/other/',
		);
		$this->list_size_4 = array(
			'https://service1.example.com/rest',
			'http://service2.example.com/my/path',
			'http://service3.example.com/other/',
			'https://service4.example.com/',
		);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{

	}

/*********************************************************
 * Tests of public (interface) methods
 *********************************************************/
	
	/**
	 * Verify that not configuring any proxies will prevent acccess.
	 */
	public function test_none()
	{
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should prevent proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should prevent proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should prevent proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should prevent proxies in front.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain_Any will work with any URL.
	 */
	public function test_any()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain_Any);
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should allow any proxies in front.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_1), 'Should allow any proxies in front.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_1), 'Should allow any proxies in front.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_1), 'Should allow any proxies in front.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_1), 'Should allow any proxies in front.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will only allow an exact match to the chain.
	 */
	public function test_exact_match_2()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'https://service1.example.com/rest',
			'http://service2.example.com/my/path',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_2), 'Should allow an exact match in length and URL');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_3), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4), 'Should not allow inexact matches in length.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will only allow an exact match to the chain.
	 */
	public function test_exact_match_2_failure()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'https://service1.example.com/rest',
			'http://other.example.com/my/path',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_2), 'Should not allow inexact URL match');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_3), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4), 'Should not allow inexact matches in length.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain_Trusted will allow an exact match or greater length of chain.
	 */
	public function test_trusted_match_2()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain_Trusted(array(
			'https://service1.example.com/rest',
			'http://service2.example.com/my/path',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_2), 'Should allow an exact match in length and URL');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_3), 'Should allow an exact match or greater in length');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_4), 'Should allow an exact match or greater in length');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will match strings as prefixes
	 */
	public function test_prefix_match_3()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'https://service1.example.com/',
			'http://service2.example.com/my',
			'http://service3.example.com/',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_2), 'Should not allow inexact matches in length.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_3), 'Should allow an exact match in length and URL');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4), 'Should not allow inexact matches in length.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will match with Regular expressions
	 */
	public function test_regex_match_2()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'/^https?:\/\/service1\.example\.com\/.*/',
			'/^http:\/\/service[0-9]\.example\.com\/[^\/]+\/path/',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_2), 'Should allow an exact match in length and URL');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_3), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4), 'Should not allow inexact matches in length.');
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will match a mixture of with Regular expressions and plain strings
	 */
	public function test_mixed_regex_match_3()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'https://service1.example.com/',
			'/^http:\/\/service[0-9]\.example\.com\/[^\/]+\/path/',
			'http://service3.example.com/',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0));
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1));
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_2));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_3));
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4));
	}
	
	/**
	 * Verify that using the CAS_ProxyChain_Trusted will match a mixture of with Regular expressions and plain strings
	 */
	public function test_mixed_regex_trusted_3()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain_Trusted(array(
			'https://service1.example.com/',
			'/^http:\/\/service[0-9]\.example\.com\/[^\/]+\/path/',
			'http://service3.example.com/',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0));
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1));
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_2));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_3));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_4));
	}
	
	/**
	 * Verify that using the CAS_ProxyChain will allow regex modifiers
	 */
	public function test_regex_modifiers()
	{
		$this->object->allowProxyChain(new CAS_ProxyChain(array(
			'/^https?:\/\/service1\.EXAMPLE\.com\/.*/i',
			'/^http:\/\/serVice[0-9]\.exa   # A comment
			mple\.com\/[^\/]+\/path/ix',
		)));
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_0), 'Should be ok with no proxies in front.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_1), 'Should not allow inexact matches in length.');
		$this->assertTrue($this->object->isProxyListAllowed($this->list_size_2), 'Should allow modifiers on Regular expressions');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_3), 'Should not allow inexact matches in length.');
		$this->assertFalse($this->object->isProxyListAllowed($this->list_size_4), 'Should not allow inexact matches in length.');
	}
}
