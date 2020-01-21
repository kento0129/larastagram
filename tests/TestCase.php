<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function getAjax($uri, array $headers = [])
    {
        $headers['X-Requested-With'] = 'XMLHttpRequest';
        return $this->get($uri, $headers);
    }
}
