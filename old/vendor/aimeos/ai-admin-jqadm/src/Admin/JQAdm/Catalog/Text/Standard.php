<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Catalog\Text;

sprintf( 'text' ); // for translation


/**
 * Default implementation of catalog text JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/catalog/text/name
	 * Name of the text subpart used by the JQAdm catalog implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Catalog\Text\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.07
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->textData = $this->toArray( $view->item, true );
		$view->textBody = parent::copy();

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$siteid = $this->context()->locale()->getSiteId();
		$data = $view->param( 'text', [] );

		foreach( $data as $idx => $entry )
		{
			$data[$idx]['catalog.lists.siteid'] = $siteid;
			$data[$idx]['text.siteid'] = $siteid;
		}

		$view->textData = $data;
		$view->textBody = parent::create();

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 *
	 * @return string|null HTML output
	 */
	public function delete() : ?string
	{
		parent::delete();

		$item = $this->view()->item;
		$item->deleteListItems( $item->getListItems( 'text', null, null, false )->toArray(), true );

		return null;
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->object()->data( $this->view() );
		$view->textData = $this->toArray( $view->item );
		$view->textBody = parent::get();

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->view();

		$view->item = $this->fromArray( $view->item, $view->param( 'text', [] ) );
		$view->textBody = parent::save();

		return null;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, ?string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/catalog/text/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/catalog/text/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/text/decorators/global
		 * @see admin/jqadm/catalog/text/decorators/local
		 */

		/** admin/jqadm/catalog/text/decorators/global
		 * Adds a list of globally available decorators only to the catalog JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/catalog/text/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/text/decorators/excludes
		 * @see admin/jqadm/catalog/text/decorators/local
		 */

		/** admin/jqadm/catalog/text/decorators/local
		 * Adds a list of local decorators only to the catalog JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Catalog\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/catalog/text/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Catalog\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2017.07
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/catalog/text/decorators/excludes
		 * @see admin/jqadm/catalog/text/decorators/global
		 */
		return $this->createSubClient( 'catalog/text/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/catalog/text/subparts
		 * List of JQAdm sub-clients rendered within the catalog text section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
		 * determines the order of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the order of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2017.07
		 */
		return $this->context()->config()->get( 'admin/jqadm/catalog/text/subparts', [] );
	}


	/**
	 * Adds the required data used in the text template
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @return \Aimeos\Base\View\Iface View object with assigned parameters
	 */
	public function data( \Aimeos\Base\View\Iface $view ) : \Aimeos\Base\View\Iface
	{
		$context = $this->context();

		$textTypeManager = \Aimeos\MShop::create( $context, 'text/type' );
		$listTypeManager = \Aimeos\MShop::create( $context, 'catalog/lists/type' );

		$search = $textTypeManager->filter( true )->slice( 0, 10000 );
		$search->add( 'text.type.domain', '==', 'catalog' )->order( 'text.type.code' );

		$listSearch = $listTypeManager->filter( true )->slice( 0, 10000 );
		$listSearch->add( 'catalog.lists.type.domain', '==', 'text' )->order( 'catalog.lists.type.code' );

		$view->textTypes = $textTypeManager->search( $search );
		$view->textListTypes = $listTypeManager->search( $listSearch );

		return $view;
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Catalog\Item\Iface $item Catalog item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Catalog\Item\Iface Modified catalog item
	 */
	protected function fromArray( \Aimeos\MShop\Catalog\Item\Iface $item, array $data ) : \Aimeos\MShop\Catalog\Item\Iface
	{
		$context = $this->context();

		$textManager = \Aimeos\MShop::create( $context, 'text' );
		$listManager = \Aimeos\MShop::create( $context, 'catalog/lists' );

		$listItems = $item->getListItems( 'text', null, null, false );


		foreach( $data as $idx => $entry )
		{
			if( trim( $this->val( $entry, 'text.content', '' ) ) === '' ) {
				continue;
			}

			$id = $this->val( $entry, 'text.id', '' );
			$type = $this->val( $entry, 'catalog.lists.type', 'default' );

			$listItem = $item->getListItem( 'text', $type, $id, false ) ?: $listManager->create();
			$refItem = $listItem->getRefItem() ?: $textManager->create();

			$refItem->fromArray( $entry, true );
			$listItem->fromArray( $entry, true )->setPosition( $idx )->setConfig( [] );

			foreach( (array) $this->val( $entry, 'config', [] ) as $cfg )
			{
				if( ( $key = trim( $cfg['key'] ?? '' ) ) !== '' && ( $val = trim( $cfg['val'] ?? '' ) ) !== '' ) {
					$listItem->setConfigValue( $key, json_decode( $val, true ) ?? $val );
				}
			}

			$item->addListItem( 'text', $listItem, $refItem );
			unset( $listItems[$listItem->getId()] );
		}

		return $item->deleteListItems( $listItems->toArray(), true );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Catalog\Item\Iface $item Catalog item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Catalog\Item\Iface $item, bool $copy = false ) : array
	{
		$data = [];
		$siteId = $this->context()->locale()->getSiteId();

		foreach( $item->getListItems( 'text', null, null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$list = $listItem->toArray( true ) + $refItem->toArray( true );

			if( $copy === true )
			{
				$list['catalog.lists.siteid'] = $siteId;
				$list['catalog.lists.id'] = '';
				$list['text.siteid'] = $siteId;
				$list['text.id'] = null;
			}

			$list['catalog.lists.datestart'] = str_replace( ' ', 'T', $list['catalog.lists.datestart'] ?? '' );
			$list['catalog.lists.dateend'] = str_replace( ' ', 'T', $list['catalog.lists.dateend'] ?? '' );
			$list['config'] = [];

			foreach( $listItem->getConfig() as $key => $value ) {
				$list['config'][] = ['key' => $key, 'val' => $value];
			}

			$data[] = $list;
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\Base\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\Base\View\Iface $view ) : string
	{
		/** admin/jqadm/catalog/text/template-item
		 * Relative path to the HTML body template of the text subpart for catalogs.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in templates/admin/jqadm).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 */
		$tplconf = 'admin/jqadm/catalog/text/template-item';
		$default = 'catalog/item-text';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
