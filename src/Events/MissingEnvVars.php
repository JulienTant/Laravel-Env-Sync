<?php

namespace Jtant\LaravelEnvSync\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MissingEnvVars
{
    use Dispatchable;

    protected $diffs;

    public function __construct($diffs)
    {
        $this->diffs = $diffs;
    }
}