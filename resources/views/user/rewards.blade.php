@extends('layouts.user')

@section('title', 'Points & Rewards - My Account - DigiGo')
@section('breadcrumb_current', 'Points & Rewards')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-solid fa-award" style="color: var(--theme-accent);"></i> Points & Rewards
    </h2>

    <div style="background: #f8fafc; border: 1px dashed var(--account-border); border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Available Reward Balance</span>
        <div style="font-size: 2.75rem; font-weight: 800; color: var(--theme-header); margin: 0.5rem 0;">0 Points</div>
        <p style="font-size: 0.9rem; color: #64748b; margin: 0;">Earn points on every digital order and redeem them for future purchases.</p>
    </div>

    <div style="text-align: center; padding: 1.5rem 1rem; color: #64748b;">
        <i class="fa-solid fa-coins" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.05rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No reward history yet</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 1.5rem;">Your points transactions and reward redemptions will be recorded here.</p>
        <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Shop & Earn Points
        </a>
    </div>
</div>
@endsection
