<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0B1120">
    <title>@yield('title', 'E-Services') — Government Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --primary:#1B4FD8;--primary-dk:#1740B8;--primary-lt:#EEF3FF;--primary-light:#EEF3FF;
        --brand:#1B4FD8;--brand-dk:#1740B8;--brand-lt:#EEF3FF;--brand-glow:rgba(27,79,216,.18);
        --blue-50:#EFF6FF;--blue-100:#DBEAFE;--blue-200:#BFDBFE;
        --emerald:#059669;--emerald-lt:#ECFDF5;--emerald-bg:#ECFDF5;
        --amber:#D97706;--amber-lt:#FFFBEB;--amber-bg:#FFFBEB;
        --rose:#DC2626;--rose-lt:#FEF2F2;--rose-bg:#FEF2F2;
        --violet:#7C3AED;--violet-lt:#F5F3FF;--violet-bg:#F5F3FF;
        --sky:#0284C7;--sky-bg:#F0F9FF;
        --cyan-lt:#ECFEFF;--green:#16A34A;--green-lt:#F0FDF4;
        --gold:#D97706;--gold-100:#FEF3C7;--gold-600:#D97706;
        --navy-50:#EFF6FF;
        --ink-900:#0F172A;--ink-800:#1E293B;--ink-600:#475569;--ink-500:#64748B;
        --ink-400:#94A3B8;--ink-300:#CBD5E1;--ink-200:#E2E8F0;--ink-100:#F1F5F9;--ink-50:#F8FAFC;
        --white:#FFFFFF;--slate:#475569;
        --tx-900:#0F172A;--tx-700:#334155;--tx-600:#475569;--tx-500:#64748B;--tx-400:#94A3B8;--tx-300:#CBD5E1;
        --sb-bg:#0B1120;--sb-border:rgba(255,255,255,.055);--sb-text:rgba(255,255,255,.5);
        --sb-hi:#FFFFFF;--sb-hover:rgba(255,255,255,.06);--sb-active-bg:rgba(27,79,216,.75);--sb-w:252px;
        --page-bg:#EEF2F7;--surface:#FFFFFF;--surface-2:#F7F9FC;--border:#E2E8F0;--border-lt:#F1F5F9;
        --shadow-sm:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
        --shadow-md:0 4px 8px rgba(0,0,0,.07),0 2px 4px rgba(0,0,0,.05);
        --shadow-lg:0 12px 28px rgba(0,0,0,.1),0 4px 8px rgba(0,0,0,.06);
        --sh-xs:0 1px 2px rgba(0,0,0,.04);--sh-sm:0 1px 3px rgba(0,0,0,.07);
        --sh-md:0 4px 8px rgba(0,0,0,.07);--sh-lg:0 12px 28px rgba(0,0,0,.1);
        --sh-xl:0 24px 56px rgba(0,0,0,.14),0 8px 16px rgba(0,0,0,.08);
        --top-h:56px;--r:12px;--r-sm:8px;--r-xs:6px;--radius:12px;--radius-sm:8px;
        --font:'Outfit',system-ui,sans-serif;--font-disp:'Outfit',system-ui,sans-serif;
        --font-mono:'IBM Plex Mono',monospace;--mono:'IBM Plex Mono',monospace;
        --ease:cubic-bezier(.4,0,.2,1);--spring:cubic-bezier(.34,1.4,.64,1);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{font-size:14px;scroll-behavior:smooth}
    body{font-family:var(--font);background:var(--page-bg);color:var(--ink-600);line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}

    /* Sidebar */
    .sidebar{position:fixed;inset:0 auto 0 0;width:var(--sb-w);background:var(--sb-bg);z-index:1050;display:flex;flex-direction:column;border-right:1px solid var(--sb-border);transition:transform .28s var(--ease)}
    .sidebar::before{content:'';position:absolute;top:0;left:0;right:0;height:200px;background:radial-gradient(ellipse 130% 110% at 50% -10%,rgba(27,79,216,.25) 0%,transparent 70%);pointer-events:none}
    .sb-brand{display:flex;align-items:center;gap:.7rem;padding:1rem .9rem .9rem;border-bottom:1px solid var(--sb-border);flex-shrink:0;position:relative;z-index:1;text-decoration:none}
    .sb-mark{width:36px;height:36px;border-radius:9px;flex-shrink:0;background:linear-gradient(140deg,var(--primary) 0%,#5B8EFF 100%);display:flex;align-items:center;justify-content:center;font-size:.95rem;color:#fff;box-shadow:0 3px 10px rgba(27,79,216,.5)}
    .sb-name{color:var(--sb-hi);font-size:.87rem;font-weight:700;line-height:1.2;letter-spacing:-.01em}
    .sb-sub{color:rgba(255,255,255,.25);font-size:.63rem;letter-spacing:.06em;text-transform:uppercase}
    .sb-scroll{flex:1;overflow-y:auto;overflow-x:hidden;padding:.5rem .6rem}
    .sb-scroll::-webkit-scrollbar{width:3px}
    .sb-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.08);border-radius:3px}
    .sb-section{color:rgba(255,255,255,.18);font-size:.61rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;padding:.7rem .5rem .2rem;user-select:none}
    .sidebar .nav-link{display:flex;align-items:center;gap:.55rem;color:var(--sb-text);padding:.5rem .65rem;border-radius:var(--r-sm);font-size:.83rem;font-weight:500;text-decoration:none;margin-bottom:1px;transition:background .15s,color .15s;position:relative;white-space:nowrap}
    .sidebar .nav-link .ni{font-size:.95rem;width:18px;text-align:center;flex-shrink:0;transition:transform .2s var(--spring)}
    .sidebar .nav-link:hover{background:var(--sb-hover);color:var(--sb-hi)}
    .sidebar .nav-link:hover .ni{transform:scale(1.18)}
    .sidebar .nav-link.active{background:var(--sb-active-bg);color:#fff;box-shadow:0 2px 10px rgba(27,79,216,.4)}
    .sidebar .nav-link.active::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:3px;background:#7BA8FF;border-radius:0 3px 3px 0}
    .nb{margin-left:auto;font-size:.6rem;font-weight:700;padding:.1rem .38rem;border-radius:99px;flex-shrink:0;background:var(--rose);color:#fff;line-height:1.4}
    .sb-footer{border-top:1px solid var(--sb-border);padding:.7rem .6rem .9rem;flex-shrink:0}
    .sb-user{display:flex;align-items:center;gap:.6rem;background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.07);border-radius:var(--r-sm);padding:.55rem .7rem}
    .sb-av{width:30px;height:30px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,var(--primary),#60A5FA);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;border:2px solid rgba(255,255,255,.12)}
    .sb-un{color:rgba(255,255,255,.85);font-size:.78rem;font-weight:600;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .sb-ur{color:rgba(255,255,255,.25);font-size:.63rem}
    .sb-logout{width:28px;height:28px;flex-shrink:0;border-radius:var(--r-xs);background:transparent;border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.3);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.82rem;transition:all .15s}
    .sb-logout:hover{background:rgba(220,38,38,.18);color:#FCA5A5;border-color:rgba(220,38,38,.35)}

    /* Overlay */
    .sb-overlay{display:none;position:fixed;inset:0;background:rgba(11,17,32,.65);z-index:1040;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
    .sb-overlay.show{display:block;animation:fadeIn .2s var(--ease)}
    @keyframes fadeIn{from{opacity:0}to{opacity:1}}

    /* Topbar */
    .topbar{position:fixed;top:0;left:var(--sb-w);right:0;height:var(--top-h);z-index:1000;background:rgba(238,242,247,.92);backdrop-filter:saturate(200%) blur(20px);-webkit-backdrop-filter:saturate(200%) blur(20px);border-bottom:1px solid rgba(0,0,0,.07);display:flex;align-items:center;gap:.65rem;padding:0 1.25rem;transition:left .28s var(--ease)}
    .menu-btn{display:none;width:34px;height:34px;border:1.5px solid var(--border);border-radius:var(--r-sm);background:var(--surface);cursor:pointer;align-items:center;justify-content:center;font-size:1.05rem;color:var(--ink-500);transition:all .15s;flex-shrink:0}
    .menu-btn:hover{background:var(--primary-lt);color:var(--primary);border-color:var(--primary)}
    .top-title{flex:1;font-size:.9rem;font-weight:700;color:var(--ink-900);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;letter-spacing:-.01em}
    .top-right{display:flex;align-items:center;gap:.4rem;flex-shrink:0}
    .top-btn{width:34px;height:34px;border-radius:var(--r-sm);border:1.5px solid var(--border);background:var(--surface);display:flex;align-items:center;justify-content:center;color:var(--ink-500);cursor:pointer;font-size:.9rem;position:relative;transition:all .15s}
    .top-btn:hover{background:var(--primary-lt);color:var(--primary);border-color:var(--blue-200)}
    .top-dot{position:absolute;top:-3px;right:-3px;width:15px;height:15px;border-radius:50%;background:var(--rose);color:#fff;font-size:.55rem;display:flex;align-items:center;justify-content:center;border:2px solid var(--page-bg);font-weight:800}
    .top-av{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#60A5FA);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;cursor:pointer;flex-shrink:0;border:2px solid var(--surface);box-shadow:0 0 0 2px var(--primary-lt);transition:box-shadow .15s}
    .top-av:hover{box-shadow:0 0 0 3px var(--blue-200)}

    /* Layout */
    .main-wrap{margin-left:var(--sb-w);padding-top:var(--top-h);min-height:100vh;transition:margin-left .28s var(--ease)}
    .main-pad{padding:1.35rem}
    .page-header,.pg-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem}
    .pg-header h1{font-size:1.2rem;font-weight:800;color:var(--ink-900);letter-spacing:-.02em;margin:0}

    /* Hero banners */
    .hero-banner,.hero-band{background:linear-gradient(125deg,#0B1120 0%,#0D1E55 50%,#0A1A56 100%);border-radius:var(--r);padding:1.35rem 1.5rem;position:relative;overflow:hidden}
    .hero-banner::before,.hero-band::before{content:'';position:absolute;top:-50px;right:-30px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(27,79,216,.22) 0%,transparent 70%);pointer-events:none}
    .hero-banner::after,.hero-band::after{content:'';position:absolute;bottom:-30px;left:20%;width:170px;height:170px;border-radius:50%;background:radial-gradient(circle,rgba(6,148,162,.12) 0%,transparent 70%);pointer-events:none}

    /* Cards */
    .card{background:var(--surface)!important;border:1px solid var(--border)!important;border-radius:var(--r)!important;box-shadow:var(--sh-sm)}
    .card-header{background:var(--surface)!important;border-bottom:1px solid var(--border-lt)!important;padding:.85rem 1.2rem!important;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem}
    .card-title{font-size:.87rem;font-weight:700;color:var(--ink-900);margin:0;letter-spacing:-.01em}
    .card-body{padding:1.2rem!important}
    .card-body.p0{padding:0!important}

    /* Stat cards */
    .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:1.1rem;box-shadow:var(--sh-sm);position:relative;overflow:hidden;transition:transform .22s var(--spring),box-shadow .22s}
    .stat-card::after{content:'';position:absolute;bottom:-18px;right:-18px;width:85px;height:85px;border-radius:50%;background:var(--sc-accent,var(--primary-lt));opacity:.5;transition:transform .3s var(--ease);pointer-events:none}
    .stat-card:hover{transform:translateY(-3px);box-shadow:var(--sh-md)}
    .stat-card:hover::after{transform:scale(1.35)}
    .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0}
    .stat-val{font-size:1.65rem;font-weight:800;letter-spacing:-.04em;line-height:1;margin-top:.65rem;color:var(--ink-900)}
    .stat-lbl{font-size:.72rem;color:var(--ink-400);font-weight:500;margin-top:.18rem}
    .stat-delta{display:inline-flex;align-items:center;gap:.15rem;font-size:.67rem;font-weight:700;padding:.15rem .45rem;border-radius:99px}
    .stat-delta.up,.chip-up{background:var(--emerald-lt);color:#065F46}
    .stat-delta.dn,.chip-dn{background:var(--rose-lt);color:#991B1B}
    .chip-neu{background:var(--ink-100);color:var(--ink-500)}

    /* Status badges */
    .sbadge{display:inline-flex;align-items:center;gap:.25rem;padding:.22rem .62rem;border-radius:99px;font-size:.68rem;font-weight:700;white-space:nowrap;letter-spacing:.01em}
    .sbadge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
    .s-pending{background:var(--amber-lt);color:#92400E}
    .s-in_review{background:var(--sky-bg);color:#0369A1}
    .s-missing_documents{background:var(--rose-lt);color:#991B1B}
    .s-approved{background:var(--emerald-lt);color:#065F46}
    .s-rejected{background:var(--rose-lt);color:#991B1B}
    .s-completed{background:var(--emerald-lt);color:#065F46}
    .s-paid{background:var(--emerald-lt);color:#065F46}
    .s-unpaid{background:var(--rose-lt);color:#991B1B}
    .s-scheduled{background:var(--sky-bg);color:#0369A1}
    .s-confirmed{background:var(--emerald-lt);color:#065F46}
    .s-cancelled{background:var(--rose-lt);color:#991B1B}
    .s-active{background:var(--emerald-lt);color:#065F46}
    .s-inactive,.s-false{background:var(--ink-100);color:var(--ink-500)}
    .s-true{background:var(--emerald-lt);color:#065F46}
    .s-neutral{background:var(--ink-100);color:var(--ink-600)}

    /* Tables */
    .table-wrap,.tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    .table{font-size:.82rem;margin:0}
    .table thead th{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-400);background:var(--surface-2);border-bottom:1px solid var(--border-lt);padding:.72rem 1.1rem;white-space:nowrap}
    .table tbody td{padding:.82rem 1.1rem;vertical-align:middle;border-color:var(--border-lt);font-size:.82rem}
    .table tbody tr{transition:background .1s}
    .table tbody tr:hover td{background:var(--surface-2)}
    .table tbody tr:last-child td{border-bottom:none}

    /* Forms */
    .form-control,.form-select{border-radius:var(--r-sm);border:1.5px solid var(--border);font-size:.83rem;padding:.55rem .9rem;font-family:var(--font);background:var(--surface);color:var(--ink-900);transition:border-color .15s,box-shadow .15s;min-height:38px}
    .form-control:focus,.form-select:focus{border-color:var(--primary);outline:none;box-shadow:0 0 0 3px rgba(27,79,216,.1)}
    .form-control::placeholder{color:var(--ink-300)}
    .form-label{font-size:.76rem;font-weight:600;color:var(--ink-600);margin-bottom:.35rem;display:block}
    .form-text{font-size:.7rem;color:var(--ink-400);margin-top:.25rem;display:block}
    .input-icon-wrap{position:relative}
    .input-icon-wrap .ii,.input-icon-wrap .field-icon{position:absolute;left:.82rem;top:50%;transform:translateY(-50%);color:var(--ink-400);font-size:.9rem;pointer-events:none}
    .input-icon-wrap .form-control{padding-left:2.45rem}
    .toggle-pw{position:absolute;right:.82rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--ink-400);cursor:pointer;font-size:.9rem;padding:0}
    .toggle-pw:hover{color:var(--primary)}

    /* Buttons */
    .btn{border-radius:var(--r-sm);font-size:.82rem;font-weight:600;font-family:var(--font);padding:.5rem 1rem;transition:all .18s;display:inline-flex;align-items:center;gap:.38rem;letter-spacing:-.01em;min-height:36px;white-space:nowrap}
    .btn-primary{background:var(--primary);border-color:var(--primary);color:#fff!important}
    .btn-primary:hover{background:var(--primary-dk);border-color:var(--primary-dk);color:#fff!important;box-shadow:0 4px 12px rgba(27,79,216,.35);transform:translateY(-1px)}
    .btn-primary:active{transform:none;box-shadow:none}
    .btn-outline-primary{background:transparent;border:1.5px solid var(--primary);color:var(--primary)}
    .btn-outline-primary:hover{background:var(--primary-lt)}
    .btn-ghost,.btn-light{background:var(--ink-50);border:1.5px solid var(--border);color:var(--ink-600)}
    .btn-ghost:hover,.btn-light:hover{background:var(--ink-100);color:var(--ink-900)}
    .btn-danger{background:var(--rose);border-color:var(--rose);color:#fff!important}
    .btn-danger:hover{background:#B91C1C;color:#fff!important;box-shadow:0 4px 12px rgba(220,38,38,.3)}
    .btn-success{background:var(--emerald);border-color:var(--emerald);color:#fff!important}
    .btn-success:hover{background:#047857;color:#fff!important}
    .btn-sm{padding:.32rem .72rem;font-size:.75rem;min-height:30px;gap:.28rem}
    .btn-lg{padding:.65rem 1.4rem;font-size:.88rem;min-height:42px}
    .btn-icon{width:32px;height:32px;padding:0;justify-content:center}
    .btn-block{width:100%;justify-content:center}

    /* Alerts */
    .alert{border-radius:var(--r-sm);border:none!important;font-size:.82rem;padding:.7rem .95rem;display:flex;align-items:flex-start;gap:.55rem}
    .alert-success{background:var(--emerald-lt);color:#065F46}
    .alert-danger{background:var(--rose-lt);color:#991B1B}
    .alert-warning{background:var(--amber-lt);color:#92400E}
    .alert-info{background:var(--sky-bg);color:#0369A1}
    .alert .btn-close{margin-left:auto;opacity:.45}

    /* Dropdowns */
    .dropdown-menu{border-radius:var(--r-sm);border:1px solid var(--border-lt);box-shadow:var(--sh-lg);font-size:.82rem;padding:.3rem}
    .dropdown-item{border-radius:var(--r-xs);padding:.45rem .8rem;color:var(--ink-600);font-size:.82rem;transition:background .1s}
    .dropdown-item:hover{background:var(--surface-2);color:var(--ink-900)}
    .dropdown-item.text-danger:hover{background:var(--rose-lt);color:var(--rose)}
    .dropdown-header{padding:.5rem .8rem .2rem;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--ink-400)}
    .dropdown-divider{margin:.25rem;border-color:var(--border-lt)}

    /* Modals */
    .modal-content{border:none!important;border-radius:var(--r)!important;box-shadow:var(--sh-xl)}
    .modal-header{border-bottom:1px solid var(--border-lt)!important;padding:1.1rem 1.3rem .7rem!important}
    .modal-title{font-weight:700;font-size:.97rem}
    .modal-body{padding:.95rem 1.3rem!important}
    .modal-footer{border-top:1px solid var(--border-lt)!important;padding:.7rem 1.3rem 1.1rem!important;gap:.4rem}

    /* Code */
    code{font-family:var(--font-mono);font-size:.75em;background:var(--primary-lt);color:var(--primary);padding:.14em .45em;border-radius:var(--r-xs);font-weight:500}

    /* Info rows */
    .info-row,.ir{display:flex;justify-content:space-between;align-items:center;padding:.55rem 0;border-bottom:1px solid var(--border-lt);font-size:.82rem}
    .info-row:last-child,.ir:last-child{border-bottom:none}
    .ir-label,.ir-l{color:var(--ink-400);font-weight:500}
    .ir-value,.ir-v{font-weight:600;color:var(--ink-900);text-align:right}

    /* Progress */
    .progress-wrap,.prog-wrap{height:5px;background:var(--border-lt);border-radius:99px;overflow:hidden}
    .progress-bar-inner,.prog-bar{height:100%;border-radius:99px;background:var(--primary);transition:width .5s var(--ease)}

    /* Empty state */
    .empty-state,.empty{text-align:center;padding:3rem 1.5rem}
    .empty-icon,.empty-ic{width:64px;height:64px;border-radius:50%;margin:0 auto .85rem;background:var(--ink-50);border:2px solid var(--border-lt);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--ink-300)}
    .empty-state h4,.empty h4{font-size:.9rem;font-weight:700;color:var(--ink-600);margin-bottom:.25rem}
    .empty-state p,.empty p{font-size:.78rem;color:var(--ink-400);margin-bottom:.85rem}

    /* Toasts */
    .toast-stack{position:fixed;bottom:1.1rem;right:1.1rem;z-index:9999;display:flex;flex-direction:column;gap:.45rem}
    .toast-item{min-width:270px;max-width:370px;background:#0F172A;color:#fff;border-radius:var(--r-sm);padding:.8rem 1rem;display:flex;align-items:center;gap:.6rem;box-shadow:var(--sh-xl);font-size:.82rem;line-height:1.45;animation:tIn .35s var(--spring)}
    .toast-item.success{border-left:3px solid var(--emerald)}
    .toast-item.error{border-left:3px solid var(--rose)}
    .toast-item.info{border-left:3px solid var(--primary)}
    .toast-item.out{animation:tOut .25s var(--ease) forwards}
    @keyframes tIn{from{opacity:0;transform:translateX(100%) scale(.95)}to{opacity:1;transform:none}}
    @keyframes tOut{to{opacity:0;transform:translateX(100%) scale(.95)}}

    /* Chat (support both .msg and .chat-msg class variants) */
    .chat-box{max-height:320px;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:.65rem;background:var(--ink-50)}
    .msg,.chat-msg{display:flex;align-items:flex-end;gap:.45rem}
    .msg.mine,.chat-msg.me{flex-direction:row-reverse}
    .msg-av,.chat-av{width:26px;height:26px;border-radius:50%;background:var(--slate);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.63rem;font-weight:700;flex-shrink:0}
    .av-me,.chat-av.mine{background:linear-gradient(135deg,var(--primary),#60A5FA)}
    .av-other{background:var(--ink-400)}
    .msg-bubble,.chat-bub{max-width:74%}
    .msg-bubble p,.chat-bub p{font-size:.82rem;margin:0;padding:.52rem .8rem;border-radius:14px;line-height:1.45}
    .msg.mine .msg-bubble p,.chat-msg.me .chat-bub p{background:var(--primary);color:#fff;border-radius:14px 14px 4px 14px}
    .msg:not(.mine) .msg-bubble p,.chat-msg:not(.me) .chat-bub p{background:var(--surface);color:var(--ink-600);border:1px solid var(--border);border-radius:14px 14px 14px 4px}
    .msg-time,.chat-ts{font-size:.6rem;color:var(--ink-400);margin-top:2px}
    .msg.mine .msg-time,.chat-msg.me .chat-ts{text-align:right}

    /* Bottom nav */
    .bnav{display:none;position:fixed;bottom:0;left:0;right:0;background:rgba(255,255,255,.96);backdrop-filter:saturate(200%) blur(18px);-webkit-backdrop-filter:saturate(200%) blur(18px);border-top:1px solid var(--border);z-index:1030;padding:.28rem 0 calc(.28rem + env(safe-area-inset-bottom));box-shadow:0 -6px 20px rgba(0,0,0,.05)}
    .bnav a{flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;color:var(--ink-400);text-decoration:none;font-size:.58rem;font-weight:600;padding:.2rem .15rem;transition:color .12s;position:relative}
    .bnav a i{font-size:1.1rem}
    .bnav a.active{color:var(--primary)}
    .bnav a.active::before{content:'';position:absolute;top:-1px;left:50%;transform:translateX(-50%);width:24px;height:3px;background:var(--primary);border-radius:0 0 3px 3px}
    .bnav .bn-dot{position:absolute;top:0;right:calc(50% - 18px);width:7px;height:7px;border-radius:50%;background:var(--rose);border:1.5px solid var(--page-bg)}

    /* Scrollbar */
    ::-webkit-scrollbar{width:5px;height:5px}
    ::-webkit-scrollbar-track{background:transparent}
    ::-webkit-scrollbar-thumb{background:var(--ink-300);border-radius:99px}
    ::-webkit-scrollbar-thumb:hover{background:var(--ink-400)}

    /* Pagination */
    .pagination{gap:.2rem}
    .page-item .page-link{border-radius:var(--r-xs)!important;border:1.5px solid var(--border);color:var(--ink-500);font-size:.77rem;padding:.38rem .65rem;transition:all .12s}
    .page-item.active .page-link{background:var(--primary);border-color:var(--primary);color:#fff}
    .page-item .page-link:hover{background:var(--primary-lt);border-color:var(--primary);color:var(--primary)}

    /* Misc */
    .hide-mobile,.hide-sm{}
    .font-mono{font-family:var(--font-mono)}

    /* Responsive */
    @media(max-width:991.98px){
        .sidebar{transform:translateX(-100%)}
        .sidebar.open{transform:translateX(0);box-shadow:var(--sh-xl)}
        .topbar{left:0}
        .menu-btn{display:flex}
        .main-wrap{margin-left:0}
        .bnav{display:flex}
        .main-pad{padding-bottom:calc(60px + env(safe-area-inset-bottom))}
        .toast-stack{bottom:calc(66px + env(safe-area-inset-bottom))}
    }
    @media(max-width:767px){
        .main-pad{padding:.9rem}
        .hide-mobile,.hide-sm{display:none!important}
    }
    @media(max-width:575px){
        .main-pad{padding:.75rem}
        .topbar{padding:0 .85rem}
    }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

@auth
<div class="sb-overlay" id="sbOverlay"></div>

<aside class="sidebar" id="sidebar" aria-label="Navigation">
    <a href="{{ match(auth()->user()->role) { 'admin' => route('admin.dashboard'), 'office_user' => route('office.dashboard'), default => route('citizen.dashboard') } }}" class="sb-brand">
        <div class="sb-mark"><i class="bi bi-building-check"></i></div>
        <div><div class="sb-name">E-Services</div><div class="sb-sub">Gov Portal</div></div>
    </a>
    <div class="sb-scroll">
        <nav>
        @if(auth()->user()->isAdmin())
            <div class="sb-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}"><i class="bi bi-speedometer2 ni"></i> Dashboard</a>
            <div class="sb-section">Management</div>
            <a href="{{ route('admin.municipalities') }}" class="nav-link {{ request()->routeIs('admin.municipalities*') ? 'active':'' }}"><i class="bi bi-geo-alt ni"></i> Municipalities</a>
            <a href="{{ route('admin.offices') }}" class="nav-link {{ request()->routeIs('admin.offices*') ? 'active':'' }}"><i class="bi bi-building ni"></i> Offices</a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active':'' }}"><i class="bi bi-people ni"></i> Users</a>
            <div class="sb-section">Analytics</div>
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active':'' }}"><i class="bi bi-bar-chart-line ni"></i> Reports</a>
        @elseif(auth()->user()->isOfficeUser())
            <div class="sb-section">Overview</div>
            <a href="{{ route('office.dashboard') }}" class="nav-link {{ request()->routeIs('office.dashboard') ? 'active':'' }}"><i class="bi bi-speedometer2 ni"></i> Dashboard</a>
            <div class="sb-section">Operations</div>
            <a href="{{ route('office.services') }}" class="nav-link {{ request()->routeIs('office.services*') ? 'active':'' }}"><i class="bi bi-grid-3x3-gap ni"></i> Services</a>
            <a href="{{ route('office.requests') }}" class="nav-link {{ request()->routeIs('office.requests*') ? 'active':'' }}">
                <i class="bi bi-inbox-fill ni"></i> Requests
                @php $pn = auth()->user()->offices()->first()?->requests()->where('status','pending')->count() ?? 0; @endphp
                @if($pn > 0)<span class="nb">{{ $pn }}</span>@endif
            </a>
            <div class="sb-section">Engagement</div>
            <a href="{{ route('office.appointments') }}" class="nav-link {{ request()->routeIs('office.appointments*') ? 'active':'' }}"><i class="bi bi-calendar-check ni"></i> Appointments</a>
            <a href="{{ route('office.feedback') }}" class="nav-link {{ request()->routeIs('office.feedback*') ? 'active':'' }}"><i class="bi bi-star ni"></i> Feedback</a>
            <div class="sb-section">Settings</div>
            <a href="{{ route('office.profile') }}" class="nav-link {{ request()->routeIs('office.profile*') ? 'active':'' }}"><i class="bi bi-gear ni"></i> Office Profile</a>
        @else
            <div class="sb-section">Overview</div>
            <a href="{{ route('citizen.dashboard') }}" class="nav-link {{ request()->routeIs('citizen.dashboard') ? 'active':'' }}"><i class="bi bi-house-fill ni"></i> Dashboard</a>
            <div class="sb-section">Services</div>
            <a href="{{ route('citizen.offices') }}" class="nav-link {{ request()->routeIs('citizen.offices*') ? 'active':'' }}"><i class="bi bi-building ni"></i> Browse Offices</a>
            <a href="{{ route('citizen.requests') }}" class="nav-link {{ request()->routeIs('citizen.requests*') ? 'active':'' }}">
                <i class="bi bi-file-text ni"></i> My Requests
                @php $an = auth()->user()->serviceRequests()->whereNotIn('status',['completed','rejected'])->count(); @endphp
                @if($an > 0)<span class="nb" style="background:var(--primary)">{{ $an }}</span>@endif
            </a>
            <div class="sb-section">Account</div>
            <a href="{{ route('citizen.profile') }}" class="nav-link {{ request()->routeIs('citizen.profile*') ? 'active':'' }}"><i class="bi bi-person ni"></i> My Profile</a>
        @endif
        </nav>
    </div>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div style="flex:1;min-width:0">
                <div class="sb-un">{{ auth()->user()->name }}</div>
                <div class="sb-ur">{{ ucfirst(str_replace('_',' ',auth()->user()->role)) }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="sb-logout" title="Sign out"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</aside>

<header class="topbar">
    <button class="menu-btn" id="menuBtn" aria-label="Open menu"><i class="bi bi-list"></i></button>
    <span class="top-title">@yield('page-title','Dashboard')</span>
    <div class="top-right">
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        <div class="dropdown">
            <button id="notificationBell" class="top-btn" data-bs-toggle="dropdown" aria-label="Notifications">
                <i class="bi bi-bell"></i>
                @if($unread > 0)<span class="top-dot">{{ min($unread,9) }}</span>@endif
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="width:300px">
                <div class="dropdown-header" style="display:flex;align-items:center;justify-content:space-between">
                    Notifications
                    @if($unread)<span style="background:var(--rose-lt);color:var(--rose);font-size:.6rem;font-weight:700;padding:.1rem .4rem;border-radius:99px">{{ $unread }} new</span>@endif
                </div>
                @forelse(auth()->user()->unreadNotifications->take(6) as $n)
                <a
                    class="dropdown-item"
                    href="{{ isset($n->data['request_id'])
                        ? route(auth()->user()->isOfficeUser() ? 'office.requests.show' : 'citizen.requests.show', $n->data['request_id'])
                        : '#' }}"
                    style="white-space:normal"
                >
                    <div style="display:flex;gap:.45rem">
                        <span style="width:7px;height:7px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:5px"></span>
                        <div>
                            <div style="font-size:.78rem;line-height:1.45;color:var(--ink-600)">{{ $n->data['message'] ?? 'New notification' }}</div>
                            <div style="font-size:.65rem;color:var(--ink-400);margin-top:1px">{{ $n->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </a>
                @empty
                <div style="text-align:center;padding:1.75rem .5rem">
                    <i class="bi bi-bell-slash" style="font-size:1.6rem;color:var(--ink-300);display:block;margin-bottom:.4rem"></i>
                    <div style="font-size:.78rem;color:var(--ink-400)">All caught up!</div>
                </div>
                @endforelse
            </div>
        </div>
        <div class="dropdown">
            <div class="top-av" data-bs-toggle="dropdown" role="button" tabindex="0">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div class="dropdown-menu dropdown-menu-end" style="min-width:200px">
                <div style="padding:.6rem .9rem .35rem">
                    <div style="font-size:.85rem;font-weight:700;color:var(--ink-900)">{{ auth()->user()->name }}</div>
                    <div style="font-size:.7rem;color:var(--ink-400)">{{ auth()->user()->email }}</div>
                    <span class="sbadge s-active" style="margin-top:.35rem;font-size:.62rem">{{ ucfirst(str_replace('_',' ',auth()->user()->role)) }}</span>
                </div>
                <div class="dropdown-divider"></div>
                @if(auth()->user()->isCitizen())
                <a class="dropdown-item" href="{{ route('citizen.profile') }}"><i class="bi bi-person me-2"></i>My Profile</a>
                @elseif(auth()->user()->isOfficeUser())
                <a class="dropdown-item" href="{{ route('office.profile') }}"><i class="bi bi-gear me-2"></i>Office Settings</a>
                @endif
                <a class="dropdown-item" href="{{ route('security.2fa') }}"><i class="bi bi-shield-lock me-2"></i>2FA Security</a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button class="dropdown-item text-danger" style="width:100%;text-align:left;background:none;border:none;cursor:pointer">
                        <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<nav class="bnav">
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active':'' }}"><i class="bi bi-speedometer2"></i>Home</a>
        <a href="{{ route('admin.offices') }}" class="{{ request()->routeIs('admin.offices*') ? 'active':'' }}"><i class="bi bi-building"></i>Offices</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active':'' }}"><i class="bi bi-people"></i>Users</a>
        <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports*') ? 'active':'' }}"><i class="bi bi-bar-chart-line"></i>Reports</a>
    @elseif(auth()->user()->isOfficeUser())
        <a href="{{ route('office.dashboard') }}" class="{{ request()->routeIs('office.dashboard') ? 'active':'' }}"><i class="bi bi-speedometer2"></i>Home</a>
        <a href="{{ route('office.requests') }}" class="{{ request()->routeIs('office.requests*') ? 'active':'' }}">
            <i class="bi bi-inbox-fill"></i>Requests
            @if(($pn ?? 0) > 0)
                <span class="bn-dot"></span>
            @endif
        </a>
        <a href="{{ route('office.appointments') }}" class="{{ request()->routeIs('office.appointments*') ? 'active':'' }}"><i class="bi bi-calendar-check"></i>Calendar</a>
        <a href="{{ route('office.feedback') }}" class="{{ request()->routeIs('office.feedback*') ? 'active':'' }}"><i class="bi bi-star"></i>Reviews</a>
    @else
        <a href="{{ route('citizen.dashboard') }}" class="{{ request()->routeIs('citizen.dashboard') ? 'active':'' }}"><i class="bi bi-house-fill"></i>Home</a>
        <a href="{{ route('citizen.offices') }}" class="{{ request()->routeIs('citizen.offices*') ? 'active':'' }}"><i class="bi bi-building"></i>Services</a>
        <a href="{{ route('citizen.requests') }}" class="{{ request()->routeIs('citizen.requests*') ? 'active':'' }}"><i class="bi bi-file-text"></i>Requests</a>
        <a href="{{ route('citizen.profile') }}" class="{{ request()->routeIs('citizen.profile*') ? 'active':'' }}"><i class="bi bi-person"></i>Profile</a>
    @endif
</nav>
@endauth

<div class="{{ auth()->check() ? 'main-wrap' : '' }}">
    <div class="{{ auth()->check() ? 'main-pad' : '' }}">

        @if(session('success') || session('error') || session('info'))
        <div id="__flash"
            data-success="{{ session('success') }}"
            data-error="{{ session('error') }}"
            data-info="{{ session('info') }}"
            style="display:none"></div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0"></i>
            <div style="flex:1">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="flex-shrink:0"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<div class="toast-stack" id="toastStack"></div>

{{-- Session expiry warning --}}
@auth
<div id="session-warn" style="display:none;position:fixed;top:56px;left:var(--sb-w);right:0;background:var(--amber-lt);border-bottom:2px solid var(--amber);padding:.5rem 1.25rem;z-index:999;align-items:center;gap:.6rem;font-size:.8rem;color:#92400E">
    <i class="bi bi-clock-history" style="flex-shrink:0"></i>
    <span>Your session expires in <strong id="session-countdown">5:00</strong>. <a href="javascript:location.reload()" style="color:inherit;font-weight:700">Refresh now</a></span>
    <button onclick="document.getElementById('session-warn').style.display='none'" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem">&times;</button>
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    'use strict';

    /* Sidebar */
    const sb=document.getElementById('sidebar'),ov=document.getElementById('sbOverlay'),btn=document.getElementById('menuBtn');
    const openSb=()=>{sb?.classList.add('open');ov?.classList.add('show');document.body.style.overflow='hidden'};
    const closeSb=()=>{sb?.classList.remove('open');ov?.classList.remove('show');document.body.style.overflow=''};
    btn?.addEventListener('click',()=>sb?.classList.contains('open')?closeSb():openSb());
    ov?.addEventListener('click',closeSb);
    sb?.querySelectorAll('.nav-link').forEach(l=>l.addEventListener('click',()=>{if(window.innerWidth<992)closeSb()}));

    /* Swipe */
    let tx=0,ty=0,drag=false;
    document.addEventListener('touchstart',e=>{tx=e.touches[0].clientX;ty=e.touches[0].clientY;drag=false},{passive:true});
    document.addEventListener('touchmove',e=>{if(Math.abs(e.touches[0].clientX-tx)>Math.abs(e.touches[0].clientY-ty))drag=true},{passive:true});
    document.addEventListener('touchend',e=>{
        if(!drag)return;
        const dx=e.changedTouches[0].clientX-tx;
        if(tx<28&&dx>65)openSb();
        if(dx<-55&&sb?.classList.contains('open'))closeSb();
    },{passive:true});

    /* Toasts */
    const ts=document.getElementById('toastStack');
    const icons={success:'bi-check-circle-fill',error:'bi-x-circle-fill',info:'bi-info-circle-fill',warning:'bi-exclamation-triangle-fill'};
    window.showToast=function(msg,type,dur){
        if(!msg||!ts)return;
        type=type||'info';dur=dur||4500;
        const t=document.createElement('div');
        t.className=`toast-item ${type}`;
        t.innerHTML=`<i class="bi ${icons[type]||icons.info}" style="font-size:.9rem;flex-shrink:0"></i><span style="flex:1">${msg}</span><button onclick="window.closeToast(this.parentElement)" style="background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;font-size:1.1rem;padding:0;line-height:1">&times;</button>`;
        ts.appendChild(t);
        setTimeout(()=>window.closeToast(t),dur);
    };
    window.closeToast=function(el){
        if(!el||!el.parentElement)return;
        el.classList.add('out');
        el.addEventListener('animationend',()=>el.remove(),{once:true});
    };

    /* Flash messages */
    const fd=document.getElementById('__flash');
    if(fd){
        if(fd.dataset.success)setTimeout(()=>showToast(fd.dataset.success,'success'),200);
        if(fd.dataset.error)  setTimeout(()=>showToast(fd.dataset.error,  'error'),  200);
        if(fd.dataset.info)   setTimeout(()=>showToast(fd.dataset.info,   'info'),   200);
    }

    @auth
        function addRealtimeNotification(message, url = '#') {
            const bellBtn = document.getElementById('notificationBell');
            const dropdownMenu = bellBtn?.nextElementSibling;
            const unreadBadge = bellBtn?.querySelector('.top-dot');
            const headerCount = dropdownMenu?.querySelector('.dropdown-header span');

            if (!unreadBadge && bellBtn) {
                const badge = document.createElement('span');
                badge.className = 'top-dot';
                badge.textContent = '1';
                bellBtn.appendChild(badge);
            } else if (unreadBadge) {
                const current = parseInt(unreadBadge.textContent || '0', 10);
                unreadBadge.textContent = String(Math.min(current + 1, 9));
            }

            if (headerCount) {
                const currentHeader = parseInt(headerCount.textContent || '0', 10) || 0;
                headerCount.textContent = `${currentHeader + 1} new`;
            }

            const emptyState = dropdownMenu?.querySelector('.bi-bell-slash')?.closest('div');
            if (emptyState) {
                emptyState.remove();
            }

            const newItem = document.createElement('a');
            newItem.className = 'dropdown-item';
            newItem.href = url;
            newItem.style.whiteSpace = 'normal';
            newItem.innerHTML = `
                <div style="display:flex;gap:.45rem">
                    <span style="width:7px;height:7px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:5px"></span>
                    <div>
                        <div style="font-size:.78rem;line-height:1.45;color:var(--ink-600)">${message}</div>
                        <div style="font-size:.65rem;color:var(--ink-400);margin-top:1px">Just now</div>
                    </div>
                </div>
            `;

            const header = dropdownMenu?.querySelector('.dropdown-header');
            if (header && header.nextSibling) {
                dropdownMenu.insertBefore(newItem, header.nextSibling);
            } else if (dropdownMenu) {
                dropdownMenu.appendChild(newItem);
            }

            showToast(message, 'info');
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Echo) {
                console.error('Echo is not loaded');
                return;
            }

            window.Echo.private('user.{{ auth()->id() }}')
                .listen('.request.status.updated', (e) => {
                    const statusText = String(e.new_status || '').replaceAll('_', ' ');
                    const message = `Your request #${e.reference_number} status changed to ${statusText}.`;
                    const url = `{{ auth()->user()->isOfficeUser() ? url('/office/requests') : url('/citizen/requests') }}/${e.request_id}`;
                    addRealtimeNotification(message, url);
                })
                .listen('.appointment.reminder', (e) => {
                    const url = e.service_request_id
                        ? `{{ auth()->user()->isOfficeUser() ? url('/office/requests') : url('/citizen/requests') }}/${e.service_request_id}`
                        : `{{ auth()->user()->isOfficeUser() ? url('/office/dashboard') : url('/citizen/offices') }}/${e.office_id}`;

                    addRealtimeNotification(e.message || 'Appointment reminder', url);
                })
                .listen('.message.sent', (e) => {
                    if (e.sender_id === {{ auth()->id() }}) {
                        return;
                    }

                    const url = `{{ auth()->user()->isOfficeUser() ? url('/office/requests') : url('/citizen/requests') }}/${e.service_request_id}`;
                    const senderName = e.sender?.name || 'Someone';
                    addRealtimeNotification(`New message from ${senderName}.`, url);
                });

            @if(auth()->user()->isOfficeUser() && auth()->user()->offices()->first())
            window.Echo.private('office.{{ auth()->user()->offices()->first()->id }}')
                .listen('.message.sent', (e) => {
                    if (e.sender_id === {{ auth()->id() }}) {
                        return;
                    }

                    const url = `{{ url('/office/requests') }}/${e.service_request_id}`;
                    const senderName = e.sender?.name || 'Citizen';
                    addRealtimeNotification(`New message from ${senderName}.`, url);
                });
            @endif

            const bellBtn = document.getElementById('notificationBell');

            bellBtn?.addEventListener('shown.bs.dropdown', async () => {
                try {
                    await fetch('{{ route('notifications.readAll') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        }
                    });

                    bellBtn.querySelector('.top-dot')?.remove();

                    const dropdownMenu = bellBtn.nextElementSibling;
                    const headerCount = dropdownMenu?.querySelector('.dropdown-header span');
                    if (headerCount) {
                        headerCount.remove();
                    }
                } catch (error) {
                    console.error('Failed to mark notifications as read');
                }
            });
        });
    @endauth

    /* Session expiry countdown + history lock for authenticated pages */
    @auth
    const ROLE_HOME_URL='{{ match(auth()->user()->role){ "admin" => route("admin.dashboard"), "office_user" => route("office.dashboard"), default => route("citizen.dashboard") } }}';

    // Prevent browser back/forward from traversing authenticated history.
    if(window.history && window.history.pushState){
        window.history.pushState({locked:true},'',window.location.href);
        window.addEventListener('popstate',()=>{
            window.history.pushState({locked:true},'',window.location.href);
            if(window.location.href!==ROLE_HOME_URL){
                window.location.replace(ROLE_HOME_URL);
            }
        });
    }

    // Intercept Alt+Left / Alt+Right and keep user in the app dashboard.
    document.addEventListener('keydown',(e)=>{
        if(e.altKey && (e.key==='ArrowLeft' || e.key==='ArrowRight')){
            e.preventDefault();
            window.location.replace(ROLE_HOME_URL);
        }
    });

    // If browser restores from BFCache, force fresh state from server.
    window.addEventListener('pageshow',(e)=>{
        if(e.persisted){
            window.location.replace(ROLE_HOME_URL);
        }
    });

    const SESSION_SECS={{ config('session.lifetime',120) }}*60;
    const startedAt=Date.now();
    let warned=false;
    function checkSession(){
        const elapsed=Math.floor((Date.now()-startedAt)/1000);
        const remaining=SESSION_SECS-elapsed;
        if(remaining<=0){window.location.href='{{ route("login") }}';return}
        if(remaining<=300){
            const warn=document.getElementById('session-warn');
            if(!warned&&warn){warned=true;warn.style.display='flex'}
            const cd=document.getElementById('session-countdown');
            if(cd){const m=Math.floor(remaining/60),s=String(remaining%60).padStart(2,'0');cd.textContent=`${m}:${s}`}
        }
    }
    setInterval(checkSession,1000);
    @endauth
})();
</script>
@stack('scripts')
</body>
</html>
