@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'التحليلات والإحصائيات' : 'Analytics & Stats')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#3D342A]">{{ app()->getLocale() === 'ar' ? 'التحليلات والإحصائيات العامة' : 'General Analytics & Stats' }}</h1>
        <p class="text-xs text-[#3D342A]/60 mt-1">{{ app()->getLocale() === 'ar' ? 'نظرة عامة على البيانات والأنشطة والمسوح في النظام' : 'Overview of system data, activities, and surveys' }}</p>
    </div>

    {{-- ============ STAT CARDS ============ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-cards.stat-card
            label="{{ app()->getLocale() === 'ar' ? 'إجمالي الردود على الاستبيانات' : 'Total Survey Responses' }}"
            :value="$responsesCount"
            icon="clipboard"
            :url="route('dashboard.surveys.index')"
        />
        <x-cards.stat-card
            label="{{ app()->getLocale() === 'ar' ? 'الاستبيانات المتاحة' : 'Available Surveys' }}"
            :value="$surveysCount"
            icon="clipboard"
            :url="route('dashboard.surveys.index')"
        />
        <x-cards.stat-card
            label="{{ app()->getLocale() === 'ar' ? 'إجمالي الأخبار والبرامج والمشاريع' : 'Total News, Programs & Projects' }}"
            :value="$newsCount + $programsCount + $projectsCount"
            icon="folder"
            :url="route('dashboard.projects.index')"
        />
        <x-cards.stat-card
            label="{{ app()->getLocale() === 'ar' ? 'المستخدمين المسجلين' : 'Registered Users' }}"
            :value="$usersCount"
            icon="users"
            :url="auth()->user()->hasRole('admin') ? route('dashboard.users.index') : null"
        />
    </div>

    {{-- ============ CHARTS SECTIONS ============ --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        
        {{-- Content Publishing Trend --}}
        <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-6 shadow-sm">
            <h3 class="text-sm font-bold text-[#3D342A] mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'معدل نشر المحتوى شهرياً' : 'Monthly Content Publication Rate' }}
            </h3>
            <div class="relative h-64">
                <canvas id="contentTrendChart"></canvas>
            </div>
        </div>

        {{-- Survey Responses Trend --}}
        <div class="rounded-2xl border border-[#B49C6E]/20 bg-background p-6 shadow-sm">
            <h3 class="text-sm font-bold text-[#3D342A] mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#A38B54]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12 0v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2v3a2 2 0 002 2h2a2 2 0 002-2zm0 0v-7a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z" />
                </svg>
                {{ app()->getLocale() === 'ar' ? 'الردود المستلمة شهرياً' : 'Monthly Survey Responses Received' }}
            </h3>
            <div class="relative h-64">
                <canvas id="responsesTrendChart"></canvas>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const months = @js($allMonths);
            const contentData = @js($allMonths->map(fn($m) => $contentTrend[$m] ?? 0));
            const responsesData = @js($allMonths->map(fn($m) => $responsesTrend[$m] ?? 0));

            // Content Trend Chart
            new Chart(document.getElementById('contentTrendChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "المحتوى المنشور" : "Published Content" }}',
                        data: contentData,
                        borderColor: '#A38B54',
                        backgroundColor: 'rgba(163, 139, 84, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // Responses Trend Chart
            new Chart(document.getElementById('responsesTrendChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: '{{ app()->getLocale() === "ar" ? "الردود المستلمة" : "Responses Received" }}',
                        data: responsesData,
                        backgroundColor: '#B49C6E',
                        borderColor: '#A38B54',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        });
    </script>
@endpush
