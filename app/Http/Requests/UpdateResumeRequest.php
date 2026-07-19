<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $resume = $this->route('resume');

        return [
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('resumes', 'slug')->ignore($resume),
            ],
            'language' => ['sometimes', 'string', 'size:3', Rule::in(['eng', 'por'])],
            'version' => [
                'sometimes',
                'string',
                'max:20',
                'regex:/^v\d+$/',
                Rule::unique('resumes')
                    ->where(fn ($query) => $query
                        ->where('slug', $this->input('slug', $resume->slug))
                        ->where('language', $this->input('language', $resume->language)))
                    ->ignore($resume),
            ],
            'full_name' => ['sometimes', 'string', 'max:255'],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'experiences' => ['nullable', 'array'],
            'experiences.*.company' => ['required_with:experiences', 'string', 'max:255'],
            'experiences.*.title' => ['required_with:experiences', 'string', 'max:255'],
            'experiences.*.location' => ['nullable', 'string', 'max:255'],
            'experiences.*.start_date' => ['nullable', 'string', 'max:20'],
            'experiences.*.end_date' => ['nullable', 'string', 'max:20'],
            'experiences.*.is_current' => ['nullable', 'boolean'],
            'experiences.*.description' => ['nullable', 'string'],
            'experiences.*.highlights' => ['nullable', 'array'],
            'experiences.*.highlights.*' => ['string'],
            'education' => ['nullable', 'array'],
            'education.*.institution' => ['required_with:education', 'string', 'max:255'],
            'education.*.degree' => ['nullable', 'string', 'max:255'],
            'education.*.field' => ['nullable', 'string', 'max:255'],
            'education.*.start_date' => ['nullable', 'string', 'max:20'],
            'education.*.end_date' => ['nullable', 'string', 'max:20'],
            'education.*.description' => ['nullable', 'string'],
            'skills' => ['nullable', 'array'],
            'skills.*.name' => ['required_with:skills', 'string', 'max:255'],
            'skills.*.level' => ['nullable', 'string', 'max:50'],
            'certifications' => ['nullable', 'array'],
            'certifications.*.name' => ['required_with:certifications', 'string', 'max:255'],
            'certifications.*.issuer' => ['nullable', 'string', 'max:255'],
            'certifications.*.date' => ['nullable', 'string', 'max:20'],
            'certifications.*.url' => ['nullable', 'url', 'max:255'],
            'projects' => ['nullable', 'array'],
            'projects.*.name' => ['required_with:projects', 'string', 'max:255'],
            'projects.*.description' => ['nullable', 'string'],
            'projects.*.url' => ['nullable', 'url', 'max:255'],
            'projects.*.technologies' => ['nullable', 'array'],
            'projects.*.technologies.*' => ['string', 'max:100'],
            'spoken_languages' => ['nullable', 'array'],
            'spoken_languages.*.name' => ['required_with:spoken_languages', 'string', 'max:100'],
            'spoken_languages.*.level' => ['nullable', 'string', 'max:50'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }
}
