<?php

return [

    'accepted' => ':attribute کو قبول کرنا ضروری ہے۔',
    'array' => ':attribute ایک فہرست ہونی چاہیے۔',
    'confirmed' => ':attribute کی تصدیق مطابقت نہیں رکھتی۔',
    'current_password' => 'پاس ورڈ غلط ہے۔',
    'email' => ':attribute ایک درست ای میل ہونی چاہیے۔',
    'exists' => 'منتخب :attribute درست نہیں ہے۔',
    'in' => 'منتخب :attribute درست نہیں ہے۔',
    'max' => [
        'string' => ':attribute :max حروف سے زیادہ نہیں ہو سکتی۔',
        'file' => ':attribute :max کلو بائٹس سے زیادہ نہیں ہو سکتی۔',
        'array' => ':attribute میں :max سے زیادہ اشیاء نہیں ہو سکتیں۔',
        'numeric' => ':attribute :max سے زیادہ نہیں ہو سکتی۔',
    ],
    'min' => [
        'string' => ':attribute کم از کم :min حروف کی ہونی چاہیے۔',
        'array' => ':attribute میں کم از کم :min اشیاء ہونی چاہئیں۔',
        'numeric' => ':attribute کم از کم :min ہونی چاہیے۔',
        'file' => ':attribute کم از کم :min کلو بائٹس ہونی چاہیے۔',
    ],
    'numeric' => ':attribute ایک عدد ہونی چاہیے۔',
    'required' => ':attribute کا خانہ لازمی ہے۔',
    'string' => ':attribute متن ہونی چاہیے۔',
    'unique' => ':attribute پہلے سے استعمال میں ہے۔',
    'image' => ':attribute ایک تصویر ہونی چاہیے۔',

    'attributes' => [
        'name' => 'نام',
        'email' => 'ای میل',
        'password' => 'پاس ورڈ',
        'password_confirmation' => 'پاس ورڈ کی تصدیق',
        'current_password' => 'موجودہ پاس ورڈ',
        'role_id' => 'رول',
        'permissions' => 'اجازتیں',
        'image' => 'تصویر',
        'locale' => 'زبان',
        'g-recaptcha-response' => 'کیپچا',
    ],

    'custom' => [
        'permissions' => [
            'required' => 'اس رول کے لیے کم از کم ایک اجازت منتخب کریں۔',
        ],
        'name' => [
            'unique' => 'یہ رول نام پہلے سے موجود ہے۔',
        ],
        'g-recaptcha-response' => [
            'required' => 'براہ کرم کیپچا مکمل کریں۔',
        ],
    ],
];
