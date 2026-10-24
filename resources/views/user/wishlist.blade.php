@extends('layouts.user')

@section('title', 'Wishlist - My Account - DigiGo')
@section('breadcrumb_current', 'Wishlist')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-regular fa-heart" style="color: var(--theme-accent);"></i> Wishlist
    </h2>

    <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
        <i class="fa-regular fa-heart" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
        <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">Your wishlist is currently empty.</h3>
        <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Explore our store to add products and services you wish to buy later.</p>
        <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Discover Products
        </a>
    </div>
</div>
@endsection
