@extends('layouts.app')
@section('title','Invoice — '.$service->invoice_number)
@section('page-title','Invoice')

@section('content')
<div style="max-width:800px;margin:0 auto;" class="anim-up">

{{-- Action buttons --}}
<div style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;">
  <a href="{{ route('services.show',$service) }}" class="btn btn-outline">
    <i class="fas fa-arrow-left"></i> Back to Service
  </a>
  <a href="{{ route('invoices.pdf',$service) }}" class="btn btn-navy" target="_blank">
    <i class="fas fa-file-pdf"></i> Download PDF
  </a>
  @if($service->balance > 0)
  <a href="{{ route('services.payments.create',$service) }}" class="btn btn-green">
    <i class="fas fa-money-bill-wave"></i> Record Payment
  </a>
  @endif
  <button onclick="window.print()" class="btn btn-outline">
    <i class="fas fa-print"></i> Print
  </button>
</div>

{{-- Invoice card --}}
<div class="card" style="overflow:hidden;">

  {{-- Header --}}
  <div style="background:var(--navy);padding:24px 28px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">

      {{-- Garage info --}}
      <div>
        <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:700;color:#fff;">
          {{ $settings->garage_name }}
        </div>
        <div style="font-size:.75rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1.5px;margin-top:2px;">
          Auto Garage Management System
        </div>
        <div style="font-size:.78rem;color:rgba(255,255,255,.5);margin-top:8px;line-height:1.8;">
          {{ $settings->garage_address }}
          <br>{{ $settings->garage_phone }}
          @if($settings->garage_email)
            &nbsp;·&nbsp; {{ $settings->garage_email }}
          @endif
          @if($settings->kra_pin)
            <br>KRA PIN: {{ $settings->kra_pin }}
          @endif
        </div>
      </div>

      {{-- Invoice number + status --}}
      <div style="text-align:right;">
        <div style="font-size:.65rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Invoice</div>
        <div style="font-family:var(--font-m);font-size:1.5rem;font-weight:700;color:var(--orange);">
          {{ $service->invoice_number }}
        </div>
        <div style="font-size:.75rem;color:rgba(255,255,255,.4);margin-top:4px;">
          {{ $service->invoice_date?->format('d M Y') }}
        </div>
        @php $ps = $service->payment_status; @endphp
        <div style="margin-top:8px;">
          <span class="badge {{ $ps==='Paid' ? 'badge-green' : ($ps==='Partial' ? 'badge-amber' : 'badge-red') }}">
            {{ $ps }}
          </span>
        </div>
      </div>

    </div>
  </div>

  {{-- Bill To + Vehicle info --}}
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;border-bottom:1px solid var(--g100);">

    <div style="padding:20px 24px;border-right:1px solid var(--g100);">
      <div style="font-size:.65rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Bill To</div>
      <div style="font-family:var(--font-h);font-size:1rem;font-weight:700;color:var(--g900);">
        {{ $service->vehicle->customer->name }}
      </div>
      <div style="font-size:.82rem;color:var(--g500);margin-top:4px;line-height:1.7;">
        {{ $service->vehicle->customer->phone }}
        @if($service->vehicle->customer->email)
          <br>{{ $service->vehicle->customer->email }}
        @endif
        @if($service->vehicle->customer->address)
          <br>{{ $service->vehicle->customer->address }}
        @endif
      </div>
    </div>

    <div style="padding:20px 24px;">
      <div style="font-size:.65rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Vehicle</div>
      <div style="font-family:var(--font-m);font-size:1.1rem;font-weight:700;color:var(--orange);">
        {{ $service->vehicle->registration_no }}
      </div>
      <div style="font-size:.82rem;color:var(--g500);margin-top:4px;line-height:1.7;">
        {{ $service->vehicle->year }} {{ $service->vehicle->make }} {{ $service->vehicle->model }}
        <br>{{ $service->vehicle->color }} &nbsp;·&nbsp; {{ $service->vehicle->category }}
        @if($service->mileage_in)
          <br>Mileage In: {{ number_format($service->mileage_in) }} km
        @endif
        @if($service->mileage_out)
          &nbsp;·&nbsp; Out: {{ number_format($service->mileage_out) }} km
        @endif
      </div>
    </div>

  </div>

  {{-- Service strip --}}
  <div style="background:var(--g50);padding:14px 24px;border-bottom:1px solid var(--g100);display:flex;gap:24px;flex-wrap:wrap;">
    <div>
      <div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">Job Card</div>
      <div style="font-family:var(--font-m);font-weight:700;color:var(--g800);font-size:.88rem;">{{ $service->job_card_no }}</div>
    </div>
    <div>
      <div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">Service Type</div>
      <div style="font-weight:700;color:var(--g800);font-size:.88rem;">{{ $service->service_type }}</div>
    </div>
    <div>
      <div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">Service Date</div>
      <div style="font-weight:700;color:var(--g800);font-size:.88rem;">{{ $service->service_date->format('d M Y') }}</div>
    </div>
    @if($service->completed_at)
    <div>
      <div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">Completed</div>
      <div style="font-weight:700;color:var(--green);font-size:.88rem;">{{ $service->completed_at->format('d M Y') }}</div>
    </div>
    @endif
    @if($service->mechanic)
    <div>
      <div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">Mechanic</div>
      <div style="font-weight:700;color:var(--g800);font-size:.88rem;">{{ $service->mechanic->name }}</div>
    </div>
    @endif
  </div>

  <div style="padding:24px;">

    {{-- Parts --}}
    @if($service->parts->count())
    <div style="margin-bottom:22px;">
      <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
        <i class="fas fa-cogs" style="color:var(--orange);margin-right:6px;"></i> Parts & Materials
      </div>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--g50);">
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Part</th>
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Part No.</th>
            <th style="padding:8px 12px;text-align:right;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Qty</th>
            <th style="padding:8px 12px;text-align:right;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Unit Price</th>
            <th style="padding:8px 12px;text-align:right;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($service->parts as $part)
          <tr>
            <td style="padding:9px 12px;font-size:.83rem;border-bottom:1px solid var(--g100);font-weight:600;color:var(--g800);">{{ $part->name }}</td>
            <td style="padding:9px 12px;font-size:.75rem;border-bottom:1px solid var(--g100);font-family:var(--font-m);color:var(--g400);">{{ $part->part_number ?: '—' }}</td>
            <td style="padding:9px 12px;text-align:right;border-bottom:1px solid var(--g100);font-size:.83rem;">{{ $part->quantity }}</td>
            <td style="padding:9px 12px;text-align:right;border-bottom:1px solid var(--g100);font-family:var(--font-m);font-size:.83rem;">{{ number_format($part->unit_price,2) }}</td>
            <td style="padding:9px 12px;text-align:right;border-bottom:1px solid var(--g100);font-family:var(--font-m);font-size:.83rem;font-weight:700;">{{ number_format($part->total,2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:var(--g50);">
            <td colspan="4" style="padding:9px 12px;font-size:.78rem;color:var(--g500);font-weight:700;">Parts Subtotal</td>
            <td style="padding:9px 12px;text-align:right;font-family:var(--font-m);font-weight:700;color:var(--g800);">KSh {{ number_format($service->parts_total,2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
    @endif

    {{-- Repairs --}}
    @if($service->repairs->count())
    <div style="margin-bottom:22px;">
      <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
        <i class="fas fa-tools" style="color:var(--orange);margin-right:6px;"></i> Repairs
      </div>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--g50);">
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Diagnosis</th>
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Action Taken</th>
            <th style="padding:8px 12px;text-align:right;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Cost</th>
          </tr>
        </thead>
        <tbody>
          @foreach($service->repairs as $repair)
          <tr>
            <td style="padding:9px 12px;font-size:.83rem;border-bottom:1px solid var(--g100);font-weight:600;color:var(--g800);">{{ $repair->diagnosis }}</td>
            <td style="padding:9px 12px;font-size:.83rem;border-bottom:1px solid var(--g100);color:var(--g600);">{{ $repair->action_taken }}</td>
            <td style="padding:9px 12px;text-align:right;border-bottom:1px solid var(--g100);font-family:var(--font-m);font-size:.83rem;font-weight:700;">{{ number_format($repair->cost,2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:var(--g50);">
            <td colspan="2" style="padding:9px 12px;font-size:.78rem;color:var(--g500);font-weight:700;">Repairs Subtotal</td>
            <td style="padding:9px 12px;text-align:right;font-family:var(--font-m);font-weight:700;color:var(--g800);">KSh {{ number_format($service->repairs_total,2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
    @endif

    {{-- Totals sidebar --}}
    <div style="display:flex;justify-content:flex-end;">
      <div style="min-width:300px;border:1px solid var(--g200);border-radius:var(--r);overflow:hidden;">

        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--g500);">Parts</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--g700);">KSh {{ number_format($service->parts_total,2) }}</span>
        </div>

        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--g500);">Repairs</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--g700);">KSh {{ number_format($service->repairs_total,2) }}</span>
        </div>

        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--g500);">Labour</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--g700);">KSh {{ number_format($service->labour_charge,2) }}</span>
        </div>

        @if($service->discount > 0)
        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--red);">Discount</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--red);">- KSh {{ number_format($service->discount,2) }}</span>
        </div>
        @endif

        @if($service->vat_amount > 0)
        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--g500);">VAT</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--g700);">KSh {{ number_format($service->vat_amount,2) }}</span>
        </div>
        @endif

        <div style="display:flex;justify-content:space-between;padding:14px 16px;background:var(--navy);border-bottom:1px solid var(--g100);">
          <span style="font-size:.9rem;font-weight:700;color:#fff;font-family:var(--font-h);">TOTAL</span>
          <span style="font-family:var(--font-m);font-size:1rem;font-weight:700;color:var(--orange);">KSh {{ number_format($service->total_cost,2) }}</span>
        </div>

        <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--g100);">
          <span style="font-size:.82rem;color:var(--green);">Amount Paid</span>
          <span style="font-family:var(--font-m);font-size:.82rem;color:var(--green);font-weight:700;">KSh {{ number_format($service->amount_paid,2) }}</span>
        </div>
<div style="display:flex;justify-content:space-between;padding:12px 16px;background:{{ $service->balance > 0 ? 'var(--red-l)' : 'var(--green-l)' }};">
  <span style="font-size:.85rem;font-weight:700;color:{{ $service->balance > 0 ? 'var(--red)' : 'var(--green)' }};">
    {{ $service->balance > 0 ? 'Balance Due' : 'Fully Paid' }}
  </span>
  @if($service->balance <= 0)
    <span style="color:var(--green);font-size:1.2rem;">
      <i class="fas fa-circle-check"></i>
    </span>
  @else
    <span style="font-family:var(--font-m);font-weight:700;color:var(--red);">
      KSh {{ number_format($service->balance, 2) }}
    </span>
  @endif
</div>

      </div>
    </div>

    {{-- Payments received --}}
    @if($service->payments->count())
    <div style="margin-top:22px;">
      <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
        <i class="fas fa-receipt" style="color:var(--orange);margin-right:6px;"></i> Payments Received
      </div>
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--g50);">
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Date</th>
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Method</th>
            <th style="padding:8px 12px;text-align:left;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Reference</th>
            <th style="padding:8px 12px;text-align:right;font-size:.68rem;color:var(--g500);font-weight:700;text-transform:uppercase;border-bottom:1.5px solid var(--g200);">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($service->payments as $pmt)
          <tr>
            <td style="padding:9px 12px;font-size:.82rem;border-bottom:1px solid var(--g100);">{{ $pmt->payment_date->format('d M Y') }}</td>
            <td style="padding:9px 12px;font-size:.82rem;border-bottom:1px solid var(--g100);">
              <span class="badge {{ $pmt->method==='M-Pesa' ? 'badge-green' : 'badge-amber' }}">
                <i class="fas fa-{{ $pmt->method==='M-Pesa' ? 'mobile-alt' : 'money-bill-wave' }}" style="margin-right:4px;"></i>
                {{ $pmt->method }}
              </span>
            </td>
            <td style="padding:9px 12px;font-size:.78rem;border-bottom:1px solid var(--g100);font-family:var(--font-m);color:var(--g400);">
              {{ $pmt->mpesa_code ?: '—' }}
            </td>
            <td style="padding:9px 12px;text-align:right;border-bottom:1px solid var(--g100);font-family:var(--font-m);font-weight:700;color:var(--green);">
              KSh {{ number_format($pmt->amount,2) }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

    {{-- Footer note --}}
    <div style="margin-top:28px;padding-top:16px;border-top:1px dashed var(--g200);text-align:center;">
      <div style="font-size:.72rem;color:var(--g400);">Thank you for your business. Please retain this invoice for your records.</div>
      @if($settings->garage_phone)
      <div style="font-size:.7rem;color:var(--g400);margin-top:4px;">For inquiries call: {{ $settings->garage_phone }}</div>
      @endif
    </div>

  </div>
</div>
</div>

@push('scripts')
<style>
@media print {
  .sidebar, .topbar, .btn, .page-hdr { display: none !important; }
  .main { margin-left: 0 !important; }
  .content { padding: 0 !important; }
  .card { box-shadow: none !important; border: none !important; }
}
</style>
@endpush
@endsection 