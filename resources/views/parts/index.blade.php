@extends('layouts.app')
@section('title','Parts')
@section('page-title','Parts')

@section('content')
<div class="page-hdr anim-up">
  <div class="page-hdr-left">
    <div class="page-hdr-title">All Parts</div>
    <div class="page-hdr-sub">{{ $parts->total() }} records</div>
  </div>
</div>

<div class="card anim-up d1">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr><th>Part Name</th><th>Part No.</th><th>Vehicle</th><th>Service</th><th>Qty</th><th>Unit Price</th><th>Total</th><th>Date</th></tr>
      </thead>
      <tbody>
        @forelse($parts as $p)
        <tr>
          <td style="font-weight:600;color:var(--g800);">{{ $p->name }}</td>
          <td><span style="font-family:var(--font-m);font-size:.75rem;color:var(--g400);">{{ $p->part_number??'—' }}</span></td>
          <td><a href="{{ route('vehicles.show',$p->service->vehicle) }}" style="font-family:var(--font-m);font-weight:700;color:var(--navy);text-decoration:none;">{{ $p->service->vehicle->registration_no }}</a></td>
          <td><a href="{{ route('services.show',$p->service) }}" style="font-family:var(--font-m);font-size:.78rem;color:var(--sky);text-decoration:none;">{{ $p->service->job_card_no }}</a></td>
          <td style="text-align:center;">{{ $p->quantity }}</td>
          <td style="font-family:var(--font-m);">KSh {{ number_format($p->unit_price,2) }}</td>
          <td style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($p->total,2) }}</td>
          <td style="font-size:.78rem;color:var(--g400);">{{ $p->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="empty-state" style="padding:30px;"><div class="empty-icon"><i class="fas fa-cogs"></i></div><div class="empty-sub">No parts recorded yet.</div></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($parts->hasPages())
  <div class="card-footer" style="display:flex;justify-content:flex-end;">
    <div class="pagination">
      @if(!$parts->onFirstPage())<a href="{{ $parts->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>@endif
      @foreach($parts->getUrlRange(max(1,$parts->currentPage()-2),min($parts->lastPage(),$parts->currentPage()+2)) as $pg=>$url)
        <a href="{{ $url }}" class="page-link {{ $pg==$parts->currentPage()?'active':'' }}">{{ $pg }}</a>
      @endforeach
      @if($parts->hasMorePages())<a href="{{ $parts->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>@endif
    </div>
  </div>
  @endif
</div>
@endsection