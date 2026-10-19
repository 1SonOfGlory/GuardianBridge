<x-app-layout x-data="{ openCreateModal: false }">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-brand-navy leading-tight">
                {{ __('GuardianBridge Dashboard') }}
            </h2>
            <button @click="openCreateModal = true" class="px-4 py-2 bg-brand-sky hover:bg-brand-navy transition duration-200 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg focus:ring-2 focus:ring-brand-sky focus:outline-none">
                + New Case
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-brand-offwhite min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-sky">
                    <p class="text-sm text-gray-500 font-medium">Active Investigations</p>
                    <p class="text-3xl font-bold text-brand-navy mt-2">{{ $cases->where('state', 'Investigating')->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-sage">
                    <p class="text-sm text-gray-500 font-medium">Cases Secured</p>
                    <p class="text-3xl font-bold text-brand-navy mt-2">{{ $cases->where('state', 'Secured')->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-crimson">
                    <p class="text-sm text-gray-500 font-medium">Newly Received</p>
                    <p class="text-3xl font-bold text-brand-navy mt-2">{{ $cases->where('state', 'Received')->count() }}</p>
                </div>
            </div>

            <!-- Case Table -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-brand-charcoal">Recent Cases</h3>
                    <div class="text-sm text-gray-400">Restricted Access (District Scope Active)</div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-semibold">Case ID</th>
                                <th class="p-4 font-semibold">Minor Name</th>
                                <th class="p-4 font-semibold">State</th>
                                <th class="p-4 font-semibold">Last Updated</th>
                                <th class="p-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($cases as $case)
                                <tr class="hover:bg-brand-sky/5 transition duration-150">
                                    <td class="p-4 font-mono text-xs text-gray-600">{{ substr($case->id, 0, 8) }}...</td>
                                    <td class="p-4 font-medium text-brand-navy">{{ $case->minor_name }}</td>
                                    <td class="p-4">
                                        @if($case->state === 'Received')
                                            <span class="px-2.5 py-1 bg-brand-crimson/10 text-brand-crimson rounded-full text-xs font-semibold">Received</span>
                                        @elseif($case->state === 'Investigating')
                                            <span class="px-2.5 py-1 bg-brand-sky/10 text-brand-sky rounded-full text-xs font-semibold">Investigating</span>
                                        @elseif($case->state === 'Secured')
                                            <span class="px-2.5 py-1 bg-brand-sage/10 text-brand-sage rounded-full text-xs font-semibold">Secured</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">{{ $case->state }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-500">{{ $case->updated_at->diffForHumans() }}</td>
                                    <td class="p-4 text-right">
                                        <a href="#" class="text-brand-sky hover:text-brand-navy font-medium text-sm transition">View Details &rarr;</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        No cases registered in your district.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Case Modal -->
    <div x-show="openCreateModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="openCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openCreateModal" @click.away="openCreateModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form action="/cases" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-brand-sky/10 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-brand-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-brand-navy" id="modal-title">Register New Case</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="minor_name" class="block text-sm font-medium text-gray-700">Minor's Name (Will be encrypted)</label>
                                        <input type="text" name="minor_name" id="minor_name" required class="mt-1 focus:ring-brand-sky focus:border-brand-sky block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number (Will be encrypted)</label>
                                        <input type="text" name="phone_number" id="phone_number" class="mt-1 focus:ring-brand-sky focus:border-brand-sky block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label for="family_details" class="block text-sm font-medium text-gray-700">Family Details (Will be encrypted)</label>
                                        <textarea name="family_details" id="family_details" rows="3" class="mt-1 focus:ring-brand-sky focus:border-brand-sky block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-sky text-base font-medium text-white hover:bg-brand-navy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                            Secure Case
                        </button>
                        <button type="button" @click="openCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
