@extends('layouts.app')
@section('title','Help')
@section('page-title','Help & Documentation')

@section('content')
<div style="max-width:860px;margin:0 auto;">

  <div style="background:linear-gradient(135deg,var(--navy),var(--navy-m));border-radius:var(--r-lg);padding:32px 36px;margin-bottom:24px;display:flex;align-items:center;gap:22px;" class="anim-up">
    <div style="width:60px;height:60px;background:var(--orange);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;flex-shrink:0;"><i class="fas fa-circle-question"></i></div>
    <div>
      <div style="font-family:var(--font-h);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:5px;">AGMS Help Centre</div>
      <div style="font-size:.85rem;color:rgba(255,255,255,.5);">Auto Garage Management System — Quick Reference Guide</div>
    </div>
  </div>

  @php
  $sections=[
    ['Getting Started','rocket','orange',[
      ['Creating a New Service','Go to <strong>Service → New</strong>. Fill in vehicle details, customer details, and select service type. Click <em>Create Service</em> to generate a job card.'],
      ['Returning Customers','Click <strong>Returning Customer</strong> on the new service form. Search by plate number or customer name to pre-fill the form automatically.'],
      ['Service Types','<strong>Regular Service</strong> — due every 5,000 km or 90 days. <strong>Full Service</strong> — due every 10,000 km or 180 days.'],
    ]],
    ['Service Workflow','list-check','sky',[
      ['Step 1 — Create Service','Enter vehicle, customer, driver details and service type. A Job Card number (JC-XXXXX) is automatically generated.'],
      ['Step 2 — Checklist','Fill the service bay checklist. Tick every item inspected.'],
      ['Step 3 — Parts & Repairs','Add any parts replaced and log any repairs or diagnostics found.'],
      ['Step 4 — Mark Complete','Once all work is done click <em>Mark Complete</em>. An invoice is auto-generated.'],
      ['Step 5 — Payment','Record payment via Cash or M-Pesa. The system tracks paid, partial, and unpaid status.'],
    ]],
    ['Payments','money-bill-wave','green',[
      ['Cash Payment','Select <strong>Cash</strong>, the amount is pre-filled to the exact balance. Click Confirm.'],
      ['M-Pesa Payment','Select <strong>M-Pesa</strong>, enter the M-Pesa transaction code (e.g. QJK2M3XXXX).'],
      ['Payment Status','<strong>Unpaid</strong> — no payment recorded. <strong>Partial</strong> — some amount paid. <strong>Paid</strong> — balance is zero.'],
    ]],
    ['Reports','chart-bar','purple',[
      ['By Date','Filter all services by date range. View totals for parts, repairs, and revenue.'],
      ['By Vehicle Type','See which vehicle categories generate the most services and revenue.'],
      ['By Service Type','Compare Regular vs Full service counts and revenue over any period.'],
      ['Custom Report','Combine any filters — date, type, status, mechanic, category — to build a tailored report.'],
    ]],
    ['Admin','shield-halved','red',[
      ['Adding Users','Go to <strong>Admin → Users</strong>. Add staff with roles: Administrator, Supervisor, Receptionist, or Mechanic.'],
      ['Service Intervals','Go to <strong>Admin → Settings → System</strong> to change default km and day intervals.'],
      ['VAT Settings','Go to <strong>Admin → Settings → Billing</strong>. Enable VAT and set the rate (default 16% for Kenya).'],
    ]],
  ];
  @endphp

  @foreach($sections as [$title,$ico,$clr,$items])
  <div class="card anim-up d{{ $loop->index+1 }}" style="margin-bottom:14px;">
    <div class="card-header">
      <div class="card-title">
        <span style="width:28px;height:28px;background:var(--{{ $clr }}-l,var(--g100));border-radius:7px;display:inline-flex;align-items:center;justify-content:center;margin-right:9px;"><i class="fas fa-{{ $ico }}" style="color:var(--{{ $clr }});font-size:.78rem;"></i></span>
        {{ $title }}
      </div>
    </div>
    <div class="card-body" style="padding:14px 20px;">
      @foreach($items as [$q,$a])
      <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:{{ !$loop->last?'1px solid var(--g100)':'none' }};">
        <div style="font-weight:700;color:var(--g800);font-size:.87rem;margin-bottom:4px;display:flex;align-items:flex-start;gap:7px;"><i class="fas fa-chevron-right" style="color:var(--{{ $clr }});font-size:.62rem;margin-top:4px;flex-shrink:0;"></i>{{ $q }}</div>
        <div style="font-size:.8rem;color:var(--g600);line-height:1.65;padding-left:15px;">{!! $a !!}</div>
      </div>
      @endforeach
    </div>
  </div>
  @endforeach

  <div class="card anim-up" style="margin-bottom:20px;">
    <div class="card-body" style="text-align:center;padding:24px;">
      <div style="font-size:.68rem;color:var(--g400);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px;">AGMS — Auto Garage Management System</div>
      <div style="font-size:.8rem;color:var(--g500);">Built with Laravel · Designed for Kenyan garages · KRA compliant</div>
    </div>
  </div>
</div>
@endsection