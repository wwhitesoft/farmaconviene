<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2024
 */


namespace Aimeos\Controller\Frontend\Subscription;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $manager;
	private $object;


	protected function setUp() : void
	{
		\Aimeos\MShop::cache( true );

		$this->context = \TestHelper::context();
		$this->context->setUser( $this->getCustomer() );

		$this->manager = $this->getMockBuilder( '\\Aimeos\\MShop\\Subscription\\Manager\\Standard' )
			->setConstructorArgs( [$this->context] )
			->onlyMethods( ['save', 'type'] )
			->getMock();

		$this->manager->method( 'type' )->willReturn( ['subscription'] );

		\Aimeos\MShop::inject( '\\Aimeos\\MShop\\Subscription\\Manager\\Standard', $this->manager );

		$this->object = new \Aimeos\Controller\Frontend\Subscription\Standard( $this->context );
	}


	protected function tearDown() : void
	{
		\Aimeos\MShop::cache( false );
		unset( $this->object, $this->manager, $this->context );
	}


	public function testCancel()
	{
		$expected = \Aimeos\MShop\Subscription\Item\Iface::class;
		$item = \Aimeos\MShop::create( $this->context, 'subscription' )->create();

		$this->manager->expects( $this->once() )->method( 'save' )
			->willReturn( $item );

		$this->assertInstanceOf( $expected, $this->object->cancel( $this->getSubscription() ) );
	}


	public function testCompare()
	{
		$this->assertEquals( 2, count( $this->object->compare( '>=', 'subscription.datenext', '2000-01-01' )->search() ) );
	}


	public function testGet()
	{
		$expected = \Aimeos\MShop\Subscription\Item\Iface::class;
		$this->assertInstanceOf( $expected, $this->object->get( $this->getSubscription() ) );
	}


	public function testGetException()
	{
		$this->expectException( \Aimeos\Controller\Frontend\Subscription\Exception::class );
		$this->object->get( -1 );
	}


	public function testGetIntervals()
	{
		$this->assertGreaterThan( 0, count( $this->object->getIntervals() ) );
	}


	public function testParse()
	{
		$cond = ['&&' => [['==' => ['subscription.datenext' => '2000-01-01']], ['==' => ['subscription.status' => 1]]]];
		$this->assertEquals( 1, count( $this->object->parse( $cond )->search() ) );
	}


	public function testSave()
	{
		$item = $this->manager->create();
		$expected = \Aimeos\MShop\Subscription\Item\Iface::class;

		$this->manager->expects( $this->once() )->method( 'save' )
			->willReturn( $item );

		$this->assertInstanceOf( $expected, $this->object->save( $item ) );
	}


	public function testSearch()
	{
		$total = 0;
		$items = $this->object->uses( ['order'] )->search( $total );

		$this->assertGreaterThanOrEqual( 2, count( $items ) );
		$this->assertGreaterThanOrEqual( 2, $total );

		foreach( $items as $item ) {
			$this->assertInstanceOf( \Aimeos\MShop\Order\Item\Iface::class, $item->getOrderItem() );
		}
	}


	public function testSlice()
	{
		$this->assertEquals( 1, count( $this->object->slice( 0, 1 )->search() ) );
	}


	public function testSort()
	{
		$this->assertEquals( 2, count( $this->object->sort()->search() ) );
	}


	public function testSortInterval()
	{
		$this->assertEquals( 2, count( $this->object->sort( 'interval' )->search() ) );
	}


	public function testSortGeneric()
	{
		$this->assertEquals( 2, count( $this->object->sort( 'subscription.dateend' )->search() ) );
	}


	public function testSortMultiple()
	{
		$this->assertEquals( 2, count( $this->object->sort( 'subscription.dateend,-subscription.id' )->search() ) );
	}


	public function testUses()
	{
		$this->assertSame( $this->object, $this->object->uses( ['order'] ) );
	}


	protected function getCustomer()
	{
		return \Aimeos\MShop::create( $this->context, 'customer' )->find( 'test@example.com' );
	}


	protected function getSubscription()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'subscription' );
		$search = $manager->filter()->slice( 0, 1 );

		return $manager->search( $search )->first( new \RuntimeException( 'No subscription item found' ) );
	}
}
