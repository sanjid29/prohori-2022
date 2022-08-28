<?php

namespace App\Mainframe\Features\Plugins\MultiLevelModel;

use App\Mainframe\Features\Modular\BaseModule\BaseModule;

/** @mixin BaseModule */
trait MultiLevelModelTrait
{

    // /**
    //  * Fill data and set calculated data in fields for saving the module
    //  * This can depend of supporting fillFunct, setFunct,calculateFunct
    //  * return $this
    //  */
    // public function populate()
    // {
    //
    //     $this->parent_id = $this->parent_id ?? 0;
    //     $this->order = $this->order ?? 999;
    //
    //     // Todo: Remove this sample code
    //     // $this->fillAddress()->setAmounts();
    //
    //     return $this;
    // }
    //
    // /**
    //  * Set name_ext field
    //  *
    //  * @return $this
    //  */
    // public function setNameExt()
    // {
    //     $this->name_ext = $this->name;
    //     if ($this->parent) {
    //         $this->name_ext = $this->parent->name_ext.' > '.$this->name;
    //     }
    //
    //     return $this;
    // }

    /**
     * One dimensional array [3,2,1]
     *
     * @return $this
     */
    public function setUpperLevelIds()
    {
        $ids = [];

        $parentId = $this->parent_id;

        while ($parentId) {
            $parent = self::find($parentId);
            $ids[] = $parentId;
            $parentId = $parent->parent_id;
        }

        $this->upper_level_ids = $ids;

        return $this;
    }

    public function setLowerLevelIds()
    {
        $ids = [];
        $children = $this->children;

        foreach ($children as $child) {
            $ids[$child->id] = $child->lower_level_ids;
        }

        $this->lower_level_ids = $ids;

        return $this;
    }

    /**
     * Re-syncs the hierarchy and sets the url
     *
     * @return void
     */
    public static function reSyncParentChild()
    {
        $levels = self::orderBy('parent_id', 'desc')->get();

        foreach ($levels as $level) {
            /** @var self $level */
            $level->setUpperLevelIds()
                ->setLowerLevelIds()
                ->saveQuietly();
        }

        $levels = self::orderBy('parent_id', 'asc')->get();
        foreach ($levels as $level) {
            /** @var self $level */
            $level->setUpperLevelIds()
                ->setLowerLevelIds()
                ->saveQuietly();
        }
    }

    /**
     * Get an array of ids including current level ids and all its children ids.
     * This is useful for searching within the level
     *
     * @return array
     */
    public function thisAndAllChildIds()
    {

        return array_merge([$this->id], array_flat_keys($this->lower_level_ids));

    }

    /**
     * @return bool
     */
    public function isEndNode()
    {
        return !$this->children()->remember(timer('long'))->exists();
    }

    /**
     * Find the levels that has no further child
     *
     * @return array
     */
    public static function endNodes()
    {
        $items = self::active()->get();
        $bucket = [];
        foreach ($items as $productLevel) {
            if (!$productLevel->children()->exists()) {
                $bucket[] = $productLevel;
            }
        }

        return $bucket;
    }

    /*
   |--------------------------------------------------------------------------
   | Relations
   |--------------------------------------------------------------------------
   */

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function subLevels()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

}