@extends($user->isSuperAdmin() || $user->role_id == 2 ? 'layouts.admin' : 'layouts.user')

@section('title', 'How to Use - ParkFlow')

@push('styles')
<style>
  .gd-wrap {
    max-width: 900px; margin: 0 auto;
    padding: 0 8px;
  }

  .gd-header {
    margin-bottom: 28px;
  }

  .gd-header h1 {
    font-size: 22px; font-weight: 700;
    color: #1F2937; margin: 0 0 4px;
  }

  .gd-header p {
    font-size: 13px; color: #6B7280; margin: 0;
  }

  .gd-section {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
  }

  .gd-section-title {
    font-size: 16px; font-weight: 700;
    color: #1F2937; margin: 0 0 6px;
    display: flex; align-items: center; gap: 10px;
  }

  .gd-section-title .gd-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    color: #0D0F11; font-weight: 800; font-size: 13px;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .gd-section-desc {
    font-size: 13px; color: #4B5563;
    line-height: 1.7; margin: 0;
    padding-left: 38px;
  }

  .gd-section-desc strong {
    color: #1F2937;
  }

  .gd-tip {
    background: #FEF3C7;
    border: 1px solid #FDE68A;
    border-radius: 8px;
    padding: 12px 16px;
    margin-top: 10px;
    margin-left: 38px;
    font-size: 12px; color: #92400E;
    line-height: 1.6;
  }

  .gd-tip::before {
    content: '💡 ';
  }

  .gd-help-link {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; border-radius: 8px;
    background: #3A9ED4; color: #fff;
    font-size: 13px; font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
    margin-top: 10px;
  }
  .gd-help-link:hover { background: #2B7EB0; color: #fff; }
</style>
@endpush

@section('content')
<div class="gd-wrap">

  <div class="gd-header">
    <h1>📖 How to Use ParkFlow</h1>
    <p>A step-by-step guide to help you navigate the system efficiently.</p>
  </div>

  @if($user->isSuperAdmin())
  {{-- ════════════════ SUPER ADMIN GUIDE ════════════════ --}}

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">1</span> Dashboard Overview</div>
    <p class="gd-section-desc">
      Your dashboard shows <strong>platform-wide statistics</strong> including total companies, active subscriptions, total revenue, and user counts.
      Use this as your main control center to monitor the health of the platform.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">2</span> Managing Companies</div>
    <p class="gd-section-desc">
      Go to <strong>Companies</strong> in the sidebar to view all registered companies. You can see company details, their admin contact, and subscription status.
      Click on a company to view or edit its details. New companies register themselves through the public registration page.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">3</span> Subscription Plans</div>
    <p class="gd-section-desc">
      Navigate to <strong>Plans</strong> to create and manage subscription packages. Each plan has a name, price, duration (monthly/yearly), and feature limits.
      Companies choose a plan during registration or when renewing their subscription.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">4</span> Managing Subscriptions</div>
    <p class="gd-section-desc">
      Under <strong>Subscriptions</strong>, you can view all company subscriptions. You can <strong>activate</strong> pending subscriptions after verifying payment,
      or <strong>renew</strong> expired ones. Companies with expired subscriptions are restricted from accessing the system until renewed.
    </p>
    <div class="gd-tip">When a company pays via MoMo, the subscription is auto-activated upon successful payment confirmation.</div>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">5</span> Reports</div>
    <p class="gd-section-desc">
      The <strong>Reports</strong> section provides platform-wide analytics: revenue trends, company activity, subscription statuses, and more.
      Use the date filters and charts to track platform performance over time.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">6</span> Help & Support Chat</div>
    <p class="gd-section-desc">
      Go to <strong>Help Chat</strong> in the sidebar to view and respond to support messages from company admins and cashiers.
      You'll see a list of all conversations — click on any to open the chat and respond directly.
    </p>
    <div class="gd-tip">Unread message counts appear as badges on the Help Chat link in your sidebar.</div>
  </div>

  @elseif($user->role_id == 2)
  {{-- ════════════════ COMPANY ADMIN GUIDE ════════════════ --}}

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">1</span> Dashboard Overview</div>
    <p class="gd-section-desc">
      Your dashboard shows your <strong>company statistics</strong>: active parkings, available slots, today's revenue, and recent activity.
      This is your daily command center for monitoring operations.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">2</span> Setting Up Zones</div>
    <p class="gd-section-desc">
      Go to <strong>Parking → Zones</strong> to create your parking zones (e.g., Zone A, VIP, Basement).
      For each zone, set the <strong>total number of slots</strong> available. The system tracks occupancy automatically as vehicles enter and exit.
    </p>
    <div class="gd-tip">You must create at least one zone before your cashiers can start registering vehicles.</div>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">3</span> Configuring Parking Rates</div>
    <p class="gd-section-desc">
      Navigate to <strong>Parking Rates</strong> under Settings. Here you define how much to charge per hour, per day, or a flat rate.
      Rates are <strong>zone-specific</strong> — you can set different prices for different zones.
      When a vehicle exits, the system calculates the charge automatically based on the active rate.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">4</span> Managing Staff (Cashiers)</div>
    <p class="gd-section-desc">
      Under <strong>Settings → Users</strong>, you can add cashier accounts. Each cashier needs a <strong>name, phone number</strong>, and a <strong>4-digit PIN</strong>.
      Cashiers use their phone + PIN to log in and manage daily parking operations from their own panel.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">5</span> Payments & Reports</div>
    <p class="gd-section-desc">
      View all payments under <strong>All Payments</strong>. Each entry shows the vehicle, amount, payment method (Cash or MoMo), and date.
      The <strong>Reports</strong> page provides visual charts for revenue trends, zone usage, and payment method breakdowns.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">6</span> Company Profile</div>
    <p class="gd-section-desc">
      Click <strong>My Company</strong> to view and update your company information — name, TIN, address, and bank details.
      Keep this updated to ensure proper billing and identification on the platform.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">7</span> Subscription & Plans</div>
    <p class="gd-section-desc">
      Go to <strong>My Plan</strong> under the Subscription section in the sidebar to view your current subscription status, including the plan name, price, start/end dates, and days remaining.
      Below your current plan, you'll find all <strong>available plans</strong> with a side-by-side feature comparison — zones, slots, staff limits, MoMo payments, and reports.
    </p>
    <div class="gd-tip">
      To <strong>renew</strong> your current plan or <strong>upgrade</strong> to a higher plan, click the button on any plan card and pay via MTN/Airtel MoMo. Your subscription activates instantly after payment.
    </div>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">8</span> Need Help?</div>
    <p class="gd-section-desc">
      If you encounter any issues or have questions, use the <strong>Help Chat</strong> in the sidebar to message the platform administrator directly.
    </p>
    <a href="{{ route('help.chat') }}" class="gd-help-link" style="margin-left: 38px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Open Help Chat
    </a>
  </div>

  @else
  {{-- ════════════════ CASHIER GUIDE ════════════════ --}}

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">1</span> Your Dashboard</div>
    <p class="gd-section-desc">
      Your dashboard is the <strong>main operations screen</strong>. You'll see available parking slots, active vehicles, and the entry form.
      This is where you spend most of your time during a shift.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">2</span> Registering a Vehicle Entry</div>
    <p class="gd-section-desc">
      When a vehicle arrives, use the <strong>entry form</strong> on the dashboard. Enter the <strong>plate number</strong>, select a <strong>zone</strong>,
      and optionally enter the <strong>driver's phone number</strong>. Click the entry button to register the vehicle.
      The system assigns a slot and starts tracking the parking duration.
    </p>
    <div class="gd-tip">The plate number field auto-formats. Make sure to select the correct zone to avoid billing errors.</div>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">3</span> Processing a Vehicle Exit</div>
    <p class="gd-section-desc">
      When a vehicle is ready to leave, find it in the <strong>active parkings list</strong> on the dashboard or in <strong>Entry & Exit Logs</strong>.
      Click the <strong>Exit</strong> button — the system will calculate the total amount based on the parking duration and the zone rate.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">4</span> Collecting Payment</div>
    <p class="gd-section-desc">
      After processing an exit, you can collect payment in two ways:<br>
      • <strong>Cash</strong> — Select cash and confirm the amount received.<br>
      • <strong>MoMo Mobile Money</strong> — Enter the driver's phone number to send a payment request directly to their phone.
    </p>
    <div class="gd-tip">For MoMo payments, the driver will receive a push notification on their phone to confirm the payment.</div>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">5</span> Entry & Exit Logs</div>
    <p class="gd-section-desc">
      The <strong>Entry & Exit Logs</strong> page shows a complete history of all vehicles you've processed.
      You can search by plate number or filter by date. Each entry shows entry time, exit time, duration, and payment status.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">6</span> Exempted Vehicles</div>
    <p class="gd-section-desc">
      Some vehicles may be <strong>exempted from charges</strong> (e.g., company vehicles, VIPs). Go to <strong>Exempted Vehicles</strong>
      to view or add plates that should not be charged when they exit.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">7</span> Payments & Reports</div>
    <p class="gd-section-desc">
      Under <strong>Payments</strong>, review all transactions you've processed. The <strong>Reports</strong> page shows
      your daily activity summary with visual charts.
    </p>
  </div>

  <div class="gd-section">
    <div class="gd-section-title"><span class="gd-num">8</span> Need Help?</div>
    <p class="gd-section-desc">
      If you have questions or need assistance, use the <strong>Help Chat</strong> to contact the platform administrator.
    </p>
    <a href="{{ route('help.chat') }}" class="gd-help-link" style="margin-left: 38px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Open Help Chat
    </a>
  </div>

  @endif

</div>
@endsection
