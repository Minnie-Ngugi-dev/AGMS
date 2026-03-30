@extends('layouts.app')
@section('title','Stock Management')
@section('page-title','Stock Management')

@section('content')
<div class="anim-up">

{{-- Stats --}}
<div class="stat-grid" style="margin-bottom:20px;">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--navy-l);color:var(--navy);"><i class="fas fa-boxes-stacked"></i></div>
    <div><div class="stat-val">{{ $items->count() }}</div><div class="stat-lbl">Total Items</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--red-l);color:var(--red);"><i class="fas fa-triangle-exclamation"></i></div>
    <div><div class="stat-val">{{ $lowStock }}</div><div class="stat-lbl">Low Stock</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-l);color:var(--green);"><i class="fas fa-money-bill-wave"></i></div>
    <div><div class="stat-val">KSh {{ number_format($totalValue,0) }}</div><div class="stat-lbl">Stock Value</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--orange-l);color:var(--orange);"><i class="fas fa-cubes"></i></div>
    <div><div class="stat-val">{{ $items->sum('quantity') }}</div><div class="stat-lbl">Total Units</div></div>
  </div>
</div>

<div class="page-hdr">
  <div>
    <div class="page-hdr-title">Stock Items</div>
    <div class="page-hdr-sub">Manage parts and materials inventory</div>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.stock.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add Stock Item
    </a>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap">
    @if($items->isEmpty())
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-boxes-stacked"></i></div>
      <div class="empty-title">No stock items yet</div>
      <div class="empty-sub">Add your first part or material to begin tracking stock.</div>
      <a href="{{ route('admin.stock.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Item</a>
    </div>
    @else
    <table class="tbl">
      <thead>
        <tr>
          <th>Part Name</th>
          <th>Part No.</th>
          <th>Category</th>
          <th>Supplier</th>
          <th class="text-right">Qty</th>
          <th class="text-right">Reorder At</th>
          <th class="text-right">Cost Price</th>
          <th class="text-right">Selling Price</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
        <tr style="{{ $item->isLowStock() ? 'background:rgba(220,38,38,.04);' : '' }}">
          <td>
            <div style="font-weight:600;color:var(--g800);">{{ $item->name }}</div>
            @if($item->notes)
            <div style="font-size:.7rem;color:var(--g400);">{{ Str::limit($item->notes,40) }}</div>
            @endif
          </td>
          <td style="font-family:var(--font-m);font-size:.75rem;color:var(--g400);">{{ $item->part_number ?: '—' }}</td>
          <td><span class="badge badge-navy">{{ $item->category }}</span></td>
          <td style="font-size:.8rem;color:var(--g500);">{{ $item->supplier ?: '—' }}</td>
          <td class="text-right">
            <span style="font-family:var(--font-m);font-weight:700;color:{{ $item->isLowStock() ? 'var(--red)' : 'var(--green)' }};">
              {{ $item->quantity }}
            </span>
            @if($item->isLowStock())
            <div style="font-size:.65rem;color:var(--red);"><i class="fas fa-triangle-exclamation"></i> Low</div>
            @endif
          </td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.8rem;color:var(--g500);">{{ $item->reorder_level }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;">{{ number_format($item->unit_price,2) }}</td>
          <td class="text-right" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--orange);">{{ number_format($item->selling_price,2) }}</td>
          <td>
            <span class="badge {{ $item->is_active ? 'badge-green' : 'badge-gray' }}">
              {{ $item->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div class="tbl-actions">
              {{-- Add stock button --}}
              <button onclick="openAddStock({{ $item->id }},'{{ $item->name }}',{{ $item->quantity }})"
                class="btn btn-ghost btn-sm btn-icon" style="color:var(--green);" title="Add Stock">
                <i class="fas fa-plus-circle"></i>
              </button>
              <a href="{{ route('admin.stock.transactions',$item) }}" class="btn btn-ghost btn-sm btn-icon" title="History">
                <i class="fas fa-history"></i>
              </a>
              <a href="{{ route('admin.stock.edit',$item) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                <i class="fas fa-pen"></i>
              </a>
              <form method="POST" action="{{ route('admin.stock.destroy',$item) }}" class="inline"
                onsubmit="return confirm('Delete {{ $item->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Delete">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>
</div>

{{-- Add Stock Modal --}}
<div class="modal-backdrop" id="addStockModal">
  <div class="modal" style="max-width:420px;">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-plus-circle" style="color:var(--green);margin-right:8px;"></i> Add Stock</div>
      <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>
    <form id="addStockForm" method="POST">
      @csrf
      <div class="modal-body">
        <div style="background:var(--g50);border-radius:var(--r-sm);padding:12px 14px;margin-bottom:16px;">
          <div style="font-size:.7rem;color:var(--g400);">Adding stock to</div>
          <div style="font-weight:700;color:var(--g800);" id="modalItemName">—</div>
          <div style="font-size:.78rem;color:var(--g500);margin-top:2px;">Current qty: <strong id="modalCurrentQty">0</strong></div>
        </div>

        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Quantity to Add <span class="req">*</span></label>
          <input type="number" name="quantity" id="addQtyInput" class="form-input"
            min="1" placeholder="e.g. 10" required oninput="updateNewQty()">
        </div>

        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Cost Price per Unit (KSh)</label>
          <div class="input-group">
            <span class="ig-addon">KSh</span>
            <input type="number" name="unit_price" class="form-input"
              style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
              min="0" step="0.01" placeholder="0.00">
          </div>
        </div>

        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Notes</label>
          <input type="text" name="notes" class="form-input" placeholder="e.g. Restocked from supplier">
        </div>

        <div style="background:var(--green-l);border-radius:var(--r-sm);padding:10px 14px;font-size:.82rem;">
          New quantity will be: <strong id="newQtyDisplay" style="color:var(--green);">—</strong>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-green"><i class="fas fa-plus"></i> Add Stock</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
let currentQty = 0;

function openAddStock(id, name, qty) {
  currentQty = qty;
  document.getElementById('modalItemName').textContent   = name;
  document.getElementById('modalCurrentQty').textContent = qty;
  document.getElementById('newQtyDisplay').textContent   = '—';
  document.getElementById('addQtyInput').value           = '';
  document.getElementById('addStockForm').action         = `/admin/stock/${id}/add`;
  document.getElementById('addStockModal').classList.add('show');
  document.getElementById('addQtyInput').focus();
}

function closeModal() {
  document.getElementById('addStockModal').classList.remove('show');
}

function updateNewQty() {
  const add = parseInt(document.getElementById('addQtyInput').value) || 0;
  document.getElementById('newQtyDisplay').textContent = currentQty + add;
}
</script>
@endpush
@endsection