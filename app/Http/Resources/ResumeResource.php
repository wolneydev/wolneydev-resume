<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'language' => $this->language,
            'version' => $this->version,
            'full_name' => $this->full_name,
            'professional_title' => $this->professional_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'location' => $this->location,
            'summary' => $this->summary,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
            'portfolio_url' => $this->portfolio_url,
            'website_url' => $this->website_url,
            'experiences' => $this->experiences,
            'education' => $this->education,
            'skills' => $this->skills,
            'certifications' => $this->certifications,
            'projects' => $this->projects,
            'spoken_languages' => $this->spoken_languages,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
