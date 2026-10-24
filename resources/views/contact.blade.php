@extends('layouts.site')

@section('title', 'Contact Us - DigiGo')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-8">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Contact Us</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 mb-3">
            <i class="fa-solid fa-headset text-[11px]"></i> We Are Here to Help
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
            Get in Touch With DigiGo
        </h1>
        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
            Have questions regarding software licenses, subscriptions, physical delivery, or custom orders? Reach out to our dedicated support team anytime.
        </p>
    </div>

    <!-- Contact Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        
        <!-- WhatsApp Support Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col items-center text-center hover:border-emerald-500 hover:shadow-md transition group">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">WhatsApp Chat</h3>
            <p class="text-xs text-slate-500 mb-4">Fastest response for instant orders & licensing</p>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('topbar_whatsapp_phone', '8801600793325')) }}" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-600 hover:text-emerald-700 text-sm flex items-center gap-1.5">
                <span>{{ setting('topbar_whatsapp_phone', '+8801600793325') }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>

        <!-- Phone Hotline Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col items-center text-center hover:border-blue-500 hover:shadow-md transition group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                <i class="fa-solid fa-phone"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Hotline & Voice Call</h3>
            <p class="text-xs text-slate-500 mb-4">Dedicated 24/7 hotline assistance</p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('topbar_support_phone', '+8809611678088')) }}" class="font-bold text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1.5">
                <span>{{ setting('topbar_support_phone', '+8809611678088') }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>

        <!-- Email & Office Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col items-center text-center hover:border-indigo-500 hover:shadow-md transition group">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Email Support</h3>
            <p class="text-xs text-slate-500 mb-4">Invoices, enterprise licenses & queries</p>
            <a href="mailto:{{ setting('mail_from_address', 'support@digigo.com.bd') }}" class="font-bold text-indigo-600 hover:text-indigo-700 text-sm flex items-center gap-1.5">
                <span>{{ setting('mail_from_address', 'support@digigo.com.bd') }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>

    </div>

    <!-- Additional Info / Office Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 max-w-3xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-1 text-center sm:text-left">
            <h4 class="font-bold text-slate-900 text-base">DigiGo Customer Care & Headquarters</h4>
            <p class="text-xs text-slate-500">Dhaka, Bangladesh &bull; Official Digital Lifestyle & Gadgets Merchant</p>
            <p class="text-xs text-emerald-600 font-semibold flex items-center justify-center sm:justify-start gap-1.5 pt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-ping"></span>
                <span>Active 24/7 for instant order dispatch & customer query</span>
            </p>
        </div>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('topbar_whatsapp_phone', '8801600793325')) }}" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/20 transition shrink-0 flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-base"></i>
            <span>Send Direct Message</span>
        </a>
    </div>

</div>
@endsection

