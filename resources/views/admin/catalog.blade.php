@extends('layouts.app')
@section('title','Service Catalog')
@section('page-title','Admin — Catalog')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">Service Catalog</div>
    <div class="page-hdr-sub">Reference items and standard charges</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:18px;" class="anim-up d1">
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-list" style="color:var(--orange);margin-right:6px;"></i>Standard Service Items</div></div>
    <div class="card-body">
      @php
      $catalog=[
        'Engine & Fluids'=>[['Oil & Filter Change',800],['Air Filter Replacement',1200],['Transmission Fluid',600],['Brake Fluid Top-up',400],['Coolant Top-up',500]],
        'Tyres & Brakes'=>[['Tyre Pressure Check',200],['Brake Pad Inspection',300],['Shock Absorber Check',300],['Wheel Alignment Check',500]],
        'Electrical'=>[['Battery Check',300],['Lights Inspection',200],['Wiper Replacement',600]],
        'General'=>[['Chassis Lubrication',400],['Exhaust Check',300],['Leak Inspection',200],['Belt & Hose Check',300]],
      ];
      @endphp
      @foreach($catalog as $group=>$items)
      <div style="margin-bottom:20px;">
        <div style="font-size:.68rem;font-weight:700;color:var(--g500);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;padding-bottom:5px;border-bottom:2px solid var(--g200);">{{ $group }}</div>
        @foreach($items as [$name,$price])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 12px;background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);margin-bottom:5px;">
          <div style="display:flex;align-items:center;gap:8px;"><div style="width:7px;height:7px;border-radius:50%;background:var(--orange);"></div><span style="font-size:.83rem;font-weight:500;color:var(--g800);">{{ $name }}</span></div>
          <span style="font-family:var(--font-m);font-size:.83rem;font-weight:700;color:var(--green);">KSh {{ number_format($price) }}</span>
        </div>
        @endforeach
      </div>
      @endforeach
    </div>
  </div>

  <div>
    <div class="card anim-up d2" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-info-circle" style="color:var(--sky);margin-right:6px;"></i>About Catalog</div></div>
      <div class="card-body" style="font-size:.83rem;color:var(--g600);line-height:1.7;">
        The service catalog lists standard items and reference prices. Use these as a guide when adding parts or repairs to a service job.
      </div>
    </div>
    <div class="card anim-up d3">
      <div class="card-header"><div class="card-title"><i class="fas fa-chart-pie" style="color:var(--orange);margin-right:6px;"></i>Service Intervals</div></div>
      <div class="card-body" style="padding:14px;">
        <div style="background:var(--sky-l);border:1px solid #7dd3fc;border-radius:var(--r-sm);padding:14px;margin-bottom:10px;"><div style="font-weight:700;color:var(--sky);margin-bottom:5px;font-size:.86rem;"><i class="fas fa-oil-can" style="margin-right:5px;"></i>Regular Service</div><div style="font-size:.8rem;color:var(--g700);">Every <strong>5,000 km</strong> or <strong>90 days</strong></div></div>
        <div style="background:var(--orange-l);border:1px solid #fdba74;border-radius:var(--r-sm);padding:14px;"><div style="font-weight:700;color:var(--orange);margin-bottom:5px;font-size:.86rem;"><i class="fas fa-star" style="margin-right:5px;"></i>Full Service</div><div style="font-size:.8rem;color:var(--g700);">Every <strong>10,000 km</strong> or <strong>180 days</strong></div></div>
        <a href="{{ route('admin.settings') }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:12px;"><i class="fas fa-gear"></i> Change in Settings</a>
      </div>
    </div>
  </div>
</div>
@endsection