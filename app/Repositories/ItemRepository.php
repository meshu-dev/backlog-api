<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository extends ModelRepository
{
    public function __construct(Item $item)
    {
        parent::__construct($item);
    }
}
