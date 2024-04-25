<?php

namespace Modes\Framework\Tests;

use Modes\Framework\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_set_and_get_flash()
    {
        $session = new Session();
        $session->setFlash('success', 'Все будет хорошо!');
        $session->setFlash('error', 'Надо исправлять!');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Все будет хорошо!'], $session->getFlash('success'));
        $this->assertEquals(['Надо исправлять!'], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}