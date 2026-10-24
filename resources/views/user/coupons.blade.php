@extends('layouts.user')

@section('title', 'Withdraw - My Account - DigiGo')
@section('breadcrumb_current', 'Withdraw')

@section('account_content')
<div class="content-card-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2>
            <i class="fa-solid fa-money-bill-transfer" style="color: var(--theme-accent);"></i> Withdraw & Payouts
        </h2>
        <span style="font-size: 0.8rem; color: #64748b;">
            Current Balance: ৳0.00
        </span>
    </div>

    <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
        <i class="fa-solid fa-wallet" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
        <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No withdrawal requests or balance available.</h3>
        <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Once you earn affiliate commissions or refundable balances, you can request withdrawals via bKash, Nagad, or Bank Transfer here.</p>
        <a href="{{ route('user.dashboard') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Return to Dashboard
        </a>
    </div>
</div>
@endsection
