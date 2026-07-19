<?php

namespace Tests\Feature;

use App\Models\Resume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
            'full_name' => 'John Doe',
            'professional_title' => 'Software Engineer',
            'email' => 'john@example.com',
            'phone' => '+15551234567',
            'location' => 'São Paulo, Brazil',
            'summary' => 'Experienced software engineer focused on backend systems.',
            'linkedin_url' => 'https://linkedin.com/in/johndoe',
            'github_url' => 'https://github.com/johndoe',
            'portfolio_url' => 'https://johndoe.dev',
            'website_url' => 'https://johndoe.com',
            'experiences' => [
                [
                    'company' => 'Acme Inc',
                    'title' => 'Backend Developer',
                    'location' => 'Remote',
                    'start_date' => '2021-01',
                    'end_date' => null,
                    'is_current' => true,
                    'description' => 'Built APIs with Laravel.',
                    'highlights' => ['Reduced latency by 40%'],
                ],
            ],
            'education' => [
                [
                    'institution' => 'MIT',
                    'degree' => 'BSc',
                    'field' => 'Computer Science',
                    'start_date' => '2016-01',
                    'end_date' => '2019-12',
                    'description' => null,
                ],
            ],
            'skills' => [
                ['name' => 'PHP', 'level' => 'advanced'],
                ['name' => 'Laravel', 'level' => 'advanced'],
            ],
            'certifications' => [
                [
                    'name' => 'AWS CCP',
                    'issuer' => 'Amazon',
                    'date' => '2024-01',
                    'url' => 'https://aws.amazon.com/cert',
                ],
            ],
            'projects' => [
                [
                    'name' => 'Resume API',
                    'description' => 'ATS-friendly resume API',
                    'url' => 'https://github.com/johndoe/resume-api',
                    'technologies' => ['Laravel', 'MySQL'],
                ],
            ],
            'spoken_languages' => [
                ['name' => 'English', 'level' => 'fluent'],
                ['name' => 'Portuguese', 'level' => 'native'],
            ],
            'is_published' => true,
        ], $overrides);
    }

    public function test_it_lists_resumes(): void
    {
        Resume::factory()->create([
            'slug' => 'resume-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]);
        Resume::factory()->create([
            'slug' => 'resume-por-v1',
            'language' => 'por',
            'version' => 'v1',
        ]);

        $this->getJson('/api/resumes')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_it_shows_seeded_resumes_by_slug(): void
    {
        $this->seed(\Database\Seeders\ResumeSeeder::class);

        $this->getJson('/api/resumes/wolney-cesar-carneiro-eng-v1')
            ->assertOk()
            ->assertJsonPath('data.slug', 'wolney-cesar-carneiro-eng-v1')
            ->assertJsonPath('data.language', 'eng')
            ->assertJsonPath('data.full_name', 'Wolney Cesar Carneiro');

        $this->getJson('/api/resumes/wolney-cesar-carneiro-por-v1')
            ->assertOk()
            ->assertJsonPath('data.slug', 'wolney-cesar-carneiro-por-v1')
            ->assertJsonPath('data.language', 'por')
            ->assertJsonPath('data.full_name', 'Wolney Cesar Carneiro');
    }

    public function test_it_creates_an_ats_friendly_resume(): void
    {
        $payload = $this->validPayload();

        $response = $this->postJson('/api/resumes', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'john-doe-eng-v1')
            ->assertJsonPath('data.language', 'eng')
            ->assertJsonPath('data.version', 'v1')
            ->assertJsonPath('data.full_name', 'John Doe');

        $this->assertDatabaseHas('resumes', [
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]);
    }

    public function test_it_allows_same_version_in_different_languages(): void
    {
        $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]))->assertCreated();

        $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-por-v1',
            'language' => 'por',
            'version' => 'v1',
            'email' => 'john.por@example.com',
        ]))->assertCreated();

        $this->assertDatabaseCount('resumes', 2);
    }

    public function test_it_allows_multiple_versions_in_the_same_language(): void
    {
        $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]))->assertCreated();

        $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-eng-v2',
            'language' => 'eng',
            'version' => 'v2',
            'email' => 'john.v2@example.com',
        ]))->assertCreated();

        $this->assertDatabaseCount('resumes', 2);
    }

    public function test_it_rejects_duplicate_slug(): void
    {
        Resume::factory()->create([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]);

        $response = $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-eng-v1',
            'language' => 'por',
            'version' => 'v1',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['slug']);
    }

    public function test_it_allows_same_language_and_version_with_different_slug(): void
    {
        Resume::factory()->create([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]);

        $response = $this->postJson('/api/resumes', $this->validPayload([
            'slug' => 'john-doe-eng-v1-alt',
            'language' => 'eng',
            'version' => 'v1',
        ]));

        $response->assertCreated();
        $this->assertDatabaseCount('resumes', 2);
    }

    public function test_it_rejects_invalid_version_format(): void
    {
        $response = $this->postJson('/api/resumes', $this->validPayload([
            'version' => '1.0',
        ]));

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['version']);
    }

    public function test_it_shows_a_resume_by_slug(): void
    {
        Resume::factory()->create([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
            'full_name' => 'John Doe',
        ]);

        $response = $this->getJson('/api/resumes/john-doe-eng-v1');

        $response->assertOk()
            ->assertJsonPath('data.slug', 'john-doe-eng-v1')
            ->assertJsonPath('data.full_name', 'John Doe');
    }

    public function test_it_updates_a_resume(): void
    {
        $resume = Resume::factory()->create([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
            'full_name' => 'John Doe',
        ]);

        $response = $this->putJson('/api/resumes/'.$resume->slug, [
            'full_name' => 'Jonathan Doe',
            'professional_title' => 'Senior Software Engineer',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.full_name', 'Jonathan Doe')
            ->assertJsonPath('data.professional_title', 'Senior Software Engineer');
    }

    public function test_it_deletes_a_resume(): void
    {
        $resume = Resume::factory()->create([
            'slug' => 'john-doe-eng-v1',
            'language' => 'eng',
            'version' => 'v1',
        ]);

        $response = $this->deleteJson('/api/resumes/'.$resume->slug);

        $response->assertNoContent();
        $this->assertDatabaseMissing('resumes', ['id' => $resume->id]);
    }
}
