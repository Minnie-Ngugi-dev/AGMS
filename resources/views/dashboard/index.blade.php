@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- Quick Actions --}}
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;" class="anim-up">
  <a href="{{ route('services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Service</a>
  <a href="{{ route('services.create') }}?type=returning" class="btn btn-navy"><i class="fas fa-rotate-right"></i> Returning Customer</a>
  <a href="{{ route('vehicles.index') }}" class="btn btn-outline"><i class="fas fa-car"></i> Find Vehicle</a>
  <a href="{{ route('reports.date') }}" class="btn btn-outline"><i class="fas fa-chart-bar"></i> Reports</a>
</div>

{{-- Stats --}}
<div class="stat-grid anim-up d1">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--sky-l);color:var(--sky);"><i class="fas fa-wrench"></i></div>
    <div class="stat-info"><div class="stat-val">{{ $stats['active_services'] }}</div><div class="stat-lbl">Active Services</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-check-circle"></i></div>
    <div class="stat-info"><div class="stat-val">{{ $stats['completed_today'] }}</div><div class="stat-lbl">Completed Today</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--purple-l);color:var(--purple);"><i class="fas fa-users"></i></div>
    <div class="stat-info"><div class="stat-val">{{ $stats['total_customers'] }}</div><div class="stat-lbl">Total Customers</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--orange-l);color:var(--orange);"><i class="fas fa-money-bill-wave"></i></div>
    <div class="stat-info"><div class="stat-val">KSh {{ number_format($stats['revenue_today']) }}</div><div class="stat-lbl">Revenue Today</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--navy-l);color:var(--navy);"><i class="fas fa-car"></i></div>
    <div class="stat-info"><div class="stat-val">{{ $stats['total_vehicles'] }}</div><div class="stat-lbl">Total Vehicles</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--red-l);color:var(--red);"><i class="fas fa-triangle-exclamation"></i></div>
    <div class="stat-info"><div class="stat-val">{{ $stats['overdue_services'] }}</div><div class="stat-lbl">Overdue</div></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:18px;">
  <div>
    {{-- Active Jobs --}}
    <div class="card anim-up d2" style="margin-bottom:18px;">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-list-check" style="color:var(--orange);margin-right:6px;"></i>Active Jobs</div>
        <a href="{{ route('services.index') }}" class="btn btn-ghost btn-sm">View All <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr><th>Job Card</th><th>Vehicle</th><th>Customer</th><th>Type</th><th>Mechanic</th><th>Payment</th><th>Status</th><th></th></tr>
          </thead>
          <tbody>
            @forelse($activeServices as $s)
            <tr>
              <td><span style="font-family:var(--font-m);font-size:.76rem;font-weight:700;color:var(--navy);">{{ $s->job_card_no }}</span></td>
              <td><span style="font-family:var(--font-m);font-weight:700;color:var(--green);">{{ $s->vehicle->registration_no }}</span></td>
              <td style="font-size:.8rem;">{{ $s->vehicle->customer->name }}</td>
              <td><span class="badge {{ $s->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $s->service_type }}</span></td>
              <td style="font-size:.78rem;color:var(--g500);">{{ $s->mechanic?->name ?? '—' }}</td>
              <td>
                @php $ps = $s->payment_status; @endphp
                <span class="badge {{ $ps==='Paid'?'badge-green':($ps==='Partial'?'badge-amber':'badge-red') }}">{{ $ps }}</span>
              </td>
              <td>
                @php $sc=['pending'=>'badge-amber','in-progress'=>'badge-sky','completed'=>'badge-green','cancelled'=>'badge-red']; @endphp
                <span class="badge {{ $sc[$s->status]??'badge-gray' }}">{{ ucfirst(str_replace('-',' ',$s->status)) }}</span>
              </td>
              <td><a href="{{ route('services.show',$s) }}" class="btn btn-ghost btn-sm btn-icon"><i class="fas fa-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state" style="padding:24px;"><div class="empty-icon"><i class="fas fa-wrench"></i></div><div class="empty-sub">No active services right now.</div></div></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Charts --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;" class="anim-up d3">
      <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-car" style="color:var(--navy);margin-right:6px;"></i>Vehicles by Category</div></div>
        <div class="card-body" style="padding:14px 18px;">
          @foreach(['Car'=>'sky','Van'=>'green','Mini Truck'=>'amber','Truck'=>'orange','Trailer'=>'purple'] as $cat=>$clr)
          @php $cnt = $vehiclesByCategory[$cat]??0; $max=$vehiclesByCategory->max()||1; @endphp
          <div style="margin-bottom:10px;">
            <div style="display:flex;justify-content:space-between;font-size:.75rem;margin-bottom:4px;">
              <span style="color:var(--g600);">{{ $cat }}</span><span style="font-family:var(--font-m);font-weight:700;color:var(--g800);">{{ $cnt }}</span>
            </div>
            <div style="height:7px;background:var(--g100);border-radius:4px;overflow:hidden;">
              <div style="height:100%;width:{{ $max>0?round($cnt/$max*100):0 }}%;background:var(--{{ $clr }});border-radius:4px;transition:width .6s;"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="card">
        <div class="card-header"><div class="card-title"><i class="fas fa-chart-pie" style="color:var(--orange);margin-right:6px;"></i>Service Types</div></div>
        <div class="card-body" style="padding:14px 18px;">
          @php $total = $serviceTypeStats->sum('count'); @endphp
          @foreach($serviceTypeStats as $st)
          @php $pct = $total > 0 ? round($st->count / $total * 100) : 0; @endphp
          <div style="margin-bottom:14px;">
            <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:5px;">
              <span class="badge {{ $st->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $st->service_type }}</span>
              <span style="font-family:var(--font-m);font-weight:700;">{{ $st->count }} ({{ $pct }}%)</span>
            </div>
            <div style="height:10px;background:var(--g100);border-radius:5px;overflow:hidden;">
              <div style="height:100%;width:{{ $pct }}%;background:{{ $st->service_type==='Full'?'var(--orange)':'var(--sky)' }};border-radius:5px;"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div>
    {{-- Overdue --}}
    <div class="card anim-up d2" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title" style="color:var(--red);"><i class="fas fa-triangle-exclamation" style="margin-right:6px;"></i>Overdue Alerts</div></div>
      <div class="card-body" style="padding:12px 16px;">
        @forelse($overdueServices as $v)
        <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--red-l);border:1px solid #fca5a5;border-radius:var(--r-sm);margin-bottom:7px;">
          <div style="flex:1;">
            <div style="font-family:var(--font-m);font-weight:700;color:var(--red);font-size:.85rem;">{{ $v->registration_no }}</div>
            <div style="font-size:.7rem;color:var(--g500);">Due {{ $v->next_service_date->diffForHumans() }}</div>
          </div>
          <a href="{{ route('vehicles.show',$v) }}" class="btn btn-ghost btn-sm btn-icon"><i class="fas fa-eye"></i></a>
        </div>
        @empty
        <div class="empty-state" style="padding:16px;"><div class="empty-icon" style="width:40px;height:40px;font-size:.9rem;"><i class="fas fa-check"></i></div><div class="empty-sub" style="margin-bottom:0;">All vehicles up to date!</div></div>
        @endforelse
      </div>
    </div>

    {{-- Recent Payments --}}
    <div class="card anim-up d3" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-money-bill-wave" style="color:var(--green);margin-right:6px;"></i>Recent Payments</div></div>
      <div class="card-body" style="padding:12px 16px;">
        @forelse($recentPayments as $pmt)
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--g100);">
          <div style="width:32px;height:32px;border-radius:8px;background:{{ $pmt->method==='M-Pesa'?'var(--green-l)':'var(--amber-l)' }};color:{{ $pmt->method==='M-Pesa'?'var(--green)':'var(--amber)' }};display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0;"><i class="fas fa-{{ $pmt->method==='M-Pesa'?'mobile-alt':'money-bill-wave' }}"></i></div>
          <div style="flex:1;">
            <div style="font-size:.8rem;font-weight:600;color:var(--g800);">{{ $pmt->service->vehicle->registration_no }}</div>
            <div style="font-size:.68rem;color:var(--g400);">{{ $pmt->payment_date->format('d M') }} · {{ $pmt->method }}</div>
          </div>
          <div style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--green);">KSh {{ number_format($pmt->amount) }}</div>
        </div>
        @empty
        <div class="empty-sub" style="text-align:center;font-size:.8rem;padding:12px;">No payments today.</div>
        @endforelse
      </div>
    </div>

    {{-- Upcoming --}}
    <div class="card anim-up d4">
      <div class="card-header"><div class="card-title"><i class="fas fa-calendar" style="color:var(--sky);margin-right:6px;"></i>Upcoming Services</div></div>
      <div class="card-body" style="padding:12px 16px;">
        @forelse($upcomingServices as $v)
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--g100);">
          <div style="flex:1;">
            <div style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--navy);">{{ $v->registration_no }}</div>
            <div style="font-size:.7rem;color:var(--g500);">{{ $v->make }} {{ $v->model }}</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:.75rem;font-weight:600;color:var(--sky);">{{ $v->next_service_date->format('d M Y') }}</div>
            <div style="font-size:.65rem;color:var(--g400);">{{ $v->next_service_date->diffForHumans() }}</div>
          </div>
        </div>
        @empty
        <div class="empty-sub" style="text-align:center;font-size:.8rem;padding:12px;">No upcoming services.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection