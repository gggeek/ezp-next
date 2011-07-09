<?php
/**
 * File containing the StorageInterface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 *
 */

namespace ezp\Persistence\Fields;

/**
 * @package ezp.persistence.fields
 */
interface StorageInterface
{

    /**
     * @return int
     */
    public function typeHint();

    /**
     * @param array $data
     * @param \ezp\Persistence\Content\Field $field
     */
    public function setValue( array $data, \ezp\Persistence\Content\Field $field );

    /**
     * @param int $fieldId
     * @param $value
     * @return bool
     *
     */
    public function storeFieldData( $fieldId, $value );

    /**
     * @param int $fieldId
     */
    public function getFieldData( $fieldId );

    /**
     * @param array $fieldId
     * @return bool
     */
    public function deleteFieldData( array $fieldId );

    /**
     * @return bool
     */
    public function hasFieldData();

    /**
     * @param int $fieldId
     */
    public function copyFieldData( $fieldId );

    /**
     * @param int $fieldId
     */
    public function getIndexData( $fieldId );
}
?>
