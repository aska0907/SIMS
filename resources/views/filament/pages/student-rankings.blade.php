<x-filament::page>
    <div class="space-y-6">

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            {{ $this->form }}

            <div class="mt-6 flex justify-center">
                <x-filament::button 
                    wire:click="showRankings" 
                    size="lg" 
                    class="px-8 py-3"
                    icon="heroicon-o-trophy"
                >
                    Get Rankings
                </x-filament::button>
            </div>
        </div>

        <!-- Results Section -->
        @if ($this->results && count($this->results))
            <div class="space-y-8">
                @foreach ($this->results as $item)
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">

                        <!-- Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">
                                            {{ $item['class'] }} — {{ $item['subject'] }}
                                        </h3>
                                        <div class="flex items-center gap-4 mt-1">
                                            <span class="bg-blue-500 px-2 py-1 rounded text-xs font-medium text-black">
                                                📚 Class: {{ $item['class'] }}
                                            </span>
                                            <span class="bg-blue-500 px-2 py-1 rounded text-xs font-medium text-black">
                                                📖 Subject: {{ $item['subject'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-blue-500 px-4 py-2 rounded-full text-sm font-medium text-white">
                                    {{ ucfirst($this->assessment) }} Assessment
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="grid lg:grid-cols-2 gap-8">

                                <!-- Top 10 Students -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-green-700">Top 10 Students</h4>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach ($item['top'] as $index => $grade)
                                            <div class="flex items-center justify-between p-3 {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200' : 'bg-gray-50 border border-gray-200' }} rounded-lg">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 {{ $index < 3 ? 'bg-yellow-500 text-white' : 'bg-gray-400 text-white' }} rounded-full flex items-center justify-center font-bold text-sm">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $grade['student']['full_name'] ?? 'Unknown' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg font-bold {{ $index < 3 ? 'text-yellow-700' : 'text-gray-700' }}">
                                                        {{ $this->assessment === 'overall'
                                                            ? number_format(collect([$grade['test1'],$grade['test2'],$grade['mid_term'],$grade['terminal']])->filter()->avg(),1)
                                                            : $grade[$this->assessment] 
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Bottom 10 Students -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-red-700">Students Needing Support</h4>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach ($item['least'] as $index => $grade)
                                            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-red-400 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $grade['student']['full_name'] ?? 'Unknown' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg font-bold text-red-700">
                                                        {{ $this->assessment === 'overall'
                                                            ? number_format(collect([$grade['test1'],$grade['test2'],$grade['mid_term'],$grade['terminal']])->filter()->avg(),1)
                                                            : $grade[$this->assessment] 
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                            <!-- Statistics Footer -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">{{ count($item['top']) + count($item['least']) }}</div>
                                        <div class="text-sm text-blue-700">Total Students</div>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600">
                                            @if(count($item['top']) > 0)
                                                {{ $this->assessment === 'overall'
                                                    ? number_format(collect([$item['top'][0]['test1'],$item['top'][0]['test2'],$item['top'][0]['mid_term'],$item['top'][0]['terminal']])->filter()->avg(),1)
                                                    : $item['top'][0][$this->assessment] 
                                                }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                        <div class="text-sm text-green-700">Highest Score</div>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg">
                                        <div class="text-2xl font-bold text-red-600">
                                            @if(count($item['least']) > 0)
                                                {{ $this->assessment === 'overall'
                                                    ? number_format(collect([$item['least'][count($item['least'])-1]['test1'],$item['least'][count($item['least'])-1]['test2'],$item['least'][count($item['least'])-1]['mid_term'],$item['least'][count($item['least'])-1]['terminal']])->filter()->avg(),1)
                                                    : $item['least'][count($item['least'])-1][$this->assessment] 
                                                }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                        <div class="text-sm text-red-700">Lowest Score</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>


        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Rankings Available</h3>
                <p class="text-gray-600 mb-6">Fill out the form above and click "Get Rankings" to view student performance data.</p>
            </div>
        @endif

    </div>
</x-filament::page>
