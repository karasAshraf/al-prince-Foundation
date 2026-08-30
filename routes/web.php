<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\ActivityFrontController;
use App\Http\Controllers\IndustryFrontController;
use App\Http\Controllers\SolutionFrontController;
use App\Http\Controllers\MediaLibraryFrontController;
use App\Http\Controllers\EventFrontController;
use App\Http\Controllers\AdvertisingCenterFrontController;
use App\Http\Controllers\ContentServicesFrontController;
use App\Http\Controllers\Dashboard\{
    NewsController,
    ProgramController,
    ProjectController,
    ServiceController,
    TeamMemberController,
    GovernanceDocumentController,
    HomePageSectionController,
    AboutSectionController,
    SurveyController,
    ContactMessageController,
    SettingController,
    DashboardController,
    ActivityController,
    IndustryController,
    SolutionController,
    MediaLibraryController,
    EventController,
    PartnerController,
    HeroSlideController,
};
use Illuminate\Support\Facades\Route;



Route::get('/lang/{locale}', function (\Illuminate\Http\Request $request, $locale) {
    if (in_array($locale, ['ar', 'en'])) {
        $scope = $request->query('scope');

        if (!$scope) {
            $referer = $request->headers->get('referer', '');
            $refererPath = parse_url($referer, PHP_URL_PATH) ?? '';
            if (
                str_starts_with($refererPath, '/dashboard') || 
                str_contains($refererPath, '/dashboard/') || 
                str_starts_with($refererPath, '/profile') || 
                str_contains($refererPath, '/profile/') || 
                $refererPath === '/login'
            ) {
                $scope = 'dashboard';
            } else {
                $scope = 'frontend';
            }
        }

        $sessionKey = ($scope === 'dashboard') ? 'dashboard_locale' : 'frontend_locale';
        session([$sessionKey => $locale]);
        session()->save();
    }

    // Determine safe redirect target (avoid loop back to lang.switch itself)
    $fallback = (($request->query('scope') === 'dashboard') ? url('/dashboard') : url('/'));
    $back = url()->previous($fallback);
    if (str_contains($back, '/lang/')) {
        $back = $fallback;
    }

    return redirect()->to($back);
})->name('lang.switch');

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about.index');
Route::get('/about/board', [\App\Http\Controllers\AboutController::class, 'board'])->name('about.board');
Route::get('/about/executive-team', [\App\Http\Controllers\AboutController::class, 'executiveTeam'])->name('about.executive-team');

Route::get('/services', [\App\Http\Controllers\ServiceFrontController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [\App\Http\Controllers\ServiceFrontController::class, 'show'])->name('services.show');

Route::get('/programs', [\App\Http\Controllers\ProgramFrontController::class, 'index'])->name('programs.index');
Route::get('/programs/{program:slug}', [\App\Http\Controllers\ProgramFrontController::class, 'show'])->name('programs.show');

Route::get('/projects', [\App\Http\Controllers\ProjectFrontController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [\App\Http\Controllers\ProjectFrontController::class, 'show'])->name('projects.show');

Route::get('/news', [\App\Http\Controllers\NewsFrontController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [\App\Http\Controllers\NewsFrontController::class, 'show'])->name('news.show');

Route::get('/governance', [\App\Http\Controllers\GovernanceFrontController::class, 'index'])->name('governance.index');

Route::get('/surveys', [\App\Http\Controllers\SurveyFrontController::class, 'index'])->name('surveys.index');
Route::get('/surveys/{survey}', [\App\Http\Controllers\SurveyFrontController::class, 'show'])->name('surveys.show');
Route::post('/surveys/response', [\App\Http\Controllers\SurveyFrontController::class, 'store'])
    ->middleware('throttle:5,1') 
    ->name('surveys.response.store');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
    
    // Frontend
Route::get('/activities', [ActivityFrontController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity:slug}', [ActivityFrontController::class, 'show'])->name('activities.show');

Route::get('/industries', [IndustryFrontController::class, 'index'])->name('industries.index');
Route::get('/industries/{industry:slug}', [IndustryFrontController::class, 'show'])->name('industries.show');

Route::get('/solutions', [SolutionFrontController::class, 'index'])->name('solutions.index');
Route::get('/solutions/developmental', [SolutionFrontController::class, 'developmental'])->name('solutions.developmental');
Route::get('/solutions/digital-technical', [SolutionFrontController::class, 'digitalTechnical'])->name('solutions.digital-technical');
Route::get('/solutions/{solution:slug}', [SolutionFrontController::class, 'show'])->name('solutions.show');


Route::get('/events', [EventFrontController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventFrontController::class, 'show'])->name('events.show');

// Frontend — تحت مجموعة "المركز الإعلامي" جنب الأخبار والفعاليات
Route::get('/media-library', [MediaLibraryFrontController::class, 'index'])->name('media-library.index');
Route::get('/advertising-center', [AdvertisingCenterFrontController::class, 'index'])->name('advertising-center.index');
Route::get('/content-services', [ContentServicesFrontController::class, 'index'])->name('content-services.index');


require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
        Route::patch('news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])->name('news.toggle-status');
        Route::resource('news', NewsController::class);

        Route::patch('programs/{program}/toggle-status', [ProgramController::class, 'toggleStatus'])->name('programs.toggle-status');
        Route::resource('programs', ProgramController::class);

        Route::patch('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::resource('projects', ProjectController::class);

        Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::resource('services', ServiceController::class);

        Route::patch('partners/{partner}/toggle-status', [PartnerController::class, 'toggleStatus'])->name('partners.toggle-status');
        Route::resource('partners', PartnerController::class);

        Route::patch('activities/{activity}/toggle-status', [ActivityController::class, 'toggleStatus'])->name('activities.toggle-status');
        Route::resource('activities', ActivityController::class);

        Route::patch('industries/{industry}/toggle-status', [IndustryController::class, 'toggleStatus'])->name('industries.toggle-status');
        Route::resource('industries', IndustryController::class);

        Route::patch('solutions/{solution}/toggle-status', [SolutionController::class, 'toggleStatus'])->name('solutions.toggle-status');
        Route::resource('solutions', SolutionController::class);

        Route::patch('media-library/{media_library}/toggle-status', [MediaLibraryController::class, 'toggleStatus'])->name('media-library.toggle-status');
        Route::resource('media-library', MediaLibraryController::class);

        Route::patch('events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggle-status');
        Route::resource('events', EventController::class);

        Route::patch('team-members/{team_member}/toggle-status', [TeamMemberController::class, 'toggleStatus'])->name('team-members.toggle-status');
        Route::resource('team-members', TeamMemberController::class);

        Route::patch('about-sections/{about_section}/toggle-status', [AboutSectionController::class, 'toggleStatus'])->name('about-sections.toggle-status');
        Route::resource('about-sections', AboutSectionController::class);

        Route::patch('hero-slides/{hero_slide}/toggle-status', [HeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle-status');
        Route::resource('hero-slides', HeroSlideController::class);

        Route::patch('governance-documents/{governance_document}/toggle-status', [GovernanceDocumentController::class, 'toggleStatus'])->name('governance-documents.toggle-status');
        Route::resource('governance-documents', GovernanceDocumentController::class);

        Route::patch('home-sections/{home_section}/toggle-status', [HomePageSectionController::class, 'toggleStatus'])->name('home-sections.toggle-status');
        Route::resource('home-sections', HomePageSectionController::class);
        Route::post('home-sections/reorder', [HomePageSectionController::class, 'reorder'])->name('home-sections.reorder');

        Route::patch('surveys/{survey}/toggle-status', [SurveyController::class, 'toggleStatus'])->name('surveys.toggle-status');
        Route::resource('surveys', SurveyController::class);
        Route::get('surveys/{survey}/responses', [SurveyController::class, 'responses'])->name('surveys.responses');
        Route::get('surveys/{survey}/analysis', [SurveyController::class, 'analysis'])->name('surveys.analysis');

        Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);
        });
    });