@extends('layouts.user')

@section('title', 'Address - My Account - DigiGo')
@section('breadcrumb_current', 'Address')

@section('account_content')
<div class="content-card-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">
            <i class="fa-regular fa-map" style="color: var(--theme-accent);"></i> My Address
        </h2>
        @if($address)
            <button type="button" onclick="toggleAddressForm()" class="btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem; border-radius: 6px;">
                <i class="fa-solid fa-pen-to-square"></i> <span id="editBtnText">Edit Address</span>
            </button>
        @endif
    </div>

    <p style="color: #64748b; font-size: 0.925rem; margin-bottom: 1.75rem;">
        Your primary delivery address used across all your orders and checkout.
    </p>

    <!-- Address View (When address exists) -->
    @if($address)
        <div id="addressCard" style="background: #f8fafc; border: 1px solid var(--account-border); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.75rem;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #eff6ff; color: var(--theme-accent); display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 1.05rem;">{{ $address->full_name }}</div>
                        <div style="font-size: 0.825rem; color: #64748b;">
                            <i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i> {{ $address->phone }}
                        </div>
                    </div>
                </div>
                <span style="background: #ecfdf5; color: #047857; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 9999px; text-transform: uppercase;">
                    Default Address
                </span>
            </div>

            <div style="border-top: 1px dashed #e2e8f0; padding-top: 1rem; font-size: 0.925rem; color: #334155; line-height: 1.6;">
                <p style="margin: 0 0 0.4rem 0;">
                    <strong>Street Address:</strong> {{ $address->street_address }}
                </p>
                <p style="margin: 0; color: #64748b;">
                    <span><strong>District:</strong> {{ $address->district }}</span> &bull; 
                    <span><strong>Division:</strong> {{ $address->division }}</span>
                    @if($address->postal_code)
                        &bull; <span><strong>Postal Code:</strong> {{ $address->postal_code }}</span>
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Address Form (Shown always if no address, or toggled via Edit button) -->
    <div id="addressFormWrapper" style="{{ $address ? 'display: none;' : '' }}">
        <div style="background: #ffffff; border: 1px solid var(--account-border); border-radius: 10px; padding: 1.5rem;">
            <h3 style="margin-top: 0; margin-bottom: 1.25rem; font-size: 1.05rem; font-weight: 700; color: #0f172a;">
                {{ $address ? 'Update Address Details' : 'Add Your Address' }}
            </h3>

            <form action="{{ route('user.addresses.update') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name" class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                        <input type="text"
                               name="full_name"
                               id="full_name"
                               class="form-input @error('full_name') is-invalid @enderror"
                               value="{{ old('full_name', $address->full_name ?? $user->name) }}"
                               required>
                        @error('full_name')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number <span style="color: #ef4444;">*</span></label>
                        <input type="tel"
                               name="phone"
                               id="phone"
                               class="form-input @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $address->phone ?? $user->phone) }}"
                               placeholder="017XXXXXXXX"
                               required>
                        @error('phone')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="division" class="form-label">Division <span style="color: #ef4444;">*</span></label>
                        <select name="division" id="division" class="form-input @error('division') is-invalid @enderror" onchange="handleDivisionChange()" required>
                            @php
                                $selectedDivision = old('division', $address->division ?? 'Dhaka');
                                $divisions = ['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'];
                            @endphp
                            @foreach($divisions as $div)
                                <option value="{{ $div }}" {{ $selectedDivision === $div ? 'selected' : '' }}>{{ $div }}</option>
                            @endforeach
                        </select>
                        @error('division')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="district" class="form-label">District <span style="color: #ef4444;">*</span></label>
                        <select name="district" id="district" class="form-input @error('district') is-invalid @enderror" required>
                            <!-- Populated dynamically via JavaScript -->
                        </select>
                        @error('district')
                            <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="street_address" class="form-label">Full Street Address <span style="color: #ef4444;">*</span></label>
                    <textarea name="street_address"
                              id="street_address"
                              rows="3"
                              class="form-input @error('street_address') is-invalid @enderror"
                              placeholder="House / Flat / Road / Area (e.g., House 12, Road 4, Dhanmondi)"
                              required>{{ old('street_address', $address->street_address ?? '') }}</textarea>
                    @error('street_address')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="postal_code" class="form-label">Postal / ZIP Code (Optional)</label>
                    <input type="text"
                           name="postal_code"
                           id="postal_code"
                           class="form-input @error('postal_code') is-invalid @enderror"
                           value="{{ old('postal_code', $address->postal_code ?? '') }}"
                           placeholder="e.g. 1205">
                    @error('postal_code')
                        <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; align-items: center;">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Save Address
                    </button>
                    @if($address)
                        <button type="button" onclick="toggleAddressForm()" class="btn-secondary" style="padding: 0.65rem 1.25rem;">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const bangladeshDistricts = {
        'Dhaka': ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Faridpur', 'Manikganj', 'Munshiganj', 'Narsingdi', 'Kishoreganj', 'Gopalganj', 'Madaripur', 'Rajbari', 'Shariatpur'],
        'Chattogram': ['Chattogram', "Cox's Bazar", 'Cumilla', 'Feni', 'Brahmanbaria', 'Rangamati', 'Noakhali', 'Chandpur', 'Lakshmipur', 'Khagrachhari', 'Bandarban'],
        'Rajshahi': ['Rajshahi', 'Bogura', 'Pabna', 'Sirajganj', 'Naogaon', 'Natore', 'Chapai Nawabganj', 'Joypurhat'],
        'Khulna': ['Khulna', 'Jashore', 'Kushtia', 'Satkhira', 'Jhenaidah', 'Bagerhat', 'Chuadanga', 'Magura', 'Meherpur', 'Narail'],
        'Barishal': ['Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Barguna', 'Jhalokati'],
        'Sylhet': ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
        'Rangpur': ['Rangpur', 'Dinajpur', 'Kurigram', 'Gaibandha', 'Nilphamari', 'Panchagarh', 'Thakurgaon', 'Lalmonirhat'],
        'Mymensingh': ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
    };

    const currentDistrict = "{{ old('district', $address->district ?? 'Dhaka') }}";

    function populateDistricts(selectedDivision) {
        const districtSelect = document.getElementById('district');
        if (!districtSelect) return;

        districtSelect.innerHTML = '';
        const list = bangladeshDistricts[selectedDivision] || ['Dhaka'];

        list.forEach(district => {
            const opt = document.createElement('option');
            opt.value = district;
            opt.textContent = district;
            if (district === currentDistrict) {
                opt.selected = true;
            }
            districtSelect.appendChild(opt);
        });
    }

    function handleDivisionChange() {
        const divisionSelect = document.getElementById('division');
        populateDistricts(divisionSelect.value);
    }

    function toggleAddressForm() {
        const formWrapper = document.getElementById('addressFormWrapper');
        const card = document.getElementById('addressCard');
        const btnText = document.getElementById('editBtnText');

        if (!formWrapper) return;

        if (formWrapper.style.display === 'none' || formWrapper.style.display === '') {
            formWrapper.style.display = 'block';
            if (card) card.style.display = 'none';
            if (btnText) btnText.textContent = 'Close Editor';
        } else {
            formWrapper.style.display = 'none';
            if (card) card.style.display = 'block';
            if (btnText) btnText.textContent = 'Edit Address';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const divisionSelect = document.getElementById('division');
        if (divisionSelect) {
            populateDistricts(divisionSelect.value);
        }
    });
</script>
@endsection
