<?php

namespace App\Exports;

use App\Models\invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoiceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return invoices::select('invoice_number' , 'invoice_Date' , 'Due_date' , 'product', 'Amount_collection' , 'Amount_Commission' , 'Discount' , 'Status' , 'Payment_Date')->get();

    }
}