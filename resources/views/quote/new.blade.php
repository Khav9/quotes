<x-app-layout>
    <div class="flex justify-center items-center min-h-[80vh] bg-yellow-100">
        <div class="relative w-full max-w-2xl min-h-[5vh] md:min-h-[50vh] p-8 bg-white rounded-lg shadow-lg text-center">
            <!-- Quote Creation Form -->
            <div class="mt-4">
                <form method="POST" action="{{ route('quote.store') }}">
                    @csrf <!-- Add CSRF protection for Laravel -->
                    
                    <!-- Quote Text Input -->
                    <div class="mb-4">
                        <label for="text" class="block text-sm font-semibold text-gray-800 mb-2">Quote</label>
                        <textarea id="text" name="text" class="w-full p-2 border rounded-lg" rows="4" placeholder="Enter the quote...">{{ old('text') }}</textarea>
                    </div>
                    
                    <!-- Name Input -->
                    <div class="mb-4">
                        <label for="credit_to" class="block text-sm font-semibold text-gray-800 mb-2">Credit To</label>
                        <input type="text" id="credit_to" name="credit_to" value="{{ old('credit_to') }}" class="w-full p-2 border rounded-lg" placeholder="Enter the name...(Optional)">
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <a href="{{route('dashboard')}}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Cancel</a>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Create Quote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
