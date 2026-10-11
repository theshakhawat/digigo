@extends('layouts.user')

@section('title', 'Orders - My Account - DigiGo')
@section('breadcrumb_current', 'Orders')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-regular fa-file-lines" style="color: var(--theme-accent);"></i> Orders
    </h2>

    <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
        <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
        <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No order has been made yet.</h3>
        <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Browse our store for top digital products, subscriptions, and AI tools.</p>
        <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Browse Products
        </a>
    </div>
</div>
@endsection
