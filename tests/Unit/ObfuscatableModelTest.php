<?php

namespace EvoMark\LaravelIdObfuscator\Tests\Unit;

use EvoMark\LaravelIdObfuscator\Tests\TestCase;
use EvoMark\LaravelIdObfuscator\Tests\Models\User;
use EvoMark\LaravelIdObfuscator\Traits\Obfuscatable;

/**
 * @covers \EvoMark\LaravelIdObfuscator\Traits\Obfuscatable
 */
class ObfuscatableModelTest extends TestCase
{

    public function test_that_an_instance_of_an_obfuscatable_model_can_be_made()
    {
        $model = User::make();

        $this->assertNotEmpty($model);
    }

    public function test_that_an_instance_of_an_obfuscatable_model_can_be_created()
    {
        $model = User::factory()->create();

        $this->assertModelExists($model);
    }
}
