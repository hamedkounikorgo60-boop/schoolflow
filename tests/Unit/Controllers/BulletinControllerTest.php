<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\BulletinController;
use Tests\TestCase;

class BulletinControllerTest extends TestCase
{
    private BulletinController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BulletinController();
    }

    public function test_get_mention_returns_excellent_for_16_and_above(): void
    {
        $method = new \ReflectionMethod(BulletinController::class, 'getMention');

        $this->assertEquals('Excellent', $method->invoke($this->controller, 16));
        $this->assertEquals('Excellent', $method->invoke($this->controller, 18));
        $this->assertEquals('Excellent', $method->invoke($this->controller, 20));
    }

    public function test_get_mention_returns_tres_bien_for_14_to_16(): void
    {
        $method = new \ReflectionMethod(BulletinController::class, 'getMention');

        $this->assertEquals('Très bien', $method->invoke($this->controller, 14));
        $this->assertEquals('Très bien', $method->invoke($this->controller, 15));
        $this->assertEquals('Très bien', $method->invoke($this->controller, 15.99));
    }

    public function test_get_mention_returns_bien_for_12_to_14(): void
    {
        $method = new \ReflectionMethod(BulletinController::class, 'getMention');

        $this->assertEquals('Bien', $method->invoke($this->controller, 12));
        $this->assertEquals('Bien', $method->invoke($this->controller, 13));
        $this->assertEquals('Bien', $method->invoke($this->controller, 13.99));
    }

    public function test_get_mention_returns_assez_bien_for_10_to_12(): void
    {
        $method = new \ReflectionMethod(BulletinController::class, 'getMention');

        $this->assertEquals('Assez bien', $method->invoke($this->controller, 10));
        $this->assertEquals('Assez bien', $method->invoke($this->controller, 11));
        $this->assertEquals('Assez bien', $method->invoke($this->controller, 11.99));
    }

    public function test_get_mention_returns_insuffisant_for_below_10(): void
    {
        $method = new \ReflectionMethod(BulletinController::class, 'getMention');

        $this->assertEquals('Insuffisant', $method->invoke($this->controller, 0));
        $this->assertEquals('Insuffisant', $method->invoke($this->controller, 5));
        $this->assertEquals('Insuffisant', $method->invoke($this->controller, 9.99));
    }
}
