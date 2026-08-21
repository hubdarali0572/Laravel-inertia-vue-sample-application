<?php

return [

    'accepted' => 'The :attribute field must be accepted.',
    'array' => 'The :attribute field must be an array.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'email' => 'The :attribute field must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'in' => 'The selected :attribute is invalid.',
    'max' => [
        'string' => 'The :attribute field must not be greater than :max characters.',
        'file' => 'The :attribute field must not be greater than :max kilobytes.',
        'array' => 'The :attribute field must not have more than :max items.',
        'numeric' => 'The :attribute field must not be greater than :max.',
    ],
    'min' => [
        'string' => 'The :attribute field must be at least :min characters.',
        'array' => 'The :attribute field must have at least :min items.',
        'numeric' => 'The :attribute field must be at least :min.',
        'file' => 'The :attribute field must be at least :min kilobytes.',
    ],
    'numeric' => 'The :attribute field must be a number.',
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute field must be a string.',
    'unique' => 'The :attribute has already been taken.',
    'image' => 'The :attribute field must be an image.',

    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'current_password' => 'current password',
        'role_id' => 'role',
        'permissions' => 'permissions',
        'image' => 'image',
        'locale' => 'language',
        'g-recaptcha-response' => 'captcha',
    ],

    'custom' => [
        'permissions' => [
            'required' => 'Please select at least one permission for this role.',
        ],
        'name' => [
            'unique' => 'This role name already exists.',
        ],
        'g-recaptcha-response' => [
            'required' => 'Please complete the captcha.',
        ],
    ],
];
