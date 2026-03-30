<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1E293B;background:#fff;}
.header{background:#0F2040;padding:22px 26px;}
.header-inner{display:flex;justify-content:space-between;align-items:flex-start;}
.logo-name{font-size:17px;font-weight:700;color:#fff;}
.logo-sub{font-size:8px;color:rgba(255,255,255,.4);margin-top:2px;letter-spacing:1.5px;text-transform:uppercase;}
.garage-info{font-size:9px;color:rgba(255,255,255,.5);margin-top:7px;line-height:1.8;}
.invoice-no{text-align:right;}
.invoice-no-lbl{font-size:8px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;}
.invoice-no-val{font-size:15px;font-weight:700;color:#FF6B2C;margin-top:3px;}
.invoice-date{font-size:9px;color:rgba(255,255,255,.45);margin-top:4px;}
.bill-row{display:flex;border-bottom:1px solid #E2E8F0;}
.bill-cell{padding:14px 20px;flex:1;}
.bill-cell:first-child{border-right:1px solid #E2E8F0;}
.bill-lbl{font-size:8px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94A3B8;margin-bottom:6px;}
.bill-name{font-size:12px;font-weight:700;color:#1E293B;}
.bill-info{font-size:9.5px;color:#64748B;margin-top:3px;line-height:1.7;}
.vehicle-reg{font-size:13px;font-weight:700;color:#16A34A;font-family:monospace;}
.strip{display:flex;background:#F8FAFC;border-bottom:1px solid #E2E8F0;}
.strip-cell{flex:1;padding:9px 18px;border-right:1px solid #E2E8F0;}
.strip-cell:last-child{border-right:none;}
.strip-lbl{font-size:7.5px;color:#94A3B8;text-transform:uppercase;letter-spacing:.8px;margin-bottom:3px;}
.strip-val{font-size:10.5px;font-weight:700;color:#334155;}
table{width:100%;border-collapse:collapse;}
thead th{padding:9px 15px;text-align:left;font-size:7.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#64748B;background:#F8FAFC;border-bottom:1px solid #E2E8F0;}
thead th:last-child,thead th:nth-child(3),thead th:nth-child(4){text-align:right;}
tbody td{padding:9px 15px;font-size:10px;color:#334155;border-bottom:1px solid #F1F5F9;}
tbody td:last-child,tbody td:nth-child(3),tbody td:nth-child(4){text-align:right;font-weight:700;}
.group-row td{background:#F1F5F9;font-weight:700;font-size:8.5px;color:#64748B;text-transform:uppercase;letter-spacing:1px;padding:6px 15px;}
.totals-area{display:flex;justify-content:flex-end;padding:14px 18px;border-top:1px solid #E2E8F0;}
.totals-box{width:240px;}
.total-row{display:flex;justify-content:space-between;padding:5px 0;font-size:10px;border-bottom:1px solid #F1F5F9;}
.total-row-lbl{color:#64748B;}
.total-row-val{font-weight:700;font-family:monospace;}
.grand-row{background:#0F2040;padding:11px 13px;display:flex;justify-content:space-between;border-radius:5px;margin-top:7px;}
.grand-lbl{font-weight:700;color:rgba(255,255,255,.65);font-size:10px;}
.grand-val{font-weight:700;color:#fff;font-size:12px;font-family:monospace;}
.pmt-history{padding:10px 18px;border-top:2px dashed #E2E8F0;}
.ph-title{font-size:7.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94A3B8;margin-bottom:7px;}
.ph-row{display:flex;justify-content:space-between;align-items:center;padding:5px 9px;background:#F8FAFC;border-radius:4px;margin-bottom:4px;font-size:9.5px;}
.balance-bar{padding:11px 18px;display:flex;justify-content:space-between;align-items:center;}
.paid-bar{background:#DCFCE7;border-top:2px dashed #86EFAC;}
.unpaid-bar{background:#FEE2E2;border-top:2px dashed #FCA5A5;}
.partial-bar{background:#FEF3C7;border-top:2px dashed #FCD34D;}
.footer{padding:12px 18px;text-align:center;font-size:8.5px;color:#94A3B8;border-top:1px solid #E2E8F0;}
</style>
</head>
<body>
<div class="header">
  <div class="header-inner">
    <div>
      <div class="logo-name">{{ $settings->garage_name }}</div>
      <div class="logo-sub">Auto Garage Management System</div>
      <div class="garage-info">{{ $settings->garage_address }}<br>{{ $settings->garage_phone }}@if($settings->garage_email) · {{ $settings->garage_email }}@endif@if($settings->kra_pin)<br>KRA PIN: {{ $settings->kra_pin }}@endif</div>
    </div>
    <div class="invoice-no">
      <div class="invoice-no-lbl">Invoice</div>
      <div class="invoice-no-val">{{ $service->invoice_number }}</div>
      <div class="invoice-date">Date: {{ $service->invoice_date?->format('d M Y') }}</div>
      @php $ps=$service->payment_status; @endphp
      <div style="margin-top:5px;display:inline-block;background:{{ $ps==='Paid'?'#16A34A':($ps==='Partial'?'#D97706':'#DC2626') }};color:#fff;padding:2px 9px;border-radius:4px;font-size:8.5px;font-weight:700;">{{ $ps }}</div>
    </div>
  </div>
</div>

<div class="bill-row">
  <div class="bill-cell">
    <div class="bill-lbl">Bill To</div>
    <div class="bill-name">{{ $service->vehicle->customer->name }}</div>
    <div class="bill-info">{{ $service->vehicle->customer->phone }}<br>{{ $service->vehicle->customer->email }}<br>{{ $service->vehicle->customer->address }}</div>
  </div>
  <div class="bill-cell">
    <div class="bill-lbl">Vehicle</div>
    <div class="vehicle-reg">{{ $service->vehicle->registration_no }}</div>
    <div class="bill-info">{{ $service->vehicle->make }} {{ $service->vehicle->model }} ({{ $service->vehicle->year }})<br>@if($service->vehicle->chassis_no)Chassis: {{ $service->vehicle->chassis_no }}@endif</div>
  </div>
</div>

<div class="strip">
  <div class="strip-cell"><div class="strip-lbl">Job Card</div><div class="strip-val">{{ $service->job_card_no }}</div></div>
  <div class="strip-cell"><div class="strip-lbl">Service Type</div><div class="strip-val">{{ $service->service_type }}</div></div>
  <div class="strip-cell"><div class="strip-lbl">Date</div><div class="strip-val">{{ $service->service_date->format('d M Y') }}</div></div>
  <div class="strip-cell"><div class="strip-lbl">Mechanic</div><div class="strip-val">{{ $service->mechanic?->name??'—' }}</div></div>
  <div class="strip-cell"><div class="strip-lbl">Mileage In</div><div class="strip-val">{{ number_format($service->mileage_in) }} km</div></div>
</div>

<table>
  <thead><tr><th>Description</th><th>Part No.</th><th>Qty</th><th>Unit Price</th><th>Amount</th></tr></thead>
  <tbody>
    @if($service->parts->count())
    <tr class="group-row"><td colspan="5">Parts & Materials</td></tr>
    @foreach($service->parts as $p)
    <tr><td>{{ $p->name }}</td><td style="font-family:monospace;font-size:9px;color:#64748B;">{{ $p->part_number??'—' }}</td><td>{{ $p->quantity }}</td><td>KSh {{ number_format($p->unit_price,2) }}</td><td>KSh {{ number_format($p->total,2) }}</td></tr>
    @endforeach
    @endif
    @if($service->repairs->count())
    <tr class="group-row"><td colspan="5">Labour & Repairs</td></tr>
    @foreach($service->repairs as $r)
    <tr><td>{{ $r->diagnosis }}@if($r->action_taken)<br><span style="font-size:8.5px;color:#64748B;">{{ $r->action_taken }}</span>@endif</td><td>—</td><td>1</td><td>KSh {{ number_format($r->cost,2) }}</td><td>KSh {{ number_format($r->cost,2) }}</td></tr>
    @endforeach
    @endif
    @if($service->labour_charge > 0)
    <tr><td>General Labour</td><td>—</td><td>1</td><td>KSh {{ number_format($service->labour_charge,2) }}</td><td>KSh {{ number_format($service->labour_charge,2) }}</td></tr>
    @endif
  </tbody>
</table>

<div class="totals-area">
  <div class="totals-box">
    <div class="total-row"><span class="total-row-lbl">Subtotal</span><span class="total-row-val">KSh {{ number_format($service->subtotal+$service->discount,2) }}</span></div>
    @if($service->discount>0)<div class="total-row"><span class="total-row-lbl">Discount</span><span class="total-row-val" style="color:#DC2626;">− KSh {{ number_format($service->discount,2) }}</span></div>@endif
    @if($service->vat_amount>0)<div class="total-row"><span class="total-row-lbl">VAT ({{ $settings->vat_rate }}%)</span><span class="total-row-val" style="color:#D97706;">KSh {{ number_format($service->vat_amount,2) }}</span></div>@endif
    <div class="grand-row"><span class="grand-lbl">TOTAL DUE</span><span class="grand-val">KSh {{ number_format($service->total_cost,2) }}</span></div>
  </div>
</div>

@if($service->payments->count())
<div class="pmt-history">
  <div class="ph-title">Payments Received</div>
  @foreach($service->payments as $pmt)
  <div class="ph-row">
    <span>{{ $pmt->method }}@if($pmt->mpesa_code) <span style="font-family:monospace;font-size:8.5px;color:#64748B;">{{ $pmt->mpesa_code }}</span>@endif</span>
    <span>{{ $pmt->payment_date->format('d M Y') }}</span>
    <span style="font-weight:700;color:#16A34A;">KSh {{ number_format($pmt->amount,2) }}</span>
  </div>
  @endforeach
</div>
@endif

<div class="{{ $service->balance<=0?'paid-bar':($service->amount_paid>0?'partial-bar':'unpaid-bar') }} balance-bar">
  <div>
    <div style="font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:{{ $service->balance<=0?'#16A34A':($service->amount_paid>0?'#D97706':'#DC2626') }};">{{ $service->balance<=0?'FULLY PAID':'BALANCE DUE' }}</div>
    <div style="font-size:8.5px;color:#64748B;margin-top:2px;">{{ $service->balance<=0?'Thank you for your payment!':'Please settle the remaining balance.' }}</div>
  </div>
  <div style="font-size:13px;font-weight:700;font-family:monospace;color:{{ $service->balance<=0?'#16A34A':($service->amount_paid>0?'#D97706':'#DC2626') }};">KSh {{ number_format(abs($service->balance),2) }}</div>
</div>

<div class="footer">Thank you for choosing <strong>{{ $settings->garage_name }}</strong>. This is a computer-generated invoice. &nbsp;|&nbsp; Printed: {{ now()->format('d M Y H:i') }}</div>
</body>
</html>