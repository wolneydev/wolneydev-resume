<?php

namespace App\Models;

use Database\Factories\ResumeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'language',
    'version',
    'full_name',
    'professional_title',
    'email',
    'phone',
    'location',
    'summary',
    'linkedin_url',
    'github_url',
    'portfolio_url',
    'website_url',
    'experiences',
    'education',
    'skills',
    'certifications',
    'projects',
    'spoken_languages',
    'is_published',
])]
class Resume extends Model
{
    /** @use HasFactory<ResumeFactory> */
    use HasFactory;

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'experiences' => 'array',
            'education' => 'array',
            'skills' => 'array',
            'certifications' => 'array',
            'projects' => 'array',
            'spoken_languages' => 'array',
            'is_published' => 'boolean',
        ];
    }
}
