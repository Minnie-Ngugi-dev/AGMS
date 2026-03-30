@extends('layouts.app')
@section('title','Payments')
@section('page-title','Payments')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">All Payments</div>
    <div class="page-hdr-sub">{{ $payments->total() }} records</div>
  </div>
</div>

@php
  $totalToday = \App\Models\Payment::whereDate('payment_date',today())->sum('amount');
  $totalMonth = \App\Models\Payment::whereMonth('payment_date',now()->month)->sum('amount');
  $totalMpesa = \App\Models\Payment::where('method','M-Pesa')->sum('amount');
  $totalCash  = \App\Models\Payment::where('method','Cash')->sum('amount');
@endphp

<div class="stat-grid anim-up d1">
  <div class="stat-card"><div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-calendar-day"></i></div><div class="stat-info"><div class="stat-val">KSh {{ number_format($totalToday) }}</div><div class="stat-lbl">Today</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:var(--sky-l);color:var(--sky);"><i class="fas fa-calendar-alt"></i></div><div class="stat-info"><div class="stat-val">KSh {{ number_format($totalMonth) }}</div><div class="stat-lbl">This Month</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-mobile-alt"></i></div><div class="stat-info"><div class="stat-val">KSh {{ number_format($totalMpesa) }}</div><div class="stat-lbl">M-Pesa Total</div></div></div>
  <div class="stat-card"><div class="stat-icon" style="background:var(--amber-l);color:var(--amber);"><i class="fas fa-money-bill-wave"></i></div><div class="stat-info"><div class="stat-val">KSh {{ number_format($totalCash) }}</div><div class="stat-lbl">Cash Total</div></div></div>
</div>

<div class="card anim-up d2">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Date</th><th>Vehicle</th><th>Customer</th><th>Job Card</th><th>Method</th><th>M-Pesa Code</th><th>Amount</th><th>Status</th></tr>
      </thead>
      <tbody>
        @forelse($payments as $p)
        <tr>
          <td style="font-size:.82rem;">{{ $p->payment_date->format('d M Y') }}</td>
          <td><a href="{{ route('vehicles.show',$p->service->vehicle) }}" style="font-family:var(--font-m);font-weight:700;color:var(--navy);text-decoration:none;">{{ $p->service->vehicle->registration_no }}</a></td>
          <td style="font-size:.82rem;">{{ $p->service->vehicle->customer->name }}</td>
          <td><a href="{{ route('services.show',$p->service) }}" style="font-family:var(--font-m);font-size:.76rem;color:var(--sky);text-decoration:none;">{{ $p->service->job_card_no }}</a></td>
          <td>
            <div style="display:flex;align-items:center;gap:6px;">
              <div style="width:26px;height:26px;border-radius:7px;background:{{ $p->method==='M-Pesa'?'var(--green-l)':'var(--amber-l)' }};color:{{ $p->method==='M-Pesa'?'var(--green)':'var(--amber)' }};display:flex;align-items:center;justify-content:center;font-size:.72rem;"><i class="fas fa-{{ $p->method==='M-Pesa'?'mobile-alt':'money-bill-wave' }}"></i></div>
              <span style="font-size:.82rem;font-weight:500;">{{ $p->method }}</span>
            </div>
          </td>
          <td><span style="font-family:var(--font-m);font-size:.75rem;color:var(--g400);">{{ $p->mpesa_code??'—' }}</span></td>
          <td style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($p->amount,2) }}</td>
          <td><span class="badge {{ $p->status==='Paid'?'badge-green':($p->status==='Partial'?'badge-amber':'badge-red') }}">{{ $p->status }}</span></td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-money-bill-wave"></i></div><div class="empty-sub">No payments yet.</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($payments->hasPages())
  <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:.78rem;color:var(--g500);">{{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }}</div>
    <div class="pagination">
      @if(!$payments->onFirstPage())<a href="{{ $payments->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($payments->getUrlRange(max(1,$payments->currentPage()-2),min($payments->lastPage(),$payments->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$payments->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($payments->hasMorePages())<a href="{{ $payments->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection