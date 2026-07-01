<!DOCTYPE html>
<html lang="ps" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ pcal_trans('converter_title') }}</title>
<link rel="stylesheet" href="{{ asset('vendor/pashto-calendar/css/pashto-calendar.css') }}">
<script defer src="{{ asset('vendor/pashto-calendar/js/pashto-calendar.js') }}"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --gold:#d4920a;--gold-l:#f0b429;--gold-ll:#fce09b;--gold-dim:rgba(212,146,10,.1);--gold-bdr:rgba(212,146,10,.28);
  --teal:#0891b2;--teal-l:#22d3ee;--teal-ll:#a5f3fc;--teal-dim:rgba(8,145,178,.1);--teal-bdr:rgba(8,145,178,.28);
  --rose:#e11d48;--rose-l:#fb7185;--rose-ll:#fecdd3;--rose-dim:rgba(225,29,72,.09);--rose-bdr:rgba(225,29,72,.28);
  --bg:#060b14;--bg2:#0b1422;--bg3:#101d30;--bg4:#16243e;
  --sur:rgba(255,255,255,.06);--sur2:rgba(255,255,255,.04);--sur3:rgba(255,255,255,.02);
  --t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--t4:#1e293b;
  --r8:8px;--r12:12px;--r16:16px;--r24:24px;--r32:32px;--r99:99px;
  --shadow-sm:0 1px 3px rgba(0,0,0,.4);
  --shadow-md:0 4px 16px rgba(0,0,0,.5);
  --shadow-lg:0 12px 40px rgba(0,0,0,.6);
  --shadow-xl:0 24px 80px rgba(0,0,0,.7);
  --ease:cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}

body{
  background:var(--bg);
  font-family:'Inter',sans-serif;
  min-height:100vh;
  display:flex;flex-direction:column;align-items:center;
  padding:40px 16px 80px;
  overflow-x:hidden;position:relative;
}

/* ── BACKGROUND ── */
.aurora{position:fixed;inset:0;pointer-events:none;z-index:0;}
.blob{position:absolute;border-radius:50%;filter:blur(100px);will-change:transform;animation:drift 22s ease-in-out infinite alternate;}
.b1{width:700px;height:700px;background:radial-gradient(circle,rgba(212,146,10,.18),transparent 70%);top:-200px;left:-200px;}
.b2{width:600px;height:600px;background:radial-gradient(circle,rgba(8,145,178,.14),transparent 70%);bottom:-180px;right:-180px;animation-delay:-8s;}
.b3{width:400px;height:400px;background:radial-gradient(circle,rgba(225,29,72,.10),transparent 70%);top:40%;left:50%;translate:-50% -50%;animation-delay:-16s;}
@keyframes drift{from{transform:translate(0,0);}to{transform:translate(30px,25px);}}

body::before{
  content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:
    linear-gradient(rgba(255,255,255,.018) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.018) 1px,transparent 1px);
  background-size:48px 48px;
}

/* ── SHELL ── */
.shell{position:relative;z-index:10;width:100%;max-width:980px;animation:up .5s var(--ease) both;}
@keyframes up{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:none;}}

/* ── HEADER ── */
.header{text-align:center;margin-bottom:40px;}
.eyebrow{
  display:inline-flex;align-items:center;gap:6px;
  background:var(--gold-dim);border:1px solid var(--gold-bdr);
  border-radius:var(--r99);padding:4px 14px;
  font-size:10px;letter-spacing:2.5px;color:var(--gold-l);text-transform:uppercase;
  margin-bottom:16px;
}
.edot{width:5px;height:5px;background:var(--gold-l);border-radius:50%;
      box-shadow:0 0 8px var(--gold-l);animation:pulse 2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(1.6);}}
.h1{
  font-size:clamp(24px,4vw,40px);font-weight:700;letter-spacing:-.5px;
  background:linear-gradient(135deg,var(--gold-l) 0%,var(--gold-ll) 30%,var(--teal-l) 65%,var(--rose-l) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  margin-bottom:10px;line-height:1.2;
}
.h2{font-size:13.5px;color:var(--t2);letter-spacing:.2px;}
.h2 span{opacity:.35;margin:0 8px;}

/* ── TABS ── */
.tabs{
  display:flex;flex-wrap:wrap;justify-content:center;gap:6px;
  margin-bottom:32px;
  padding:6px;
  background:var(--bg2);
  border:1px solid var(--sur);
  border-radius:var(--r16);
  width:fit-content;
  margin-left:auto;margin-right:auto;
}
.tab{
  padding:8px 16px;border-radius:var(--r12);font-size:12px;font-weight:500;
  cursor:pointer;border:none;background:transparent;color:var(--t3);
  transition:all .18s var(--ease);white-space:nowrap;font-family:inherit;
}
.tab:hover{color:var(--t2);background:var(--sur);}
.tab.ag{background:var(--gold-dim);color:var(--gold-l);box-shadow:0 0 0 1px var(--gold-bdr);}
.tab.at{background:var(--teal-dim);color:var(--teal-l);box-shadow:0 0 0 1px var(--teal-bdr);}
.tab.ar{background:var(--rose-dim);color:var(--rose-l);box-shadow:0 0 0 1px var(--rose-bdr);}

/* ── CARD ── */
.card{
  background:var(--bg2);
  border:1px solid var(--sur);
  border-radius:var(--r32);
  box-shadow:var(--shadow-xl),0 0 0 1px rgba(255,255,255,.03) inset;
  overflow:visible;  /* CRITICAL: never clip — dropdowns must escape */
  position:relative;
}
.card::before{
  content:'';position:absolute;inset:0;border-radius:var(--r32);pointer-events:none;
  background:linear-gradient(135deg,rgba(212,146,10,.04) 0%,transparent 50%,rgba(8,145,178,.03) 100%);
}

/* ── DIRECTION STRIP ── */
.dstrip{
  display:flex;align-items:center;justify-content:center;gap:10px;
  padding:14px 24px;border-bottom:1px solid var(--sur);
  border-radius:var(--r32) var(--r32) 0 0;
  background:var(--sur3);
}
.dpill{
  display:inline-flex;align-items:center;gap:6px;
  padding:5px 14px;border-radius:var(--r99);font-size:12px;font-weight:600;
}
.dpill.g{background:var(--gold-dim);border:1px solid var(--gold-bdr);color:var(--gold-l);}
.dpill.t{background:var(--teal-dim);border:1px solid var(--teal-bdr);color:var(--teal-l);}
.dpill.r{background:var(--rose-dim);border:1px solid var(--rose-bdr);color:var(--rose-l);}
.darrow{color:var(--t3);font-size:18px;line-height:1;}

/* ── PANELS ── */
.pgrid{display:grid;grid-template-columns:1fr 48px 1fr;}

.panel{padding:28px 28px 28px;position:relative;}
.panel-l{border-radius:0 0 0 var(--r32);}
.panel-r{border-radius:0 0 var(--r32) 0;}

/* accent line */
.abar{position:absolute;top:0;left:20px;right:20px;height:1px;border-radius:var(--r99);opacity:.6;}
.abar.g{background:linear-gradient(90deg,transparent,var(--gold-l),transparent);}
.abar.t{background:linear-gradient(90deg,transparent,var(--teal-l),transparent);}
.abar.r{background:linear-gradient(90deg,transparent,var(--rose-l),transparent);}

/* badge */
.badge{
  display:inline-flex;align-items:center;gap:7px;
  border-radius:var(--r99);padding:4px 12px 4px 5px;margin-bottom:14px;
  font-size:11px;font-weight:600;letter-spacing:.3px;
}
.badge.g{background:var(--gold-dim);border:1px solid var(--gold-bdr);color:var(--gold-l);}
.badge.t{background:var(--teal-dim);border:1px solid var(--teal-bdr);color:var(--teal-l);}
.badge.r{background:var(--rose-dim);border:1px solid var(--rose-bdr);color:var(--rose-l);}
.bicon{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bicon.g{background:linear-gradient(135deg,var(--gold-l),#a16207);}
.bicon.t{background:linear-gradient(135deg,var(--teal-l),#0e7490);}
.bicon.r{background:linear-gradient(135deg,var(--rose-l),#be123c);}

.ptitle{font-size:15px;font-weight:600;color:var(--t1);margin-bottom:20px;line-height:1.4;}

/* ── FORM ── */
.flabel{
  font-size:10.5px;font-weight:600;letter-spacing:1.4px;
  text-transform:uppercase;color:var(--t3);margin-bottom:7px;display:block;
}
.fwrap{position:relative;margin-bottom:14px;}
.finput{
  width:100%;background:var(--bg3);
  border:1px solid rgba(255,255,255,.09);border-radius:var(--r12);
  padding:13px 16px;color:var(--t1);font-size:14px;outline:none;
  transition:border-color .18s,box-shadow .18s;
  font-family:'Inter',sans-serif;-webkit-appearance:none;appearance:none;
}
.finput::placeholder{color:var(--t3);}
.finput.g:focus{border-color:var(--gold-l);box-shadow:0 0 0 3px var(--gold-dim);}
.finput.t:focus{border-color:var(--teal-l);box-shadow:0 0 0 3px var(--teal-dim);}
.finput.r:focus{border-color:var(--rose-l);box-shadow:0 0 0 3px var(--rose-dim);}
input[type="date"].finput::-webkit-calendar-picker-indicator{
  filter:invert(.5) sepia(1) hue-rotate(10deg) saturate(2);cursor:pointer;opacity:.7;
}
.finput-pr{padding-inline-end:46px;}

.cal-btn{
  position:absolute;inset-inline-end:11px;top:50%;translate:0 -50%;
  background:none;border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  width:30px;height:30px;border-radius:var(--r8);
  transition:background .15s,color .15s;
}
.cal-btn.t{color:var(--t3);}
.cal-btn.t:hover{background:var(--teal-dim);color:var(--teal-l);}
.cal-btn.r{color:var(--t3);}
.cal-btn.r:hover{background:var(--rose-dim);color:var(--rose-l);}

/* ── BUTTON ── */
.btn{
  width:100%;font-weight:600;font-size:14px;font-family:'Inter',sans-serif;
  border:none;border-radius:var(--r12);padding:13px 18px;cursor:pointer;
  position:relative;overflow:hidden;transition:transform .18s,box-shadow .18s;
  display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(180deg,rgba(255,255,255,.12) 0%,transparent 100%);
  pointer-events:none;
}
.btn.g{background:linear-gradient(135deg,var(--gold-l),#92400e);color:#000;box-shadow:0 4px 20px rgba(212,146,10,.3);}
.btn.t{background:linear-gradient(135deg,var(--teal-l),#0e7490);color:#fff;box-shadow:0 4px 20px rgba(8,145,178,.3);}
.btn.r{background:linear-gradient(135deg,var(--rose-l),#9f1239);color:#fff;box-shadow:0 4px 20px rgba(225,29,72,.3);}
.btn.g:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(212,146,10,.45);}
.btn.t:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(8,145,178,.45);}
.btn.r:hover{transform:translateY(-1px);box-shadow:0 6px 28px rgba(225,29,72,.45);}
.btn:active{transform:scale(.97);}

/* ── RESULT ── */
.result-area{min-height:118px;display:flex;flex-direction:column;justify-content:flex-end;margin-top:14px;}
.hint{display:flex;align-items:center;gap:10px;padding:12px 0;}
.hbar{width:2px;height:40px;border-radius:var(--r99);flex-shrink:0;}
.hbar.g{background:linear-gradient(180deg,var(--gold-l),transparent);}
.hbar.t{background:linear-gradient(180deg,var(--teal-l),transparent);}
.hbar.r{background:linear-gradient(180deg,var(--rose-l),transparent);}
.htxt{font-size:12.5px;color:var(--t3);line-height:1.65;}

.spinner-box{display:flex;justify-content:center;padding:20px 0;}
.spinner{animation:spin 1s linear infinite;}
@keyframes spin{to{transform:rotate(360deg)}}

.rbox{
  padding:16px 18px;border-radius:var(--r16);position:relative;overflow:hidden;
  animation:rIn .3s var(--ease) both;
}
.rbox.g{background:linear-gradient(135deg,rgba(212,146,10,.1),rgba(8,145,178,.04));border:1px solid var(--gold-bdr);}
.rbox.t{background:linear-gradient(135deg,rgba(8,145,178,.1),rgba(212,146,10,.04));border:1px solid var(--teal-bdr);}
.rbox.r{background:linear-gradient(135deg,rgba(225,29,72,.1),rgba(212,146,10,.04));border:1px solid var(--rose-bdr);}
@keyframes rIn{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:none;}}
.rbox::before{
  content:'';position:absolute;inset-inline-start:0;top:0;bottom:0;width:3px;border-radius:var(--r99);
}
.rbox.g::before{background:linear-gradient(180deg,var(--gold-l),var(--teal-l));}
.rbox.t::before{background:linear-gradient(180deg,var(--teal-l),var(--gold-l));}
.rbox.r::before{background:linear-gradient(180deg,var(--rose-l),var(--rose-ll));}
.rlabel{font-size:9.5px;letter-spacing:1.8px;text-transform:uppercase;color:var(--t3);margin-bottom:4px;display:block;}
.rval{font-size:18px;font-weight:700;line-height:1.4;word-break:break-word;color:var(--t1);}
.rsub{font-size:12px;color:var(--t3);margin-top:3px;}
.rcopy{
  position:absolute;inset-inline-end:10px;top:10px;
  background:var(--sur2);border:1px solid var(--sur);border-radius:var(--r8);
  padding:3px 9px;font-size:10.5px;cursor:pointer;
  display:flex;align-items:center;gap:4px;
  color:var(--t2);transition:all .15s;font-family:inherit;
}
.rcopy:hover{background:var(--sur);color:var(--t1);}

/* ── DIVIDER COL ── */
.dcol{display:flex;flex-direction:column;align-items:center;padding:16px 0;}
.dline{flex:1;width:1px;background:linear-gradient(180deg,transparent,var(--sur),transparent);}
.swapbtn{
  width:36px;height:36px;border-radius:50%;
  background:var(--bg3);border:1px solid var(--sur);
  color:var(--t3);display:flex;align-items:center;justify-content:center;
  cursor:pointer;margin:6px 0;flex-shrink:0;
  transition:all .22s var(--ease);font-family:inherit;
}
.swapbtn:hover{border-color:rgba(255,255,255,.2);color:var(--t1);transform:rotate(180deg);}

/* ── CALENDAR DROPDOWN ──────────────────────────────────────────
   KEY FIX: rendered in a portal div at end of <body>,
   position:fixed so it is NEVER clipped by any parent overflow.
   JS measures the trigger button and sets top/left each time.
─────────────────────────────────────────────────────────────── */
.pdrop{
  position:fixed;
  z-index:99999;
  width:300px;
  background:var(--bg2);
  border-radius:var(--r24);
  padding:16px;
  box-shadow:var(--shadow-xl),0 0 0 1px rgba(255,255,255,.06) inset;
  animation:dropIn .18s var(--ease) both;
  /* scroll inside if month has many rows */
  max-height:90vh;overflow-y:auto;
}
.pdrop.g{border:1px solid var(--gold-bdr);}
.pdrop.t{border:1px solid var(--teal-bdr);}
.pdrop.r{border:1px solid var(--rose-bdr);}
@keyframes dropIn{from{opacity:0;transform:translateY(-6px) scale(.97);}to{opacity:1;transform:none;}}

.phdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.pnav{
  width:30px;height:30px;border-radius:var(--r8);
  border:1px solid var(--sur);background:var(--bg3);
  color:var(--t2);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:all .14s;font-family:inherit;
}
.pnav:hover{background:var(--sur);color:var(--t1);}
.pmtitle{font-size:13.5px;font-weight:600;color:var(--t1);text-align:center;}
.pmtitle .pyear{font-size:11px;color:var(--t3);display:block;margin-top:1px;}

.pcalgrid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-top:8px;}
.pdn{text-align:center;font-size:10px;color:var(--t3);padding:4px 0;font-weight:600;}
.pcell{
  text-align:center;padding:7px 3px;border-radius:var(--r8);
  cursor:pointer;color:var(--t2);font-size:12.5px;
  transition:all .12s;line-height:1;font-weight:500;
}
.pcell:hover{color:var(--t1);background:var(--sur);}
.pcell.empty{cursor:default;pointer-events:none;color:transparent;}
/* teal — Pashto */
.pdrop.t .pcell:hover{background:var(--teal-dim);color:var(--teal-l);}
.pdrop.t .pcell.today{border:1px solid var(--teal-bdr);color:var(--teal-l);font-weight:700;}
.pdrop.t .pcell.sel{background:var(--teal);color:#fff;font-weight:700;box-shadow:0 2px 8px rgba(8,145,178,.4);}
/* rose — Hijri */
.pdrop.r .pcell:hover{background:var(--rose-dim);color:var(--rose-l);}
.pdrop.r .pcell.today{border:1px solid var(--rose-bdr);color:var(--rose-l);font-weight:700;}
.pdrop.r .pcell.sel{background:var(--rose);color:#fff;font-weight:700;box-shadow:0 2px 8px rgba(225,29,72,.4);}

/* ── MOBILE SEP ── */
.hsep{display:none;height:1px;background:var(--sur);margin:0;}

/* ── FOOTER ── */
.footer{text-align:center;margin-top:28px;}
.blink{
  display:inline-flex;align-items:center;gap:7px;
  color:var(--t3);text-decoration:none;font-size:13px;
  padding:9px 20px;border:1px solid var(--sur);border-radius:var(--r99);
  transition:all .2s var(--ease);background:var(--sur3);
  font-family:inherit;
}
.blink:hover{color:var(--gold-l);border-color:var(--gold-bdr);background:var(--gold-dim);}

/* scrollbar */
::-webkit-scrollbar{width:4px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--sur);border-radius:var(--r99);}

/* ── RESPONSIVE ── */
@media(max-width:680px){
  .pgrid{grid-template-columns:1fr;}
  .dcol{display:none;}
  .hsep{display:block;}
  .panel{padding:22px 18px 20px;}
  .panel-l{border-radius:0;}
  .panel-r{border-radius:0 0 var(--r32) var(--r32);}
  .rval{font-size:15px;}
  .tabs{width:100%;border-radius:var(--r12);}
  .pdrop{width:calc(100vw - 32px) !important;}
}
@media(max-width:400px){
  body{padding:24px 10px 60px;}
  .h1{font-size:20px;}
  .tab{font-size:11px;padding:7px 11px;}
}
</style>
</head>
<body>

<!-- BACKGROUND -->
<div class="aurora">
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="blob b3"></div>
</div>

<!-- CALENDAR DROPDOWN PORTAL — rendered outside card, never clipped -->
<div id="picker-portal"></div>

<div class="shell" x-data="mainApp">

  <!-- HEADER -->
  <div class="header">
    <div class="eyebrow"><span class="edot"></span>{{ pcal_trans('converter_title') }}</div>
    <h1 class="h1">{{ pcal_trans('converter_title') }}</h1>
    <p class="h2">Gregorian <span>•</span> Pashto (Shamsi) <span>•</span> Islamic (Hijri)</p>
  </div>

  <!-- TABS -->
  <div class="tabs">
    <template x-for="(tab,i) in tabs" :key="i">
      <button class="tab" :class="activeTab===i?'a'+tab.fc:''" @click="switchTab(i)" x-text="tab.label"></button>
    </template>
  </div>

  <!-- CARD -->
  <div class="card">

    <!-- Direction strip -->
    <div class="dstrip">
      <div class="dpill" :class="cur.fc">
        <span x-text="cur.fromPill"></span>
      </div>
      <div class="darrow">→</div>
      <div class="dpill" :class="cur.tc">
        <span x-text="cur.toPill"></span>
      </div>
    </div>

    <div class="pgrid">

      <!-- ── FROM PANEL ── -->
      <div class="panel panel-l">
        <div class="abar" :class="cur.fc"></div>

        <div class="badge" :class="cur.fc">
          <span class="bicon" :class="cur.fc">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                 :stroke="cur.fromType==='hijri'?'#fff':'#000'" stroke-width="2.5">
              <template x-if="cur.fromType!=='hijri'">
                <g><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></g>
              </template>
              <template x-if="cur.fromType==='hijri'">
                <g><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></g>
              </template>
            </svg>
          </span>
          <span x-text="cur.fromBadge"></span>
        </div>

        <div class="ptitle" x-text="cur.fromTitle"></div>

        <!-- Gregorian input -->
        <template x-if="cur.fromType==='gregorian'">
          <div>
            <label class="flabel">{{ pcal_trans('gregorian_date_label') }}</label>
            <div class="fwrap">
              <input type="date" class="finput g"
                     x-model="gregInput"
                     @change="runConvert"
                     @keyup.enter="runConvert">
            </div>
            <button class="btn g" @click="runConvert">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              {{ pcal_trans('convert') }}
            </button>
          </div>
        </template>

        <!-- Pashto input + picker -->
        <template x-if="cur.fromType==='pashto'">
          <div>
            <label class="flabel">{{ pcal_trans('pashto_date_label') }}</label>
            <div class="fwrap">
              <input type="text" class="finput t finput-pr"
                     x-model="pashtoTxt"
                     placeholder="{{ pcal_trans('eg_date') }}"
                     @keyup.enter="submitPashto">
              <button class="cal-btn t" @click.stop="togglePicker('pashto',$event)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
              </button>
            </div>
            <button class="btn t" @click="submitPashto">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              {{ pcal_trans('convert') }}
            </button>
          </div>
        </template>

        <!-- Hijri input + picker -->
        <template x-if="cur.fromType==='hijri'">
          <div>
            <label class="flabel">Hijri Date</label>
            <div class="fwrap">
              <input type="text" class="finput r finput-pr"
                     x-model="hijriTxt"
                     placeholder="e.g. 1447/01/01"
                     @keyup.enter="submitHijri">
              <button class="cal-btn r" @click.stop="togglePicker('hijri',$event)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
              </button>
            </div>
            <button class="btn r" @click="submitHijri">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              {{ pcal_trans('convert') }}
            </button>
          </div>
        </template>
      </div>

      <!-- ── CENTRE DIVIDER ── -->
      <div class="dcol">
        <div class="dline"></div>
        <button class="swapbtn" @click="swap" title="Swap">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3L4 7l4 4"/><path d="M4 7h16"/><path d="M16 21l4-4-4-4"/><path d="M20 17H4"/></svg>
        </button>
        <div class="dline"></div>
      </div>

      <!-- ── TO PANEL ── -->
      <div class="panel panel-r">
        <div class="abar" :class="cur.tc"></div>

        <div class="badge" :class="cur.tc">
          <span class="bicon" :class="cur.tc">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                 :stroke="cur.toType==='hijri'?'#fff':'#000'" stroke-width="2.5">
              <template x-if="cur.toType!=='hijri'">
                <g><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></g>
              </template>
              <template x-if="cur.toType==='hijri'">
                <g><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></g>
              </template>
            </svg>
          </span>
          <span x-text="cur.toBadge"></span>
        </div>

        <div class="ptitle" x-text="cur.toTitle"></div>

        <div class="result-area">
          <div x-show="!result&&!loading" class="hint">
            <div class="hbar" :class="cur.tc"></div>
            <div class="htxt">Pick a date on the left<br>and press Convert.</div>
          </div>

          <div x-show="loading" class="spinner-box">
            <svg class="spinner" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--t3)">
              <circle cx="12" cy="12" r="10" stroke-dasharray="32" stroke-dashoffset="10"/>
            </svg>
          </div>

          <div x-show="result" x-transition class="rbox" :class="cur.tc" style="display:none">
            <span class="rlabel">Result</span>
            <button class="rcopy" @click="copy">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
              <span x-text="copied?'✓ Copied':'Copy'"></span>
            </button>
            <div class="rval" x-text="resultMain"></div>
            <div class="rsub" x-show="resultSub" x-text="resultSub"></div>
          </div>
        </div>
      </div>

    </div><!-- /pgrid -->
    <div class="hsep"></div>
  </div><!-- /card -->

  <div class="footer">
    <a href="/pashto-calendar" class="blink">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
      {{ pcal_trans('back_to_calendar') }}
    </a>
  </div>
</div>

<script>
/* ── Translation strings passed from PHP ── */
window.pcalT = {
  invalidFormat: @json(pcal_trans('invalid_format')),
  failed:        @json(pcal_trans('conversion_failed')),
};

/* ─────────────────────────────────────────────────────────────
   CALENDAR PICKER — portal-based, position:fixed
   Rendered into #picker-portal (outside .card) so it is NEVER
   clipped by any parent's overflow or border-radius.
───────────────────────────────────────────────────────────── */
const PickerPortal = {
  _el: null,
  _type: null,   // 'pashto' | 'hijri'
  _state: null,  // reference to the Alpine state object
  _trigger: null,

  pashtoMonths: ['','وری','غویی','غبرګولی','چنګاښ','زمری','وږی','تله','لړم','لیندۍ','مرغومی','سلواغه','کب'],
  hijriMonths:  ['','محرم','صفر','ربیع الأول','ربیع الثاني','جمادى الأولى','جمادى الآخرة','رجب','شعبان','رمضان','شوال','ذو القعدة','ذو الحجة'],

  /* ── Hijri helpers ── */
  hijriMonthLen(y, m) {
    if (m % 2 === 1) return 30;
    const leapYears = [2,5,7,10,13,16,18,21,24,26,29];
    if (m === 12 && leapYears.includes(y % 30)) return 30;
    return 29;
  },
  hijriToJd(y, m, d) {
    return Math.floor((11*y+3)/30) + 354*y + 30*m - Math.floor((m-1)/2) + d + 1948440 - 385;
  },
  hijriFirstDow(y, m) {
    return (this.hijriToJd(y, m, 1) + 1) % 7; // 0=Sun
  },

  /* ── Pashto helpers ── */
  pashtoMonthLen(y, m) {
    const l = [31,31,31,31,31,31,30,30,30,30,30,29];
    if (m === 12 && [1,5,9,13,17,21,25,29].includes(y % 33)) return 30;
    return l[m-1];
  },
  pashtoFirstDow(y, m) {
    let t = 0;
    for (let yr = 1403; yr < y; yr++)
      t += [1,5,9,13,17,21,25,29].includes(yr%33) ? 366 : 365;
    for (let mo = 1; mo < m; mo++)
      t += this.pashtoMonthLen(y, mo);
    return (t + 4) % 7; // 0=Sat in Pashto week
  },

  open(type, triggerEl, state) {
    this._type    = type;
    this._state   = state;
    this._trigger = triggerEl;
    this._render();
    this._position();
    // close on outside click
    setTimeout(() => {
      document.addEventListener('click', this._outside, { once: true });
      document.addEventListener('scroll', this._onScroll, { passive:true, capture:true });
    }, 10);
  },

  close() {
    const el = document.getElementById('picker-portal');
    if (el) el.innerHTML = '';
    document.removeEventListener('click', this._outside);
    document.removeEventListener('scroll', this._onScroll, true);
    this._el = null;
  },

  _outside(e) {
    const el = document.getElementById('picker-portal');
    if (el && !el.contains(e.target)) PickerPortal.close();
  },
  _onScroll() { PickerPortal._position(); },

  _position() {
    const drop = document.querySelector('#picker-portal .pdrop');
    if (!drop || !this._trigger) return;
    const tr  = this._trigger.getBoundingClientRect();
    const vw  = window.innerWidth;
    const vh  = window.innerHeight;
    const dw  = Math.min(300, vw - 32);
    drop.style.width = dw + 'px';

    let left = tr.left;
    if (left + dw > vw - 8) left = Math.max(8, vw - dw - 8);

    // Open below trigger; flip above if not enough room
    const dropH = drop.offsetHeight || 320;
    let top = tr.bottom + 8;
    if (top + dropH > vh - 8) top = Math.max(8, tr.top - dropH - 8);

    drop.style.top  = top  + 'px';
    drop.style.left = left + 'px';
  },

  _navigate(delta) {
    const s = this._state;
    let m = s.vm + delta, y = s.vy;
    if (m > 12) { m = 1; y++; }
    if (m < 1)  { m = 12; y--; }
    s.vm = m; s.vy = y;
    this._render();
    this._position();
  },

  _select(day) {
    const s  = this._state;
    s.sy = s.vy; s.sm = s.vm; s.sd = day;
    const pad = n => String(n).padStart(2,'0');
    const txt = `${s.vy}/${pad(s.vm)}/${pad(day)}`;
    if (this._type === 'pashto') {
      window.__mc.pashtoTxt = txt;
      window.__mc.submitPashto();
    } else {
      window.__mc.hijriTxt  = txt;
      window.__mc.submitHijri();
    }
    this.close();
  },

  _render() {
    const portal = document.getElementById('picker-portal');
    if (!portal) return;
    const s = this._state;
    const t = this._type;
    const color = t === 'pashto' ? 't' : 'r';

    const monthName = t === 'pashto'
      ? this.pashtoMonths[s.vm]
      : this.hijriMonths[s.vm];

    const dim  = t === 'pashto'
      ? this.pashtoMonthLen(s.vy, s.vm)
      : this.hijriMonthLen(s.vy, s.vm);

    const first = t === 'pashto'
      ? this.pashtoFirstDow(s.vy, s.vm)
      : this.hijriFirstDow(s.vy, s.vm);

    const dayNames = t === 'pashto'
      ? ['ش','ی','د','س','چ','پ','ج']
      : ['ح','ن','ث','ر','خ','ج','س'];

    let cells = '';
    for (let i = 0; i < first; i++)
      cells += `<div class="pcell empty"></div>`;
    for (let d = 1; d <= dim; d++) {
      const isSel   = s.sy === s.vy && s.sm === s.vm && s.sd === d;
      const isToday = s.ty === s.vy && s.tm === s.vm && s.td === d;
      const cls = ['pcell', isSel?'sel':'', isToday?'today':''].filter(Boolean).join(' ');
      cells += `<div class="${cls}" onclick="PickerPortal._select(${d})">${d}</div>`;
    }

    portal.innerHTML = `
      <div class="pdrop ${color}">
        <div class="phdr">
          <button class="pnav" onclick="PickerPortal._navigate(-1)">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <div class="pmtitle">
            ${monthName}
            <span class="pyear">${s.vy}</span>
          </div>
          <button class="pnav" onclick="PickerPortal._navigate(1)">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
        <div class="pcalgrid">
          ${dayNames.map(n=>`<div class="pdn">${n}</div>`).join('')}
          ${cells}
        </div>
      </div>`;
  },
};

/* ─────────────────────────────────────────────────────────────
   ALPINE APP
───────────────────────────────────────────────────────────── */
document.addEventListener('alpine:init', () => {
  Alpine.data('mainApp', () => ({
    activeTab: 0,
    result: '', resultMain: '', resultSub: '',
    loading: false,
    copied:  false,

    gregInput:  '',
    pashtoTxt:  '',
    hijriTxt:   '',

    /* Picker state (shared between Pashto + Hijri pickers via PickerPortal) */
    pashtoState: {
      vy: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
      vm: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
      sy: null, sm: null, sd: null,
      ty: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->year }},
      tm: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->month }},
      td: {{ \Qadir\PashtoCalendar\PashtoCalendar::now()->day }},
    },
    hijriState: {
      vy: {{ (int) \Qadir\PashtoCalendar\Converter\HijriConverter::gregorianToHijri(
                date('Y'), date('n'), date('j')
            )[0] }},
      vm: {{ (int) \Qadir\PashtoCalendar\Converter\HijriConverter::gregorianToHijri(
                date('Y'), date('n'), date('j')
            )[1] }},
      sy: null, sm: null, sd: null,
      ty: {{ (int) \Qadir\PashtoCalendar\Converter\HijriConverter::gregorianToHijri(
                date('Y'), date('n'), date('j')
            )[0] }},
      tm: {{ (int) \Qadir\PashtoCalendar\Converter\HijriConverter::gregorianToHijri(
                date('Y'), date('n'), date('j')
            )[1] }},
      td: {{ (int) \Qadir\PashtoCalendar\Converter\HijriConverter::gregorianToHijri(
                date('Y'), date('n'), date('j')
            )[2] }},
    },

    tabs: [
      {label:'Gregorian → Pashto',  fromType:'gregorian',toType:'pashto',    fc:'g',tc:'t',
       fromPill:'Gregorian',   toPill:'Pashto',        fromBadge:'Gregorian',    toBadge:'Pashto (Shamsi)',
       fromTitle:'Enter a Gregorian date', toTitle:'Pashto (Shamsi) result'},
      {label:'Gregorian → Hijri',   fromType:'gregorian',toType:'hijri',     fc:'g',tc:'r',
       fromPill:'Gregorian',   toPill:'Islamic Hijri', fromBadge:'Gregorian',    toBadge:'Islamic Hijri',
       fromTitle:'Enter a Gregorian date', toTitle:'Hijri result'},
      {label:'Pashto → Gregorian',  fromType:'pashto',   toType:'gregorian', fc:'t',tc:'g',
       fromPill:'Pashto',      toPill:'Gregorian',     fromBadge:'Pashto (Shamsi)', toBadge:'Gregorian',
       fromTitle:'Enter a Pashto (Shamsi) date', toTitle:'Gregorian result'},
      {label:'Pashto → Hijri',      fromType:'pashto',   toType:'hijri',     fc:'t',tc:'r',
       fromPill:'Pashto',      toPill:'Islamic Hijri', fromBadge:'Pashto (Shamsi)', toBadge:'Islamic Hijri',
       fromTitle:'Enter a Pashto (Shamsi) date', toTitle:'Hijri result'},
      {label:'Hijri → Gregorian',   fromType:'hijri',    toType:'gregorian', fc:'r',tc:'g',
       fromPill:'Islamic Hijri',toPill:'Gregorian',    fromBadge:'Islamic Hijri',toBadge:'Gregorian',
       fromTitle:'Enter an Islamic (Hijri) date', toTitle:'Gregorian result'},
      {label:'Hijri → Pashto',      fromType:'hijri',    toType:'pashto',    fc:'r',tc:'t',
       fromPill:'Islamic Hijri',toPill:'Pashto',       fromBadge:'Islamic Hijri',toBadge:'Pashto (Shamsi)',
       fromTitle:'Enter an Islamic (Hijri) date', toTitle:'Pashto result'},
    ],

    get cur() { return this.tabs[this.activeTab]; },

    init() { window.__mc = this; },

    switchTab(i) {
      this.activeTab = i;
      this.result = this.resultMain = this.resultSub = '';
      PickerPortal.close();
    },

    swap() {
      const t = this.cur;
      const rev = this.tabs.findIndex(x => x.fromType===t.toType && x.toType===t.fromType);
      if (rev !== -1) { this.activeTab = rev; this.result = this.resultMain = this.resultSub = ''; }
      PickerPortal.close();
    },

    togglePicker(type, event) {
      const el = document.querySelector('#picker-portal .pdrop');
      if (el) { PickerPortal.close(); return; }
      const state = type === 'pashto' ? this.pashtoState : this.hijriState;
      PickerPortal.open(type, event.currentTarget, state);
    },

    copy() {
      if (!this.result) return;
      navigator.clipboard?.writeText(this.result);
      this.copied = true;
      setTimeout(() => this.copied = false, 2000);
    },

    /* ── GREGORIAN source ── */
    async runConvert() {
      if (!this.gregInput) return;
      this.loading = true; this.result = this.resultMain = this.resultSub = '';
      try {
        const toType = this.cur.toType;
        const url = toType === 'pashto'
          ? `/pashto-calendar/convert/gregorian?date=${encodeURIComponent(this.gregInput)}`
          : `/pashto-calendar/convert/gregorian-to-hijri?date=${encodeURIComponent(this.gregInput)}`;
        const d = await fetch(url).then(r => r.json());
        if (d.error) { alert(d.error); return; }
        this._setResult(d, toType);
      } catch { alert(window.pcalT.failed); }
      finally   { this.loading = false; }
    },

    /* ── PASHTO source ── */
    submitPashto() {
      const p = this.pashtoTxt.split('/');
      if (p.length !== 3 || p.some(x => !x.trim())) { alert(window.pcalT.invalidFormat); return; }
      const y=+p[0], m=+p[1], d=+p[2];
      this.pashtoState.sy = y; this.pashtoState.sm = m; this.pashtoState.sd = d;
      this.pashtoState.vy = y; this.pashtoState.vm = m;
      this._runFromPashto(y, m, d);
    },

    async _runFromPashto(y, m, d) {
      this.loading = true; this.result = this.resultMain = this.resultSub = '';
      try {
        const toType = this.cur.toType;
        const url = toType === 'gregorian'
          ? `/pashto-calendar/convert/pashto?year=${y}&month=${m}&day=${d}`
          : `/pashto-calendar/convert/pashto-to-hijri?year=${y}&month=${m}&day=${d}`;
        const data = await fetch(url).then(r => r.json());
        if (data.error) { alert(data.error); return; }
        this._setResult(data, toType);
      } catch { alert(window.pcalT.failed); }
      finally   { this.loading = false; }
    },

    /* ── HIJRI source ── */
    submitHijri() {
      const p = this.hijriTxt.split('/');
      if (p.length !== 3 || p.some(x => !x.trim())) { alert(window.pcalT.invalidFormat); return; }
      const y=+p[0], m=+p[1], d=+p[2];
      this.hijriState.sy = y; this.hijriState.sm = m; this.hijriState.sd = d;
      this.hijriState.vy = y; this.hijriState.vm = m;
      this._runFromHijri(y, m, d);
    },

    async _runFromHijri(y, m, d) {
      this.loading = true; this.result = this.resultMain = this.resultSub = '';
      try {
        const toType = this.cur.toType;
        const url = toType === 'gregorian'
          ? `/pashto-calendar/convert/hijri-to-gregorian?year=${y}&month=${m}&day=${d}`
          : `/pashto-calendar/convert/hijri-to-pashto?year=${y}&month=${m}&day=${d}`;
        const data = await fetch(url).then(r => r.json());
        if (data.error) { alert(data.error); return; }
        this._setResult(data, toType);
      } catch { alert(window.pcalT.failed); }
      finally   { this.loading = false; }
    },

    /* ── Parse & display result ── */
    _setResult(d, toType) {
      if (toType === 'gregorian') {
        // Clean Gregorian: "2025-06-27" → "27 June 2025"
        const raw = d.gregorian || `${d.year}-${String(d.month).padStart(2,'0')}-${String(d.day).padStart(2,'0')}`;
        const dt  = new Date(raw + 'T12:00:00');
        this.resultMain = dt.toLocaleDateString('en-GB',{day:'numeric',month:'long',year:'numeric'});
        this.resultSub  = raw;
        this.result     = this.resultMain;

      } else if (toType === 'pashto') {
        // Pashto: show formatted + numeric
        this.resultMain = d.formatted || `${d.year}/${String(d.month).padStart(2,'0')}/${String(d.day).padStart(2,'0')}`;
        this.resultSub  = `${d.year}/${String(d.month).padStart(2,'0')}/${String(d.day).padStart(2,'0')}`;
        this.result     = this.resultMain;

      } else {
        // Hijri: show "1 محرم 1447" on top, numeric below
        // d.formatted = "1 محرم 1447  (1447/01/01)"  — split on the parenthesis
        const formatted = d.formatted || '';
        const parenIdx  = formatted.indexOf('(');
        if (parenIdx > 0) {
          this.resultMain = formatted.substring(0, parenIdx).trim();
          this.resultSub  = d.hijri || formatted.substring(parenIdx).replace(/[()]/g,'').trim();
        } else {
          this.resultMain = formatted || d.hijri || `${d.year}/${d.month}/${d.day}`;
          this.resultSub  = d.hijri  || '';
        }
        this.result = this.resultMain;
      }
    },
  }));
});
</script>

</body>
</html>