<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


namespace Aimeos\Controller\Frontend\Customer\Decorator;


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

		$this->stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Customer\Standard::class )
			->disableOriginalConstructor()
			->getMock();

		$this->object = new \Aimeos\Controller\Frontend\Customer\Decorator\Example( $this->stub, $this->context );
	}


	protected function tearDown() : void
	{
		unset( $this->context, $this->object, $this->stub );
	}


	public function testCall()
	{
		$stub = $this->getMockBuilder( \Aimeos\Controller\Frontend\Customer\Standard::class )
			->disableOriginalConstructor()
			->onlyMethods( ['__call'] )
			->getMock();

		$object = new \Aimeos\Controller\Frontend\Customer\Decorator\Example( $stub, $this->context );

		$stub->expects( $this->once() )->method( '__call' )->willReturn( true );

		$this->assertTrue( $object->invalid() );
	}


	public function testAdd()
	{
		$this->stub->expects( $this->once() )->method( 'add' );
		$this->assertSame( $this->object, $this->object->add( [] ) );
	}


	public function testAddAddressItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/address' )->create();

		$this->stub->expects( $this->once() )->method( 'addAddressItem' );
		$this->assertSame( $this->object, $this->object->addAddressItem( $item ) );
	}


	public function testAddListItem()
	{
		$listItem = \Aimeos\MShop::create( $this->context, 'customer/lists' )->create();

		$this->stub->expects( $this->once() )->method( 'addListItem' );
		$this->assertSame( $this->object, $this->object->addListItem( 'customer', $listItem ) );
	}


	public function testAddPropertyItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/property' )->create();

		$this->stub->expects( $this->once() )->method( 'addPropertyItem' );
		$this->assertSame( $this->object, $this->object->addPropertyItem( $item ) );
	}


	public function testCreateAddressItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/address' )->create();

		$this->stub->expects( $this->once() )->method( 'createAddressItem' )->willReturn( $item );
		$this->assertInstanceOf( \Aimeos\MShop\Common\Item\Address\Iface::class, $this->object->createAddressItem() );
	}


	public function testCreateListItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/lists' )->create();

		$this->stub->expects( $this->once() )->method( 'createListItem' )->willReturn( $item );
		$this->assertInstanceOf( \Aimeos\MShop\Common\Item\Lists\Iface::class, $this->object->createListItem() );
	}


	public function testCreatePropertyItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/property' )->create();

		$this->stub->expects( $this->once() )->method( 'createPropertyItem' )->willReturn( $item );
		$this->assertInstanceOf( \Aimeos\MShop\Common\Item\Property\Iface::class, $this->object->createPropertyItem() );
	}


	public function testDeleteItem()
	{
		$this->stub->expects( $this->once() )->method( 'delete' );
		$this->assertSame( $this->object, $this->object->delete() );
	}


	public function testDeleteAddressItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/address' )->create();

		$this->stub->expects( $this->once() )->method( 'deleteAddressItem' );
		$this->assertSame( $this->object, $this->object->deleteAddressItem( $item ) );
	}


	public function testDeleteListItem()
	{
		$listItem = \Aimeos\MShop::create( $this->context, 'customer/lists' )->create();

		$this->stub->expects( $this->once() )->method( 'deleteListItem' );
		$this->assertSame( $this->object, $this->object->deleteListItem( 'customer', $listItem ) );
	}


	public function testDeletePropertyItem()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer/property' )->create();

		$this->stub->expects( $this->once() )->method( 'deletePropertyItem' );
		$this->assertSame( $this->object, $this->object->deletePropertyItem( $item ) );
	}


	public function testFind()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer' )->create();

		$this->stub->expects( $this->once() )->method( 'find' )
			->willReturn( $item );

		$this->assertInstanceOf( \Aimeos\MShop\Customer\Item\Iface::class, $this->object->find( 'test' ) );
	}


	public function testGet()
	{
		$item = \Aimeos\MShop::create( $this->context, 'customer' )->create();

		$this->stub->expects( $this->once() )->method( 'get' )
			->willReturn( $item );

		$this->assertInstanceOf( \Aimeos\MShop\Customer\Item\Iface::class, $this->object->get() );
	}


	public function testStore()
	{
		$this->stub->expects( $this->once() )->method( 'store' );
		$this->assertSame( $this->object, $this->object->store() );
	}


	public function testUses()
	{
		$this->assertSame( $this->object, $this->object->uses( ['text'] ) );
	}


	public function testGetController()
	{
		$this->assertSame( $this->stub, $this->access( 'getController' )->invokeArgs( $this->object, [] ) );
	}


	protected function access( $name )
	{
		$class = new \ReflectionClass( \Aimeos\Controller\Frontend\Customer\Decorator\Base::class );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );

		return $method;
	}
}
