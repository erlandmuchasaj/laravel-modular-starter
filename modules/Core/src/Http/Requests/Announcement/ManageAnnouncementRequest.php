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
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // here we can check if user is loggedin and is admin.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
