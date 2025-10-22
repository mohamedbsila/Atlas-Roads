<div>
    <div class="container mx-auto max-w-md py-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>
        
        <form wire:submit.prevent="resetPassword" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" id="email" wire:model="email" class="w-full border rounded px-3 py-2" readonly />
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">New Password</label>
                <input type="password" id="password" wire:model="password" class="w-full border rounded px-3 py-2" required />
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="passwordConfirmation" class="block text-sm font-medium">Confirm Password</label>
                <input type="password" id="passwordConfirmation" wire:model="passwordConfirmation" class="w-full border rounded px-3 py-2" required />
                @error('passwordConfirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Reset Password
            </button>
        </form>

        @if (session('status'))
            <div class="mt-4 text-green-600 text-center">{{ session('status') }}</div>
        @endif

        @if (session('email'))
            <div class="mt-4 text-red-600 text-center">{{ session('email') }}</div>
        @endif
    </div>
</div>
