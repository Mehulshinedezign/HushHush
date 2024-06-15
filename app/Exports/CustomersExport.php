<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class CustomersExport implements FromCollection , WithCustomCsvSettings ,WithHeadings , WithMapping , FromQuery, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
    public function headings(): array
    {
        return [
            'Name',
            'Username',
            'Email',
            'Phone Number',
            'Profile File',
            'Status',
            'Approve',
            'Zipcode',
            'Address',
            'Country',
            'State',
            'City',
            'About',
          
        ];
    }

    public function map($users): array
    {
        return [
            $users->name,
            $users->username,
            $users->email,
            $users->phone_number,
            $users->frontend_profile_url,
            $users->status == '1' ? 'Active' : 'Inactive',
            $users->is_approved == '1' ? 'Approved' : 'Notapproved',
            $users->zipcode,
            @$users->UserDetail->address1 .' , '. @$users->UserDetail->address2,
            @$users->UserDetail->country->name,
            @$users->UserDetail->state->name,
            @$users->UserDetail->city->name,
            @$users->UserDetail->about,
              
        ];
    }

    public function query()
    {

        $users = User::with('UserDetail')->where('id', '!=', '1');
        return $users;
    }

    public function styles(Worksheet $sheet)
    { 
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function collection()
    {
       
       $users = User::with('UserDetail')->where('id', '!=' ,'1')->get();
        return $users;
       
    }
}
