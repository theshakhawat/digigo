@extends('layouts.user')

@section('title', 'Downloads - My Account - DigiGo')
@section('breadcrumb_current', 'Downloads')

@section('account_content')
<div class="content-card-box">
    <h2>
        <i class="fa-solid fa-arrow-down-to-bracket" style="color: var(--theme-accent);"></i> Downloads
    </h2>

    <div style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
        <i class="fa-solid fa-cloud-arrow-down" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.25rem;"></i>
        <h3 style="font-size: 1.15rem; color: #1e293b; margin-bottom: 0.5rem; border: none; padding: 0;">No downloads available yet.</h3>
        <p style="font-size: 0.925rem; margin-bottom: 1.75rem; color: #64748b;">Digital licenses, download links, and credentials will appear here once purchased.</p>
        <a href="{{ url('/') }}" class="ribbon-pill-btn" style="background: var(--theme-header); color: #fff; text-decoration: none; display: inline-flex; padding: 0.65rem 1.75rem;">
            Browse Digital Store
        </a>
    </div>
</div>
@endsection
