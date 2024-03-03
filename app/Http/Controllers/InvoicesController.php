<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\sections;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoices::all();
        return view('invoices.invoices' , compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices.add_invoice' , compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number'    => $request->invoice_number,
            'invoice_Date'      => $request->invoice_Date,
            'Due_date'          => $request->Due_date,
            'product'           => $request->product,
            'section_id'        => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount'          => $request->Discount,
            'Value_VAT'         => $request->Value_VAT,
            'Rate_VAT'          => $request->Rate_VAT,
            'Total'             => $request->Total,
            'Status'            => 'غير مدفوعة',
            'Value_Status'      => 2,
            'note'              => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice'     => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product'        => $request->product,
            'Section'        => $request->Section,
            'Status'         => 'غير مدفوعة',
            'Value_Status'   => 2,
            'note'           => $request->note,
            'user'           => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id                  = Invoices::latest()->first()->id;
            $image                       = $request->file('pic');
            $file_name                   = $image->getClientOriginalName();
            $invoice_number              = $request->invoice_number;

            $attachments                 = new invoice_attachments();
            $attachments->file_name      = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by     = Auth::user()->name;
            $attachments->invoice_id     = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }


        //    $user = User::first();
        //    Notification::send($user, new AddInvoice($invoice_id));

        // $user = User::get();
        // $invoices = invoices::latest()->first();
        // Notification::send($user, new \App\Notifications\Add_invoice_new($invoices));

     




        
        // event(new MyEventClass('hello world'));

        session()->flash('Add');
        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = invoices::where('id' , $id)->first();
        return view('invoices.status_update' , compact('invoice'));
    }


    public function showPaidInvoice()
    {
        $invoices= invoices::where('Value_Status' , 1)->get();
        return view('invoices.invoices_paid' , compact('invoices'));
    }


    public function showUnpaidInvoice()
    {
        $invoices= invoices::where('Value_Status' , 2)->get();
        return view('invoices.invoices_unpaid' , compact('invoices'));
    }


    public function showPartialInvoice()
    {
        $invoices= invoices::where('Value_Status' , 3)->get();
        return view('invoices.invoices_Partial' , compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = invoices::findOrFail($id);
        $sections = sections::all();
        return view('invoices.edit_invoice' , compact('invoice' , 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        $invoice = invoices::findOrFail($request->invoice_id);
        $invoice->update([
            'invoice_number'     => $request->invoice_number,
            'invoice_Date'       => $request->invoice_Date,
            'Due_date'           => $request->Due_date,
            'section_id'         => $request->Section,
            'product'            => $request->product,
            'Amount_collection'  => $request->Amount_collection,
            'Amount_Commission'  => $request->Amount_Commission,
            'Discount'           => $request->Discount,
            'Rate_VAT'           => $request->Rate_VAT,
            'Value_VAT'          => $request->Value_VAT,
            'Total'              => $request->Total,
            'note'               => $request->note

        ]);
        session()->flash('Status_Update');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // $invoice = invoices::findOrFail($request->invoice_id);
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $attachments = invoice_attachments::where('invoice_id' , $request->invoice_id)->first();
        $id_page = $request->id_page;
        
        if(!$id_page == 2){
            if(!empty($attachments->invoice_number)){
                Storage::disk('public_uploads')->deleteDirectory($attachments->invoice_number);
            }
            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        }
        else{
            $invoices->delete();
            session()->flash('archieve_invoice');
            return redirect('/invoices');
        }
    }


    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function Status_Update(Request $request , $id)
    {
        $invoice = invoices::findOrFail($id);
        if($request->Status === 'مدفوعة'){
            $invoice->update([
                'Value_Status' => 1 ,
                'Status'       => $request->Status,
                'Payment_Date' => $request->Payment_Date
            ] 
            );
          
          invoices_details::create([
            'id_Invoice'        => $request->invoice_id,
            'invoice_number'    => $request->invoice_number,
            'product'           => $request->product,
            'Section'           => $request->Section,
            'Value_Status'      => 1,
            'Status'            => $request->Status,
            'Payment_Date'      => $request->Payment_Date,
            'user'              => (Auth::user()->name),

        ]);
        }else{
            $invoice->update([
                'Value_Status' => 3 ,
                'Status'       => $request->Status,
                'Payment_Date' => $request->Payment_Date
            ] 
          );
          
          invoices_details::create([
            'id_Invoice'        => $request->invoice_id,
            'invoice_number'    => $request->invoice_number,
            'product'           => $request->product,
            'Section'           => $request->Section,
            'Value_Status'      => 3,
            'Status'            => $request->Status,
            'Payment_Date'      => $request->Payment_Date,
            'user'              => (Auth::user()->name),
          ]);
          
        }
        
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    public function Print_invoice($id)
    {
        $invoice = invoices::where('id' ,$id)->first();
        return view('invoices.Print_invoice' , compact('invoice'));
    }
    
}