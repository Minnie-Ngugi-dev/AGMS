@extends('layouts.app')
@section('title','Record Payment')
@section('page-title','Record Payment')

@section('content')
<div style="max-width:680px;margin:0 auto;" class="anim-up">

{{-- Top banner --}}
<div style="background:var(--navy);border-radius:var(--r-lg) var(--r-lg) 0 0;padding:22px 28px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px;">
    <div>
      <div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Job Card</div>
      <div style="font-family:var(--font-m);font-size:1.15rem;font-weight:700;color:#fff;">{{ $service->job_card_no }}</div>
    </div>
    <div style="text-align:right;">
      <div style="font-family:var(--font-m);font-size:1.7rem;font-weight:700;color:var(--orange);">KSh {{ number_format($service->total_cost,2) }}</div>
      <div style="font-size:.7rem;color:rgba(255,255,255,.4);">Total Due</div>
    </div>
  </div>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
    @foreach([
      ['Parts',   'KSh '.number_format($service->parts_total,2),   'green'],
      ['Repairs', 'KSh '.number_format($service->repairs_total,2), 'purple'],
      ['Labour',  'KSh '.number_format($service->labour_charge,2), 'sky'],
      ['Paid',    'KSh '.number_format($service->amount_paid,2),   'amber'],
    ] as [$lbl,$val,$clr])
    <div style="background:rgba(255,255,255,.06);border-radius:var(--r-sm);padding:10px;text-align:center;">
      <div style="font-size:.6rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">{{ $lbl }}</div>
      <div style="font-family:var(--font-m);font-size:.85rem;font-weight:700;color:var(--{{ $clr }});">{{ $val }}</div>
    </div>
    @endforeach
  </div>
</div>

<div class="card" style="border-radius:0 0 var(--r-lg) var(--r-lg);border-top:none;">

  @if($service->balance <= 0)
  {{-- Already paid --}}
  <div class="card-body">
    <div style="text-align:center;padding:36px 20px;">
      <div style="width:72px;height:72px;background:var(--green-l);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:var(--green);margin:0 auto 16px;">
        <i class="fas fa-check-circle"></i>
      </div>
      <div style="font-family:var(--font-h);font-size:1.15rem;font-weight:700;color:var(--green);margin-bottom:6px;">Fully Paid!</div>
      <div style="font-size:.83rem;color:var(--g500);margin-bottom:20px;">This service has been paid in full.</div>
      <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('services.show',$service) }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Service</a>
        <a href="{{ route('invoices.show',$service) }}" class="btn btn-navy"><i class="fas fa-file-invoice"></i> View Invoice</a>
      </div>
    </div>
  </div>

  @else
  <div class="card-body">

    {{-- Balance banner --}}
    <div style="background:var(--orange-l);border:2px solid var(--orange);border-radius:var(--r-sm);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px;">
      <div>
        <div style="font-size:.68rem;font-weight:700;color:var(--orange);text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Balance Due</div>
        <div style="font-size:.8rem;color:var(--g600);">
          Total: <strong>KSh {{ number_format($service->total_cost,2) }}</strong>
          &nbsp;·&nbsp; Paid: <strong>KSh {{ number_format($service->amount_paid,2) }}</strong>
        </div>
      </div>
      <div style="font-family:var(--font-m);font-size:1.6rem;font-weight:700;color:var(--orange);">
        KSh {{ number_format($service->balance,2) }}
      </div>
    </div>

    {{-- Method tabs --}}
    <div style="margin-bottom:22px;">
      <label class="form-label">Payment Method <span class="req">*</span></label>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:8px;">
        <div onclick="selectMethod('Cash')" style="cursor:pointer;">
          <div id="methodCash" style="border:2px solid var(--amber);background:var(--amber-l);border-radius:var(--r);padding:16px;text-align:center;transition:all .2s;">
            <i class="fas fa-money-bill-wave" style="font-size:1.4rem;color:var(--amber);margin-bottom:8px;display:block;"></i>
            <div style="font-weight:700;color:var(--amber);font-size:.9rem;">Cash</div>
            <div style="font-size:.7rem;color:var(--g500);margin-top:2px;">Physical payment</div>
          </div>
        </div>
        <div onclick="selectMethod('M-Pesa')" style="cursor:pointer;">
          <div id="methodMpesa" style="border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:16px;text-align:center;transition:all .2s;">
            <i class="fas fa-mobile-alt" style="font-size:1.4rem;color:var(--g400);margin-bottom:8px;display:block;"></i>
            <div style="font-weight:700;color:var(--g600);font-size:.9rem;">M-Pesa</div>
            <div style="font-size:.7rem;color:var(--g500);margin-top:2px;">STK Push / Code</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── CASH ── --}}
    <div id="cashSection">
      <form method="POST" action="{{ route('services.payments.store', $service) }}">
        @csrf
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Amount (KSh) <span class="req">*</span></label>
          <div class="input-group">
            <span class="ig-addon">KSh</span>
            <input type="number" name="amount" id="cashAmount" class="form-input"
              style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
              value="{{ number_format($service->balance,2,'.','') }}"
              min="1" step="0.01" required>
          </div>
          <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
            <button type="button" onclick="setCashAmount({{ $service->balance }})" class="btn btn-ghost btn-sm" style="font-size:.72rem;">Full Balance</button>
            <button type="button" onclick="setCashAmount({{ round($service->balance/2,2) }})" class="btn btn-ghost btn-sm" style="font-size:.72rem;">Half</button>
            <button type="button" onclick="setCashAmount(1000)" class="btn btn-ghost btn-sm" style="font-size:.72rem;">1,000</button>
            <button type="button" onclick="setCashAmount(5000)" class="btn btn-ghost btn-sm" style="font-size:.72rem;">5,000</button>
          </div>
          @error('amount')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Notes (optional)</label>
          <textarea name="notes" class="form-textarea" style="min-height:60px;" placeholder="Any notes…">{{ old('notes') }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--g100);">
          <a href="{{ route('services.show',$service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-green btn-lg">
            <i class="fas fa-money-bill-wave"></i> Confirm Cash Payment
          </button>
        </div>
      </form>
    </div>

    {{-- ── MPESA ── --}}
    <div id="mpesaSection" style="display:none;">

      {{-- STK Push form --}}
      <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r);padding:20px;margin-bottom:16px;">
        <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;">
          <i class="fas fa-mobile-alt" style="color:var(--green);margin-right:5px;"></i>
          STK Push — Send to Customer Phone
        </div>

        {{-- Amount --}}
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Amount (KSh) <span class="req">*</span></label>
          <div class="input-group">
            <span class="ig-addon">KSh</span>
            <input type="number" id="mpesaAmount" class="form-input"
              style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
              value="{{ number_format($service->balance,2,'.','') }}"
              min="1" step="0.01">
          </div>
          <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
            <button type="button" onclick="setMpesaAmount({{ $service->balance }})" class="btn btn-ghost btn-sm" style="font-size:.72rem;">Full Balance</button>
            <button type="button" onclick="setMpesaAmount({{ round($service->balance/2,2) }})" class="btn btn-ghost btn-sm" style="font-size:.72rem;">Half</button>
            <button type="button" onclick="setMpesaAmount(1000)" class="btn btn-ghost btn-sm" style="font-size:.72rem;">1,000</button>
            <button type="button" onclick="setMpesaAmount(5000)" class="btn btn-ghost btn-sm" style="font-size:.72rem;">5,000</button>
          </div>
        </div>

        {{-- Phone --}}
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">
            <i class="fas fa-mobile-alt" style="color:var(--green);margin-right:5px;"></i>
            Customer Phone Number
          </label>
          <input type="tel" id="mpesaPhone" class="form-input"
            value="{{ $service->vehicle->customer->phone ?? '' }}"
            placeholder="e.g. 0712345678">
          <div class="form-hint">Any format accepted — 07XX, 01XX, +254, or 254</div>
        </div>

        {{-- Send button --}}
        <button type="button" id="stkBtn" onclick="sendStkPush()"
          style="width:100%;padding:14px;background:var(--green);color:#fff;border:none;border-radius:var(--r-sm);font-size:.95rem;font-weight:700;font-family:var(--font-h);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;">
          <i class="fas fa-mobile-alt"></i> Send STK Push to Phone
        </button>

        {{-- Status message --}}
        <div id="stkStatus" style="display:none;margin-top:10px;padding:12px 14px;border-radius:var(--r-sm);font-size:.83rem;font-weight:500;"></div>

        {{-- Waiting / polling UI --}}
        <div id="waitingBox" style="display:none;margin-top:14px;background:#fff;border:1px solid var(--g200);border-radius:var(--r-sm);padding:20px;text-align:center;">

          {{-- Animated phone icon --}}
          <div style="position:relative;width:56px;height:56px;margin:0 auto 14px;">
            <div style="width:56px;height:56px;background:var(--green-l);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--green);">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <div id="pulseRing" style="position:absolute;inset:-6px;border:3px solid var(--green);border-radius:50%;opacity:.5;animation:pulse 1.4s ease-out infinite;"></div>
          </div>

          <div style="font-family:var(--font-h);font-size:.95rem;font-weight:700;color:var(--navy);margin-bottom:6px;">
            Waiting for customer to pay...
          </div>
          <div style="font-size:.78rem;color:var(--g500);margin-bottom:16px;">
            A PIN prompt has been sent to the customer's phone.<br>
            <strong style="color:var(--navy);">Please ask them to enter their M-Pesa PIN.</strong>
          </div>

          {{-- Progress bar --}}
          <div style="height:5px;background:var(--g200);border-radius:3px;overflow:hidden;margin-bottom:8px;">
            <div id="progressBar" style="height:100%;width:0%;background:var(--green);border-radius:3px;transition:width 5s linear;"></div>
          </div>
          <div style="font-size:.72rem;color:var(--g400);">
            Checking every 5 seconds · <span id="pollCountdown">2:00</span> remaining
          </div>

          <button type="button" onclick="cancelPolling()"
            style="margin-top:14px;background:none;border:1px solid var(--g300);color:var(--g500);border-radius:var(--r-sm);padding:6px 16px;font-size:.75rem;cursor:pointer;">
            Cancel
          </button>
        </div>
      </div>

      {{-- Manual fallback --}}
      <div style="border:1px dashed var(--g300);border-radius:var(--r-sm);padding:16px;margin-bottom:14px;">
        <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">
          <i class="fas fa-keyboard" style="margin-right:5px;"></i> Manual Code Entry — Fallback
        </div>
        <div style="font-size:.78rem;color:var(--g500);margin-bottom:12px;">
          Already paid but push did not arrive? Enter the M-Pesa code from your SMS.
        </div>
        <form method="POST" action="{{ route('services.payments.mpesa.manual', $service) }}">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
            <div class="form-group">
              <label class="form-label">M-Pesa Code <span class="req">*</span></label>
              <input type="text" name="mpesa_code" class="form-input"
                placeholder="e.g. QJK2M3ABCD"
                style="font-family:var(--font-m);text-transform:uppercase;letter-spacing:1px;"
                oninput="this.value=this.value.toUpperCase()" required>
              @error('mpesa_code')
                <div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Amount (KSh) <span class="req">*</span></label>
              <input type="number" name="amount" class="form-input"
                value="{{ number_format($service->balance,2,'.','') }}"
                min="1" step="0.01" required>
              @error('amount')
                <div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>
              @enderror
            </div>
          </div>
          <button type="submit" class="btn btn-navy" style="width:100%;">
            <i class="fas fa-check"></i> Record Manual M-Pesa Payment
          </button>
        </form>
      </div>

      <a href="{{ route('services.show',$service) }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Cancel
      </a>
    </div>

  </div>
  @endif

  {{-- Payment history --}}
  @if($service->payments->count())
  <div class="card-footer">
    <div style="font-size:.7rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
      <i class="fas fa-history" style="margin-right:5px;"></i> Payment History
    </div>
    @foreach($service->payments->sortByDesc('payment_date') as $pmt)
    <div style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--g100);">
      <div style="width:32px;height:32px;background:{{ $pmt->method==='M-Pesa'?'var(--green-l)':'var(--amber-l)' }};color:{{ $pmt->method==='M-Pesa'?'var(--green)':'var(--amber)' }};border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0;">
        <i class="fas fa-{{ $pmt->method==='M-Pesa'?'mobile-alt':'money-bill-wave' }}"></i>
      </div>
      <div style="flex:1;">
        <div style="font-size:.82rem;font-weight:600;color:var(--g800);">
          {{ $pmt->method }}
          @if($pmt->mpesa_code)
            <span style="font-family:var(--font-m);font-size:.7rem;color:var(--g400);margin-left:5px;">{{ $pmt->mpesa_code }}</span>
          @endif
        </div>
        <div style="font-size:.7rem;color:var(--g400);">{{ $pmt->payment_date->format('d M Y, h:i A') }}</div>
      </div>
      <div style="font-family:var(--font-m);font-size:.88rem;font-weight:700;color:var(--green);">
        KSh {{ number_format($pmt->amount,2) }}
      </div>
      <form action="{{ route('payments.destroy',$pmt) }}" method="POST" class="inline"
        onsubmit="return confirm('Remove this payment?')">
        @csrf @method('DELETE')
        <button class="btn btn-ghost btn-sm btn-icon" style="color:var(--red);" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </form>
    </div>
    @endforeach
    <div style="display:flex;justify-content:space-between;padding-top:10px;font-size:.83rem;">
      <span style="color:var(--g500);">Total Paid</span>
      <span style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh {{ number_format($service->amount_paid,2) }}</span>
    </div>
  </div>
  @endif

</div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- SUCCESS MODAL — appears after customer pays and PIN confirmed --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="mpesaSuccessModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:var(--r-lg);max-width:440px;width:calc(100% - 40px);box-shadow:0 24px 60px rgba(0,0,0,.25);animation:slideUp .35s ease;">
    <div style="padding:36px 28px;text-align:center;">

      {{-- Animated tick --}}
      <div style="width:88px;height:88px;background:var(--green-l);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;animation:popIn .45s ease;">
        <i class="fas fa-check-circle" style="font-size:2.5rem;color:var(--green);"></i>
      </div>

      <div style="font-family:var(--font-h);font-size:1.4rem;font-weight:800;color:var(--green);margin-bottom:4px;">
        Payment Successful!
      </div>
      <div style="font-size:.85rem;color:var(--g500);margin-bottom:24px;">
        M-Pesa payment confirmed and recorded.
      </div>

      {{-- Payment details card --}}
      <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);padding:4px 16px;margin-bottom:22px;text-align:left;">

        <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--g100);">
          <span style="font-size:.75rem;color:var(--g500);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-money-bill-wave" style="color:var(--green);"></i> Amount Paid
          </span>
          <span id="modalAmount" style="font-family:var(--font-m);font-weight:800;color:var(--green);font-size:1.1rem;">—</span>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--g100);">
          <span style="font-size:.75rem;color:var(--g500);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-receipt" style="color:var(--navy);"></i> M-Pesa Code
          </span>
          <span id="modalCode" style="font-family:var(--font-m);font-weight:700;color:var(--navy);letter-spacing:1.5px;font-size:.9rem;">—</span>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--g100);">
          <span style="font-size:.75rem;color:var(--g500);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-mobile-alt" style="color:var(--sky);"></i> Phone
          </span>
          <span id="modalPhone" style="font-family:var(--font-m);font-size:.85rem;font-weight:600;">—</span>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid var(--g100);">
          <span style="font-size:.75rem;color:var(--g500);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-clock" style="color:var(--purple);"></i> Time
          </span>
          <span id="modalTime" style="font-family:var(--font-m);font-size:.82rem;font-weight:600;">—</span>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;">
          <span style="font-size:.75rem;color:var(--g500);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-file-alt" style="color:var(--orange);"></i> Job Card
          </span>
          <span style="font-family:var(--font-m);font-weight:700;color:var(--orange);">{{ $service->job_card_no }}</span>
        </div>
      </div>

      {{-- Action button — NO auto redirect --}}
      <a href="{{ route('services.show', $service) }}" class="btn btn-green btn-lg" style="width:100%;justify-content:center;font-size:.95rem;">
        <i class="fas fa-arrow-right"></i> View Service Page
      </a>

      <div style="font-size:.72rem;color:var(--g400);margin-top:12px;">
        Click the button above when you are ready
      </div>

    </div>
  </div>
</div>

@push('scripts')
<style>
@keyframes popIn {
  0%   { transform:scale(0.4);opacity:0; }
  70%  { transform:scale(1.12); }
  100% { transform:scale(1);opacity:1; }
}
@keyframes slideUp {
  from { transform:translateY(40px);opacity:0; }
  to   { transform:translateY(0);opacity:1; }
}
@keyframes pulse {
  0%   { transform:scale(1);opacity:.6; }
  100% { transform:scale(1.7);opacity:0; }
}
</style>
<script>
// ── METHOD SELECT ──────────────────────────────────────────────────
function selectMethod(m) {
  const c = document.getElementById('methodCash');
  const p = document.getElementById('methodMpesa');

  c.style.border     = '2px solid var(--g200)';
  c.style.background = '#fff';
  p.style.border     = '2px solid var(--g200)';
  p.style.background = '#fff';

  if (m === 'Cash') {
    c.style.border     = '2px solid var(--amber)';
    c.style.background = 'var(--amber-l)';
    document.getElementById('cashSection').style.display  = 'block';
    document.getElementById('mpesaSection').style.display = 'none';
  } else {
    p.style.border     = '2px solid var(--green)';
    p.style.background = 'var(--green-l)';
    document.getElementById('cashSection').style.display  = 'none';
    document.getElementById('mpesaSection').style.display = 'block';
  }
}

function setCashAmount(v)  { document.getElementById('cashAmount').value  = parseFloat(v).toFixed(2); }
function setMpesaAmount(v) { document.getElementById('mpesaAmount').value = parseFloat(v).toFixed(2); }

// ── POLLING STATE ──────────────────────────────────────────────────
let checkoutId   = null;
let pollTimer    = null;
let countTimer   = null;
let pollCount    = 0;
const MAX_POLLS  = 24;        // 24 × 5s = 2 minutes
let totalSeconds = 120;

// ── SEND STK PUSH ──────────────────────────────────────────────────
async function sendStkPush() {
  const phone  = document.getElementById('mpesaPhone').value.trim();
  const amount = document.getElementById('mpesaAmount').value;
  const btn    = document.getElementById('stkBtn');

  if (!phone)                        { showStatus('error','Enter a phone number.'); return; }
  if (!amount || parseFloat(amount) < 1) { showStatus('error','Minimum amount is KSh 1.'); return; }

  // Disable button and show sending state
  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending push...';
  hideStatus();

  try {
    const res = await fetch('{{ route("services.payments.mpesa.push", $service) }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        'Accept':        'application/json',
      },
      body: JSON.stringify({ phone, amount }),
    });

    const data = await res.json();

    if (data.success) {
      checkoutId = data.CheckoutRequestID;
      // Show the waiting box
      btn.innerHTML = '<i class="fas fa-check"></i> Push Sent — Waiting for PIN...';
      document.getElementById('waitingBox').style.display = 'block';
      startCountdown();
      startPolling();
    } else {
      showStatus('error', data.message || 'Push failed. Use manual entry below.');
      resetBtn();
    }

  } catch (e) {
    showStatus('error', 'Network error. Use manual entry below.');
    resetBtn();
  }
}

// ── COUNTDOWN TIMER ────────────────────────────────────────────────
function startCountdown() {
  totalSeconds = 120;
  updateCountdownDisplay();

  clearInterval(countTimer);
  countTimer = setInterval(() => {
    totalSeconds--;
    updateCountdownDisplay();

    // Animate progress bar
    const pct = ((120 - totalSeconds) / 120) * 100;
    document.getElementById('progressBar').style.width = pct + '%';

    if (totalSeconds <= 0) clearInterval(countTimer);
  }, 1000);
}

function updateCountdownDisplay() {
  const m = Math.floor(totalSeconds / 60);
  const s = totalSeconds % 60;
  const el = document.getElementById('pollCountdown');
  if (el) el.textContent = m + ':' + String(s).padStart(2,'0');
}

// ── POLL FOR PAYMENT ───────────────────────────────────────────────
function startPolling() {
  pollCount = 0;
  clearInterval(pollTimer);

  pollTimer = setInterval(async () => {
    pollCount++;

    if (pollCount > MAX_POLLS) {
      stopPolling();
      document.getElementById('waitingBox').style.display = 'none';
      showStatus('warning', '⏱ Timed out waiting. Enter the M-Pesa code manually below if you already paid.');
      resetBtn();
      return;
    }

    try {
      const res = await fetch('{{ route("services.payments.mpesa.query", $service) }}', {
        method:  'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
          'Accept':        'application/json',
        },
        body: JSON.stringify({ checkout_request_id: checkoutId }),
      });

      const data = await res.json();

      if (data.paid) {
        // ✅ Customer paid and PIN confirmed — show success modal
        stopPolling();
        document.getElementById('waitingBox').style.display = 'none';
        showSuccessModal(data);

      } else if (data.cancelled) {
        stopPolling();
        document.getElementById('waitingBox').style.display = 'none';
        showStatus('error', '❌ Customer cancelled the payment. Please try again.');
        resetBtn();

      } else if (data.wrong_pin) {
        stopPolling();
        document.getElementById('waitingBox').style.display = 'none';
        showStatus('error', '❌ Wrong PIN entered. Please try the STK push again.');
        resetBtn();

      } else if (data.timeout) {
        stopPolling();
        document.getElementById('waitingBox').style.display = 'none';
        showStatus('error', '❌ PIN entry timed out. Please try again.');
        resetBtn();

      } else if (data.insufficient) {
        stopPolling();
        document.getElementById('waitingBox').style.display = 'none';
        showStatus('error', '❌ Insufficient M-Pesa balance. Please top up and try again.');
        resetBtn();
      }
      // else still pending — keep polling

    } catch (e) {
      // Network hiccup — keep polling silently
    }

  }, 5000); // poll every 5 seconds
}

function stopPolling() {
  clearInterval(pollTimer);
  clearInterval(countTimer);
}

function cancelPolling() {
  stopPolling();
  document.getElementById('waitingBox').style.display = 'none';
  hideStatus();
  resetBtn();
}

// ── SUCCESS MODAL ──────────────────────────────────────────────────
function showSuccessModal(data) {
  const phone  = document.getElementById('mpesaPhone').value.trim();
  const amount = data.amount
    ? parseFloat(data.amount).toLocaleString('en-KE', { minimumFractionDigits: 2 })
    : parseFloat(document.getElementById('mpesaAmount').value).toLocaleString('en-KE', { minimumFractionDigits: 2 });

  document.getElementById('modalAmount').textContent = 'KSh ' + amount;
  document.getElementById('modalCode').textContent   = data.mpesa_code || '—';  // ← real Safaricom code
  document.getElementById('modalPhone').textContent  = phone;
  document.getElementById('modalTime').textContent   = data.paid_at || new Date().toLocaleString('en-KE');

  document.getElementById('mpesaSuccessModal').style.display = 'flex';
}

// ── HELPERS ────────────────────────────────────────────────────────
function resetBtn() {
  const btn     = document.getElementById('stkBtn');
  btn.disabled  = false;
  btn.innerHTML = '<i class="fas fa-mobile-alt"></i> Send STK Push to Phone';
}

function showStatus(type, msg) {
  const el = document.getElementById('stkStatus');
  const map = {
    success: 'background:var(--green-l);border:1px solid #86efac;color:var(--green);',
    error:   'background:var(--red-l);border:1px solid #fca5a5;color:var(--red);',
    info:    'background:var(--sky-l);border:1px solid #7dd3fc;color:var(--sky);',
    warning: 'background:var(--amber-l);border:1px solid #fcd34d;color:#92400e;',
  };
  el.style.cssText = (map[type] || map.info) + 'display:block;padding:12px 14px;border-radius:var(--r-sm);font-size:.83rem;font-weight:500;';
  el.textContent   = msg;
}

function hideStatus() {
  document.getElementById('stkStatus').style.display = 'none';
}

window.addEventListener('beforeunload', stopPolling);
</script>
@endpush
@endsection