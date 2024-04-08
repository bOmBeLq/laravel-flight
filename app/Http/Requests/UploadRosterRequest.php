<?php

namespace App\Http\Requests;

use App\Http\Validator\RosterTypeValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadRosterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'roster' => 'required',
            'type' => [
                'required',
                $this->container->get(RosterTypeValidator::class)
            ]
        ];
    }

    public function getRoster(): UploadedFile
    {
        return $this->roster;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
