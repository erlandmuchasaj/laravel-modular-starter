<?php

namespace Modules\Core\Http\Requests\Announcement;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ManageAnnouncementRequest.
 */
class ManageAnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // here we can check if user is logged and is admin.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
