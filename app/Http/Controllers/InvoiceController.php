<?php
namespace App\Http\Controllers;

use App\Models\{Service, GarageSetting};
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller {
    public function show(Service $service) {
        $service->load(['vehicle.customer','mechanic','parts','repairs','payments','checklist']);
        $settings = GarageSetting::get();
        return view('invoices.show', compact('service','settings'));
    }
    public function generate(Service $service) {
        if (!$service->invoice_number) {
            $service->update([
                'invoice_number'    => $service->generateInvoiceNumber(),
                'invoice_date'      => now(),
                'invoice_generated' => true,
            ]);
        }
        return redirect()->route('invoices.show',$service)->with('success','Invoice generated.');
    }
    public function pdf(Service $service) {
        $service->load(['vehicle.customer','mechanic','parts','repairs','payments']);
        $settings = GarageSetting::get();
        $pdf = Pdf::loadView('invoices.pdf', compact('service','settings'))->setPaper('a4','portrait');
        return $pdf->download("Invoice-{$service->invoice_number}.pdf");
    }
}