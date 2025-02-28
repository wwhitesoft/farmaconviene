<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


namespace Aimeos\Controller\Frontend\Attribute\Decorator;


class Example extends Base
{
}


class BaseTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $stub;


	protected function setUp() : void
	{
		$this->context = \TestHelper::context();

		$this->stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Attribute\Standard::class )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\Controller\Frontend\Attribute\Decorator\Example( $this->stub, $this->context );
	}


	protected function tearDown() : void
	{
		unset( $this->context, $this->object, $this->stub );
	}


	public function testCall()
	{
		$stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Attribute\Standard::class )
			->disableOriginalConstructor()
			->onlyMethods( ['__call'] )
			->getMock();

		$object = new \Aimeos\Controller\Frontend\Attribute\Decorator\Example( $stub, $this->context );

		$stub->expects( $this->once() )->method( '__call' )->willReturn( true );

		$this->assertTrue( $object->invalid() );
	}


	public function testAttribute()
	{
		$this->assertSame( $this->object, $this->object->attribute( [1, 3] ) );
	}


	public function testDomain()
	{
		$this->assertSame( $this->object, $this->object->domain( 'catalog' ) );
	}


	public function testCompare()
	{
		$this->assertSame( $this->object, $this->object->compare( '==', 'attribute.code', 'test' ) );
	}


	public function testFind()
	{
		$item = \Aimeos\MShop::create( $this->context, 'attribute' )->create();
		$expected = \Aimeos\MShop\Attribute\Item\Iface::class;

		$this->stub->expects( $this->once() )->method( 'find' )
			->willReturn( $item );

		$this->assertInstanceOf( $expected, $this->object->find( 'test', 'color' ) );
	}


	public function testFunction()
	{
		$this->stub->expects( $this->once() )->method( 'function' )
			->willReturn( 'attribute:prop("type",null,"value")' );

		$str = $this->object->function( 'attribute:prop', ['type', null, 'value'] );
		$this->assertEquals( 'attribute:prop("type",null,"value")', $str );
	}


	public function testGet()
	{
		$item = \Aimeos\MShop::create( $this->context, 'attribute' )->create();
		$expected = \Aimeos\MShop\Attribute\Item\Iface::class;

		$this->stub->expects( $this->once() )->method( 'get' )
			->willReturn( $item );

		$this->assertInstanceOf( $expected, $this->object->get( 1 ) );
	}


	public function testHas()
	{
		$this->assertSame( $this->object, $this->object->has( 'price', 'default', -1 ) );
	}


	public function testParse()
	{
		$this->assertSame( $this->object, $this->object->parse( [] ) );
	}


	public function testProperty()
	{
		$this->assertSame( $this->object, $this->object->property( 'test', 'value' ) );
	}


	public function testSearch()
	{
		$item = \Aimeos\MShop::create( $this->context, 'attribute' )->create();
		$expected = \Aimeos\MShop\Attribute\Item\Iface::class;
		$total = 0;

		$this->stub->expects( $this->once() )->method( 'search' )
			->willReturn( map( [$item] ) );

		$this->assertEquals( [$item], $this->object->search( $total )->toArray() );
	}


	public function testSlice()
	{
		$this->assertSame( $this->object, $this->object->slice( 0, 100 ) );
	}


	public function testSort()
	{
		$this->assertSame( $this->object, $this->object->sort( 'position' ) );
	}


	public function testUses()
	{
		$this->assertSame( $this->object, $this->object->uses( ['text'] ) );
	}


	public function testGetController()
	{
		$result = $this->access( 'getController' )->invokeArgs( $this->object, [] );

		$this->assertSame( $this->stub, $result );
	}


	protected function access( $name )
	{
		$class = new \ReflectionClass( \Aimeos\Controller\Frontend\Attribute\Decorator\Base::class );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );

		return $method;
	}
}
