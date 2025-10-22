<?php

namespace Spot2HQ\SpotValidation\Http\Requests\Rules\Photos;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidCloudImage implements ValidationRule
{
    /**
     * @var array Initial set of URLs.
     */
    private array $imageURLs = [];

    /**
     * Determine if the validation rule passes.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Validate
            $this->imageURLs = explode(',', $value);
            foreach ($this->imageURLs as $index => $url) {
                // Check if resource comes from Google Drive
                if (preg_match(config('photos.google_drive_pattern'), $url)) {
                    preg_match(config('photos.file_id_pattern'), $url, $matches);
                    $fileID = $matches[1] ?? '';
                    $googleURL = "https://drive.google.com/uc?id=$fileID";
                }

                // Check if resource is an image
                $response = Http::head($googleURL ?? $url);
                $contentType = $response->header('content-type');
                if ($response->ok() && in_array($contentType, config('photos.valid_image_types'))) {
                    unset($this->imageURLs[$index]);
                }
            }

            if (count($this->imageURLs) > 0) {
                $fail($this->message());
            }
        } catch (Exception $e) {
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        $invalids = implode(',', $this->imageURLs);

        return "Foto(s) no encontrada(s): $invalids";
    }
}
