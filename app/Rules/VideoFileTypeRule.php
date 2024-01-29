<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class VideoFileTypeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $getFileExtension = strrchr($value->getClientOriginalName(), '.');
        $nameOfExtension = Str::replace('.', '', $getFileExtension);
        $ruleCheckWithExtension = checkVideoFileType($nameOfExtension);
        $ruleCheckWithMimeType = Str::contains($value->getClientMimeType(), 'video/');
        if ($ruleCheckWithMimeType) {
            return true;
        } elseif ($ruleCheckWithExtension) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Please Upload file with ext:flv,mp4,mov,avi,wmv';
    }
}
