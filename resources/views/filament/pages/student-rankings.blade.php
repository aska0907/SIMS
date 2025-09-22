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
                    Generate Analytics
                </x-filament::button>
            </div>
        </div>

        @if ($this->results && count($this->results))
            
            <!-- Overall Statistics Dashboard -->
            @if($this->overallStats)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    <!-- Class Performance Comparison -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Class Performance Ranking
                        </h3>
                        <div class="space-y-3">
                            @foreach($this->overallStats['class_comparisons']->take(5) as $class)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium">{{ $class['class'] }}</span>
                                        <div class="text-sm text-gray-600">{{ $class['total_students'] }} students</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">{{ $class['average'] }}%</div>
                                        <div class="text-sm text-green-600">{{ $class['pass_rate'] }}% pass</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Subject Performance Comparison -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Subject Performance Ranking
                        </h3>
                        <div class="space-y-3">
                            @foreach($this->overallStats['subject_comparisons']->take(5) as $subject)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium">{{ $subject['subject'] }}</span>
                                        <div class="text-sm text-gray-600">{{ $subject['total_students'] }} students</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">{{ $subject['average'] }}%</div>
                                        <div class="text-sm text-green-600">{{ $subject['pass_rate'] }}% pass</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Key Metrics Summary -->
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Overall Metrics
                        </h3>
                        <div class="space-y-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $this->overallStats['total_students_analyzed'] }}</div>
                                <div class="text-sm text-blue-700">Total Students</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $this->overallStats['total_records'] }}</div>
                                <div class="text-sm text-green-700">Records Analyzed</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">{{ count($this->overallStats['class_comparisons']) }}</div>
                                <div class="text-sm text-yellow-700">Classes</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Detailed Subject Analysis -->
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
                                                📚 {{ $item['class'] }}
                                            </span>
                                            <span class="bg-blue-500 px-2 py-1 rounded text-xs font-medium text-black">
                                                📖 {{ $item['subject'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-blue-500 px-4 py-2 rounded-full text-sm font-medium text-white">
                                    {{ ucfirst($this->assessment) }} Assessment
                                </div>
                            </div>
                        </div>

                        <!-- Comprehensive Statistics -->
                        <div class="p-6 border-b border-gray-200 bg-gray-50">
                            <h4 class="text-lg font-semibold mb-4">Statistical Analysis</h4>
                            
                            <!-- Basic Stats -->
                           <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-blue-600">{{ $item['stats']['total_students'] }}</div>
                                    <div class="text-xs text-gray-600">Students</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-green-600">{{ $item['stats']['average'] }}%</div>
                                    <div class="text-xs text-gray-600">Average</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-yellow-600">{{ $item['stats']['median'] }}%</div>
                                    <div class="text-xs text-gray-600">Median</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-purple-600">{{ $item['stats']['standard_deviation'] }}</div>
                                    <div class="text-xs text-gray-600">Std Dev</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-green-600">{{ $item['stats']['pass_rate'] }}%</div>
                                    <div class="text-xs text-gray-600">Pass Rate</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-red-600">{{ $item['stats']['fail_rate'] }}%</div>
                                    <div class="text-xs text-gray-600">Fail Rate</div>
                                </div>
                            </div>

                            <!-- Performance Categories -->
                            <div class="mb-6 mt-6">
                                <h5 class="font-medium mb-3">Performance Distribution</h5>
                                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
                                    @foreach($item['stats']['performance_categories'] as $category => $data)
                                        <div class="text-center p-3 bg-white shadow-sm rounded-lg {{ $category === 'excellent' ? 'bg-green-100 text-green-800' : ($category === 'failing' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            <div class="font-bold">{{ $data['count'] }}</div>
                                            <div class="text-xs">{{ ucfirst(str_replace('_', ' ', $category)) }}</div>
                                            <div class="text-xs opacity-75">{{ $data['percentage'] }}%</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Grade Distribution -->
                            <div class="mb-6">
                                <h5 class="font-medium mb-3">Grade Distribution</h5>
                                 <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-6">
                                    @foreach($item['stats']['grade_distribution'] as $grade => $count)
                                        <div class="text-center p-2 bg-white rounded border">
                                            <div class="font-bold text-sm">{{ $count }}</div>
                                            <div class="text-xs text-gray-600">{{ $grade }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Quartiles -->
                            <!-- <div>
                                <h5 class="font-medium mb-3">Quartile Analysis</h5>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-lg font-bold text-blue-600">{{ $item['stats']['quartiles']['Q1'] }}%</div>
                                        <div class="text-xs text-gray-600">Q1 (25th percentile)</div>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-lg font-bold text-green-600">{{ $item['stats']['quartiles']['Q2'] }}%</div>
                                        <div class="text-xs text-gray-600">Q2 (Median)</div>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-lg font-bold text-purple-600">{{ $item['stats']['quartiles']['Q3'] }}%</div>
                                        <div class="text-xs text-gray-600">Q3 (75th percentile)</div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Student Rankings -->
                        <div class="p-6">
                            <div class="grid lg:grid-cols-2 gap-8">

                                <!-- Top Performers -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-green-700">Top {{ count($item['top']) }} Students</h4>
                                    </div>

                                    @if(count($item['top']) > 0)
                                        <div class="space-y-2">
                                            @foreach ($item['top'] as $index => $student)
                                                <div class="flex items-center justify-between p-3 {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200' : 'bg-gray-50 border border-gray-200' }} rounded-lg">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 {{ $index < 3 ? 'bg-yellow-500 text-white' : 'bg-gray-400 text-white' }} rounded-full flex items-center justify-center font-bold text-sm">
                                                            {{ $index + 1 }}
                                                        </div>
                                                        <span class="font-medium text-gray-900">
                                                            {{ $student['student_name'] }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-lg font-bold {{ $index < 3 ? 'text-yellow-700' : 'text-gray-700' }}">
                                                            {{ $student['score'] }}%
                                                        </span>
                                                        @if($index === 0)
                                                            <span class="text-xs bg-gold text-white px-2 py-1 rounded">🏆 Best</span>
                                                        @elseif($index === 1)
                                                            <span class="text-xs bg-gray-400 text-white px-2 py-1 rounded">🥈 2nd</span>
                                                        @elseif($index === 2)
                                                            <span class="text-xs bg-orange-400 text-white px-2 py-1 rounded">🥉 3rd</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No students found</p>
                                    @endif
                                </div>

                                <!-- Students Needing Support -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-red-700">Students Needing Support ({{ count($item['bottom']) }})</h4>
                                    </div>

                                    @if(count($item['bottom']) > 0)
                                        <div class="space-y-2">
                                            @foreach ($item['bottom'] as $index => $student)
                                                <div class="flex items-center justify-between p-3 {{ $student['score'] < 50 ? 'bg-red-100 border border-red-300' : 'bg-orange-50 border border-orange-200' }} rounded-lg">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 {{ $student['score'] < 50 ? 'bg-red-500' : 'bg-orange-400' }} text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                            {{ $index + 1 }}
                                                        </div>
                                                        <span class="font-medium text-gray-900">
                                                            {{ $student['student_name'] }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-lg font-bold {{ $student['score'] < 50 ? 'text-red-700' : 'text-orange-700' }}">
                                                            {{ $student['score'] }}%
                                                        </span>
                                                        @if($student['score'] < 40)
                                                            <span class="text-xs bg-red-600 text-white px-2 py-1 rounded">🚨 Critical</span>
                                                        @elseif($student['score'] < 50)
                                                            <span class="text-xs bg-orange-600 text-white px-2 py-1 rounded">⚠️ At Risk</span>
                                                        @else
                                                            <span class="text-xs bg-yellow-600 text-white px-2 py-1 rounded">📈 Needs Help</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No students found</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Advanced Analytics Section -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-lg font-semibold mb-4 text-gray-900">Advanced Analytics</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Score Range -->
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg">
                                        <h5 class="font-medium text-blue-900 mb-2">Score Range</h5>
                                        <div class="text-2xl font-bold text-blue-700">{{ $item['stats']['range'] }}</div>
                                        <div class="text-sm text-blue-600">{{ $item['stats']['min'] }}% - {{ $item['stats']['max'] }}%</div>
                                    </div>
                                    
                                    <!-- Variance -->
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg">
                                        <h5 class="font-medium text-purple-900 mb-2">Variance</h5>
                                        <div class="text-2xl font-bold text-purple-700">{{ $item['stats']['variance'] }}</div>
                                        <div class="text-sm text-purple-600">Score variability</div>
                                    </div>
                                    
                                    <!-- Mode -->
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg">
                                        <h5 class="font-medium text-green-900 mb-2">Most Common Score</h5>
                                        <div class="text-2xl font-bold text-green-700">
                                            @if(is_array($item['stats']['mode']))
                                                {{ implode(', ', $item['stats']['mode']) }}
                                            @else
                                                {{ $item['stats']['mode'] }}
                                            @endif
                                        </div>
                                        <div class="text-sm text-green-600">Mode value(s)</div>
                                    </div>
                                    
                                    <!-- Performance Indicator -->
                                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg">
                                        <h5 class="font-medium text-yellow-900 mb-2">Class Health</h5>
                                        <div class="text-2xl font-bold {{ $item['stats']['pass_rate'] > 80 ? 'text-green-600' : ($item['stats']['pass_rate'] > 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            @if($item['stats']['pass_rate'] > 80) Excellent
                                            @elseif($item['stats']['pass_rate'] > 60) Good
                                            @elseif($item['stats']['pass_rate'] > 40) Fair
                                            @else Needs Attention
                                            @endif
                                        </div>
                                        <div class="text-sm text-yellow-600">Overall assessment</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recommendations -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h5 class="font-medium mb-3 text-gray-900">📋 Recommendations</h5>
                                <div class="space-y-2">
                                    @if($item['stats']['fail_rate'] > 30)
                                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <span class="text-red-600">🚨</span>
                                                <span class="text-red-800 font-medium">High Failure Rate Alert</span>
                                            </div>
                                            <p class="text-red-700 text-sm mt-1">Consider additional tutoring sessions or curriculum review. {{ $item['stats']['performance_categories']['failing']['count'] }} students are failing.</p>
                                        </div>
                                    @endif
                                    
                                    @if($item['stats']['standard_deviation'] > 20)
                                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <span class="text-yellow-600">⚠️</span>
                                                <span class="text-yellow-800 font-medium">High Score Variance</span>
                                            </div>
                                            <p class="text-yellow-700 text-sm mt-1">Students show wide performance gaps. Consider differentiated instruction approaches.</p>
                                        </div>
                                    @endif
                                    
                                    @if($item['stats']['average'] > 85)
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <span class="text-green-600">🏆</span>
                                                <span class="text-green-800 font-medium">Excellent Performance</span>
                                            </div>
                                            <p class="text-green-700 text-sm mt-1">Outstanding class performance! Consider advanced enrichment activities.</p>
                                        </div>
                                    @elseif($item['stats']['average'] < 60)
                                        <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <span class="text-orange-600">📚</span>
                                                <span class="text-orange-800 font-medium">Below Average Performance</span>
                                            </div>
                                            <p class="text-orange-700 text-sm mt-1">Class average is below expectations. Review teaching methods and provide additional support.</p>
                                        </div>
                                    @endif
                                    
                                    @if($item['stats']['performance_categories']['excellent']['count'] > 0)
                                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <span class="text-blue-600">⭐</span>
                                                <span class="text-blue-800 font-medium">Top Performers Recognition</span>
                                            </div>
                                            <p class="text-blue-700 text-sm mt-1">{{ $item['stats']['performance_categories']['excellent']['count'] }} students achieved excellent grades. Consider peer tutoring opportunities.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Overall Insights -->
            @if($this->overallStats && isset($this->overallStats['trend_analysis']))
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        Assessment Trends Analysis
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($this->overallStats['trend_analysis'] as $assessment => $data)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">{{ ucfirst(str_replace('_', ' ', $assessment)) }}</h4>
                                <div class="text-2xl font-bold text-gray-700 mb-1">{{ $data['average'] }}%</div>
                                <div class="text-sm text-gray-600">{{ $data['count'] }} students</div>
                                <div class="mt-2 text-xs text-gray-500">
                                    Room for improvement: {{ number_format($data['improvement_potential'], 1) }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Analytics Available</h3>
                <p class="text-gray-600 mb-6">Fill out the form above and click "Generate Analytics" to view comprehensive performance data and insights.</p>
                <div class="text-sm text-gray-500">
                    <p>📊 Statistical analysis • 🏆 Student rankings • 📈 Performance trends</p>
                </div>
            </div>
        @endif

    </div>
</x-filament::page>