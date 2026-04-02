<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CedarGov Platform for Lebanese municipalities.">
    <title>CedarGov — Lebanon</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/brand/cedar-logo-icon-trim.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/brand/cedar-logo-icon-trim.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital,wght@0,400;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        /* ── Page-level overrides for the landing page ── */
        :root {
            --cream: #F5F0E8;
            --ink:   #1A1714;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--cream);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAV ──────────────────────────────── */
        .lp-nav {
            position: sticky;
            top: 0;
            z-index: 200;
            background: rgba(245,240,232,0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 0 2rem;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .lp-brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
            color: var(--ink);
        }

        .lp-brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            overflow: hidden;
            background: #1D1916;
            box-shadow: 0 6px 14px rgba(26, 23, 20, 0.22);
            flex-shrink: 0;
        }

        .lp-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .lp-brand-text {
            font-family: 'Inter', sans-serif;
            line-height: 1.16;
        }

        .lp-brand-text strong {
            display: block;
            font-size: 0.875rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--ink);
        }

        .lp-brand-text span {
            display: block;
            font-size: 0.625rem;
            color: #78716C;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .lp-nav-actions { display: flex; align-items: center; gap: 0.5rem; }

        .lp-btn-ghost {
            background: none;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            color: #57534E;
            padding: 0.4rem 0.875rem;
            border-radius: 6px;
            text-decoration: none;
            transition: color 0.15s, background 0.15s;
            font-family: 'Inter', sans-serif;
        }
        .lp-btn-ghost:hover { color: var(--ink); background: rgba(0,0,0,0.05); }

        .lp-btn-black {
            background: var(--ink);
            color: #fff;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.45rem 1.125rem;
            border-radius: 7px;
            text-decoration: none;
            transition: background 0.15s;
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.01em;
        }
        .lp-btn-black:hover { background: #2D2926; color: #fff; }

        /* ── HERO ─────────────────────────────── */
        .lp-hero {
            min-height: calc(100vh - 58px);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 5rem 0 4rem;
        }

        /* Warm gradient glow — top-right, behind card */
        .lp-hero::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -5%;
            width: 65%;
            height: 90%;
            background: radial-gradient(ellipse at 60% 30%,
                rgba(253, 224, 71, 0.28) 0%,
                rgba(251, 191, 36, 0.14) 35%,
                rgba(245, 240, 232, 0)   65%);
            pointer-events: none;
            z-index: 0;
        }

        /* Subtle secondary warm glow bottom-left */
        .lp-hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -8%;
            width: 40%;
            height: 60%;
            background: radial-gradient(ellipse at 40% 60%,
                rgba(253, 224, 71, 0.10) 0%,
                transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .lp-hero-inner {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 430px;
            align-items: center;
            gap: 4rem;
        }

        /* Eyebrow label */
        .lp-eyebrow {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #78716C;
            margin-bottom: 1.5rem;
        }

        /* Big serif headline */
        .lp-headline {
            font-family: 'DM Serif Display', Georgia, serif;
            font-style: italic;
            font-weight: 400;
            font-size: clamp(3rem, 5.5vw, 4.75rem);
            line-height: 1.04;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 1.625rem;
        }

        .lp-sub {
            font-size: 1rem;
            line-height: 1.72;
            color: #78716C;
            max-width: 480px;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }

        .lp-cta {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            flex-wrap: wrap;
        }

        .lp-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--ink);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            padding: 0.75rem 1.625rem;
            border-radius: 8px;
            text-decoration: none;
            letter-spacing: -0.01em;
            transition: background 0.15s;
        }
        .lp-cta-primary:hover { background: #2D2926; color: #fff; }
        .lp-cta-primary .arrow {
            font-size: 1rem;
            transition: transform 0.2s;
        }
        .lp-cta-primary:hover .arrow { transform: translateX(3px); }

        .lp-cta-secondary {
            font-size: 0.9375rem;
            font-weight: 500;
            color: #57534E;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: color 0.15s, background 0.15s;
            font-family: 'Inter', sans-serif;
        }
        .lp-cta-secondary:hover { color: var(--ink); background: rgba(0,0,0,0.04); }

        /* ── PLATFORM CARD ─────────────────────── */
        .lp-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.09);
            border-radius: 16px;
            overflow: hidden;
            box-shadow:
                0 2px 4px rgba(0,0,0,0.04),
                0 8px 24px rgba(0,0,0,0.07),
                0 24px 64px rgba(0,0,0,0.06);
            position: relative;
        }

        .lp-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid #F0EDE8;
        }

        .lp-card-title {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #A8A29E;
        }

        .lp-live {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: #0D9488;
        }
        .lp-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #0D9488;
            animation: livePulse 2s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.45; transform:scale(0.8); }
        }

        /* 2×2 stats grid */
        .lp-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .lp-stat {
            padding: 1.375rem 1.5rem;
            border-right: 1px solid #F0EDE8;
            border-bottom: 1px solid #F0EDE8;
        }
        .lp-stat:nth-child(even)  { border-right: none; }
        .lp-stat:nth-last-child(-n+2) { border-bottom: none; }

        .lp-stat-label {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #A8A29E;
            margin-bottom: 0.375rem;
        }

        .lp-stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: var(--ink);
            letter-spacing: -0.03em;
        }
        .lp-stat-value.accent { color: #0D9488; }

        /* Recent activity */
        .lp-activity-hd {
            padding: 0.875rem 1.5rem 0.5rem;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #A8A29E;
            border-top: 1px solid #F0EDE8;
        }

        .lp-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.625rem 1.5rem;
            border-top: 1px solid #F5F2EF;
        }

        .lp-row-ref {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.01em;
        }
        .lp-row-type {
            font-size: 0.72rem;
            color: #A8A29E;
            margin-top: 0.1rem;
        }

        /* Activity status chips */
        .chip {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.275em 0.7em;
            border-radius: 50px;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .chip-approved  { background:#CCFBF1; color:#0F766E; border-color:#99F6E4; }
        .chip-inreview  { background:#E0F2FE; color:#0369A1; border-color:#BAE6FD; }
        .chip-pending   { background:#FEF3C7; color:#92400E; border-color:#FDE68A; }

        /* ── FEATURES ──────────────────────────── */
        .lp-section {
            padding: 5rem 0;
        }

        .lp-section-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .lp-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: #CCFBF1;
            color: #0F766E;
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }

        .lp-section-title {
            font-family: 'DM Serif Display', Georgia, serif;
            font-style: italic;
            font-weight: 400;
            font-size: clamp(1.75rem, 3vw, 2.25rem);
            color: var(--ink);
            margin-bottom: 0.625rem;
            letter-spacing: -0.01em;
            line-height: 1.15;
        }

        .lp-section-desc {
            font-size: 0.9375rem;
            color: #78716C;
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 2.75rem;
        }

        .lp-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .lp-feature {
            background: #fff;
            border: 1px solid #EAE6DF;
            border-radius: 14px;
            padding: 1.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .lp-feature:hover {
            border-color: #99F6E4;
            box-shadow: 0 8px 28px rgba(13,148,136,0.07);
        }

        .lp-fi {
            width: 46px;
            height: 46px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }
        .fi-t { background:#CCFBF1; color:#0F766E; }
        .fi-s { background:#E0F2FE; color:#0369A1; }
        .fi-a { background:#FEF3C7; color:#B45309; }
        .fi-v { background:#EDE9FE; color:#6D28D9; }
        .fi-e { background:#D1FAE5; color:#047857; }
        .fi-r { background:#FFE4E6; color:#BE123C; }

        .lp-feature h3 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.4rem;
            letter-spacing: -0.01em;
        }
        .lp-feature p {
            font-size: 0.84rem;
            color: #78716C;
            line-height: 1.65;
            margin: 0;
        }

        /* ── STEPS ─────────────────────────────── */
        .lp-steps-section {
            background: #fff;
            border-top: 1px solid #EAE6DF;
            border-bottom: 1px solid #EAE6DF;
        }

        .lp-steps-layout {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 4rem;
            align-items: start;
        }

        .lp-step {
            display: flex;
            gap: 1.25rem;
            padding: 1.375rem 0;
            align-items: flex-start;
        }
        .lp-step + .lp-step { border-top: 1px solid #F5F2EF; }

        .lp-step-n {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--ink);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .lp-step h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.3rem;
            letter-spacing: -0.01em;
        }
        .lp-step p {
            font-size: 0.84rem;
            color: #78716C;
            line-height: 1.65;
            margin: 0;
        }

        /* ── ROLES ─────────────────────────────── */
        .lp-grid-roles {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .lp-role {
            background: #fff;
            border: 1px solid #EAE6DF;
            border-radius: 14px;
            padding: 1.5rem;
            transition: border-color 0.2s;
        }
        .lp-role:hover { border-color: #99F6E4; }

        .lp-role-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 0.875rem;
        }

        .lp-role h3 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.75rem;
            letter-spacing: -0.01em;
        }

        .lp-role ul { list-style: none; padding: 0; }
        .lp-role li {
            font-size: 0.84rem;
            color: #57534E;
            padding: 0.3rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .lp-role li i { color: #0D9488; font-size: 0.72rem; flex-shrink: 0; }

        /* ── CTA BANNER ────────────────────────── */
        .lp-banner {
            background: var(--ink);
            border-radius: 16px;
            padding: 3.5rem 3rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .lp-banner::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .lp-banner::after {
            content: '';
            position: absolute;
            bottom: -120px; left: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .lp-banner h2 {
            font-family: 'DM Serif Display', Georgia, serif;
            font-style: italic;
            font-weight: 400;
            font-size: clamp(1.75rem, 3vw, 2.25rem);
            margin-bottom: 0.75rem;
            position: relative;
            letter-spacing: -0.01em;
        }
        .lp-banner p {
            color: rgba(255,255,255,0.65);
            font-size: 0.9375rem;
            line-height: 1.7;
            max-width: 440px;
            margin-bottom: 2rem;
            position: relative;
        }
        .lp-banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            color: var(--ink);
            font-size: 0.9375rem;
            font-weight: 700;
            padding: 0.75rem 1.75rem;
            border-radius: 8px;
            text-decoration: none;
            position: relative;
            transition: background 0.15s;
            letter-spacing: -0.01em;
        }
        .lp-banner-btn:hover { background: #F5F0E8; color: var(--ink); }

        /* ── FOOTER ────────────────────────────── */
        .lp-footer {
            border-top: 1px solid #EAE6DF;
            background: #fff;
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: #78716C;
        }

        /* Premium Motion And Polish Layer */
        :root {
            --lp-ease: cubic-bezier(0.22, 1, 0.36, 1);
            --lp-shadow-soft: 0 8px 24px rgba(26, 23, 20, 0.08);
            --lp-shadow-deep: 0 20px 52px rgba(26, 23, 20, 0.13);
        }

        html { scroll-behavior: smooth; }

        body {
            background:
                radial-gradient(circle at 8% -8%, rgba(246, 196, 83, 0.2), transparent 34%),
                radial-gradient(circle at 90% 12%, rgba(13, 148, 136, 0.09), transparent 38%),
                var(--cream);
            text-rendering: optimizeLegibility;
        }

        [data-reveal] {
            opacity: 1;
            filter: none;
            transform: none;
        }

        .js-motion [data-reveal] {
            opacity: 0;
            filter: blur(2px);
            transform: translate3d(0, 24px, 0);
            transition:
                opacity 0.75s var(--lp-ease),
                transform 0.75s var(--lp-ease),
                filter 0.75s var(--lp-ease);
            transition-delay: var(--reveal-delay, 0ms);
            will-change: transform, opacity;
        }

        .js-motion [data-reveal="left"] { transform: translate3d(-24px, 0, 0); }
        .js-motion [data-reveal="right"] { transform: translate3d(24px, 0, 0); }
        .js-motion [data-reveal="scale"] { transform: translate3d(0, 20px, 0) scale(0.97); }

        .js-motion [data-reveal].is-visible {
            opacity: 1;
            filter: blur(0);
            transform: translate3d(0, 0, 0) scale(1);
        }

        .lp-nav {
            transition:
                background 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                height 0.25s ease;
            border-bottom-color: transparent;
        }

        .lp-nav.scrolled {
            height: 56px;
            background: rgba(250, 245, 237, 0.9);
            border-bottom-color: rgba(26, 23, 20, 0.1);
            box-shadow: 0 10px 26px rgba(26, 23, 20, 0.08);
        }

        .lp-brand-mark {
            box-shadow: 0 7px 16px rgba(26, 23, 20, 0.24);
        }

        .lp-btn-ghost,
        .lp-btn-black,
        .lp-cta-primary,
        .lp-cta-secondary,
        .lp-banner-btn {
            transition:
                transform 0.2s var(--lp-ease),
                box-shadow 0.2s var(--lp-ease),
                background-color 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .lp-btn-ghost {
            border: 1px solid transparent;
            font-weight: 600;
        }
        .lp-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.66);
            border-color: rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .lp-btn-black {
            border: 1px solid var(--ink);
            box-shadow: 0 2px 6px rgba(26, 23, 20, 0.16);
        }
        .lp-btn-black:hover {
            transform: translateY(-1px);
            box-shadow: var(--lp-shadow-soft);
        }

        .lp-btn-ghost:focus-visible,
        .lp-btn-black:focus-visible,
        .lp-cta-primary:focus-visible,
        .lp-cta-secondary:focus-visible,
        .lp-banner-btn:focus-visible {
            outline: 2px solid rgba(13, 148, 136, 0.55);
            outline-offset: 2px;
        }

        .lp-hero::before,
        .lp-hero::after { animation: lpGlowDrift 12s ease-in-out infinite; }
        .lp-hero::after { animation-direction: reverse; }

        @keyframes lpGlowDrift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(-2%, 2%, 0) scale(1.03); }
        }

        .lp-headline em {
            color: #134E4A;
            text-shadow: 0 10px 24px rgba(19, 78, 74, 0.15);
        }

        .lp-cta-primary {
            background: linear-gradient(140deg, #191613 0%, #312A25 100%);
            border: 1px solid #191613;
            box-shadow: 0 10px 22px rgba(26, 23, 20, 0.2);
        }
        .lp-cta-primary:hover {
            background: linear-gradient(140deg, #24201C 0%, #3A322C 100%);
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(26, 23, 20, 0.24);
        }
        .lp-cta-primary .arrow { transition: transform 0.2s var(--lp-ease); }
        .lp-cta-primary:hover .arrow { transform: translateX(4px); }

        .lp-cta-secondary {
            border: 1px solid transparent;
            font-weight: 600;
        }
        .lp-cta-secondary:hover {
            background: rgba(255, 255, 255, 0.66);
            border-color: rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .lp-card-wrap {
            position: relative;
            animation: lpCardFloat 7.5s ease-in-out infinite;
        }
        .lp-card-wrap::before {
            content: '';
            position: absolute;
            inset: -14px;
            background: radial-gradient(circle at 50% 0%, rgba(13, 148, 136, 0.24), rgba(13, 148, 136, 0.02) 45%, transparent 70%);
            filter: blur(18px);
            z-index: -1;
            pointer-events: none;
        }
        @keyframes lpCardFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .lp-card {
            background: linear-gradient(165deg, #FFFFFF 0%, #FFFCF8 100%);
            box-shadow:
                0 2px 5px rgba(0, 0, 0, 0.05),
                0 18px 44px rgba(0, 0, 0, 0.09),
                0 36px 90px rgba(0, 0, 0, 0.09);
            transition: transform 0.25s var(--lp-ease), box-shadow 0.25s var(--lp-ease), border-color 0.25s ease;
        }
        .lp-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.7) 0%, rgba(246, 196, 83, 0.75) 70%, rgba(246, 196, 83, 0.25) 100%);
        }
        .lp-card:hover {
            transform: translateY(-4px);
            border-color: rgba(13, 148, 136, 0.32);
            box-shadow:
                0 3px 8px rgba(0, 0, 0, 0.06),
                0 24px 54px rgba(0, 0, 0, 0.11),
                0 42px 100px rgba(0, 0, 0, 0.12);
        }

        .lp-stat,
        .lp-row { transition: background-color 0.2s ease; }
        .lp-stat:hover { background: rgba(252, 248, 242, 0.85); }
        .lp-row:hover { background: rgba(249, 246, 241, 0.8); }

        .chip { box-shadow: 0 1px 2px rgba(26, 23, 20, 0.08); }

        .lp-section {
            position: relative;
            padding: clamp(4rem, 6vw, 5.75rem) 0;
        }

        .lp-tag {
            border: 1px solid #9DE7DC;
        }

        .lp-section-title {
            font-size: clamp(1.85rem, 3.2vw, 2.5rem);
            line-height: 1.12;
            text-wrap: balance;
        }

        .lp-section-desc {
            max-width: 520px;
            line-height: 1.72;
        }

        .lp-feature,
        .lp-role {
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(26, 23, 20, 0.05);
            transition:
                transform 0.24s var(--lp-ease),
                border-color 0.24s ease,
                box-shadow 0.24s var(--lp-ease);
        }
        .lp-feature::before,
        .lp-role::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(13, 148, 136, 0.08) 0%, rgba(246, 196, 83, 0.08) 40%, rgba(255, 255, 255, 0) 65%);
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }
        .lp-feature:hover,
        .lp-role:hover {
            transform: translateY(-5px);
            border-color: #BDEBE3;
            box-shadow: var(--lp-shadow-deep);
        }
        .lp-feature:hover::before,
        .lp-role:hover::before { opacity: 1; }

        .lp-step { transition: transform 0.22s var(--lp-ease); }
        .lp-step:hover { transform: translateX(4px); }
        .lp-step-n {
            background: linear-gradient(150deg, #1A1714 0%, #332C27 100%);
            box-shadow: 0 10px 22px rgba(26, 23, 20, 0.22);
            transition: transform 0.22s var(--lp-ease), box-shadow 0.22s var(--lp-ease);
        }
        .lp-step:hover .lp-step-n {
            transform: translateY(-1px) scale(1.03);
            box-shadow: 0 14px 24px rgba(26, 23, 20, 0.24);
        }

        .lp-banner {
            background: linear-gradient(135deg, #1A1714 0%, #29231E 44%, #302923 100%);
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: var(--lp-shadow-deep);
        }
        .lp-banner::before {
            background: radial-gradient(circle at center, rgba(13, 148, 136, 0.24) 0%, rgba(13, 148, 136, 0.02) 65%);
        }
        .lp-banner::after {
            background: radial-gradient(circle at center, rgba(246, 196, 83, 0.26) 0%, rgba(246, 196, 83, 0.02) 68%);
        }
        .lp-banner p { color: rgba(255, 255, 255, 0.73); }

        .lp-banner-btn {
            border: 1px solid #fff;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
        }
        .lp-banner-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25);
        }

        .lp-footer {
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *,
            *::before,
            *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
            [data-reveal] {
                opacity: 1;
                filter: none;
                transform: none;
            }
        }

        /* ── RESPONSIVE ────────────────────────── */
        @media (max-width: 1023px) {
            .lp-hero-inner { grid-template-columns: 1fr; gap: 3rem; }
            .lp-card { max-width: 480px; }
            .lp-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .lp-steps-layout { grid-template-columns: 1fr; gap: 2.5rem; }
            .lp-grid-roles { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 639px) {
            .lp-nav { padding: 0 1rem; }
            .lp-brand-mark { width: 36px; height: 36px; border-radius: 10px; }
            .lp-brand-text strong { font-size: .81rem; }
            .lp-brand-text span { font-size: .58rem; }
            .lp-hero-inner { padding: 0 1rem; }
            .lp-section-inner { padding: 0 1rem; }
            .lp-headline { font-size: 2.5rem; }
            .lp-grid-3 { grid-template-columns: 1fr; }
            .lp-grid-roles { grid-template-columns: 1fr; }
            .lp-banner { padding: 2.25rem 1.5rem; }
            .lp-footer { flex-direction: column; gap: 0.5rem; text-align: center; }
            .lp-hero { padding: 3.5rem 0; min-height: auto; }
        }
    </style>
</head>
<body>

{{-- ── NAV ────────────────────────────────────────────── --}}
<nav class="lp-nav" id="lpNav" aria-label="Primary navigation">
    <a href="{{ route('home') }}" class="lp-brand" data-reveal="left">
        <span class="lp-brand-mark">
            <img src="{{ asset('assets/img/brand/cedar-logo-icon-trim.png') }}" alt="CedarGov icon">
        </span>
        <div class="lp-brand-text">
            <strong>CedarGov</strong>
            <span>Lebanon Gov Portal</span>
        </div>
    </a>
    <div class="lp-nav-actions" data-reveal="right">
        <a href="{{ route('login') }}" class="lp-btn-ghost">Sign in</a>
        <a href="{{ route('register') }}" class="lp-btn-black">Get started</a>
    </div>
</nav>

{{-- ── HERO ─────────────────────────────────────────────── --}}
<section class="lp-hero">
    <div class="lp-hero-inner">

        {{-- Left: headline --}}
        <div data-reveal="left">
            <p class="lp-eyebrow">Municipal E-Services &mdash; Lebanon</p>

            <h1 class="lp-headline">
                Government<br>
                services, made<br>
                <em>simple.</em>
            </h1>

            <p class="lp-sub">
                Submit requests, upload documents, pay fees, and track progress
                through a single platform built for Lebanese municipalities.
                No queues, no paperwork.
            </p>

            <div class="lp-cta">
                <a href="{{ route('register') }}" class="lp-cta-primary">
                    Create free account <span class="arrow">&rarr;</span>
                </a>
                <a href="{{ route('login') }}" class="lp-cta-secondary">Sign in</a>
            </div>
        </div>

        {{-- Right: floating platform card --}}
        <div class="lp-card-wrap" data-reveal="right" data-delay="120">
            <div class="lp-card">

                <div class="lp-card-header">
                    <span class="lp-card-title">Platform Overview</span>
                    <span class="lp-live">
                        <span class="lp-live-dot"></span> Live
                    </span>
                </div>

                <div class="lp-stats">
                    <div class="lp-stat">
                        <div class="lp-stat-label">Municipalities</div>
                        <div class="lp-stat-value accent" data-counter="3">3</div>
                    </div>
                    <div class="lp-stat">
                        <div class="lp-stat-label">Active Offices</div>
                        <div class="lp-stat-value" data-counter="3">3</div>
                    </div>
                    <div class="lp-stat">
                        <div class="lp-stat-label">Services</div>
                        <div class="lp-stat-value" data-counter="21">21</div>
                    </div>
                    <div class="lp-stat">
                        <div class="lp-stat-label">Requests</div>
                        <div class="lp-stat-value" data-counter="0">0</div>
                    </div>
                </div>

                <div class="lp-activity-hd">Recent Activity</div>

                <div class="lp-row">
                    <div>
                        <div class="lp-row-ref">SRQ-2024-00041</div>
                        <div class="lp-row-type">Birth Certificate</div>
                    </div>
                    <span class="chip chip-approved">Approved</span>
                </div>
                <div class="lp-row">
                    <div>
                        <div class="lp-row-ref">SRQ-2024-00038</div>
                        <div class="lp-row-type">Building Permit</div>
                    </div>
                    <span class="chip chip-inreview">In Review</span>
                </div>
                <div class="lp-row">
                    <div>
                        <div class="lp-row-ref">SRQ-2024-00031</div>
                        <div class="lp-row-type">Land Registration</div>
                    </div>
                    <span class="chip chip-pending">Pending</span>
                </div>

            </div>
        </div>

    </div>
</section>

{{-- ── FEATURES ──────────────────────────────────────────── --}}
<section class="lp-section lp-section-surface" style="background:#fff; border-top:1px solid #EAE6DF; border-bottom:1px solid #EAE6DF;">
    <div class="lp-section-inner">
        <span class="lp-tag" data-reveal="left"><i class="bi bi-grid-3x3-gap-fill"></i> Platform capabilities</span>
        <h2 class="lp-section-title" data-reveal="left" data-delay="40">Everything you need in one place</h2>
        <p class="lp-section-desc" data-reveal="left" data-delay="80">A complete digital workflow replacing in-person visits with secure, trackable online processes.</p>

        <div class="lp-grid-3">
            <div class="lp-feature" data-reveal="scale" data-delay="0">
                <div class="lp-fi fi-t"><i class="bi bi-credit-card-2-front"></i></div>
                <h3>Online payments</h3>
                <p>Pay service fees directly through the platform — credit card and cryptocurrency supported.</p>
            </div>
            <div class="lp-feature" data-reveal="scale" data-delay="70">
                <div class="lp-fi fi-s"><i class="bi bi-qr-code-scan"></i></div>
                <h3>QR code tracking</h3>
                <p>Every request gets a unique QR code for instant status lookup. No login required.</p>
            </div>
            <div class="lp-feature" data-reveal="scale" data-delay="140">
                <div class="lp-fi fi-a"><i class="bi bi-calendar-check"></i></div>
                <h3>Appointment booking</h3>
                <p>Book and manage appointments tied to your service requests with reminders.</p>
            </div>
            <div class="lp-feature" data-reveal="scale" data-delay="210">
                <div class="lp-fi fi-v"><i class="bi bi-bell"></i></div>
                <h3>Real-time notifications</h3>
                <p>Get alerted on status changes, document requests, and payment deadlines instantly.</p>
            </div>
            <div class="lp-feature" data-reveal="scale" data-delay="280">
                <div class="lp-fi fi-e"><i class="bi bi-file-earmark-arrow-up"></i></div>
                <h3>Document uploads</h3>
                <p>Attach supporting documents digitally. Offices can request additional files as needed.</p>
            </div>
            <div class="lp-feature" data-reveal="scale" data-delay="350">
                <div class="lp-fi fi-r"><i class="bi bi-currency-exchange"></i></div>
                <h3>Multi-currency fees</h3>
                <p>View service fees in USD, LBP, and EUR with live exchange rates automatically.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── HOW IT WORKS ─────────────────────────────────────── --}}
<section class="lp-section lp-steps-section">
    <div class="lp-section-inner">
        <div class="lp-steps-layout">
            <div data-reveal="left">
                <span class="lp-tag"><i class="bi bi-signpost-split"></i> How it works</span>
                <h2 class="lp-section-title">Simple, predictable process</h2>
                <p class="lp-section-desc mb-0" style="margin-bottom:0;">From registration to completion, every step is transparent and trackable.</p>
            </div>
            <div>
                <div class="lp-step" data-reveal="right" data-delay="0">
                    <span class="lp-step-n">1</span>
                    <div>
                        <h4>Register and verify your profile</h4>
                        <p>Create an account with email, Google, or GitHub. Enable two-factor authentication for added security.</p>
                    </div>
                </div>
                <div class="lp-step" data-reveal="right" data-delay="80">
                    <span class="lp-step-n">2</span>
                    <div>
                        <h4>Select a municipality and submit a request</h4>
                        <p>Browse offices, review available services, and submit with any required supporting documents.</p>
                    </div>
                </div>
                <div class="lp-step" data-reveal="right" data-delay="160">
                    <span class="lp-step-n">3</span>
                    <div>
                        <h4>Track progress and pay fees</h4>
                        <p>Monitor your request in real time. Pay online via card or cryptocurrency when ready.</p>
                    </div>
                </div>
                <div class="lp-step" data-reveal="right" data-delay="240">
                    <span class="lp-step-n">4</span>
                    <div>
                        <h4>Receive your result</h4>
                        <p>Get your outcome digitally, download receipts and certificates, or attend a scheduled appointment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BUILT FOR EVERY ROLE ────────────────────────────── --}}
<section class="lp-section lp-section-surface" style="background:#fff; border-top:1px solid #EAE6DF; border-bottom:1px solid #EAE6DF;">
    <div class="lp-section-inner">
        <div style="text-align:center; margin-bottom:2.75rem;" data-reveal="scale">
            <span class="lp-tag"><i class="bi bi-people"></i> Multi-role platform</span>
            <h2 class="lp-section-title">Designed for every user in the process</h2>
            <p class="lp-section-desc" style="margin:0 auto;">Each role gets a tailored dashboard with tools specific to their tasks.</p>
        </div>

        <div class="lp-grid-roles">
            <div class="lp-role" data-reveal="scale" data-delay="0">
                <div class="lp-role-icon fi-t"><i class="bi bi-person"></i></div>
                <h3>Citizens</h3>
                <ul>
                    <li><i class="bi bi-check-circle-fill"></i>Submit and track service requests</li>
                    <li><i class="bi bi-check-circle-fill"></i>Pay fees and download receipts</li>
                    <li><i class="bi bi-check-circle-fill"></i>Book appointments with offices</li>
                    <li><i class="bi bi-check-circle-fill"></i>Receive real-time notifications</li>
                    <li><i class="bi bi-check-circle-fill"></i>Track via QR code — no login needed</li>
                </ul>
            </div>
            <div class="lp-role" data-reveal="scale" data-delay="90">
                <div class="lp-role-icon fi-s"><i class="bi bi-buildings"></i></div>
                <h3>Office Users</h3>
                <ul>
                    <li><i class="bi bi-check-circle-fill"></i>Review and process requests</li>
                    <li><i class="bi bi-check-circle-fill"></i>Manage services and fees</li>
                    <li><i class="bi bi-check-circle-fill"></i>Handle appointments and schedules</li>
                    <li><i class="bi bi-check-circle-fill"></i>Respond to citizen feedback</li>
                    <li><i class="bi bi-check-circle-fill"></i>Generate PDF reports and documents</li>
                </ul>
            </div>
            <div class="lp-role" data-reveal="scale" data-delay="180">
                <div class="lp-role-icon fi-a"><i class="bi bi-shield-lock"></i></div>
                <h3>Administrators</h3>
                <ul>
                    <li><i class="bi bi-check-circle-fill"></i>Manage municipalities and offices</li>
                    <li><i class="bi bi-check-circle-fill"></i>Oversee all platform users</li>
                    <li><i class="bi bi-check-circle-fill"></i>Monitor requests and revenue</li>
                    <li><i class="bi bi-check-circle-fill"></i>View analytics and reports</li>
                    <li><i class="bi bi-check-circle-fill"></i>Configure platform settings</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────────────────────── --}}
<section class="lp-section">
    <div class="lp-section-inner">
        <div class="lp-banner" data-reveal="scale">
            <h2>Ready to get started?</h2>
            <p>Join the platform and access municipal services from anywhere. Registration takes less than a minute.</p>
            <a href="{{ route('register') }}" class="lp-banner-btn">
                Create your free account <span>&rarr;</span>
            </a>
        </div>
    </div>
</section>

{{-- ── FOOTER ────────────────────────────────────────────── --}}
<footer class="lp-footer" data-reveal="scale">
    <div style="display:flex;align-items:center;gap:0.625rem;">
        <span style="width:22px;height:22px;border-radius:5px;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;">
            <img src="{{ asset('assets/img/brand/cedar-logo-icon-trim.png') }}" alt="CedarGov icon" style="width:100%;height:100%;object-fit:cover;display:block;">
        </span>
        <span>CedarGov &mdash; Lebanese Municipalities</span>
    </div>
    <span>&copy; {{ now()->year }} All rights reserved</span>
</footer>

<script>
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const root = document.documentElement;

        if (!reduceMotion) {
            root.classList.add('js-motion');
        }

        const nav = document.getElementById('lpNav');
        const onScroll = () => {
            if (!nav) {
                return;
            }
            nav.classList.toggle('scrolled', window.scrollY > 10);
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        const revealTargets = document.querySelectorAll('[data-reveal]');

        if (reduceMotion) {
            revealTargets.forEach((el) => el.classList.add('is-visible'));
        } else {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const el = entry.target;
                    const delay = Number.parseInt(el.dataset.delay || '0', 10);
                    if (!Number.isNaN(delay) && delay > 0) {
                        el.style.setProperty('--reveal-delay', `${delay}ms`);
                    }

                    el.classList.add('is-visible');
                    observer.unobserve(el);
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -10% 0px'
            });

            revealTargets.forEach((el) => revealObserver.observe(el));
        }

        const counters = document.querySelectorAll('[data-counter]');

        const formatNumber = (value, decimals) => value.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        const animateCounter = (el) => {
            if (el.dataset.counted === 'true') {
                return;
            }

            const target = Number.parseFloat(el.dataset.counter || '0');
            if (Number.isNaN(target)) {
                return;
            }

            const decimals = Number.parseInt(el.dataset.decimals || (Number.isInteger(target) ? '0' : '1'), 10);
            const suffix = el.dataset.suffix || '';
            const prefix = el.dataset.prefix || '';

            if (reduceMotion || target === 0) {
                el.textContent = `${prefix}${formatNumber(target, decimals)}${suffix}`;
                el.dataset.counted = 'true';
                return;
            }

            const duration = 1100;
            const startAt = performance.now();

            const tick = (now) => {
                const elapsed = now - startAt;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = target * eased;

                el.textContent = `${prefix}${formatNumber(current, decimals)}${suffix}`;

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.textContent = `${prefix}${formatNumber(target, decimals)}${suffix}`;
                    el.dataset.counted = 'true';
                }
            };

            requestAnimationFrame(tick);
        };

        if (counters.length) {
            if (reduceMotion) {
                counters.forEach((counter) => animateCounter(counter));
            } else {
                const counterObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.45,
                    rootMargin: '0px 0px -8% 0px'
                });

                counters.forEach((counter) => counterObserver.observe(counter));
            }
        }
    })();
</script>

</body>
</html>



