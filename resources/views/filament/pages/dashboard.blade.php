<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Critical Alerts Section --}}
        @if($criticalAlerts && $criticalAlerts->isNotEmpty())
            <div class="bg-black-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-black mb-3">Critical Alerts</h3>
                <div class="space-y-2">
                    @foreach($criticalAlerts as $alert)
                        <div class="flex items-center justify-between bg-white rounded-md p-3 border-l-4 
                            {{ $alert['type'] === 'urgent' ? 'border-red-500' : ($alert['type'] === 'critical' ? 'border-orange-500' : 'border-yellow-500') }}">
                            <div>
                                <p class="font-medium text-black">{{ $alert['title'] }}</p>
                                <p class="text-sm text-black">{{ $alert['message'] }}</p>
                            </div>
                            @if($alert['action_required'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-black">
                                    Action Required
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Overall Statistics Cards --}}
        @if(!empty($overallStats))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-black">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Total Students</p>
                            <p class="text-black font-bold">{{ $overallStats['total_students'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-black">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Overall Average</p>
                            <p class="text-3xl font-bold">{{ $overallStats['overall_average'] }}%</p>
                        </div>
                        <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-black">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-medium">Pass Rate</p>
                            <p class="text-3xl font-bold">{{ $overallStats['pass_rate'] }}%</p>
                        </div>
                        <div class="p-3 bg-yellow-400 bg-opacity-30 rounded-full">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-black">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Excellence Rate</p>
                            <p class="text-3xl font-bold">{{ $overallStats['excellence_rate'] }}%</p>
                        </div>
                        <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

           {{-- Grade Distribution Chart --}}
        @if(!empty($overallStats['grade_distribution']))
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Grade Distribution</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach($overallStats['grade_distribution'] as $grade => $data)
                            <div class="text-center p-4 border rounded-lg 
                                {{ $grade === 'A' ? 'border-green-200 bg-green-50' : 
                                   ($grade === 'B' ? 'border-blue-200 bg-blue-50' : 
                                   ($grade === 'C' ? 'border-yellow-200 bg-yellow-50' : 
                                   ($grade === 'D' ? 'border-orange-200 bg-orange-50' : 
                                   ($grade === 'E' ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50')))) }}">
                                <div class="text-2xl font-bold 
                                    {{ $grade === 'A' ? 'text-green-600' : 
                                       ($grade === 'B' ? 'text-blue-600' : 
                                       ($grade === 'C' ? 'text-yellow-600' : 
                                       ($grade === 'D' ? 'text-orange-600' : 
                                       ($grade === 'E' ? 'text-red-600' : 'text-gray-600')))) }}">
                                    {{ $grade }}
                                </div>
                                <div class="text-sm text-gray-600">{{ $data['count'] }}</div>
                                <div class="text-xs text-gray-500">{{ $data['percentage'] }}%</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        {{-- Class Performance Overview --}}
        @if($classPerformance && $classPerformance->isNotEmpty())
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Class Performance Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($classPerformance as $class)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $class['class_name'] }}</h4>
                                    <span class="text-sm text-gray-500">{{ $class['total_students'] }} students</span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Average:</span>
                                        <span class="text-sm font-semibold 
                                            {{ $class['average_score'] >= 70 ? 'text-green-600' : ($class['average_score'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $class['average_score'] }}%
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Pass Rate:</span>
                                        <span class="text-sm font-semibold">{{ $class['pass_rate'] }}%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Performance:</span>
                                        <span class="text-sm font-semibold text-blue-600">{{ $class['performance_level'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        
        {{-- Subject Performance Overview --}}
        @if($subjectPerformance && $subjectPerformance->isNotEmpty())
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Subject Performance Overview</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pass Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Excellence Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subjectPerformance->take(10) as $subject)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $subject['subject_name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subject['total_students'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold 
                                                {{ $subject['average_score'] >= 70 ? 'text-green-600' : ($subject['average_score'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $subject['average_score'] }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subject['pass_rate'] }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subject['excellence_rate'] }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($subject['needs_attention'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Needs Attention
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $subject['performance_trend'] === 'positive' ? 'bg-green-100 text-green-800' : 
                                                       ($subject['performance_trend'] === 'stable' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($subject['performance_trend']) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

     

        {{-- Top and Bottom Students Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Best Students --}}
            @if($overallBestStudents && $overallBestStudents->isNotEmpty())
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Top Performing Students
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($overallBestStudents->take(5) as $index => $student)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $student['student_name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $student['class'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-green-600">{{ $student['average_score'] }}%</p>
                                        <p class="text-xs text-gray-500">Grade {{ $student['grade_letter'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Students Needing Attention --}}
            @if($overallWorstStudents && $overallWorstStudents->isNotEmpty())
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
                            </svg>
                            Students Needing Support
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($overallWorstStudents->take(5) as $student)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $student['student_name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $student['class'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-red-600">{{ $student['average_score'] }}%</p>
                                        <p class="text-xs text-gray-500">{{ $student['performance_category'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>


        {{-- Refresh Button --}}
        <div class="flex justify-end">
            <button 
                wire:click="refreshDashboard"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Dashboard
            </button>
        </div>
    </div>
</x-filament-panels::page>