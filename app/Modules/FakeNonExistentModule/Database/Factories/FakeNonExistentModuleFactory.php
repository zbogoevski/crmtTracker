<?php

declare(strict_types=1);

namespace App\Modules\FakeNonExistentModule\Database\Factories;

use App\Modules\FakeNonExistentModule\Infrastructure\Models\FakeNonExistentModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FakeNonExistentModule>
 */
class FakeNonExistentModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<FakeNonExistentModule>
     */
    protected $model = FakeNonExistentModule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
        ];
    }
}
