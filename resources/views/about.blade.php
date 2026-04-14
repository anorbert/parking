@extends('layouts.app')

@section('title', 'About ParkFlow - Smart Parking Management')
@section('body-class', 'lp-body')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; }

  .lp-body {
    background: #111315 !important;
    min-height: 100vh;
    margin: 0; padding: 0;
  }

  .lp-bg {
    position: fixed; inset: 0; z-index: 0;
    background: radial-gradient(ellipse 80% 60% at 15% 10%, rgba(245,168,0,0.12) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 90%, rgba(58,158,212,0.10) 0%, transparent 55%),
                linear-gradient(160deg, #0E1012 0%, #141618 50%, #0C1018 100%);
  }

  .lp-grid {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 48px 48px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 20%, transparent 80%);
  }

  .ab-page {
    position: relative; z-index: 1;
    min-height: 100vh;
    font-family: 'Dosis', sans-serif;
    padding: 40px 24px 60px;
  }

  /* ── Top Bar ──────────────────────────────── */
  .ab-topbar {
    max-width: 960px; margin: 0 auto 36px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
  }

  .ab-logo {
    display: flex; align-items: center; gap: 10px;
    text-decoration: none;
  }

  .ab-logo-icon {
    width: 38px; height: 38px; border-radius: 8px;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .ab-logo-icon span {
    font-family: 'Dosis', sans-serif; font-weight: 800;
    font-size: 20px; color: #0D0F11; line-height: 1;
  }

  .ab-logo-name {
    font-size: 18px; font-weight: 800;
    letter-spacing: 3px; text-transform: uppercase;
    color: #F3F4F6;
  }

  .ab-back {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 8px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    color: #60C4F5; font-size: 13px; font-weight: 600;
    text-decoration: none; letter-spacing: 0.5px;
    transition: background 0.2s, border-color 0.2s;
  }
  .ab-back:hover {
    background: rgba(96,196,245,0.08);
    border-color: rgba(96,196,245,0.25);
    color: #60C4F5;
  }

  /* ── Hero ──────────────────────────────────── */
  .ab-hero {
    max-width: 960px; margin: 0 auto 48px;
    text-align: center;
  }

  .ab-hero-eyebrow {
    font-size: 11px; font-weight: 600;
    letter-spacing: 3px; text-transform: uppercase;
    color: #F5A800; margin-bottom: 12px;
  }

  .ab-hero-title {
    font-size: 36px; font-weight: 800;
    color: #F3F4F6; line-height: 1.15;
    margin-bottom: 12px;
  }

  .ab-hero-desc {
    font-size: 15px; font-weight: 400;
    color: #6B7280; line-height: 1.7;
    max-width: 620px; margin: 0 auto;
  }

  /* ── Section ──────────────────────────────── */
  .ab-section {
    max-width: 960px; margin: 0 auto 48px;
  }

  .ab-section-label {
    font-size: 11px; font-weight: 600;
    letter-spacing: 3px; text-transform: uppercase;
    color: #F5A800; margin-bottom: 8px;
  }

  .ab-section-title {
    font-size: 24px; font-weight: 800;
    color: #E5E7EB; margin-bottom: 20px;
  }

  /* ── Feature Cards Grid ───────────────────── */
  .ab-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
  }

  .ab-fcard {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 24px 20px;
    transition: border-color 0.2s, background 0.2s, transform 0.2s;
  }
  .ab-fcard:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(245,168,0,0.18);
    transform: translateY(-2px);
  }

  .ab-fcard-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px; font-size: 20px;
  }

  .ab-fcard-icon.yellow  { background: rgba(245,168,0,0.12); color: #F5A800; }
  .ab-fcard-icon.blue    { background: rgba(58,158,212,0.12); color: #60C4F5; }
  .ab-fcard-icon.green   { background: rgba(74,222,128,0.12); color: #4ADE80; }
  .ab-fcard-icon.purple  { background: rgba(168,85,247,0.12); color: #A855F7; }
  .ab-fcard-icon.red     { background: rgba(239,68,68,0.12);  color: #EF4444; }
  .ab-fcard-icon.cyan    { background: rgba(34,211,238,0.12);  color: #22D3EE; }

  .ab-fcard-title {
    font-size: 15px; font-weight: 700;
    color: #E5E7EB; margin-bottom: 6px;
  }

  .ab-fcard-desc {
    font-size: 12px; font-weight: 400;
    color: #6B7280; line-height: 1.6;
  }

  /* ── How It Works Steps ───────────────────── */
  .ab-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .ab-step {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 24px 18px;
    text-align: center;
    position: relative;
    transition: border-color 0.2s, background 0.2s;
  }
  .ab-step:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(96,196,245,0.2);
  }

  .ab-step-num {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    color: #0D0F11; font-weight: 800; font-size: 16px;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
  }

  .ab-step-title {
    font-size: 14px; font-weight: 700;
    color: #E5E7EB; margin-bottom: 6px;
  }

  .ab-step-desc {
    font-size: 12px; font-weight: 400;
    color: #6B7280; line-height: 1.6;
  }

  /* ── User Roles ───────────────────────────── */
  .ab-roles {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    max-width: 700px;
  }

  .ab-role {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 24px 20px;
    transition: border-color 0.2s, background 0.2s;
  }
  .ab-role:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(74,222,128,0.2);
  }

  .ab-role-badge {
    display: inline-block;
    padding: 3px 10px; border-radius: 6px;
    font-size: 10px; font-weight: 700;
    letter-spacing: 1.5px; text-transform: uppercase;
    margin-bottom: 12px;
  }
  .ab-role-badge.gold   { background: rgba(245,168,0,0.15); color: #F5A800; }
  .ab-role-badge.blue   { background: rgba(58,158,212,0.15); color: #60C4F5; }
  .ab-role-badge.teal   { background: rgba(74,222,128,0.15); color: #4ADE80; }

  .ab-role-title {
    font-size: 16px; font-weight: 700;
    color: #E5E7EB; margin-bottom: 8px;
  }

  .ab-role-list {
    list-style: none; padding: 0; margin: 0;
  }

  .ab-role-list li {
    font-size: 12px; font-weight: 400;
    color: #6B7280; padding: 4px 0;
    display: flex; align-items: flex-start; gap: 8px;
    line-height: 1.5;
  }

  .ab-role-list li::before {
    content: '';
    width: 5px; height: 5px; border-radius: 50%;
    background: #F5A800; margin-top: 6px;
    flex-shrink: 0;
  }

  /* ── Pricing Highlight ────────────────────── */
  .ab-pricing {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  .ab-price-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 24px 20px;
    transition: border-color 0.2s, background 0.2s;
  }
  .ab-price-card:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(245,168,0,0.18);
  }

  .ab-price-icon {
    font-size: 20px; margin-bottom: 10px;
  }

  .ab-price-title {
    font-size: 15px; font-weight: 700;
    color: #E5E7EB; margin-bottom: 6px;
  }

  .ab-price-desc {
    font-size: 12px; font-weight: 400;
    color: #6B7280; line-height: 1.6;
  }

  /* ── CTA ──────────────────────────────────── */
  .ab-cta {
    max-width: 960px; margin: 0 auto 40px;
    text-align: center;
  }

  .ab-cta-card {
    background: linear-gradient(135deg, rgba(245,168,0,0.08) 0%, rgba(58,158,212,0.06) 100%);
    border: 1px solid rgba(245,168,0,0.15);
    border-radius: 18px;
    padding: 40px 30px;
  }

  .ab-cta-title {
    font-size: 24px; font-weight: 800;
    color: #F3F4F6; margin-bottom: 10px;
  }

  .ab-cta-desc {
    font-size: 14px; color: #6B7280;
    line-height: 1.6; margin-bottom: 22px;
    max-width: 480px; margin-left: auto; margin-right: auto;
  }

  .ab-cta-btns {
    display: flex; justify-content: center;
    gap: 12px; flex-wrap: wrap;
  }

  .ab-btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 12px 28px; border-radius: 10px;
    background: linear-gradient(90deg, #F5A800 0%, #FFD166 50%, #F5A800 100%);
    background-size: 200% 100%;
    color: #0D0F11;
    font-family: 'Dosis', sans-serif; font-size: 13px;
    font-weight: 800; letter-spacing: 2px;
    text-transform: uppercase; text-decoration: none;
    transition: background-position 0.5s, box-shadow 0.3s;
    box-shadow: 0 4px 20px rgba(245,168,0,0.25);
  }
  .ab-btn-primary:hover {
    background-position: right center;
    box-shadow: 0 6px 28px rgba(245,168,0,0.4);
    color: #0D0F11;
  }

  .ab-btn-secondary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 12px 28px; border-radius: 10px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.12);
    color: #E5E7EB;
    font-family: 'Dosis', sans-serif; font-size: 13px;
    font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; text-decoration: none;
    transition: background 0.2s, border-color 0.2s;
  }
  .ab-btn-secondary:hover {
    background: rgba(255,255,255,0.07);
    border-color: rgba(96,196,245,0.3);
    color: #60C4F5;
  }

  /* ── Footer ───────────────────────────────── */
  .ab-footer {
    max-width: 960px; margin: 0 auto;
    text-align: center;
    font-size: 11px; font-weight: 500;
    letter-spacing: 0.5px; color: #353A42;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
  }

  /* ── Responsive ───────────────────────────── */
  @media (max-width: 768px) {
    .ab-hero-title { font-size: 28px; }
    .ab-features { grid-template-columns: repeat(2, 1fr); }
    .ab-steps { grid-template-columns: repeat(2, 1fr); }
    .ab-roles { grid-template-columns: 1fr; max-width: 100%; }
    .ab-pricing { grid-template-columns: 1fr; }
  }

  @media (max-width: 480px) {
    .ab-page { padding: 24px 16px 40px; }
    .ab-hero-title { font-size: 24px; }
    .ab-hero-desc { font-size: 13px; }
    .ab-features { grid-template-columns: 1fr; }
    .ab-steps { grid-template-columns: 1fr; }
    .ab-topbar { justify-content: center; }
  }
</style>
@endpush

@section('content')
<div class="lp-bg"><div class="lp-grid"></div></div>

<div class="ab-page">

  {{-- Top Bar --}}
  <div class="ab-topbar">
    <a href="{{ route('login') }}" class="ab-logo">
      <div class="ab-logo-icon"><span>P</span></div>
      <div class="ab-logo-name">ParkFlow</div>
    </a>
    <a href="{{ route('login') }}" class="ab-back">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Back to Login
    </a>
  </div>

  {{-- Hero --}}
  <div class="ab-hero">
    <div class="ab-hero-eyebrow">All-in-One Parking Solution</div>
    <h1 class="ab-hero-title">How ParkFlow Works</h1>
    <p class="ab-hero-desc">
      ParkFlow is a complete parking management platform designed for companies that operate paid parking lots.
      From vehicle entry to payment collection and daily reporting, everything is managed in one system.
    </p>
  </div>

  {{-- Core Features --}}
  <div class="ab-section">
    <div class="ab-section-label">Platform Features</div>
    <h2 class="ab-section-title">Everything You Need to Run a Parking Business</h2>

    <div class="ab-features">
      <div class="ab-fcard">
        <div class="ab-fcard-icon yellow">&#9881;</div>
        <div class="ab-fcard-title">Zone & Slot Management</div>
        <div class="ab-fcard-desc">Define parking zones (e.g. Zone A, VIP, Basement). Set the number of slots per zone and track real-time occupancy from your dashboard.</div>
      </div>

      <div class="ab-fcard">
        <div class="ab-fcard-icon blue">&#128663;</div>
        <div class="ab-fcard-title">Vehicle Entry & Exit</div>
        <div class="ab-fcard-desc">Record each vehicle as it enters — plate number, zone assignment, and driver phone. Upon exit, the system automatically calculates duration and amount owed.</div>
      </div>

      <div class="ab-fcard">
        <div class="ab-fcard-icon green">&#128176;</div>
        <div class="ab-fcard-title">Flexible Rate System</div>
        <div class="ab-fcard-desc">Configure custom parking rates per zone — set hourly, daily, or flat rates. Rates are automatically applied when calculating charges at exit time.</div>
      </div>

      <div class="ab-fcard">
        <div class="ab-fcard-icon purple">&#128179;</div>
        <div class="ab-fcard-title">Cash & Mobile Payments</div>
        <div class="ab-fcard-desc">Accept cash payments on the spot or send a MoMo mobile money request directly to the driver's phone. Each payment generates a receipt instantly.</div>
      </div>

      <div class="ab-fcard">
        <div class="ab-fcard-icon red">&#128202;</div>
        <div class="ab-fcard-title">Reports & Analytics</div>
        <div class="ab-fcard-desc">View daily and monthly revenue charts, zone-by-zone usage statistics, and payment method breakdowns. Export data any time for your accounting needs.</div>
      </div>

      <div class="ab-fcard">
        <div class="ab-fcard-icon cyan">&#128276;</div>
        <div class="ab-fcard-title">Real-Time Notifications</div>
        <div class="ab-fcard-desc">Get instant alerts when vehicles enter or exit, payments are received, subscriptions are nearing expiry, or new staff accounts are created.</div>
      </div>
    </div>
  </div>

  {{-- How It Works --}}
  <div class="ab-section">
    <div class="ab-section-label">Step by Step</div>
    <h2 class="ab-section-title">How the System Works</h2>

    <div class="ab-steps">
      <div class="ab-step">
        <div class="ab-step-num">1</div>
        <div class="ab-step-title">Register Your Company</div>
        <div class="ab-step-desc">Create a company account by providing your business name, TIN, location, and admin details. You can start using the system immediately.</div>
      </div>

      <div class="ab-step">
        <div class="ab-step-num">2</div>
        <div class="ab-step-title">Set Up Zones & Rates</div>
        <div class="ab-step-desc">Define your parking zones and assign available slots. Then configure your pricing — set hourly, daily, or custom rates per zone.</div>
      </div>

      <div class="ab-step">
        <div class="ab-step-num">3</div>
        <div class="ab-step-title">Manage Daily Operations</div>
        <div class="ab-step-desc">Your cashiers log vehicles at entry, record plate numbers, and assign zones. When vehicles exit, charges are calculated automatically.</div>
      </div>

      <div class="ab-step">
        <div class="ab-step-num">4</div>
        <div class="ab-step-title">Collect & Track Payments</div>
        <div class="ab-step-desc">Accept cash or trigger MoMo payments. Every transaction is recorded with full history, making daily reconciliation effortless.</div>
      </div>
    </div>
  </div>

  {{-- User Roles --}}
  <div class="ab-section">
    <div class="ab-section-label">Access Levels</div>
    <h2 class="ab-section-title">Two Roles, One System</h2>

    <div class="ab-roles">
      <div class="ab-role">
        <div class="ab-role-badge blue">Company Admin</div>
        <div class="ab-role-title">Business Manager</div>
        <ul class="ab-role-list">
          <li>Set up parking zones and slot capacities</li>
          <li>Configure parking rates per zone</li>
          <li>Add and manage cashier staff accounts</li>
          <li>View company revenue reports and analytics</li>
          <li>Manage company profile and bank details</li>
          <li>View, compare, renew, or upgrade subscription plans</li>
        </ul>
      </div>

      <div class="ab-role">
        <div class="ab-role-badge teal">Cashier</div>
        <div class="ab-role-title">Field Operator</div>
        <ul class="ab-role-list">
          <li>Record vehicle entries with plate and zone</li>
          <li>Process vehicle exits and generate charges</li>
          <li>Collect cash or initiate MoMo payments</li>
          <li>View personal daily activity summaries</li>
          <li>Manage exempted vehicles list</li>
        </ul>
      </div>
    </div>
  </div>

  {{-- Payments & Subscriptions --}}
  <div class="ab-section">
    <div class="ab-section-label">Billing & Payments</div>
    <h2 class="ab-section-title">Simple Subscription Model</h2>

    <div class="ab-pricing">
      <div class="ab-price-card">
        <div class="ab-price-icon">&#128205;</div>
        <div class="ab-price-title">Company Subscription</div>
        <div class="ab-price-desc">
          Each company subscribes to a plan to use the platform. Multiple plans are available with different feature limits — zones, slots, staff, MoMo payments, and reports.
          Company admins can compare plans side by side, renew their current plan, or upgrade to a higher plan at any time from the "My Plan" page.
        </div>
      </div>

      <div class="ab-price-card">
        <div class="ab-price-icon">&#128241;</div>
        <div class="ab-price-title">MoMo Mobile Money Integration</div>
        <div class="ab-price-desc">
          When a driver is ready to pay, the cashier can send a payment request directly to the driver's phone using MoMo mobile money.
          Payments are processed securely and recorded in the system with a full transaction history.
        </div>
      </div>
    </div>
  </div>

  {{-- CTA --}}
  <div class="ab-cta">
    <div class="ab-cta-card">
      <div class="ab-cta-title">Ready to Get Started?</div>
      <p class="ab-cta-desc">
        Register your company today and start managing your parking operations with ParkFlow — professional, efficient, and all in one place.
      </p>
      <div class="ab-cta-btns">
        <a href="{{ route('register') }}" class="ab-btn-primary">Register Your Company</a>
        <a href="{{ route('login') }}" class="ab-btn-secondary">Sign In</a>
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <div class="ab-footer">
    &copy; {{ date('Y') }} ParkFlow Parking System &middot; All Rights Reserved
  </div>

</div>
@endsection
