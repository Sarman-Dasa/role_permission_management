<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection, WithHeadings
{

    public function __construct($start_date,$end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::whereBetween('birthdate',[$this->start_date,$this->end_date])->get(array('id' ,'first_name' ,'last_name' ,'email' ,'phone' ,'birthdate' ,'is_active'));
    }

    public function headings(): array
    {
        return ['id' ,'first_name' ,'last_name' ,'email' ,'phone' ,'birthdate','is_active'];
    }
    
}
