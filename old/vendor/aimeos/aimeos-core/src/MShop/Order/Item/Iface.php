<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2024
 * @package MShop
 * @subpackage Order
 */


namespace Aimeos\MShop\Order\Item;


/**
 * Interface for all order item implementations.
 *
 * @package MShop
 * @subpackage Order
 */
interface Iface extends \Aimeos\MShop\Common\Item\Iface
{
	/**
	 * Adds a listener to a publisher object.
	 *
	 * @param \Aimeos\MShop\Plugin\Provider\Iface $l Object implementing listener interface
	 * @param string $action Name of the action to listen for
	 * @return \Aimeos\MShop\Order\Item\Iface Publisher object for method chaining
	 */
	 public function attach( \Aimeos\MShop\Plugin\Provider\Iface $l, string $action ) : Iface;

	/**
	 * Removes all attached listeners from the publisher
	 *
	 * @return \Aimeos\MShop\Order\Item\Iface Publisher object for method chaining
	 */
	 public function off() : Iface;

	/**
	 * Returns the order number
	 *
	 * @return string Order number
	 */
	public function getOrderNumber() : string;

	/**
	 * Returns the number of the invoice.
	 *
	 * @return string Invoice number
	 */
	public function getInvoiceNumber() : string;

	/**
	 * Sets the number of the invoice.
	 *
	 * @param string|null $value Invoice number
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setInvoiceNumber( ?string $value ) : \Aimeos\MShop\Common\Item\Iface;

	/**
	 * Returns the channel of the invoice (repeating, web, phone, etc).
	 *
	 * @return string Invoice channel
	 */
	public function getChannel() : string;

	/**
	 * Sets the channel of the invoice.
	 *
	 * @param string|null $channel Invoice channel
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setChannel( ?string $channel ) : \Aimeos\MShop\Common\Item\Iface;

	/**
	 * Returns the delivery date of the invoice.
	 *
	 * @return string|null ISO date in yyyy-mm-dd HH:ii:ss format
	 */
	public function getDateDelivery() : ?string;

	/**
	 * Sets the delivery date of the invoice.
	 *
	 * @param string|null $date ISO date in yyyy-mm-dd HH:ii:ss format
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setDateDelivery( ?string $date ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the payment date of the invoice.
	 *
	 * @return string|null ISO date in yyyy-mm-dd HH:ii:ss format
	 */
	public function getDatePayment() : ?string;

	/**
	 * Sets the payment date of the invoice.
	 *
	 * @param string|null $date ISO date in yyyy-mm-dd HH:ii:ss format
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setDatePayment( ?string $date ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the delivery status of the invoice.
	 *
	 * @return int Status code constant from \Aimeos\MShop\Order\Item\Base
	 */
	public function getStatusDelivery() : int;

	/**
	 * Sets the delivery status of the invoice.
	 *
	 * @param int $status Status code constant from \Aimeos\MShop\Order\Item\Base
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setStatusDelivery( int $status ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the payment status of the invoice.
	 *
	 * @return int Payment constant from \Aimeos\MShop\Order\Item\Base
	 */
	public function getStatusPayment() : ?int;

	/**
	 * Sets the payment status of the invoice.
	 *
	 * @param int $status Payment constant from \Aimeos\MShop\Order\Item\Base
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setStatusPayment( int $status ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the related invoice ID.
	 *
	 * @return string|null Related invoice ID
	 */
	public function getRelatedId() : ?string;

	/**
	 * Sets the related invoice ID.
	 *
	 * @param string|null $id Related invoice ID
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for chaining method calls
	 */
	public function setRelatedId( ?string $id ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Tests if all necessary items are available to create the order.
	 *
	 * @param array $what Type of data
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function check( array $what = ['order/address', 'order/coupon', 'order/product', 'order/service'] ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Notifies listeners before the basket becomes an order.
	 *
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function finish() : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the associated customer item
	 *
	 * @return \Aimeos\MShop\Customer\Item\Iface|null Customer item
	 */
	public function getCustomerItem() : ?\Aimeos\MShop\Customer\Item\Iface;

	/**
	 * Returns the comment field of the order item
	 *
	 * @return string Comment for the order
	 */
	public function getComment() : string;

	/**
	 * Sets the comment field of the order item
	 *
	 * @param string|null $comment Comment for the order
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function setComment( ?string $comment ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the customer code of the customer who has ordered.
	 *
	 * @return string Unique ID of the customer
	 */
	public function getCustomerId() : string;

	/**
	 * Sets the customer code of the customer who has ordered.
	 *
	 * @param string|null $customerid Unique ID of the customer
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function setCustomerId( ?string $customerid ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the customer reference field of the order item
	 *
	 * @return string Customer reference for the order
	 */
	public function getCustomerReference() : string;

	/**
	 * Sets the customer reference field of the order item
	 *
	 * @param string|null $value Customer reference for the order
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function setCustomerReference( ?string $value ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the locales for the basic order item.
	 *
	 * @return \Aimeos\MShop\Locale\Item\Iface Object containing information about site, language, country and currency
	 */
	public function locale() : \Aimeos\MShop\Locale\Item\Iface;

	/**
	 * Sets the locales for the basic order item.
	 *
	 * @param \Aimeos\MShop\Locale\Item\Iface $locale Object containing information about site, language, country and currency
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for chaining method calls
	 */
	public function setLocale( \Aimeos\MShop\Locale\Item\Iface $locale ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns a price item with amounts calculated for the products, shipping costs and rebate.
	 *
	 * @return \Aimeos\MShop\Price\Item\Iface Price Item containing price, shipping, rebate, etc.
	 */
	public function getPrice() : \Aimeos\MShop\Price\Item\Iface;

	/**
	 * Returns the code of the site the order was stored in.
	 *
	 * @return string Site code (or empty string if not available)
	 */
	public function getSiteCode() : string;

	/**
	 * Adds a customer address as billing or delivery address for an order.
	 *
	 * @param \Aimeos\MShop\Order\Item\Address\Iface $address Order address item for the given type
	 * @param string $type Address type defined in \Aimeos\MShop\Order\Item\Address\Base
	 * @param int|null $position Position of the address in the list to overwrite
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function addAddress( \Aimeos\MShop\Order\Item\Address\Iface $address, string $type, ?int $position = null ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Deleted a customer address for billing or delivery of an order.
	 *
	 * @param string $type Address type defined in \Aimeos\MShop\Order\Item\Address\Base
	 * @param int|null $position Position of the address in the list to overwrite
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function deleteAddress( string $type, ?int $position = null ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the billing or delivery address depending on the given type.
	 *
	 * @param string $type Address type defined in \Aimeos\MShop\Order\Item\Address\Base
	 * @param int|null $position Address position in list of addresses
	 * @return \Aimeos\MShop\Order\Item\Address\Iface[]|\Aimeos\MShop\Order\Item\Address\Iface
	 * 	Order address item or list of address items for the requested type
	 */
	public function getAddress( string $type, ?int $position = null );

	/**
	 * Returns all addresses of the (future) order.
	 *
	 * @return \Aimeos\Map Array of \Aimeos\MShop\Order\Item\Address\Iface order address items
	 */
	public function getAddresses() : \Aimeos\Map;

	/**
	 * Replaces all addresses in the current basket with the new ones
	 *
	 * @param \Aimeos\Map|array $map Associative list of order addresses as returned by getAddresses()
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function setAddresses( iterable $map ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Adds a coupon code entered by the customer and the given product item to the basket.
	 *
	 * @param string $code Coupon code
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function addCoupon( string $code ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Removes a coupon from the order.
	 *
	 * @param string $code Coupon code
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function deleteCoupon( string $code ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns all coupon codes and the lists of affected product items.
	 *
	 * @return \Aimeos\Map Associative list of codes and lists of product items implementing \Aimeos\MShop\Order\Item\Product\Iface
	 */
	public function getCoupons() : \Aimeos\Map;

	/**
	 * Sets a coupon code and the given product items in the basket.
	 *
	 * @param string $code Coupon code
	 * @param \Aimeos\MShop\Order\Item\Product\Iface[] $products List of coupon products
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function setCoupon( string $code, iterable $products = [] ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Replaces all coupons in the current basket with the new ones
	 *
	 * @param iterable $map Associative list of order coupons as returned by getCoupons()
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function setCoupons( iterable $map ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Adds an order product item to the (future) order.
	 *
	 * @param \Aimeos\MShop\Order\Item\Product\Iface $item Order product item to be added
	 * @param int|null $position position of the new order product item
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function addProduct( \Aimeos\MShop\Order\Item\Product\Iface $item, ?int $position = null ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Deletes an order product item from the (future) order.
	 *
	 * @param int $position Position id of the order product item
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function deleteProduct( int $position ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the product item of an (future) order specified by its key.
	 *
	 * @param int $key Key returned by getProducts() identifying the requested product
	 * @return \Aimeos\MShop\Order\Item\Product\Iface Product item of an order
	 */
	public function getProduct( int $key ) : \Aimeos\MShop\Order\Item\Product\Iface;

	/**
	 * Returns the product items that are or should be part of an (future) order.
	 *
	 * @return \Aimeos\Map List of order product items implementing \Aimeos\MShop\Order\Item\Product\Iface
	 */
	public function getProducts() : \Aimeos\Map;

	/**
	 * Replaces all products in the current basket with the new ones
	 *
	 * @param \Aimeos\MShop\Order\Item\Product\Iface[] $map Associative list of ordered products as returned by getProducts()
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function setProducts( iterable $map ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Adds an order service item as delivery or payment service to the basket
	 *
	 * @param \Aimeos\MShop\Order\Item\Service\Iface $service Order service item for the given domain
	 * @param string $type Service type constant from \Aimeos\MShop\Order\Item\Service\Base
	 * @param int|null $position Position of the address in the list to overwrite
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function addService( \Aimeos\MShop\Order\Item\Service\Iface $service, string $type, ?int $position = null ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Deletes the delivery or payment service from the basket.
	 *
	 * @param string $type Service type constant from \Aimeos\MShop\Order\Item\Service\Base
	 * @param int|null $position Position of the address in the list to overwrite
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function deleteService( string $type, ?int $position = null ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the order services depending on the given type
	 *
	 * @param string $type Service type constant from \Aimeos\MShop\Order\Item\Service\Base
	 * @param int|null $position Position of the service in the list to retrieve
	 * @return \Aimeos\MShop\Order\Item\Service\Iface[]|\Aimeos\MShop\Order\Item\Service\Iface
	 * 	Order service item or list of items for the requested type
	 * @throws \Aimeos\MShop\Order\Exception If no service for the given type and position is found
	 */
	public function getService( string $type, ?int $position = null );

	/**
	 * Returns all services (delivery, payment, etc.) attached to the shopping basket.
	 *
	 * @return \Aimeos\Map List of \Aimeos\MShop\Order\Item\Service\Iface Order service items
	 */
	public function getServices() : \Aimeos\Map;

	/**
	 * Replaces all services in the current basket with the new ones
	 *
	 * @param \Aimeos\MShop\Order\Item\Service\Iface[] $map Associative list of order services as returned by getServices()
	 * @return \Aimeos\MShop\Order\Item\Iface Order base item for method chaining
	 */
	public function setServices( iterable $map ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Adds a status item to the order
	 *
	 * @param \Aimeos\MShop\Order\Item\Status\Iface $item Order status item
	 * @return \Aimeos\MShop\Order\Item\Iface Order item for method chaining
	 */
	public function addStatus( \Aimeos\MShop\Order\Item\Status\Iface $item ) : \Aimeos\MShop\Order\Item\Iface;

	/**
	 * Returns the status item specified by its type and value
	 *
	 * @param string $type Status type
	 * @param string $value Status value
	 * @return \Aimeos\MShop\Order\Item\Status\Iface Status item of an order
	 * @throws \Aimeos\MShop\Order\Exception If status item is not available
	 */
	public function getStatus( string $type, string $value ) : \Aimeos\MShop\Order\Item\Status\Iface;

	/**
	 * Returns the status items
	 *
	 * @return \Aimeos\Map Associative list of status types as keys and list of
	 *	status value/item pairs implementing \Aimeos\MShop\Order\Status\Iface as values
	 */
	public function getStatuses() : \Aimeos\Map;
}
