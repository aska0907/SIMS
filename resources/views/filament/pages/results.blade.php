<x-filament::page>
    <div class="space-y-8 py-6">

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Report Filters</h2>
            {{ $this->form }}

            {{-- New Export Button --}}
            @if ($this->getResults()->isNotEmpty())
                <div class="mt-4 flex justify-end">
                    <x-filament::button 
                        wire:click="exportPdf"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-blue-600 hover:bg-blue-700"
                    >
                        <x-filament::icon icon="heroicon-o-document-arrow-down" class="h-5 w-5 mr-2" />
                        Export to PDF
                    </x-filament::button>
                </div>

                <x-filament::button 
    wire:click="exportReportBook"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-50 cursor-not-allowed"
    class="ml-2 bg-green-600 hover:bg-green-700"
>
    <x-filament::icon icon="heroicon-o-book-open" class="h-5 w-5 mr-2" />
    Export Report Book
</x-filament::button>

            @endif
            
        </div>
        

        @if ($this->getResults()->isNotEmpty())
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                {{-- Report Header --}}
                <div class="bg-primary-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Academic Results: {{ $this->semester }}</h2>
                </div>
                
                {{-- Results Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Class</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Student</th>

                                {{-- Dynamic subjects header (only assigned subjects) --}}
                                @foreach(array_keys($this->getResults()->first()['grades']) as $subjectName)
                                    <th colspan="2" class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-l border-gray-200">
                                        {{ $subjectName }}
                                    </th>
                                @endforeach

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-l border-gray-200">Points (Best 7)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Division</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                @foreach(array_keys($this->getResults()->first()['grades']) as $subjectName)
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-l border-gray-200">Score</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                @endforeach
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($this->getResults() as $row)
                                <tr class="{{ $row['rank'] == 1 ? 'bg-yellow-50 font-semibold' : ($row['rank'] == 2 ? 'bg-gray-50 font-medium' : 'hover:bg-gray-50 transition-colors duration-150') }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row['class'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        @if($row['rank'] == 1)
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-yellow-500 text-white">{{ $row['rank'] }}</span>
                                        @elseif($row['rank'] == 2)
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-400 text-white">{{ $row['rank'] }}</span>
                                        @else
                                            {{ $row['rank'] }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row['student_name'] }}</td>

                                    {{-- Dynamic subjects marks/grades per student --}}
                                    @foreach(array_keys($row['grades']) as $subjectName)
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700 border-l border-gray-200">
                                            {{ $row['marks'][$subjectName] ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700 font-medium">
                                            {{ $row['grades'][$subjectName] ?? '-' }}
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 border-l border-gray-200">
                                        {{ $row['totalPoints'] }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="px-2 py-1 rounded-full text-xs 
                                            {{ $row['division'] == 'Division I' ? 'bg-green-100 text-green-800' : 
                                               ($row['division'] == 'Division II' ? 'bg-blue-100 text-blue-800' : 
                                               ($row['division'] == 'Division III' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ $row['division'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Table Footer --}}
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500">Report generated on {{ now()->format('M d, Y') }}</p>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-200">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No results to display</h3>
                <p class="mt-2 text-sm text-gray-500">Please select a semester and marks components to view results.</p>
            </div>
        @endif
        
    </div>
</x-filament::page>