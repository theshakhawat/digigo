@extends('layouts.user')

@section('title', 'Subscriptions - My Account - DigiGo')
@section('breadcrumb_current', 'Subscriptions')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-solid fa-cloud-arrow-down" style="color: var(--theme-accent);"></i> Subscriptions
    </h2>

    <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
        <i class="fa-solid fa-arrows-rotate" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
        <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No active subscriptions available yet.</h3>
        <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Your active subscription keys, plans, validity, and credentials will appear here once purchased.</p>
        <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Browse Subscriptions & Digital Store
        </a>
    </div>
</div>
@endsection
