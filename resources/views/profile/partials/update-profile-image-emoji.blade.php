<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile / Avatar') }}
        </h2>
        <style>
            /* Main container to align switch and text */
            .custom-switch-container {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 15px;
            }

            /* Container for the custom switch */
            .custom-switch {
                position: relative;
                width: 50px;
                height: 28px;
                display: inline-block;
                margin-right: 10px;
            }

            /* Hide the default checkbox */
            .custom-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* Style the switch background */
            .custom-switch label {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: #ccc;
                border-radius: 34px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            /* Style the circle (the toggle button) */
            .custom-switch label:before {
                content: "";
                position: absolute;
                height: 22px;
                width: 22px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s ease;
            }

            /* Change background color when checkbox is checked */
            .custom-switch input:checked + label {
                background-color: #4CAF50;
            }

            /* Move the circle to the right when checked */
            .custom-switch input:checked + label:before {
                transform: translateX(22px);
            }

            /* Style the label text next to the switch */
            .switch-label {
                font-size: 16px;
                font-weight: 600;
                color: #333;
                margin-left: 5px;
                cursor: pointer;
            }

            /* Optional hover effects */
            .custom-switch input:hover + label {
                background-color: #bfbfbf;
            }
        </style>
    </header>

    <!-- Display current profile image or emoji -->
    <div id="avatar-container">
        @if ($user->is_profile)
            @if (isset($user->profile))
                <img src="{{$user->profile }}" alt="profile" class="w-32 h-32 rounded-full object-cover" id="profile-image">
            @endif
        @else
            <img src="{{ Storage::url('emojis/' . $avatar->profile) }}" alt="{{ $avatar->name }}" class="w-32 h-32 rounded-full object-cover" id="profile-image">
        @endif
    </div>

    <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-6 space-y-6" id="profile-form">
        @csrf
        @method('post')

        <div id="image-upload" style="{{ $user->is_profile ? '' : 'display: none;' }}">
            <x-input-label for="profile" :value="__('user_image')" />
            <input id="profile" name="profile" type="file" class="mt-1 block w-full" accept="image/*" value="{{ old('profile') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('profile')" />
        </div>

        <div id="emoji-selection" style="{{ $user->is_profile ? 'display: none;' : '' }}">
            @include('profile.partials.change-emoji')
        </div>

        <!-- Checkbox to choose between profile image or emoji -->
        <div class="custom-switch-container">
            <div class="custom-switch">
                <input type="checkbox" id="flexSwitchCheckDefault" name="use_profile" {{ $user->is_profile ? 'checked' : '' }}>
                <label for="flexSwitchCheckDefault"></label>
            </div>
            <label class="switch-label" for="flexSwitchCheckDefault">{{ __('do_u_want_to_use_profile_picture')}}</label>
        </div>

        <!-- Save button -->
        <div class="flex items-center gap-4" id="save-button" style="{{ $user->is_profile ? '' : 'display: none;' }}">
            <x-primary-button>{{ __('save') }}</x-primary-button>

            @if (session('status') === 'avatar-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('saved') }}</p>
            @endif
        </div>
    </form>
    <div id="loading-spinner" style="display: none;">
    <img src="spinner.gif" alt="Loading..."> <!-- Replace with your spinner image -->
</div>
</section>

<script>
    const checkbox = document.getElementById('flexSwitchCheckDefault');
    const imageUpload = document.getElementById('image-upload');
    const emojiSelection = document.getElementById('emoji-selection');
    const saveButton = document.getElementById('save-button');
    const profileImage = document.getElementById('profile-image');

    // Function to handle avatar update
    function updateAvatar(src, type) {
        profileImage.src = src;
        profileImage.alt = type === 'profile' ? 'profile' : 'emoji';
    }

    // Function to display a loading state
    function setLoadingState(isLoading) {
        if (isLoading) {
            // Optionally show a loading spinner or disable elements
            saveButton.style.display = 'none';
            checkbox.disabled = true;
        } else {
            // Hide loading spinner or re-enable elements
            checkbox.disabled = false;
        }
    }

    checkbox.addEventListener('change', async (e) => {
        const isProfile = e.target.checked;
        // Start loading state
        setLoadingState(true);

        try {
            // Send the profile status update to the server and await the response
            const response = await fetch('{{ route('update.profile.status') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_profile: isProfile ? 1 : 0 })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.success) {
                // Only now update the DOM after the response
                if (isProfile) {
                    imageUpload.style.display = 'block';
                    emojiSelection.style.display = 'none';
                    saveButton.style.display = 'flex';
                    updateAvatar('{{ $user->profile }}', 'profile');
                } else {
                    imageUpload.style.display = 'none';
                    emojiSelection.style.display = 'block';
                    saveButton.style.display = 'none';
                    updateAvatar('{{ Storage::url('emojis/' . $avatar->profile) }}', 'emoji'); // Update to show emoji
                }
            } else {
                console.error('Failed to update profile status');
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            // End loading state
            setLoadingState(false);
        }
    });
    
</script>
