@extends('layouts.app')
@section('title','Repairs')
@section('page-title','Repairs & Diagnostics')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">All Repairs</div>
    <div class="page-hdr-sub">{{ $repairs->total() }} records</div>
  </div>
</div>

<div class="card anim-up d1">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Diagnosis</th><th>Action Taken</th><th>Vehicle</th><th>Job Card</th><th>Status</th><th>Cost</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($repairs as $r)
        <tr>
          <td style="font-weight:600;color:var(--g800);max-width:200px;">{{ $r->diagnosis }}</td>
          <td style="font-size:.8rem;color:var(--g500);max-width:200px;">{{ Str::limit($r->action_taken,60)??'—' }}</td>
          <td><a href="{{ route('vehicles.show',$r->service->vehicle) }}" style="font-family:var(--font-m);font-weight:700;color:var(--navy);text-decoration:none;">{{ $r->service->vehicle->registration_no }}</a></td>
          <td><a href="{{ route('services.show',$r->service) }}" style="font-family:var(--font-m);font-size:.78rem;color:var(--sky);text-decoration:none;">{{ $r->service->job_card_no }}</a></td>
          <td><span class="badge {{ $r->status==='completed'?'badge-green':($r->status==='in-progress'?'badge-sky':'badge-amber') }}">{{ ucfirst($r->status) }}</span></td>
          <td style="font-family:var(--font-m);font-weight:700;color:var(--purple);">KSh {{ number_format($r->cost,2) }}</td>
          <td style="font-size:.78rem;color:var(--g400);">{{ $r->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-tools"></i></div><div class="empty-sub">No repairs recorded yet.</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($repairs->hasPages())
  <div class="card-footer" style="display:flex;justify-content:flex-end;">
    <div class="pagination">
      @if(!$repairs->onFirstPage())<a href="{{ $repairs->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($repairs->getUrlRange(max(1,$repairs->currentPage()-2),min($repairs->lastPage(),$repairs->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$repairs->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($repairs->hasMorePages())<a href="{{ $repairs->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection