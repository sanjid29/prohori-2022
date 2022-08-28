<?php

namespace App\Projects\DefaultProject\Modules\Comments;

use App\Mainframe\Modules\Comments\Traits\CommentObserverTrait;
use App\Projects\DefaultProject\Features\Modular\BaseModule\BaseModuleObserver;
use App\Comment;

class CommentObserver extends BaseModuleObserver
{
    use CommentObserverTrait;

    // /**
    //  * @param  App\Comment  $element
    //  * @return void|bool
    //  */
    // public function saving($element) { }

    // public function creating($element) { }
    // public function created($element) { }
    // public function updating($element) { }
    // public function updated($element) { }
    // public function saved($element) { }
    // public function deleting($element) { }
    // public function deleted($element) { }
    // public function restored($element) { }
    // public function forceDeleted($element) { }

}