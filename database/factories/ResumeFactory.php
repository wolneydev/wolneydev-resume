<?php

namespace Database\Factories;

use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Resume>
 */
class ResumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fullName = fake()->name();

        return [
            'slug' => Str::slug($fullName).'-'.fake()->unique()->numerify('###'),
            'language' => fake()->randomElement(['eng', 'por']),
            'version' => 'v'.fake()->unique()->numberBetween(1, 9999),
            'full_name' => $fullName,
            'professional_title' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'location' => fake()->city().', '.fake()->country(),
            'summary' => fake()->paragraph(),
            'linkedin_url' => 'https://linkedin.com/in/'.Str::slug($fullName),
            'github_url' => 'https://github.com/'.Str::slug($fullName),
            'portfolio_url' => fake()->url(),
            'website_url' => fake()->url(),
            'experiences' => [
                [
                    'company' => fake()->company(),
                    'title' => fake()->jobTitle(),
                    'location' => fake()->city(),
                    'start_date' => '2020-01',
                    'end_date' => '2023-06',
                    'is_current' => false,
                    'description' => fake()->paragraph(),
                    'highlights' => [fake()->sentence(), fake()->sentence()],
                ],
            ],
            'education' => [
                [
                    'institution' => fake()->company().' University',
                    'degree' => 'Bachelor',
                    'field' => 'Computer Science',
                    'start_date' => '2016-01',
                    'end_date' => '2019-12',
                    'description' => fake()->sentence(),
                ],
            ],
            'skills' => [
                ['name' => 'PHP', 'level' => 'advanced'],
                ['name' => 'Laravel', 'level' => 'advanced'],
                ['name' => 'MySQL', 'level' => 'intermediate'],
            ],
            'certifications' => [
                [
                    'name' => 'AWS Cloud Practitioner',
                    'issuer' => 'Amazon',
                    'date' => '2024-01',
                    'url' => fake()->url(),
                ],
            ],
            'projects' => [
                [
                    'name' => fake()->words(3, true),
                    'description' => fake()->paragraph(),
                    'url' => fake()->url(),
                    'technologies' => ['Laravel', 'MySQL', 'Vue'],
                ],
            ],
            'spoken_languages' => [
                ['name' => 'Portuguese', 'level' => 'native'],
                ['name' => 'English', 'level' => 'fluent'],
            ],
            'is_published' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function english(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'eng',
        ]);
    }

    public function portuguese(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'por',
        ]);
    }
}
