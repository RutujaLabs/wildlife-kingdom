@extends('admin.layout')

@section('title', 'Bookings')
@section('page-title', '')

@section('content')
@php
    $pdfRoute = \Illuminate\Support\Facades\Route::has('admin.bookings.pdf') ? 'admin.bookings.pdf' : null;
    $paginator = $bookings->withQueryString();
@endphp

<style>
  .bk-page {
    --bk-green: #0d3b26;
    --bk-green-dark: #0a2e1e;
    --bk-orange: #f6a33a;
    --bk-cream: #fdf7e8;
    --bk-text: #1f2a22;
    --bk-line: #e8e1cf;
    color: var(--bk-text);
    background: var(--bk-cream);
  }
  .bk-page, .bk-page * { box-sizing: border-box; }

  /* ---------- Banner ---------- */
  .bk-banner {
    position: relative; min-height: 205px; padding: 44px 52px 56px;
    background:
      url('{{ asset('images/bookings-banner.jpg') }}') center right / cover no-repeat,
      linear-gradient(100deg, #f6e3b4 0%, #e6dca0 50%, #bcd19a 100%);
  }
  .bk-banner::before {
    content: ""; position: absolute; inset: 0;
    background: linear-gradient(90deg, rgba(253,247,232,.85) 0%, rgba(253,247,232,.35) 45%, transparent 75%);
  }
  .bk-banner > * { position: relative; z-index: 1; }
  .bk-banner h1 {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
    font-weight: 700; font-size: 52px; line-height: 1.1; margin: 0; color: var(--bk-green-dark);
  }
  .bk-banner .bk-rule { width: 60px; height: 2px; background: var(--bk-orange); margin: 8px 0 14px; }
  .bk-banner p {
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
    font-size: 19px; margin: 0; color: #1d2b20;
  }

  /* ---------- Body ---------- */
  .bk-body { padding: 0 38px 40px; margin-top: -26px; position: relative; z-index: 2; }
  .bk-card {
    background: var(--bk-cream); border-radius: 22px;
    box-shadow: 0 8px 28px rgba(13, 59, 38, .14); border: 1px solid rgba(255,255,255,.7);
  }

  /* ---------- Filter ---------- */
  .bk-filter { padding: 26px 34px 28px; margin-bottom: 32px; }
  .bk-filter-grid { display: grid; grid-template-columns: 1.1fr 1fr 1fr 1fr; gap: 28px; }
  .bk-field label { display: block; font-weight: 700; font-size: 16px; margin-bottom: 10px; color: #14281c; }
  .bk-input, .bk-select {
    width: 100%; height: 50px; border: 1px solid var(--bk-line); border-radius: 14px;
    background: #fffdf6; padding: 0 18px; font-size: 16px; color: var(--bk-text); font-family: inherit;
    outline: none; transition: border-color .15s, box-shadow .15s;
  }
  .bk-input:focus, .bk-select:focus { border-color: var(--bk-green); box-shadow: 0 0 0 3px rgba(13, 59, 38, .15); }
  .bk-input::placeholder { color: #8a8f88; }
  .bk-input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0; position: absolute; right: 0; top: 0; width: 46px; height: 100%; cursor: pointer; }
  .bk-input-wrap { position: relative; }
  .bk-input-wrap .bk-input { padding-right: 46px; }
  .bk-input-wrap svg { position: absolute; right: 16px; top: 50%; width: 20px; height: 20px; transform: translateY(-50%); pointer-events: none; color: #14281c; }
  .bk-select {
    appearance: none; -webkit-appearance: none; padding-right: 44px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2314281c' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 16px center; background-size: 16px;
  }
  .bk-actions { display: flex; gap: 14px; margin-top: 18px; }
  .bk-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px; height: 50px;
    padding: 0 26px; border-radius: 30px; font-size: 16px; font-weight: 700; font-family: inherit;
    cursor: pointer; text-decoration: none; transition: transform .15s, background .15s;
  }
  .bk-btn svg { width: 18px; height: 18px; }
  .bk-btn:hover { transform: translateY(-1px); }
  .bk-btn:focus-visible { outline: 3px solid var(--bk-orange); outline-offset: 2px; }
  .bk-btn-green { background: var(--bk-green); color: #fff; border: 2px solid var(--bk-green); }
  .bk-btn-outline { background: transparent; color: var(--bk-green); border: 2px solid var(--bk-green); }

  /* ---------- Table ---------- */
  .bk-table-card { overflow: hidden; }
  .bk-table-wrap { overflow-x: auto; }
  .bk-table { width: 100%; border-collapse: collapse; min-width: 1060px; }
  .bk-table thead th {
    background: var(--bk-green); color: #fff; font-weight: 700; font-size: 16px;
    text-align: left; padding: 20px 14px; white-space: nowrap;
  }
  .bk-table thead th:first-child { padding-left: 22px; border-top-left-radius: 20px; }
  .bk-table thead th:last-child { border-top-right-radius: 20px; text-align: center; }
  .bk-table tbody td {
    padding: 14px; font-size: 17px; border-bottom: 1px solid var(--bk-line); vertical-align: middle;
    background: #fffdf6;
  }
  .bk-table tbody td:first-child { padding-left: 22px; }
  .bk-table tbody tr:hover td { background: #fff8e3; }
  .bk-ref { color: var(--bk-text); text-decoration: underline; text-decoration-color: #c9a062; text-underline-offset: 3px; }
  .bk-date-time { line-height: 1.3; font-size: 16px; }
  .bk-empty { text-align: center; padding: 40px 14px !important; color: #6b7280; }

  /* Badges */
  .bk-badge {
    display: inline-flex; align-items: center; gap: 7px; padding: 6px 15px; border-radius: 20px;
    font-size: 16px; font-weight: 700; white-space: nowrap;
  }
  .bk-badge svg { width: 18px; height: 18px; flex: 0 0 18px; }
  .bk-badge-green { background: #d7f2d2; color: #14532d; }
  .bk-badge-orange { background: #fde3bd; color: #7a4506; }
  .bk-badge-red { background: #fbd9d6; color: #8a1c14; }

  /* Row buttons */
  .bk-row-actions { display: flex; gap: 10px; justify-content: center; }
  .bk-mini {
    display: inline-flex; align-items: center; gap: 7px; height: 36px; padding: 0 16px; border-radius: 20px;
    font-size: 16px; font-weight: 700; text-decoration: none; transition: transform .15s;
  }
  .bk-mini svg { width: 18px; height: 18px; }
  .bk-mini:hover { transform: translateY(-1px); }
  .bk-mini:focus-visible { outline: 3px solid var(--bk-orange); outline-offset: 2px; }
  .bk-mini-green { background: var(--bk-green); color: #fff; }
  .bk-mini-orange { background: #fbc57c; color: #14281c; }

  /* Footer / pagination */
  .bk-foot { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; background: #fffdf6; flex-wrap: wrap; gap: 12px; }
  .bk-foot span { font-size: 16px; }
  .bk-pager { display: flex; gap: 10px; align-items: center; }
  .bk-pg {
    min-width: 36px; height: 36px; padding: 0 10px; border-radius: 10px; display: grid; place-items: center;
    border: 1px solid var(--bk-line); background: #fffdf6; color: var(--bk-text); text-decoration: none; font-weight: 700; font-size: 16px;
  }
  .bk-pg svg { width: 16px; height: 16px; }
  .bk-pg.is-active { background: var(--bk-green); border-color: var(--bk-green); color: #fff; }
  .bk-pg.is-disabled { opacity: .45; pointer-events: none; }

  @media (max-width: 1100px) { .bk-filter-grid { grid-template-columns: 1fr 1fr; } }
  @media (max-width: 640px) {
    .bk-banner { padding: 32px 20px 80px; }
    .bk-banner h1 { font-size: 38px; }
    .bk-body { padding: 0 14px 30px; }
    .bk-filter { padding: 20px; }
    .bk-filter-grid { grid-template-columns: 1fr; gap: 18px; }
    .bk-actions { flex-direction: column; }
  }
</style>

<div class="bk-page">

  {{-- ===== Banner ===== --}}
  <section class="bk-banner">
    <h1>Bookings</h1>
    <div class="bk-rule"></div>
    <p>Manage your visitor bookings and ticket reservations</p>
  </section>

  <div class="bk-body">

    {{-- ===== Filters ===== --}}
    <div class="bk-card bk-filter">
      <form method="GET" action="{{ route('admin.bookings.index') }}">
        <div class="bk-filter-grid">
          <div class="bk-field">
            <label for="q">Booking ID, Reference or Email</label>
            <div class="bk-input-wrap">
              <input id="q" name="q" type="search" class="bk-input" placeholder="Search by booking ID..."
                     value="{{ $filters['q'] ?? '' }}" maxlength="254">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
            </div>
          </div>

          <div class="bk-field">
            <label for="visit_date">Visit Date</label>
            <div class="bk-input-wrap">
              <input id="visit_date" name="visit_date" class="bk-input" placeholder="Select date"
                     type="{{ !empty($filters['visit_date']) ? 'date' : 'text' }}"
                     value="{{ $filters['visit_date'] ?? '' }}"
                     onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.200" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            </div>
          </div>

          <div class="bk-field">
            <label for="payment_status">Payment Status</label>
            <select id="payment_status" name="payment_status" class="bk-select">
              <option value="">All Payment Statuses</option>
              @foreach(['pending', 'paid', 'failed', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ ($filters['payment_status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
              @endforeach
            </select>
          </div>

          <div class="bk-field">
            <label for="booking_status">Booking Status</label>
            <select id="booking_status" name="booking_status" class="bk-select">
              <option value="">All Booking Statuses</option>
              @foreach(['pending', 'confirmed', 'failed', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ ($filters['booking_status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="bk-actions">
          <button type="submit" class="bk-btn bk-btn-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
            Filter Bookings
          </button>
          <a href="{{ route('admin.bookings.index') }}" class="bk-btn bk-btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11a8 8 0 0 0-14.500-4M4 4v4h4"/><path d="M4 13a8 8 0 0 0 14.500 4M20 20v-4h-4"/></svg>
            Clear
          </a>
        </div>
      </form>
    </div>

    {{-- ===== Table ===== --}}
    <div class="bk-card bk-table-card">
      <div class="bk-table-wrap">
        <table class="bk-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Visitor</th>
              <th>Visit Date</th>
              <th>Ticket Type</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Payment</th>
              <th>Booking Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bookings as $booking)
              @php
                $pay = $booking->payment_status;
                $bks = $booking->booking_status;
                $badge = function ($s) {
                    return match ($s) {
                        'paid', 'confirmed' => 'bk-badge-green',
                        'pending'           => 'bk-badge-orange',
                        default             => 'bk-badge-red',
                    };
                };
              @endphp
              <tr>
                <td><a class="bk-ref" href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->booking_reference ?: '#'.$booking->id }}</a></td>
                <td>{{ $booking->visitor_name }}</td>
                <td>{{ $booking->visit_date->format('j M Y') }}</td>
                <td>{{ $booking->ticket->ticket_type ?? '—' }}</td>
                <td>{{ $booking->ticket_quantity }}</td>
                <td>${{ number_format($booking->total_amount, 2) }}</td>
                <td>
                  <span class="bk-badge {{ $badge($pay) }}">
                    @if($pay === 'paid')
                      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#1f9d4d"/><path d="M7 12.500l3.200 3.200L17 9" fill="none" stroke="#fff" stroke-width="2.600" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @elseif($pay === 'pending')
                      <svg viewBox="0 0 24 24" fill="none" stroke="#e08a12" stroke-width="2" stroke-linejoin="round"><path d="M6 3h12M6 21h12M7 3c0 5 5 6 5 9s-5 4-5 9M17 3c0 5-5 6-5 9s5 4 5 9"/></svg>
                    @endif
                    {{ ucfirst($pay) }}
                  </span>
                </td>
                <td>
                  <span class="bk-badge {{ $badge($bks) }}">
                    @if($bks === 'pending')
                      <svg viewBox="0 0 24 24" fill="none" stroke="#e08a12" stroke-width="2" stroke-linejoin="round"><path d="M6 3h12M6 21h12M7 3c0 5 5 6 5 9s-5 4-5 9M17 3c0 5-5 6-5 9s5 4 5 9"/></svg>
                    @endif
                    {{ ucfirst($bks) }}
                  </span>
                </td>
                <td class="bk-date-time">{{ $booking->created_at->format('j M Y') }}<br>{{ $booking->created_at->format('h:i A') }}</td>
                <td>
                  <div class="bk-row-actions">
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="bk-mini bk-mini-green">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.200" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.500-7 10-7 10 7 10 7-3.500 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                      View
                    </a>
                    <a href="{{ $pdfRoute ? route($pdfRoute, $booking) : '#' }}" class="bk-mini bk-mini-orange">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.400" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v11M7 11l5 5 5-5M5 20h14"/></svg>
                      PDF
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="10" class="bk-empty">No bookings match these filters.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="bk-foot">
        <span>Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} bookings</span>

        @if($paginator->hasPages())
          @php
            $cur = $paginator->currentPage();
            $last = $paginator->lastPage();
            $from = max(1, $cur - 2);
            $to = min($last, $cur + 2);
          @endphp
          <nav class="bk-pager" aria-label="Pagination">
            <a class="bk-pg {{ $paginator->onFirstPage() ? 'is-disabled' : '' }}" href="{{ $paginator->previousPageUrl() ?? '#' }}" aria-label="Previous page">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
            </a>
            @for($p = $from; $p <= $to; $p++)
              <a class="bk-pg {{ $p === $cur ? 'is-active' : '' }}" href="{{ $paginator->url($p) }}" @if($p === $cur) aria-current="page" @endif>{{ $p }}</a>
            @endfor
            <a class="bk-pg {{ $paginator->hasMorePages() ? '' : 'is-disabled' }}" href="{{ $paginator->nextPageUrl() ?? '#' }}" aria-label="Next page">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
            </a>
          </nav>
        @else
          <nav class="bk-pager" aria-label="Pagination">
            <span class="bk-pg is-disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg></span>
            <span class="bk-pg is-active">1</span>
            <span class="bk-pg is-disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></span>
          </nav>
        @endif
      </div>
    </div>

  </div>
</div>

<script>
  // Banner ko content area ke kinaron tak failao (layout ka padding cancel karo)
  (function () {
    var page = document.querySelector('.bk-page');
    if (!page || !page.parentElement) return;
    var cs = getComputedStyle(page.parentElement);
    page.style.margin = '-' + cs.paddingTop + ' -' + cs.paddingRight + ' -' + cs.paddingBottom + ' -' + cs.paddingLeft;
    page.parentElement.style.overflowX = 'clip';
  })();
</script>
@endsection