@extends('layouts.app')
@section('title','Stock History — '.$stock->name)
@section('page-title','Stock History')

@section('content')
<div class="anim-up">
<div class="page-hdr">
  <div>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back to Stock</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;margin-bottom:20px;">
  <div class="card" style="padding:20px;">
    <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Stock Item</div>
    <div style="font-family:var(--font-h);font-size:1.1rem;font-weight:700;color:var(--g900);">{{ $stock->name }}</div>
    @if($stock->part_number)
    <div style="font-family:var(--font-m);font-size:.75rem;color:var(--g400);margin-top:2px;">{{ $stock->part_number }}</div>
    @endif
    <div style="margin-top:12px;display:flex;gap:12px;">
      <div style="text-align:center;">
        <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:700;color:{{ $stock->isLowStock()?'var(--red)':'var(--green)' }};">{{ $stock->quantity }}</div>
        <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;">In Stock</div>
      </div>
      <div style="text-align:center;">
        <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:700;color:var(--amber);">{{ $stock->reorder_level }}</div>
        <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;">Reorder At</div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-history" style="color:var(--orange);margin-right:8px;"></i> Transaction History</div>
    </div>
    <div class="tbl-wrap">
      @if($transactions->isEmpty())
      <div class="empty-state"><div class="empty-icon"><i class="fas fa-history"></i></div><div class="empty-title">No transactions yet</div></div>
      @else
      <table class="tbl">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Before</th>
            <th class="text-right">After</th>
            <th>By</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transactions as $tx)
          <tr>
            <td style="font-size:.78rem;color:var(--g500);">{{ $tx->created_at->format('d M Y H:i') }}</td>
            <td>
              <span class="badge {{ $tx->type==='in'?'badge-green':($tx->type==='out'?'badge-red':'badge-amber') }}">
                <i class="fas fa-{{ $tx->type==='in'?'arrow-down':($tx->type==='out'?'arrow-up':'sliders') }}"></i>
                {{ ucfirst($tx->type) }}
              </span>
            </td>
            <td class="text-right" style="font-family:var(--font-m);font-weight:700;color:{{ $tx->type==='in'?'var(--green)':'var(--red)' }};">
              {{ $tx->type==='in'?'+':'-' }}{{ $tx->quantity }}
            </td>
            <td class="text-right" style="font-family:var(--font-m);font-size:.78rem;color:var(--g400);">{{ $tx->quantity_before }}</td>
            <td class="text-right" style="font-family:var(--font-m);font-weight:700;">{{ $tx->quantity_after }}</td>
            <td style="font-size:.78rem;">{{ $tx->user->name ?? '—' }}</td>
            <td style="font-size:.78rem;color:var(--g500);">{{ $tx->notes ?: '—' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="padding:12px 16px;">{{ $transactions->links() }}</div>
      @endif
    </div>
  </div>
</div>
</div>
@endsection