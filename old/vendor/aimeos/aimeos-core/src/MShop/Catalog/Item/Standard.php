<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2024
 * @package MShop
 * @subpackage Catalog
 */


namespace Aimeos\MShop\Catalog\Item;

use \Aimeos\MShop\Common\Item\Config;
use \Aimeos\MShop\Common\Item\ListsRef;


/**
 * Generic interface for catalog items.
 *
 * @package MShop
 * @subpackage Catalog
 */
class Standard
	extends \Aimeos\MShop\Common\Item\Base
	implements \Aimeos\MShop\Catalog\Item\Iface
{
	use Config\Traits, ListsRef\Traits {
		ListsRef\Traits::__clone as __cloneList;
		ListsRef\Traits::getName as getNameList;
	}


	private \Aimeos\MW\Tree\Node\Iface $node;
	private array $deletedItems = [];
	private array $children;


	/**
	 * Initializes the catalog item.
	 *
	 * @param \Aimeos\MW\Tree\Node\Iface $node Tree node
	 * @param array $values Assoicative list of key/value pairs
	 * @param \Aimeos\MShop\Catalog\Item\Iface[] $children List of children of the item
	 * @param \Aimeos\MShop\Common\Item\Lists\Iface[] $listItems List of list items
	 * @param \Aimeos\MShop\Common\Item\Iface[] $refItems List of referenced items
	 */
	public function __construct( \Aimeos\MW\Tree\Node\Iface $node, array $values = [], array $children = [],
		array $listItems = [], array $refItems = [] )
	{
		parent::__construct( '', $values );

		map( $children )->implements( \Aimeos\MShop\Catalog\Item\Iface::class, true );

		$this->initListItems( $listItems, $refItems );
		$this->children = $children;
		$this->node = $node;
	}


	/**
	 * Clones internal objects of the catalog item.
	 */
	public function __clone()
	{
		parent::__clone();
		$this->__cloneList();
		$this->node = clone $this->node;
	}


	/**
	 * Tests if the item property for the given name is available
	 *
	 * @param string $name Name of the property
	 * @return bool True if the property exists, false if not
	 */
	public function __isset( string $name ) : bool
	{
		if( $name === 'children' ) {
			return true;
		}

		return parent::__isset( $name ) ?: isset( $this->node->$name );
	}


	/**
	 * Returns the item property for the given name
	 *
	 * @param string $name Name of the property
	 * @param mixed $default Default value if property is unknown
	 * @return mixed|null Property value or default value if property is unknown
	 */
	public function get( string $name, $default = null )
	{
		if( $name === 'children' ) {
			return $this->children;
		}

		if( ( $value = parent::get( $name ) ) !== null ) {
			return $value;
		}

		return $this->node->$name ?? $default;
	}


	/**
	 * Returns the unique ID of the node.
	 *
	 * @return string|null Unique ID of the node
	 */
	public function getId() : ?string
	{
		return $this->node->getId();
	}


	/**
	 * Sets the unique ID of the node.
	 *
	 * @param string|null $id Unique ID of the node
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setId( ?string $id = null ) : \Aimeos\MShop\Common\Item\Iface
	{
		$this->node->setId( $id );
		return $this;
	}


	/**
	 * Returns the site ID of the item.
	 *
	 * @return string Site ID of the item
	 */
	public function getSiteId() : string
	{
		return (string) $this->node->siteid;
	}


	/**
	 * Returns the internal name of the item.
	 *
	 * @return string Name of the item
	 */
	public function getLabel() : string
	{
		return $this->node->getLabel();
	}


	/**
	 * Sets the new internal name of the item.
	 *
	 * @param string $name New name of the item
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setLabel( string $name ) : \Aimeos\MShop\Common\Item\Tree\Iface
	{
		$this->node->setLabel( $name );
		return $this;
	}


	/**
	 * Returns the localized text type of the item or the internal label if no name is available.
	 *
	 * @param string $type Text type to be returned
	 * @param string|null $langId Two letter ISO Language code of the text
	 * @return string Specified text type or label of the item
	 */
	public function getName( string $type = 'name', ?string $langId = null ) : string
	{
		$name = $this->getNameList( $type, $langId );

		if( $type === 'url' && $name === $this->getLabel() ) {
			return $this->getUrl();
		}

		return $name;
	}


	/**
	 * Returns the URL segment for the catalog item.
	 *
	 * @return string URL segment of the catalog item
	 */
	public function getUrl() : string
	{
		return (string) ( $this->node->url ?: \Aimeos\Base\Str::slug( $this->getLabel() ) );
	}


	/**
	 * Sets a new URL segment for the catalog.
	 *
	 * @param string|null $url New URL segment of the catalog item
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setUrl( ?string $url ) : \Aimeos\MShop\Catalog\Item\Iface
	{
		$this->node->url = (string) $url;
		return $this;
	}


	/**
	 * Returns the config property of the catalog.
	 *
	 * @return array Returns the config of the catalog node
	 */
	public function getConfig() : array
	{
		return (array) parent::get( 'config', [] );
	}


	/**
	 * Sets the config property of the catalog item.
	 *
	 * @param array $options Options to be set for the catalog node
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setConfig( array $options ) : \Aimeos\MShop\Common\Item\Iface
	{
		parent::set( 'config', $options );
		return $this;
	}


	/**
	 * Returns the code of the item.
	 *
	 * @return string Code of the item
	 */
	public function getCode() : string
	{
		return $this->node->getCode();
	}


	/**
	 * Sets the new code of the item.
	 *
	 * @param string $code New code of the item
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setCode( string $code ) : \Aimeos\MShop\Common\Item\Tree\Iface
	{
		$this->node->setCode( $this->checkCode( $code ) );
		return $this;
	}


	/**
	 * Returns the status of the item.
	 *
	 * @return int Greater than zero if enabled, zero or negative values if disabled
	 */
	public function getStatus() : int
	{
		return $this->node->getStatus();
	}


	/**
	 * Sets the new status of the item.
	 *
	 * @param int $status True if enabled, false if not
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setStatus( int $status ) : \Aimeos\MShop\Common\Item\Iface
	{
		$this->node->setStatus( $status );
		return $this;
	}


	/**
	 * Returns the URL target specific for that category
	 *
	 * @return string URL target specific for that category
	 */
	public function getTarget() : string
	{
		return (string) $this->node->target;
	}


	/**
	 * Sets a new URL target specific for that category
	 *
	 * @param string $value New URL target specific for that category
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function setTarget( ?string $value ) : \Aimeos\MShop\Catalog\Item\Iface
	{
		$this->node->target = (string) $value;
		return $this;
	}


	/**
	 * Returns modify date/time of the order item base product.
	 *
	 * @return string|null Returns modify date/time of the order base item
	 */
	public function getTimeModified() : ?string
	{
		return $this->node->mtime;
	}


	/**
	 * Returns the create date of the item.
	 *
	 * @return string|null ISO date in YYYY-MM-DD hh:mm:ss format
	 */
	public function getTimeCreated() : ?string
	{
		return $this->node->ctime;
	}


	/**
	 * Returns the editor code of editor who created/modified the item at last.
	 *
	 * @return string Editor who created/modified the item at last
	 */
	public function editor() : string
	{
		return (string) $this->node->editor;
	}


	/**
	 * Adds a child node to this node.
	 *
	 * @param \Aimeos\MShop\Common\Item\Tree\Iface $item Child node to add
	 * @return \Aimeos\MShop\Common\Item\Tree\Iface Tree item for chaining method calls
	 */
	public function addChild( \Aimeos\MShop\Common\Item\Tree\Iface $item ) : \Aimeos\MShop\Common\Item\Tree\Iface
	{
		// don't set the modified flag as it's only for the values
		$this->children[] = $item;

		return $this;
	}


	/**
	 * Removes a child node from this node.
	 *
	 * @param \Aimeos\MShop\Common\Item\Tree\Iface $item Child node to remove
	 * @return \Aimeos\MShop\Common\Item\Tree\Iface Tree item for chaining method calls
	 */
	public function deleteChild( \Aimeos\MShop\Common\Item\Tree\Iface $item ) : \Aimeos\MShop\Common\Item\Tree\Iface
	{
		foreach( $this->children as $idx => $child )
		{
			if( $child === $item )
			{
				$this->deletedItems[] = $item;
				unset( $this->children[$idx] );
			}
		}

		return $this;
	}


	/**
	 * Returns a child of this node identified by its index.
	 *
	 * @param int $index Index of child node
	 * @return \Aimeos\MShop\Catalog\Item\Iface Selected node
	 */
	public function getChild( int $index ) : \Aimeos\MShop\Common\Item\Tree\Iface
	{
		if( isset( $this->children[$index] ) ) {
			return $this->children[$index];
		}

		throw new \Aimeos\MShop\Catalog\Exception( sprintf( 'Child node with index "%1$d" not available', $index ) );
	}


	/**
	 * Returns all children of this node.
	 *
	 * @return \Aimeos\Map Numerically indexed list of children implementing \Aimeos\MShop\Catalog\Item\Iface
	 */
	public function getChildren() : \Aimeos\Map
	{
		return map( $this->children );
	}


	/**
	 * Returns the deleted children.
	 *
	 * @return \Aimeos\Map List of removed children implementing \Aimeos\MShop\Catalog\Item\Iface
	 */
	public function getChildrenDeleted() : \Aimeos\Map
	{
		return map( $this->deletedItems );
	}


	/**
	 * Tests if a node has children.
	 *
	 * @return bool True if node has children, false if not
	 */
	public function hasChildren() : bool
	{
		if( count( $this->children ) > 0 ) {
			return true;
		}

		return $this->node->hasChildren();
	}


	/**
	 * Returns the internal node.
	 *
	 * For internal use only!
	 *
	 * @return \Aimeos\MW\Tree\Node\Iface Internal node object
	 */
	public function getNode() : \Aimeos\MW\Tree\Node\Iface
	{
		return $this->node;
	}


	/**
	 * Returns the level of the item in the tree
	 *
	 * For internal use only!
	 *
	 * @return int Level of the item starting with "0" for the root node
	 */
	public function getLevel() : int
	{
		return $this->node->level ?: 0;
	}


	/**
	 * Returns the ID of the parent category
	 *
	 * For internal use only!
	 *
	 * @return string|null Unique ID of the parent category
	 */
	public function getParentId() : ?string
	{
		return $this->node->parentid;
	}


	/**
	 * Tests if the item is available based on status, time, language and currency
	 *
	 * @return bool True if available, false if not
	 */
	public function isAvailable() : bool
	{
		return parent::isAvailable() && $this->getStatus() > 0;
	}


	/**
	 * Checks, whether this node was modified.
	 *
	 * @return bool True if the content of the node is modified, false if not
	 */
	public function isModified() : bool
	{
		return parent::isModified() || $this->node->isModified();
	}


	/*
	 * Sets the item values from the given array and removes that entries from the list
	 *
	 * @param array &$list Associative list of item keys and their values
	 * @param bool True to set private properties too, false for public only
	 * @return \Aimeos\MShop\Catalog\Item\Iface Catalog item for chaining method calls
	 */
	public function fromArray( array &$list, bool $private = false ) : \Aimeos\MShop\Common\Item\Iface
	{
		$item = parent::fromArray( $list, $private );

		foreach( $list as $key => $value )
		{
			switch( $key )
			{
				case 'catalog.url': $item->setUrl( $value ); break;
				case 'catalog.code': $item->setCode( $value ); break;
				case 'catalog.label': $item->setLabel( $value ); break;
				case 'catalog.target': $item->setTarget( $value ); break;
				case 'catalog.status': $item->setStatus( (int) $value ); break;
				case 'catalog.config': $item->setConfig( (array) $value ); break;
				case 'catalog.id': !$private ?: $item->setId( $value ); break;
				default: continue 2;
			}

			unset( $list[$key] );
		}

		return $item;
	}


	/**
	 * Returns the public values of the node as array.
	 *
	 * @param bool True to return private properties, false for public only
	 * @return array Assciative list of key/value pairs
	 */
	public function toArray( bool $private = false ) : array
	{
		$list = [
			'catalog.url' => $this->getUrl(),
			'catalog.code' => $this->getCode(),
			'catalog.label' => $this->getLabel(),
			'catalog.config' => $this->getConfig(),
			'catalog.status' => $this->getStatus(),
			'catalog.target' => $this->getTarget(),
			'catalog.hasChildren' => $this->hasChildren(),
		];

		if( $private === true )
		{
			$list['catalog.id'] = $this->getId();
			$list['catalog.level'] = $this->getLevel();
			$list['catalog.siteid'] = $this->getSiteId();
			$list['catalog.parentid'] = $this->getParentId();
			$list['catalog.ctime'] = $this->getTimeCreated();
			$list['catalog.mtime'] = $this->getTimeModified();
			$list['catalog.editor'] = $this->editor();
		}

		return $list;
	}


	/**
	 * Returns the node and its children as list
	 *
	 * @return \Aimeos\Map List of IDs as keys and items implementing \Aimeos\MShop\Catalog\Item\Iface
	 */
	public function toList() : \Aimeos\Map
	{
		$list = map( [$this->getId() => $this] );

		foreach( $this->getChildren() as $child ) {
			$list = $list->union( $child->toList() );
		}

		return $list;
	}
}
