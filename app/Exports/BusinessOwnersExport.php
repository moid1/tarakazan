<?php

namespace App\Exports;

use App\Models\BusinessOwner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BusinessOwnersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get all business owners
        return BusinessOwner::all(['id', 'business_name', 'address', 'business_email', 'package', 'slug']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Business Name',
            'Address',
            'Business Email',
            'Package',
            'Slug',
        ];
    }
}
