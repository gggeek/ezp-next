<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\API\Repository\Values\Content\Trash;

use ArrayIterator;
use eZ\Publish\API\Repository\Values\ValueObject;
use Traversable;

class SearchResult extends ValueObject implements \IteratorAggregate
{
    public function __construct(array $properties = [])
    {
        if (isset($properties['totalCount'])) {
            $this->count = $properties['totalCount'];
        }

        parent::__construct($properties);
    }

    /**
     * The total number of Trash items.
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * The total number of Trash items.
     *
     * @deprecated Property is here purely for BC with 5.x/6.x.
     * @var int
     */
    public $count = 0;

    /**
     * The Trash items found for the query.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\TrashItem[]
     */
    public $items = [];

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
