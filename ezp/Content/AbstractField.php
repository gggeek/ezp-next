<?php
/**
 * Contains Abstract Field (content [class] attribute) class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package ezp
 * @subpackage content
 */

namespace ezp\Content;

/**
 * Abstract field class, used for content field and content type field
 */
use \ezp\Base\Exception;
abstract class AbstractField extends \ezp\Base\AbstractModel implements \ezp\Base\Interfaces\Observer
{
    /**
     * @var AbstractFieldType
     */
    protected $type;

    /**
     * Initialize and return field type
     *
     * @throws Exception\BadConfiguration If configuration for field type is missing.
     * @throws Exception\MissingClass If field class is missing.
     * @return AbstractFieldType
     */
    protected function getType()
    {
        if ( $this->type instanceof AbstractFieldType )
           return $this->type;

        $list = $this->getTypeList();
        if ( !isset( $list[$this->fieldTypeString] ) )
            throw new Exception\BadConfiguration( 'content.ini[fields]', "could not load {$this->fieldTypeString}");

        if ( !class_exists( $list[$this->fieldTypeString] ) )
            throw new Exception\MissingClass(  $list[$this->fieldTypeString], 'field type' );

        $this->type = $this->initType( $list[ $this->fieldTypeString ] );

        return $this->attach( $this->type, 'store' )->type->attach( $this, 'store' );// listen on each other and return type
    }

    /**
     * Get mapping of type/definition identifier to class
     *
     * @return array
     */
    protected function getTypeList()
    {
        return \ezp\Base\Configuration::getInstance( 'content' )->get( 'fields', 'Type' );
    }

    /**
     * Initialize field type class
     *
     * @throws \RuntimeException If $className is not instanceof AbstractFieldType
     * @param string $className
     * @return AbstractFieldType
     */
    protected function initType( $className )
    {
        $type = new $className();
        if ( !$type instanceof AbstractFieldType )
            throw new \RuntimeException( "Field type value '{$className}' does not implement ezp\\content\\AbstractFieldType" );
        $this->toType( $type );
        return $type;
    }

    /**
     * Set values from field type to field
     *
     * @param AbstractFieldType $type
     * @return Field
     */
    protected function fromType( AbstractFieldType $type )
    {
        foreach ( $type->properties() as $property => $legacyProperty )
        {
            if ( !isset( $this->readableProperties[$legacyProperty] ) )
                throw new \RuntimeException( "'{$legacyProperty}' is not a valid property on " . get_class( $this ) );

            $this->$legacyProperty = $type->$property;
        }
        return $this;
    }

    /**
     * Set values from field type to field
     *
     * @param AbstractFieldType $type
     * @return Field
     */
    protected function toType( AbstractFieldType $type )
    {
        foreach ( $type->properties() as $property => $legacyProperty )
        {
            if ( !isset( $this->readableProperties[$legacyProperty] ) )
                throw new \RuntimeException( "'{$legacyProperty}' is not a valid property on " . get_class( $this ) );

            $type->$property = $this->$legacyProperty;
        }
        return $this;
    }

    /**
     * Called when subject has been updated
     *
     * @param \ezp\Base\Interfaces\Observable $subject
     * @param string $event
     * @return Field
     */
    public function update( \ezp\Base\Interfaces\Observable $subject, $event = 'update' )
    {
        if ( !$subject instanceof AbstractFieldType )
            return $this;

        $type = $this->getType();
        if ( $type !== $subject )
            throw new \RuntimeException( "Field should only listen to it's own attached field value, not others! type: '{$this->fieldTypeString}' " );

        return $this->fromType( $type );
    }
}
