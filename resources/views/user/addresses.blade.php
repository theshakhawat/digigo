@extends('layouts.user')

@section('title', 'Addresses - My Account - DigiGo')
@section('breadcrumb_current', 'Addresses')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-regular fa-map" style="color: var(--theme-accent);"></i> Addresses
    </h2>
    <p style="color: #64748b; font-size: 0.925rem; margin-bottom: 2rem;">
        The following addresses will be used on the checkout page by default for billing and invoice records.
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <!-- Billing Address -->
        <div style="border: 1px solid var(--account-border); border-radius: 8px; padding: 1.5rem; background: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; border: none; padding: 0;">Billing address</h3>
                <a href="javascript:void(0)" style="font-size: 0.85rem; color: var(--theme-accent); text-decoration: none; font-weight: 600;">Add</a>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; line-height: 1.6; margin: 0;">
                You have not set up this type of address yet.
            </p>
        </div>

        <!-- Shipping Address -->
        <div style="border: 1px solid var(--account-border); border-radius: 8px; padding: 1.5rem; background: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; border: none; padding: 0;">Shipping address</h3>
                <a href="javascript:void(0)" style="font-size: 0.85rem; color: var(--theme-accent); text-decoration: none; font-weight: 600;">Add</a>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; line-height: 1.6; margin: 0;">
                You have not set up this type of address yet.
            </p>
        </div>
    </div>
</div>
@endsection
