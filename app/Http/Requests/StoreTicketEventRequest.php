<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTicketEventRequest extends FormRequest
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
        return [
            'version' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                Rule::enum(TicketStatus::class),
            ],

            'comment' => [
                'nullable',
                'array',
            ],

            'comment.author' => [
                'required_with:comment',
                'string',
                'max:100',
            ],

            'comment.message' => [
                'required_with:comment',
                'string',
                'min:3',
            ],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    !$this->has('status')
                    && !$this->has('comment')
                ) {
                    $validator->errors()->add(
                        'event',
                        'Status or comment is required.'
                    );
                }
            },
        ];
    }
}
