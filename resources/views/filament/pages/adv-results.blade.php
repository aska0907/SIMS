<x-filament::page>
    <div class="space-y-8 py-6">

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Advanced Level Report Filters</h2>
            {{ $this->form }}

            {{-- Export Button --}}
            @if ($this->getResults()->isNotEmpty())
                <div class="mt-4 flex justify-end">
                    <x-filament::button 
                        wire:click="exportPdf"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="bg-emerald-600 hover:bg-emerald-700"
                    >
                        <x-filament::icon icon="heroicon-o-document-arrow-down" class="h-5 w-5 mr-2" />
                        Export to PDF
                    </x-filament::button>
                </div>
            @endif
            @if ($this->getResults()->isNotEmpty())
    <div class="mt-4 flex justify-end">
        <x-filament::button 
            wire:click="exportReportBookPdf"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50 cursor-not-allowed"
            class="bg-green-600 hover:bg-green-700"
        >
            <x-filament::icon icon="heroicon-o-document-arrow-down" class="h-5 w-5 mr-2" />
            Export Report Book PDF
        </x-filament::button>
    </div>
@endif

        </div>
        

        @if ($this->getResults()->isNotEmpty())
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                {{-- Report Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Advanced Level Results: {{ $semester }}</h2>
                </div>
                
                {{-- Results Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Class</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Student</th>

                                {{-- Dynamic subjects --}}
                                @foreach(array_keys($this->getResults()->first()['grades']) as $subjectName)
                                    <th colspan="2" class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-l border-gray-200">
                                        {{ $subjectName }}
                                    </th>
                                @endforeach

                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-l border-gray-200">Points</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Division</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                @foreach(array_keys($this->getResults()->first()['grades']) as $subjectName)
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 border-l border-gray-200">Score</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Grade</th>
                                @endforeach
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($this->getResults() as $row)
                                <tr class="{{ $row['rank'] == 1 ? 'bg-yellow-50 font-semibold' : ($row['rank'] == 2 ? 'bg-gray-50 font-medium' : 'hover:bg-gray-50 transition-colors duration-150') }}">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $row['class'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $row['rank'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $row['student_name'] }}</td>

                                    {{-- Marks/Grades --}}
                                    @foreach(array_keys($row['grades']) as $subjectName)
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 border-l border-gray-200">
                                            {{ $row['marks'][$subjectName] ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-700 font-medium">
                                            {{ $row['grades'][$subjectName] ?? '-' }}
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 border-l border-gray-200">
                                        {{ $row['totalPoints'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
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
                
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500">Report generated on {{ now()->format('M d, Y') }}</p>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-200">
                <h3 class="mt-4 text-lg font-medium text-gray-900">No results to display</h3>
                <p class="mt-2 text-sm text-gray-500">Please select semester, class, and marks components to view results.</p>
            </div>
        @endif
        
    </div>
</x-filament::page>
