<?php

namespace App\Projects\Prohori\DataBlocks;

use App\Projects\Prohori\Features\DataBlocks\DataBlock;

class SampleDataBlock extends DataBlock
{
    /**
     * Load the result
     *
     * @var mixed
     */
    public $data;

    /**
     * Cache Seconds
     *
     * @var int
     */
    public $cache = 6;

    /**
     * Process the result
     */
    public function process()
    {

        // Todo: Prepare and load data

        $this->data = [
            'books' => [
                'purchased' => 10,
                'read' => 7,
            ],
        ];
    }

    // Write Additional helper for data calculation if needed.

}