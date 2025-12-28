<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-blue-800">Create Account</h2>
            <p class="text-blue-600 mt-1">Register to continue</p>
        </div>

        <div class="space-y-4">
            <!-- Name Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="first_name" value="First Name" />
                    <x-text-input 
                        id="first_name" 
                        class="w-full mt-1 border-blue-200" 
                        type="text" 
                        name="first_name" 
                        value="{{ old('first_name') }}" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="last_name" value="Last Name" />
                    <x-text-input 
                        id="last_name" 
                        class="w-full mt-1 border-blue-200" 
                        type="text" 
                        name="last_name" 
                        value="{{ old('last_name') }}" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                </div>
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input 
                    id="email" 
                    class="w-full mt-1 border-blue-200" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- DOB & Gender Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="date_of_birth" value="Date of Birth" />
                    <x-text-input 
                        id="date_of_birth" 
                        class="w-full mt-1 border-blue-200" 
                        type="date" 
                        name="date_of_birth" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="gender" value="Gender" />
                    <select 
                        name="gender" 
                        class="w-full mt-1 border-blue-200 rounded-md"
                    >
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                </div>
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" value="Address" />
                <textarea 
                    name="address" 
                    class="w-full mt-1 border-blue-200 rounded-md" 
                    rows="2"
                    required
                >{{ old('address') }}</textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>

            <!-- Phone -->
            <div>
                <x-input-label for="phone" value="Phone (Optional)" />
                <x-text-input 
                    id="phone" 
                    class="w-full mt-1 border-blue-200" 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone') }}"
                />
            </div>

            <!-- Password Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input 
                        id="password" 
                        class="w-full mt-1 border-blue-200" 
                        type="password" 
                        name="password" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirm Password" />
                    <x-text-input 
                        id="password_confirmation" 
                        class="w-full mt-1 border-blue-200" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                    />
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button 
                type="submit" 
                class="w-full py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
                Register
            </button>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-blue-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-700 hover:text-blue-800 ml-1">
                    Login here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>