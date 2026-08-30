# 🔍 Database Performance Audit — Al-Athar Foundation

Scanned: `app/`, `resources/views/`, `database/migrations/`  
Date: 2026-08-03

---

## 📊 Summary

| Category | Issues Found | Severity |
|---|---|---|
| Queries in Blade templates | 4 | 🔴 High |
| Missing eager loads (N+1 risk) | 5 | 🔴 High |
| Duplicate Settings queries in AppServiceProvider | 1 | 🟡 Medium |
| Missing `select()` column constraints | 6 | 🟡 Medium |
| `reorder()` loop issuing N queries | 1 | 🟡 Medium |
| Missing eager load of `media` on TeamMember in front-end | 1 | 🟡 Medium |
| Duplicate service files (dead code) | 2 | 🟠 Low |
| `withCount` usage | ✅ Used correctly in ProgramService | — |
| `preventLazyLoading` in AppServiceProvider | ✅ Present | — |
| Indexes | ✅ Good — dedicated migration exists | — |
| `chunk()` / `cursor()` | ℹ️ Not needed yet (all queries paginated) | — |

---

## 🔴 HIGH — Queries in Blade Templates

### 1. `frontend/contact/index.blade.php` — Lines 2–4

```blade
@php
    $sitePhone   = \App\Models\Setting::get('contact', 'phone', '+966 50 000 0000');
    $siteEmail   = \App\Models\Setting::get('contact', 'email', 'info@alathar.org.sa');
    $siteAddress = \App\Models\Setting::get('contact', 'address', __('frontend.address_fallback'));
@endphp
```

**Problem:** 3 separate DB queries fired inside the Blade template. This is the exact "queries in Blade" anti-pattern.

**Fix:** Move these lookups to `ContactController::index()` and pass via `compact()`. Since settings are already shared globally via `AppServiceProvider`, they can simply be extracted from `$companyInfo` there.

---

### 2. `components/application-logo.blade.php` — Line 6

```blade
$dbLogo = \App\Models\Setting::get('company_info', 'logo');
```

**Problem:** This component is rendered on every page (header), firing a DB query on every single page load.

**Fix:** The `AppServiceProvider` already shares `$companyInfo` globally. Use that instead — `$companyInfo['logo'] ?? null`.

---

## 🔴 HIGH — Missing Eager Loads (N+1 Risk)

### 3. `NewsFrontController::show()` — `$news->author->name` in Blade

**File:** [`NewsFrontController.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Http/Controllers/NewsFrontController.php#L15-L18)  
**Blade:** [`frontend/news/show.blade.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/resources/views/frontend/news/show.blade.php#L35) — Line 35

```php
// Controller
public function show(News $news)
{
    return view('frontend.news.show', compact('news'));  // ❌ author not eager-loaded
}

// Blade
{{ $news->author->name }}  // ← lazy-loads User for every show page
```

**Problem:** `author` relationship is accessed in the Blade template but never eager-loaded. The `preventLazyLoading` in `AppServiceProvider` will throw a `LazyLoadingViolationException` in development.

**Fix:**
```php
public function show(News $news)
{
    $news->loadMissing('author');
    return view('frontend.news.show', compact('news'));
}
```

Also note: `$news->seoMeta` is accessed on line 9 of the Blade file with no eager load either.

---

### 4. `NewsService::list()` — Missing `author` eager load

**File:** [`NewsService.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Services/NewsService.php#L10-L17)

```php
return News::query()
    ->with('media')  // ❌ author not loaded — N+1 in news index table
    ->...
    ->paginate(15);
```

**Problem:** The dashboard news index table almost certainly displays author names. Each row will trigger a separate query for `author`.

**Fix:**
```php
->with(['media', 'author'])
```

---

### 5. `AboutController::board()` and `executiveTeam()` — Missing `media` eager load

**File:** [`AboutController.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Http/Controllers/AboutController.php#L16-L26)

```php
$boardMembers = TeamMember::board()->get();         // ❌ media not loaded
$executiveMembers = TeamMember::executive()->get(); // ❌ media not loaded
```

**Problem:** Team member pages render photos. `getFirstMediaUrl()` will lazy-load `media` per member, causing N+1 (1 query per team member).

**Fix:** Add `->with('media')` to both queries, or move this logic into `TeamMemberService::list()` and use it from the controller.

---

### 6. `ProgramController::show()` → `programs/show.blade.php` — `$program->projects` accessed without eager load

**File:** [`ProgramController.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Http/Controllers/Dashboard/ProgramController.php#L32-L35)  
**Blade:** [`dashboard/programs/show.blade.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/resources/views/dashboard/programs/show.blade.php#L67-L84)

```php
// Controller
public function show(Program $program)
{
    return view('dashboard.programs.show', compact('program')); // ❌ projects not loaded
}

// Blade (lines 67, 70, 73, 116)
$program->projects->isNotEmpty()
$program->projects->count()
@foreach($program->projects as $project)
$program->projects->count()  // counted AGAIN
```

**Problems:**
1. `projects` is lazy-loaded (N+1 violation).
2. `$program->projects->count()` is called **twice** (lines 70 & 116) — the collection is loaded once but `.count()` is repeated unnecessarily.
3. `withCount('projects')` already works fine in the index list, but the `show` page doesn't benefit from it.

**Fix:**
```php
public function show(Program $program)
{
    $program->loadMissing('projects');
    return view('dashboard.programs.show', compact('program'));
}
```
And in Blade, cache the count: `@php $projectCount = $program->projects->count() @endphp` then reuse `$projectCount`.

---

### 7. `ProjectFrontController::index()` — `$project->program` accessed in Blade index table without eager load

**File:** [`ProjectFrontController.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Http/Controllers/ProjectFrontController.php#L9-L12)

```php
public function index()
{
    $projects = Project::published()->latest()->paginate(12); // ❌ program not loaded
    return view('frontend.projects.index', compact('projects'));
}
```

**Note:** Whether this is a real N+1 depends on what `frontend/projects/index.blade.php` renders. If it shows program names, this is an active N+1.

---

## 🟡 MEDIUM — Duplicate DB Queries in AppServiceProvider

**File:** [`AppServiceProvider.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Providers/AppServiceProvider.php#L27-L50)

```php
// boot() runs ONCE at bootstrap
$companyInfo = Setting::group('company_info');  // Query 1
$socialLinks = Setting::group('social_links');  // Query 2
View::share('companyInfo', $companyInfo);
View::share('socialLinksSettings', $socialLinks);

// View::composer('*') runs on EVERY view render
View::composer('*', function ($view) {
    $companyInfo = Setting::group('company_info');  // ⚠️ Query 3 (again, per view)
    $socialLinks = Setting::group('social_links');  // ⚠️ Query 4 (again, per view)
    $view->with('companyInfo', $companyInfo)
         ->with('socialLinksSettings', $socialLinks);
});
```

**Problem:** The `View::share()` already shares the data globally. The `View::composer('*', ...)` closure then runs on **every** view render (including partials, components, layouts), firing 2 extra DB queries each time. This means a single page load with 5 rendered views = 10 extra setting queries.

**Fix:** Remove the entire `View::composer('*', ...)` block — `View::share()` is sufficient:

```php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());

    try {
        $companyInfo = Setting::group('company_info');
        $socialLinks = Setting::group('social_links');
    } catch (\Throwable $e) {
        $companyInfo = [];
        $socialLinks = [];
    }

    View::share('companyInfo', $companyInfo);
    View::share('socialLinksSettings', $socialLinks);
}
```

---

## 🟡 MEDIUM — Missing `select()` Column Constraints

None of the services or controllers call `select()` to restrict columns. This means every query returns `SELECT *`, including large text/JSON columns like `description_ar`, `description_en`, `content_ar`, `content_en`, `goal_ar`, `goal_en`, and `data` (JSON).

**Most impactful cases:**

| Service | Query | Large Columns Fetched Unnecessarily |
|---|---|---|
| `NewsService::list()` | Dashboard news index | `content_ar`, `content_en` |
| `ProjectService::list()` | Dashboard projects index | `description_ar`, `description_en`, `goal_ar`, `goal_en` |
| `ProgramService::list()` | Dashboard programs index | `description_ar`, `description_en` |
| `HomePageSectionService::list()` | Dashboard home sections | `data` (JSON blob) |
| `AboutSectionService::list()` | Dashboard about sections | `description_ar`, `description_en` |
| `GovernanceDocumentService::list()` | Dashboard governance | `file_path`, large text fields |

**Example fix for `NewsService::list()`:**
```php
return News::query()
    ->select('id', 'title_ar', 'title_en', 'slug', 'status', 'published_at', 'created_by', 'created_at')
    ->with(['media', 'author:id,name'])
    ->...
    ->paginate(15);
```

> [!NOTE]
> Always include `id` and any foreign key columns in `select()`, otherwise relationships won't resolve.

---

## 🟡 MEDIUM — `HomePageSectionService::reorder()` Issues N Queries in a Loop

**File:** [`HomePageSectionService.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Services/HomePageSectionService.php#L88-L93)

```php
public function reorder(array $orderedIds): void
{
    foreach ($orderedIds as $index => $id) {
        HomePageSection::where('id', $id)->update(['order' => $index]); // ❌ N queries
    }
}
```

**Problem:** If you have 20 home sections, this fires 20 separate `UPDATE` queries.

**Fix (upsert approach):**
```php
public function reorder(array $orderedIds): void
{
    $cases = collect($orderedIds)
        ->map(fn($id, $index) => ['id' => $id, 'order' => $index])
        ->all();

    HomePageSection::upsert($cases, ['id'], ['order']);
}
```

---

## 🟡 MEDIUM — Missing `media` Eager Load on `GovernanceDocumentService::groupedForDisplay()`

**File:** [`GovernanceDocumentService.php`](file:///d:/xampp/htdocs/projectlaravel/al-athar-foundation/app/Services/GovernanceDocumentService.php#L90-L97)

```php
public function groupedForDisplay(?int $year = null)
{
    return GovernanceDocument::active()
        ->when($year, fn($q) => $q->year($year))
        ->orderByDesc('fiscal_year')
        ->get()           // ❌ media not eager loaded
        ->groupBy('category');
}
```

**Problem:** If the frontend renders document download links using Spatie Media Library, each document will lazy-load `media` individually.

**Fix:**
```php
->with('media')
->get()
->groupBy('category');
```

---

## 🟠 LOW — Duplicate Service Files (Dead Code)

Two pairs of duplicated service files exist:

| File A | File B | Status |
|---|---|---|
| `Services/ContactMessageService.php` | `Services/ContectMessageService.php` | Typo duplicate — one should be deleted |
| `Services/SurveyService.php` | `Services/SurveryService.php` | Typo duplicate — one should be deleted |

These are identical implementations with different (misspelled) filenames. Keeping both risks confusion about which is actually used.

---

## ✅ What's Already Done Well

| Practice | Status |
|---|---|
| `Model::preventLazyLoading(!app()->isProduction())` in `AppServiceProvider` | ✅ |
| All list queries use `paginate(15)` (no unbounded `->get()` on indexes) | ✅ |
| `withCount('projects')` in `ProgramService::list()` | ✅ |
| Dedicated performance indexes migration (`2026_08_01_000001_add_performance_indexes.php`) | ✅ |
| Composite indexes on `(status, created_at)` patterns | ✅ |
| All dashboard list services use `->with('media')` | ✅ |
| `ProjectService::list()` eager-loads `program` + `media` | ✅ |
| `ProjectController::show()` uses `$project->load('program')` | ✅ |
| Controller queries use `->select(['id', 'name', 'message', ...])` on dashboard home | ✅ |
| No raw `Model::all()` calls found anywhere | ✅ |
| `when()` filters instead of conditional query reassignment | ✅ |

---

## 🗂 Priority Fix Order

| # | Fix | Effort | Impact |
|---|---|---|---|
| 1 | Remove `View::composer('*')` block in `AppServiceProvider` | 🟢 Easy | 🔴 High — eliminates 2 queries per every view render |
| 2 | Add `loadMissing('author')` in `NewsFrontController::show()` | 🟢 Easy | 🔴 High — eliminates crash in dev + N+1 in prod |
| 3 | Add `->with('media')` in `AboutController::board()` / `executiveTeam()` | 🟢 Easy | 🔴 High |
| 4 | Add `loadMissing('projects')` in `ProgramController::show()` | 🟢 Easy | 🔴 High |
| 5 | Move contact page settings from Blade to `ContactController` | 🟢 Easy | 🔴 High |
| 6 | Replace logo DB query in `application-logo.blade.php` with `$companyInfo` | 🟢 Easy | 🟡 Medium |
| 7 | Add `select()` to `NewsService`, `ProjectService`, `ProgramService` list methods | 🟡 Moderate | 🟡 Medium |
| 8 | Fix `reorder()` to use `upsert()` | 🟡 Moderate | 🟡 Medium |
| 9 | Delete duplicate typo service files | 🟢 Easy | 🟠 Low |
