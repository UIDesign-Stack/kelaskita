<?php

namespace App\Http\Requests;

class UpdateSchoolClassRequest extends StoreSchoolClassRequest
{
    // rules() dan withValidator() diwariskan dari StoreSchoolClassRequest.
    // Exclude ID saat update sudah otomatis ditangani lewat $this->route('class')
    // di dalam withValidator() milik parent.
}