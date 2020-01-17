<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Follower extends Pivot
{
    protected $table = 'followers';
}
