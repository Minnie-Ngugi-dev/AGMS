<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — AGMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ── CSS VARIABLES ─────────────────────────────────────────── */
:root {
  --orange:#FF6B2C; --orange-l:#FFF0E9; --orange-d:#E55A1E;
  --navy:#0F2040;   --navy-m:#1A3260;   --navy-l:#E8EDF5;
  --sky:#0EA5E9;    --sky-l:#E0F2FE;
  --green:#16A34A;  --green-l:#DCFCE7;
  --red:#DC2626;    --red-l:#FEE2E2;
  --amber:#D97706;  --amber-l:#FEF3C7;
  --purple:#7C3AED; --purple-l:#EDE9FE;
  --g50:#F8FAFC; --g100:#F1F5F9; --g200:#E2E8F0; --g300:#CBD5E1;
  --g400:#94A3B8; --g500:#64748B; --g600:#475569; --g700:#334155;
  --g800:#1E293B; --g900:#0F172A;
  --font-h:'Space Grotesk',sans-serif;
  --font-b:'DM Sans',sans-serif;
  --font-m:'DM Mono',monospace;
  --r:12px; --r-sm:8px; --r-lg:16px;
  --sh:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
  --sh-md:0 4px 16px rgba(0,0,0,.1);
  --sh-lg:0 10px 40px rgba(0,0,0,.15);
  --sidebar:260px;
}

/* ── DARK MODE ─────────────────────────────────────────────── */
html.dark {
  --g50:#0D1117; --g100:#161B25; --g200:#1F2937; --g300:#374151;
  --g400:#6B7280; --g500:#9CA3AF; --g600:#CBD5E1;
  --g700:#E2E8F0; --g800:#F1F5F9; --g900:#F8FAFC;
}
html.dark body                  { background:#0D1117; color:#E2E8F0; }
html.dark .main                 { background:#0D1117; }
html.dark .card                 { background:#161B25; border-color:#1F2937; }
html.dark .card-header          { border-color:#1F2937; }
html.dark .card-body            { background:#161B25; }
html.dark .card-footer          { background:#111827; border-color:#1F2937; }
html.dark .card-title           { color:#E2E8F0; }
html.dark .topbar               { background:#111827; border-color:#1F2937; }
html.dark .topbar-title         { color:#F1F5F9; }
html.dark .topbar-btn           { background:#1F2937; border-color:#374151; color:#9CA3AF; }
html.dark .topbar-btn:hover     { border-color:var(--orange); color:var(--orange); }
html.dark .search-bar           { background:#1F2937; border-color:#374151; }
html.dark .search-bar span      { color:#6B7280; }
html.dark .search-bar kbd       { background:#374151; color:#9CA3AF; }
html.dark .search-bar:hover     { background:#253047; border-color:var(--orange); }
html.dark .tbl thead tr         { background:#111827; }
html.dark .tbl th               { color:#6B7280; border-color:#1F2937; }
html.dark .tbl td               { border-color:#1F2937; color:#CBD5E1; }
html.dark .tbl tbody tr:hover   { background:#1A2233; }
html.dark .form-input,
html.dark .form-select,
html.dark .form-textarea        { background:#1F2937; border-color:#374151; color:#E2E8F0; }
html.dark .form-input:focus,
html.dark .form-select:focus,
html.dark .form-textarea:focus  { border-color:var(--orange); background:#253047; }
html.dark .form-label           { color:#CBD5E1; }
html.dark .form-hint            { color:#6B7280; }
html.dark .filter-bar           { background:#161B25; border-color:#1F2937; }
html.dark .stat-card            { background:#161B25; border-color:#1F2937; }
html.dark .ig-addon             { background:#1F2937; border-color:#374151; color:#9CA3AF; }
html.dark .modal                { background:#161B25; }
html.dark .modal-header         { border-color:#1F2937; }
html.dark .modal-footer         { border-color:#1F2937; }
html.dark .modal-close          { background:#1F2937; color:#9CA3AF; }
html.dark .page-link            { background:#1F2937; border-color:#374151; color:#9CA3AF; }
html.dark .page-link.active     { background:var(--orange); border-color:var(--orange); color:#fff; }
html.dark .btn-outline          { border-color:#374151; color:#CBD5E1; }
html.dark .btn-outline:hover    { border-color:#9CA3AF; }
html.dark .btn-ghost            { color:#9CA3AF; }
html.dark .btn-ghost:hover      { background:#1F2937; color:#E2E8F0; }
html.dark .cl-item              { background:#1F2937; border-color:#374151; color:#CBD5E1; }
html.dark .cl-item:hover        { border-color:var(--orange); background:#2A1F14; }
html.dark .cl-item.checked      { background:#052E16; border-color:#166534; }
html.dark .cl-item.checked .cl-name { color:#4ADE80; }
html.dark .flash-success        { background:#052E16; border-color:#166534; color:#4ADE80; }
html.dark .flash-error          { background:#450A0A; border-color:#991B1B; color:#F87171; }
html.dark .flash-info           { background:#0C1E2E; border-color:#1E40AF; color:#60A5FA; }
html.dark .flash-warning        { background:#2E1C00; border-color:#92400E; color:#FCD34D; }
html.dark .search-box           { background:#161B25; border:1px solid #1F2937; }
html.dark .search-input-wrap    { border-color:#1F2937; }
html.dark .search-input-wrap input { background:transparent; color:#E2E8F0; }
html.dark .search-result-item:hover { background:#1F2937; }
html.dark .search-result-title  { color:#E2E8F0; }
html.dark .search-result-sub    { color:#6B7280; }
html.dark .tabs                 { border-color:#1F2937; }
html.dark .tab                  { color:#6B7280; }
html.dark .tab.active           { color:var(--orange); border-color:var(--orange); }
html.dark .tl-item::before      { background:#1F2937; }
html.dark .empty-icon           { background:#1F2937; color:#6B7280; }

/* ── BASE ──────────────────────────────────────────────────── */
*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html,body { height:100%; }
body {
  font-family:var(--font-b);
  font-size:14px;
  color:var(--g700);
  background:var(--g50);
  display:flex;
  overflow:hidden;
  transition:background .3s,color .3s;
}

/* ── SIDEBAR ───────────────────────────────────────────────── */
.sidebar {
  width:var(--sidebar);
  height:100vh;
  background:var(--navy);
  display:flex;
  flex-direction:column;
  flex-shrink:0;
  position:fixed;
  top:0; left:0;
  z-index:100;
  overflow-y:auto;
  overflow-x:hidden;
  transition:transform .3s;
}
.sidebar::-webkit-scrollbar { width:4px; }
.sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); border-radius:2px; }

.sidebar-logo {
  padding:22px 20px 18px;
  display:flex;
  align-items:center;
  gap:12px;
  border-bottom:1px solid rgba(255,255,255,.06);
}
.sidebar-logo-icon {
  width:40px; height:40px;
  background:var(--orange);
  border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.1rem; color:#fff; flex-shrink:0;
}
.sidebar-logo-text { font-family:var(--font-h); font-size:1.1rem; font-weight:700; color:#fff; line-height:1.1; }
.sidebar-logo-sub  { font-size:.6rem; color:rgba(255,255,255,.35); text-transform:uppercase; letter-spacing:1.5px; }

.sidebar-nav { padding:14px 12px; flex:1; }
.nav-group   { margin-bottom:22px; }
.nav-group-label {
  font-size:.58rem; font-weight:700;
  color:rgba(255,255,255,.25);
  text-transform:uppercase; letter-spacing:2px;
  padding:0 8px; margin-bottom:6px;
}
.nav-item {
  display:flex; align-items:center; gap:11px;
  padding:9px 12px;
  border-radius:var(--r-sm);
  text-decoration:none;
  color:rgba(255,255,255,.55);
  font-size:.83rem; font-weight:500;
  transition:all .18s;
  margin-bottom:2px;
}
.nav-item:hover  { background:rgba(255,255,255,.06); color:rgba(255,255,255,.85); }
.nav-item.active { background:var(--orange); color:#fff; font-weight:600; box-shadow:0 4px 12px rgba(255,107,44,.35); }
.nav-icon        { width:22px; text-align:center; font-size:.82rem; flex-shrink:0; }

.sidebar-footer { padding:14px 12px; border-top:1px solid rgba(255,255,255,.06); }
.sidebar-user {
  display:flex; align-items:center; gap:10px;
  padding:10px 12px;
  border-radius:var(--r-sm);
  background:rgba(255,255,255,.04);
}
.sidebar-avatar {
  width:34px; height:34px;
  border-radius:50%;
  background:var(--orange);
  display:flex; align-items:center; justify-content:center;
  font-weight:700; font-size:.82rem; color:#fff; flex-shrink:0;
}
.sidebar-user-name { font-size:.8rem; font-weight:600; color:rgba(255,255,255,.8); }
.sidebar-user-role { font-size:.68rem; color:rgba(255,255,255,.35); text-transform:capitalize; }

/* ── MAIN ──────────────────────────────────────────────────── */
.main {
  margin-left:var(--sidebar);
  flex:1;
  display:flex;
  flex-direction:column;
  min-height:100vh;
  max-height:100vh;
  overflow:hidden;
  transition:background .3s;
}
.topbar {
  height:62px;
  background:#fff;
  border-bottom:1px solid var(--g200);
  display:flex;
  align-items:center;
  padding:0 24px;
  gap:14px;
  flex-shrink:0;
  position:sticky; top:0; z-index:50;
  transition:background .3s, border-color .3s;
}
.topbar-title { font-family:var(--font-h); font-weight:700; font-size:1rem; color:var(--g900); margin-right:auto; }

.search-bar {
  display:flex; align-items:center; gap:8px;
  background:var(--g50);
  border:1.5px solid var(--g200);
  border-radius:var(--r-sm);
  padding:7px 13px;
  cursor:pointer;
  transition:all .18s;
  min-width:220px;
}
.search-bar:hover  { border-color:var(--orange); background:#fff; }
.search-bar i      { color:var(--g400); font-size:.8rem; }
.search-bar span   { font-size:.8rem; color:var(--g400); }
.search-bar kbd    { margin-left:auto; font-size:.62rem; background:var(--g200); color:var(--g500); padding:1px 5px; border-radius:3px; font-family:var(--font-m); }

.topbar-btn {
  width:36px; height:36px;
  border-radius:var(--r-sm);
  border:1.5px solid var(--g200);
  background:#fff;
  display:flex; align-items:center; justify-content:center;
  cursor:pointer;
  color:var(--g500); font-size:.85rem;
  transition:all .18s;
  position:relative;
  text-decoration:none;
}
.topbar-btn:hover { border-color:var(--orange); color:var(--orange); }
.notif-dot {
  position:absolute; top:6px; right:6px;
  width:7px; height:7px;
  background:var(--red); border-radius:50%;
  border:1.5px solid #fff;
}

.content {
  flex:1; overflow-y:auto;
  padding:22px 24px;
  max-width:1400px; width:100%;
  transition:background .3s;
}
.content::-webkit-scrollbar       { width:5px; }
.content::-webkit-scrollbar-thumb { background:var(--g200); border-radius:3px; }

/* ── PAGE HEADER ───────────────────────────────────────────── */
.page-hdr         { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:12px; flex-wrap:wrap; }
.page-hdr-title   { font-family:var(--font-h); font-size:1.25rem; font-weight:700; color:var(--g900); }
.page-hdr-sub     { font-size:.78rem; color:var(--g400); margin-top:2px; }
.page-hdr-actions { display:flex; gap:8px; align-items:center; }

/* ── CARDS ─────────────────────────────────────────────────── */
.card        { background:#fff; border:1px solid var(--g200); border-radius:var(--r); box-shadow:var(--sh); transition:background .3s, border-color .3s; }
.card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid var(--g100); }
.card-title  { font-family:var(--font-h); font-size:.92rem; font-weight:700; color:var(--g800); display:flex; align-items:center; }
.card-body   { padding:18px 20px; }
.card-footer { padding:12px 20px; border-top:1px solid var(--g100); background:var(--g50); border-radius:0 0 var(--r) var(--r); }

/* ── STAT CARDS ────────────────────────────────────────────── */
.stat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px; margin-bottom:20px; }
.stat-card {
  background:#fff; border:1px solid var(--g200); border-radius:var(--r);
  padding:16px; display:flex; align-items:center; gap:14px;
  box-shadow:var(--sh); transition:all .2s;
}
.stat-card:hover { transform:translateY(-1px); box-shadow:var(--sh-md); }
.stat-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.05rem; flex-shrink:0; }
.stat-val  { font-family:var(--font-h); font-size:1.35rem; font-weight:700; color:var(--g900); line-height:1; }
.stat-lbl  { font-size:.68rem; color:var(--g400); margin-top:4px; text-transform:uppercase; letter-spacing:.7px; }

/* ── BUTTONS ───────────────────────────────────────────────── */
.btn          { display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:var(--r-sm); font-size:.82rem; font-weight:600; cursor:pointer; border:none; transition:all .18s; text-decoration:none; font-family:var(--font-b); line-height:1; }
.btn-primary  { background:var(--orange); color:#fff; } .btn-primary:hover  { background:var(--orange-d); }
.btn-navy     { background:var(--navy);   color:#fff; } .btn-navy:hover     { background:var(--navy-m); }
.btn-sky      { background:var(--sky);    color:#fff; } .btn-sky:hover      { opacity:.9; }
.btn-green    { background:var(--green);  color:#fff; } .btn-green:hover    { opacity:.9; }
.btn-red      { background:var(--red);    color:#fff; } .btn-red:hover      { opacity:.9; }
.btn-outline  { background:transparent; border:1.5px solid var(--g200); color:var(--g600); } .btn-outline:hover { border-color:var(--g400); color:var(--g800); }
.btn-ghost    { background:transparent; border:none; color:var(--g500); padding:6px 10px; } .btn-ghost:hover { background:var(--g100); color:var(--g800); }
.btn-sm       { padding:6px 12px; font-size:.76rem; }
.btn-lg       { padding:11px 22px; font-size:.9rem; }
.btn-icon     { padding:6px 9px; }
.btn:disabled { opacity:.5; cursor:not-allowed; }

/* ── BADGES ────────────────────────────────────────────────── */
.badge        { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:.68rem; font-weight:700; letter-spacing:.3px; }
.badge-green  { background:var(--green-l);  color:var(--green); }
.badge-red    { background:var(--red-l);    color:var(--red); }
.badge-amber  { background:var(--amber-l);  color:var(--amber); }
.badge-sky    { background:var(--sky-l);    color:var(--sky); }
.badge-purple { background:var(--purple-l); color:var(--purple); }
.badge-orange { background:var(--orange-l); color:var(--orange); }
.badge-gray   { background:var(--g100);     color:var(--g500); }
.badge-navy   { background:var(--navy-l);   color:var(--navy); }

/* ── TABLE ─────────────────────────────────────────────────── */
.tbl-wrap              { overflow-x:auto; }
.tbl                   { width:100%; border-collapse:collapse; }
.tbl thead tr          { background:var(--g50); }
.tbl th                { padding:10px 16px; text-align:left; font-size:.68rem; font-weight:700; color:var(--g500); text-transform:uppercase; letter-spacing:.8px; border-bottom:1.5px solid var(--g200); white-space:nowrap; }
.tbl td                { padding:12px 16px; border-bottom:1px solid var(--g100); vertical-align:middle; font-size:.83rem; }
.tbl tbody tr:hover    { background:var(--g50); }
.tbl tbody tr:last-child td { border-bottom:none; }
.tbl-actions           { display:flex; gap:4px; align-items:center; }

/* ── FORMS ─────────────────────────────────────────────────── */
.form-grid     { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; }
.form-group    { display:flex; flex-direction:column; gap:5px; }
.form-label    { font-size:.78rem; font-weight:600; color:var(--g700); }
.req           { color:var(--red); }
.form-input,
.form-select,
.form-textarea {
  width:100%; padding:9px 12px;
  border:1.5px solid var(--g200);
  border-radius:var(--r-sm);
  font-size:.83rem; font-family:var(--font-b);
  color:var(--g800); background:#fff;
  transition:border-color .18s, box-shadow .18s, background .3s;
  outline:none;
}
.form-input:focus,
.form-select:focus,
.form-textarea:focus { border-color:var(--orange); box-shadow:0 0 0 3px rgba(255,107,44,.1); }
.form-textarea       { resize:vertical; min-height:90px; line-height:1.55; }
.form-error          { font-size:.72rem; color:var(--red); display:flex; align-items:center; gap:4px; margin-top:2px; }
.form-hint           { font-size:.7rem; color:var(--g400); margin-top:2px; }
.form-section        { margin-bottom:22px; padding-bottom:18px; border-bottom:1px solid var(--g100); }
.form-section-title  { font-family:var(--font-h); font-size:.8rem; font-weight:700; color:var(--g500); text-transform:uppercase; letter-spacing:1px; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
.form-section-title i { color:var(--orange); }
.input-group  { display:flex; align-items:stretch; }
.ig-addon     { background:var(--g100); border:1.5px solid var(--g200); border-right:none; border-radius:var(--r-sm) 0 0 var(--r-sm); padding:9px 12px; font-size:.78rem; color:var(--g500); font-weight:600; white-space:nowrap; display:flex; align-items:center; transition:background .3s, border-color .3s; }
.input-group .form-input { border-radius:0 var(--r-sm) var(--r-sm) 0; }

/* ── CHECKLIST ─────────────────────────────────────────────── */
.cl-item         { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:var(--r-sm); border:1.5px solid var(--g200); background:#fff; margin-bottom:6px; cursor:pointer; transition:all .18s; }
.cl-item:hover   { border-color:var(--orange); background:var(--orange-l); }
.cl-item.checked { background:var(--green-l); border-color:#86efac; }
.cl-cb           { width:22px; height:22px; border-radius:6px; border:2px solid var(--g300); display:flex; align-items:center; justify-content:center; font-size:.7rem; color:transparent; flex-shrink:0; transition:all .18s; }
.cl-item.checked .cl-cb  { background:var(--green); border-color:var(--green); color:#fff; }
.cl-name         { font-size:.83rem; font-weight:500; color:var(--g700); transition:color .18s; }
.cl-item.checked .cl-name { color:var(--green); font-weight:600; }

/* ── PAGINATION ────────────────────────────────────────────── */
.pagination { display:flex; gap:4px; align-items:center; }
.page-link  { width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:var(--r-sm); font-size:.78rem; border:1.5px solid var(--g200); color:var(--g600); text-decoration:none; transition:all .18s; }
.page-link:hover  { border-color:var(--orange); color:var(--orange); }
.page-link.active { background:var(--orange); border-color:var(--orange); color:#fff; font-weight:700; }

/* ── FILTER BAR ────────────────────────────────────────────── */
.filter-bar   { background:#fff; border:1px solid var(--g200); border-radius:var(--r); padding:14px 18px; margin-bottom:16px; box-shadow:var(--sh); transition:background .3s, border-color .3s; }
.filter-item  { display:flex; flex-direction:column; gap:4px; }
.filter-label { font-size:.68rem; font-weight:700; color:var(--g400); text-transform:uppercase; letter-spacing:.8px; }

/* ── EMPTY STATE ───────────────────────────────────────────── */
.empty-state { text-align:center; padding:40px 20px; }
.empty-icon  { width:56px; height:56px; background:var(--g100); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:var(--g400); margin:0 auto 12px; }
.empty-title { font-family:var(--font-h); font-size:.95rem; font-weight:700; color:var(--g600); margin-bottom:5px; }
.empty-sub   { font-size:.8rem; color:var(--g400); margin-bottom:14px; }

/* ── TIMELINE ──────────────────────────────────────────────── */
.timeline { padding:0; list-style:none; }
.tl-item  { display:flex; gap:14px; padding-bottom:20px; position:relative; }
.tl-item:not(:last-child)::before { content:''; position:absolute; left:14px; top:28px; bottom:0; width:2px; background:var(--g200); }
.tl-dot   { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.7rem; z-index:1; }
.tl-body  { flex:1; padding-top:3px; }
.tl-date  { font-size:.68rem; color:var(--g400); margin-bottom:4px; }
.tl-title { font-weight:700; color:var(--g800); font-size:.88rem; margin-bottom:3px; }

/* ── MODAL ─────────────────────────────────────────────────── */
.modal-backdrop { position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:999; display:none; align-items:center; justify-content:center; padding:20px; }
.modal-backdrop.show { display:flex; }
.modal        { background:#fff; border-radius:var(--r-lg); box-shadow:var(--sh-lg); width:100%; max-width:520px; max-height:90vh; overflow-y:auto; transition:background .3s; }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--g100); }
.modal-title  { font-family:var(--font-h); font-size:.95rem; font-weight:700; color:var(--g800); }
.modal-close  { width:30px; height:30px; border:none; background:var(--g100); border-radius:var(--r-sm); cursor:pointer; color:var(--g500); font-size:.8rem; display:flex; align-items:center; justify-content:center; }
.modal-body   { padding:18px 20px; }
.modal-footer { padding:14px 20px; border-top:1px solid var(--g100); display:flex; justify-content:flex-end; gap:10px; }

/* ── FLASH MESSAGES ────────────────────────────────────────── */
.flash         { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:var(--r-sm); margin-bottom:16px; font-size:.83rem; font-weight:500; }
.flash-success { background:var(--green-l); color:var(--green);  border:1px solid #86efac; }
.flash-error   { background:var(--red-l);   color:var(--red);    border:1px solid #fca5a5; }
.flash-info    { background:var(--sky-l);   color:var(--sky);    border:1px solid #7dd3fc; }
.flash-warning { background:var(--amber-l); color:var(--amber);  border:1px solid #fcd34d; }

/* ── TABS ──────────────────────────────────────────────────── */
.tabs { display:flex; gap:2px; border-bottom:2px solid var(--g200); margin-bottom:20px; }
.tab  { padding:10px 16px; font-size:.82rem; font-weight:600; color:var(--g500); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .18s; text-decoration:none; }
.tab:hover  { color:var(--g800); }
.tab.active { color:var(--orange); border-bottom-color:var(--orange); }

/* ── ANIMATIONS ────────────────────────────────────────────── */
@keyframes fadeUp { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }
.anim-up { animation:fadeUp .35s ease both; }
.d1{animation-delay:.05s;} .d2{animation-delay:.1s;} .d3{animation-delay:.15s;} .d4{animation-delay:.2s;}

/* ── MISC ──────────────────────────────────────────────────── */
.inline      { display:inline; }
.text-right  { text-align:right; }
.divider     { height:1px; background:var(--g200); margin:16px 0; }

/* ── GLOBAL SEARCH OVERLAY ─────────────────────────────────── */
.search-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; display:none; align-items:flex-start; justify-content:center; padding-top:100px; }
.search-overlay.open { display:flex; }
.search-box         { background:#fff; border-radius:var(--r-lg); box-shadow:var(--sh-lg); width:100%; max-width:580px; overflow:hidden; transition:background .3s; }
.search-input-wrap  { display:flex; align-items:center; gap:10px; padding:14px 18px; border-bottom:1px solid var(--g100); }
.search-input-wrap i     { color:var(--g400); font-size:1rem; }
.search-input-wrap input { flex:1; border:none; outline:none; font-size:1rem; font-family:var(--font-b); color:var(--g800); background:transparent; }
.search-results      { max-height:400px; overflow-y:auto; }
.search-result-item  { display:flex; align-items:center; gap:12px; padding:11px 18px; cursor:pointer; text-decoration:none; color:inherit; transition:background .15s; }
.search-result-item:hover { background:var(--g50); }
.search-result-icon  { width:32px; height:32px; background:var(--orange-l); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--orange); font-size:.78rem; flex-shrink:0; }
.search-result-title { font-size:.85rem; font-weight:600; color:var(--g800); }
.search-result-sub   { font-size:.72rem; color:var(--g400); }
.search-empty        { padding:30px; text-align:center; font-size:.82rem; color:var(--g400); }

/* ── RESPONSIVE ────────────────────────────────────────────── */
@media(max-width:768px) {
  .sidebar          { transform:translateX(-100%); }
  .sidebar.open     { transform:translateX(0); }
  .main             { margin-left:0; }
  .form-grid        { grid-template-columns:1fr; }
  .stat-grid        { grid-template-columns:1fr 1fr; }
  .content          { padding:16px; }
  #menuBtn          { display:flex !important; }
}
</style>
</head>
<body>

<!-- ── SIDEBAR ─────────────────────────────────────────────── -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon"><i class="fas fa-car-side"></i></div>
    <div>
      <div class="sidebar-logo-text">AGMS</div>
      <div class="sidebar-logo-sub">Garage Management</div>
    </div>
  </div>

  <nav class="sidebar-nav">

    <div class="nav-group">
      <div class="nav-group-label">Main</div>
      <a href="{{ route('dashboard') }}"
        class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gauge"></i> Dashboard
      </a>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Service</div>
      <a href="{{ route('services.index') }}"
        class="nav-item {{ request()->routeIs('services.*') || request()->routeIs('checklists.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-wrench"></i> Services
      </a>
      <a href="{{ route('services.create') }}" class="nav-item">
        <i class="nav-icon fas fa-plus-circle"></i> New Service
      </a>
      <a href="{{ route('parts.index') }}"
        class="nav-item {{ request()->routeIs('parts.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cogs"></i> Parts
      </a>
      <a href="{{ route('repairs.index') }}"
        class="nav-item {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tools"></i> Repairs
      </a>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Fleet</div>
      <a href="{{ route('customers.index') }}"
        class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i> Customers
      </a>
      <a href="{{ route('vehicles.index') }}"
        class="nav-item {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-car"></i> Vehicles
      </a>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Finance</div>
      <a href="{{ route('payments.index') }}"
        class="nav-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-money-bill-wave"></i> Payments
      </a>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Reports</div>
      <a href="{{ route('reports.date') }}"
        class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i> Reports
      </a>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">System</div>
      <a href="{{ route('admin.users') }}"
        class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users-gear"></i> Users
      </a>
      <a href="{{ route('admin.service-items.index') }}"
        class="nav-item {{ request()->routeIs('admin.service-items*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-list-check"></i> Service Items
      </a>
      <a href="{{ route('admin.stock.index') }}"
        class="nav-item {{ request()->routeIs('admin.stock*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-boxes-stacked"></i> Stock
      </a>
      <a href="{{ route('admin.settings') }}"
        class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gear"></i> Settings
      </a>
      <a href="{{ route('help.index') }}"
        class="nav-item {{ request()->routeIs('help.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-circle-question"></i> Help
      </a>
    </div>

  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div style="flex:1;min-width:0;">
        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-user-role">{{ auth()->user()->role ?? 'user' }}</div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:var(--r-sm);cursor:pointer;color:rgba(255,255,255,.45);font-size:.75rem;padding:6px 8px;transition:all .2s;display:flex;align-items:center;gap:5px;"
          onmouseover="this.style.background='rgba(220,38,38,.2)';this.style.color='#f87171';this.style.borderColor='rgba(220,38,38,.3)'"
          onmouseout="this.style.background='rgba(255,255,255,.08)';this.style.color='rgba(255,255,255,.45)';this.style.borderColor='rgba(255,255,255,.12)'"
          title="Logout">
          <i class="fas fa-right-from-bracket"></i>
          <span style="font-size:.68rem;font-weight:600;">Logout</span>
        </button>
      </form>
    </div>
  </div>
</aside>

<!-- ── MAIN ────────────────────────────────────────────────── -->
<div class="main">

  <!-- TOPBAR -->
  <header class="topbar">
    <button onclick="document.getElementById('sidebar').classList.toggle('open')"
      id="menuBtn"
      style="display:none;background:none;border:none;cursor:pointer;color:var(--g600);font-size:1.1rem;padding:4px;">
      <i class="fas fa-bars"></i>
    </button>

    <div class="topbar-title">@yield('page-title','Dashboard')</div>

    <div class="search-bar" onclick="openSearch()">
      <i class="fas fa-search"></i>
      <span>Search vehicles, customers…</span>
      <kbd>Ctrl K</kbd>
    </div>

    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
      <i class="fas fa-plus"></i> New Service
    </a>

    <a href="{{ route('reports.date') }}" class="topbar-btn" title="Reports">
      <i class="fas fa-chart-bar"></i>
    </a>

    <button class="topbar-btn" onclick="toggleTheme()" id="themeBtn" title="Toggle dark/light mode">
      <i class="fas fa-moon" id="themeIcon"></i>
    </button>

    <div class="topbar-btn" style="position:relative;" title="Overdue vehicles">
      <i class="fas fa-bell"></i>
      @php
        $overdueCount = \App\Models\Vehicle::whereNotNull('next_service_date')
            ->where('next_service_date','<',today())->count();
      @endphp
      @if($overdueCount > 0)
        <div class="notif-dot"></div>
      @endif
    </div>
  </header>

  <!-- CONTENT -->
  <div class="content">

    {{-- Flash messages --}}
    @foreach(['success','error','info','warning'] as $type)
      @if(session($type))
      <div class="flash flash-{{ $type }}">
        <i class="fas fa-{{ $type==='success' ? 'check-circle' : ($type==='error' ? 'circle-xmark' : ($type==='warning' ? 'triangle-exclamation' : 'circle-info')) }}"></i>
        <span>{{ session($type) }}</span>
        <button onclick="this.parentElement.remove()"
          style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;opacity:.6;font-size:.8rem;padding:0 4px;">
          <i class="fas fa-times"></i>
        </button>
      </div>
      @endif
    @endforeach

    @yield('content')
  </div>
</div>

<!-- ── GLOBAL SEARCH OVERLAY ────────────────────────────────── -->
<div class="search-overlay" id="searchOverlay"
  onclick="if(event.target===this) closeSearch()">
  <div class="search-box">
    <div class="search-input-wrap">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput"
        placeholder="Search plate number, customer name, job card…"
        autocomplete="off">
      <kbd onclick="closeSearch()"
        style="font-size:.7rem;background:var(--g100);color:var(--g500);padding:2px 6px;border-radius:3px;cursor:pointer;flex-shrink:0;">
        ESC
      </kbd>
    </div>
    <div class="search-results" id="searchResults">
      <div class="search-empty">
        <i class="fas fa-search" style="font-size:1.5rem;margin-bottom:8px;display:block;color:var(--g300);"></i>
        Type to search…
      </div>
    </div>
  </div>
</div>

@stack('scripts')

<script>
// ── GLOBAL SEARCH ──────────────────────────────────────────────
function openSearch() {
  document.getElementById('searchOverlay').classList.add('open');
  setTimeout(() => document.getElementById('searchInput').focus(), 80);
}

function closeSearch() {
  document.getElementById('searchOverlay').classList.remove('open');
  document.getElementById('searchInput').value = '';
  document.getElementById('searchResults').innerHTML = `
    <div class="search-empty">
      <i class="fas fa-search" style="font-size:1.5rem;margin-bottom:8px;display:block;color:var(--g300);"></i>
      Type to search…
    </div>`;
}

let searchTimer;
document.getElementById('searchInput').addEventListener('input', function () {
  clearTimeout(searchTimer);
  const q = this.value.trim();
  if (!q) { closeSearch(); openSearch(); return; }

  searchTimer = setTimeout(async () => {
    try {
      const res  = await fetch(`/search?q=${encodeURIComponent(q)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      const container = document.getElementById('searchResults');

      if (!data.length) {
        container.innerHTML = `<div class="search-empty">No results for "<strong>${q}</strong>"</div>`;
        return;
      }

      container.innerHTML = data.map(r => `
        <a href="${r.url}" class="search-result-item" onclick="closeSearch()">
          <div class="search-result-icon"><i class="fas fa-${r.icon}"></i></div>
          <div>
            <div class="search-result-title">${r.title}</div>
            <div class="search-result-sub">${r.subtitle}</div>
          </div>
        </a>`).join('');
    } catch(e) {
      console.error('Search error:', e);
    }
  }, 280);
});

document.addEventListener('keydown', e => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    openSearch();
  }
  if (e.key === 'Escape') {
    closeSearch();
    document.getElementById('searchOverlay').classList.remove('open');
  }
});

// ── DARK MODE ──────────────────────────────────────────────────
function toggleTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  document.getElementById('themeIcon').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  localStorage.setItem('agms-theme', isDark ? 'dark' : 'light');
}

// Apply saved theme on every page load
(function () {
  if (localStorage.getItem('agms-theme') === 'dark') {
    document.documentElement.classList.add('dark');
    const icon = document.getElementById('themeIcon');
    if (icon) icon.className = 'fas fa-sun';
  }
})();

// ── MOBILE SIDEBAR ─────────────────────────────────────────────
document.addEventListener('click', function (e) {
  const sidebar = document.getElementById('sidebar');
  const menuBtn = document.getElementById('menuBtn');
  if (window.innerWidth <= 768 &&
      sidebar.classList.contains('open') &&
      !sidebar.contains(e.target) &&
      e.target !== menuBtn) {
    sidebar.classList.remove('open');
  }
});

// ── AUTO DISMISS FLASH ─────────────────────────────────────────
document.querySelectorAll('.flash').forEach(el => {
  setTimeout(() => {
    el.style.transition = 'opacity .5s';
    el.style.opacity    = '0';
    setTimeout(() => el.remove(), 500);
  }, 5000);
});
</script>
</body>
</html>