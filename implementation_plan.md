# Enterprise CMS — Full Architecture Audit Report
## Al-Athar Foundation — Laravel 12

---

> [!IMPORTANT]
> This report identifies **13 bugs/issues** that will crash or break the application in production.
> They are grouped by severity. Fix them in order: **Critical → High → Medium → Low**.

---

## CRITICAL BUGS (Will Crash / Block All Users)

---

### BUG-01 · `GovernanceDocumentRequest` — `authorize()` returns `false`
**File:** `app/Http/Requests/GovernanceDocumentRequest.php` — Line 15
**Problem:** `return false;` → Every create/update request returns HTTP 403 Forbidden.
**Why:** Laravel calls `authorize()` before validation. `false` = always unauthorized.

```diff
- return false;
+ return true;
```

---

### BUG-02 · `HomePageSectionRequest` — `authorize()` returns `false`
**File:** `app/Http/Requests/HomePageSectionRequest.php` — Line 15
**Problem:** Same issue — `return false;` blocks every home section create/update.

```diff
- return false;
+ return true;
```

---

### BUG-03 · `NewsRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/NewsRequest.php`
**Problem:** Uses `Rule::unique()` and `Rule::in()` without importing `Rule`. Will throw `Class "Rule" not found` fatal error.
**Fix:** Add import after namespace line.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### BUG-04 · `ProjectRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/ProjectRequest.php`
**Problem:** Same as BUG-03. Uses `Rule::unique()`, `Rule::in()` without import.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### BUG-05 · `ProgramRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/ProgramRequest.php`
**Problem:** Uses `Rule::unique()`, `Rule::in()` without import.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### BUG-06 · `ServiceRequest` — Completely wrong rules (copy-paste from TeamMemberRequest)
**File:** `app/Http/Requests/ServiceRequest.php`
**Problem:** The rules are for `TeamMember` (type board/executive, name_ar, position_ar, photo…) not for `Service` (title_ar, title_en, slug, description, icon, order, is_active). This will cause all Service create/update operations to fail validation on every legitimate request.

**Fix — Replace the entire `rules()` method:**

```php
public function rules(): array
{
    $serviceId = $this->route('service')?->id;

    return [
        'title_ar'       => ['required', 'string', 'max:255'],
        'title_en'       => ['nullable', 'string', 'max:255'],
        'slug'           => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($serviceId)],
        'description_ar' => ['nullable', 'string'],
        'description_en' => ['nullable', 'string'],
        'icon'           => ['nullable', 'string', 'max:100'],
        'external_link'  => ['nullable', 'url'],
        'order'          => ['nullable', 'integer', 'min:0'],
        'is_active'      => ['boolean'],
        'meta_title_ar'       => ['nullable', 'string', 'max:255'],
        'meta_title_en'       => ['nullable', 'string', 'max:255'],
        'meta_description_ar' => ['nullable', 'string', 'max:500'],
        'meta_description_en' => ['nullable', 'string', 'max:500'],
    ];
}

public function messages(): array
{
    return [
        'title_ar.required' => 'اسم الخدمة بالعربي مطلوب',
    ];
}
```

Also add `use Illuminate\Validation\Rule;` to the imports.

---

### BUG-07 · Missing `DashboardController` class
**File:** `routes/web.php` — Line 17 and 61
**Problem:** `DashboardController` is imported and used (`Route::get('/', [DashboardController::class, 'index'])`) but the class file does not exist in `app/Http/Controllers/Dashboard/`. This throws a fatal error on every page load since the route group depends on it.

**Fix — Create** `app/Http/Controllers/Dashboard/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\Survey;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'news_count'     => News::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'surveys_count'   => Survey::count(),
        ];

        return view('dashboard.home', compact('stats'));
    }
}
```

---

### BUG-08 · Missing `UserController` class
**File:** `routes/web.php` — Line 83
**Problem:** `Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class)` references a class that does not exist. Throws a fatal error when the route file is loaded.

**Fix — Create** `app/Http/Controllers/Dashboard/UserController.php`:

```php
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('dashboard.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'string'],
        ]);
        $user->update($validated);
        return back()->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
```

---

### BUG-09 · Missing frontend controllers (referenced in routes but files don't exist)
**Problem:** `routes/web.php` references these controllers which do not exist — any visitor to the public site will get a fatal error:
- `App\Http\Controllers\HomeController`
- `App\Http\Controllers\AboutController`
- `App\Http\Controllers\ServiceFrontController`
- `App\Http\Controllers\ProgramFrontController`
- `App\Http\Controllers\ProjectFrontController`
- `App\Http\Controllers\NewsFrontController`
- `App\Http\Controllers\GovernanceFrontController`
- `App\Http\Controllers\ContactController`

> [!CAUTION]
> Since you said the backend is almost finished and not to create new features, this audit notes these are missing. You must create stub versions of all these controllers so the route file does not throw a fatal error on boot.

---

### BUG-10 · `TeamMemberService` — Invalid import (`use ZipStream\Test\Tempfile`)
**File:** `app/Services/TeamMemberService.php` — Line 5
**Problem:** `use ZipStream\Test\Tempfile;` is a stray debug import that has no use in this file and is not in the composer dependencies. This will throw a `Class not found` error when Laravel boots the service container.

```diff
  namespace App\Services;

  use App\Models\TeamMember;
- use ZipStream\Test\Tempfile;

  class TeamMemberService extends BaseService
```

---

### BUG-11 · `TeamMemberService` — Media collection typo (inconsistent collection name)
**File:** `app/Services/TeamMemberService.php` — Lines 19 and 25
**Problem:** `create()` uses `'team_photos'` but `update()` uses `'team_photes'` (typo). This means updated photos are uploaded to a different collection that is never read, and the old photo is never cleared on update.

```diff
  public function update(TeamMember $member, array $data): TeamMember {
      $member->update($data);
-     $this->attachImage($member, $data["photo"] ?? null, "team_photes");
+     $this->attachImage($member, $data["photo"] ?? null, "team_photos");
      return $member;
  }
```

---

## HIGH SEVERITY BUGS

---

### BUG-12 · `HomePageSectionRequest` — Wrong `type` validation values & missing `data` field in model
**File:** `app/Http/Requests/HomePageSectionRequest.php` — Line 26
**Problem:** The request validates `type` against `['hero_slider', 'counters', 'latest_news']` but the model constants are `TYPE_SLIDER = 'slider'`, `TYPE_HOME_SECTION = 'home_section'`, `TYPE_COUNTER = 'counter'`, `TYPE_LATEST_NEWS = 'latest_news'`. The values don't match — all create/update will fail validation for slider and counters.

Also `"data"` field in the request is required but the model `$fillable` does NOT include `data`. This will silently drop the field.

**Fix 1 — Align type values in request:**
```php
'type' => ['required', Rule::in(['slider', 'home_section', 'counter', 'latest_news'])],
```

**Fix 2 — Add `data` to HomePageSection `$fillable`:**
```php
protected $fillable = [
    'type', 'title_ar', 'title_en', 'description_ar', 'description_en',
    'image', 'extra_link', 'label', 'data', 'order', 'is_active',
];
```

**Fix 3 — Add `data` cast to the model:**
```php
protected function casts(): array
{
    return [
        'is_active' => 'boolean',
        'order'     => 'integer',
        'data'      => 'array',  // ADD THIS
    ];
}
```

**Fix 4 — Fix typo `"nubllable"` in request:**
```diff
- "label" => ["nubllable", "string", "max:255"],
+ "label" => ["nullable", "string", "max:255"],
```

---

### BUG-13 · `contact_messages` migration — `subject` field is NOT NULL but `ContactMessageRequest` allows `nullable`
**File:** `database/migrations/2026_07_25_134449_create_contact_messages_table.php` — Line 19
**Problem:** `$table->string('subject')` (no `->nullable()`) but the request has `'subject' => ['nullable', 'string', 'max:255']`. Submitting without a subject will throw a database integrity constraint error.

**Fix — Make `subject` nullable in the migration:**
```diff
- $table->string('subject');
+ $table->string('subject')->nullable();
```
Then run: `php artisan migrate:refresh` or add a new migration.

---

### BUG-14 · `SurveyController` imports wrong service class name
**File:** `app/Http/Controllers/Dashboard/SurveyController.php` — Line 8
**Problem:** The controller imports `use App\Services\SurveyService;` but the actual file is named `SurveyService.php` (class `SurveyService`). However the **file on disk is named `SurveryService.php`** (typo). PHP autoloader resolves by class name (PSR-4) not filename, so this depends on whether `SurveyService` or `SurveryService` is the class name inside.

**Verification:** The file `SurveryService.php` contains `class SurveyService` — the filename has a typo but the class name is correct. PSR-4 autoload will fail because Composer maps `App\Services\SurveyService` → `app/Services/SurveyService.php` which doesn't exist.

**Fix — Rename the file:**
```
Rename: app/Services/SurveryService.php → app/Services/SurveyService.php
```
Then run: `php artisan optimize:clear`

---

### BUG-15 · `ContectMessageService.php` — Filename typo
**File:** `app/Services/ContectMessageService.php`
**Problem:** Same issue. The file is `ContectMessageService.php` (typo in "Contact"). The controller imports `use App\Services\ContactMessageService;` which maps to `app/Services/ContactMessageService.php` — a file that doesn't exist.

**Fix — Rename the file:**
```
Rename: app/Services/ContectMessageService.php → app/Services/ContactMessageService.php
```

---

### BUG-16 · `News` model — `image` field in `$fillable` but media is handled by Spatie Library
**File:** `app/Models/News.php` — Line 30
**Problem:** `'image'` is in `$fillable` and in the migration as a `string` column. But `NewsService::create()` also calls `$this->attachImage($news, $data['image'] ?? null, 'news_images')` passing the uploaded file object. This means:
1. The `image` field (a string column) will receive a serialized `UploadedFile` object if `News::create($data)` is called before `$data['image']` is removed from the data array.
2. The `UploadedFile` cannot be stored as a string — this will throw a database error or corrupt data.

**Fix — Remove `image` from the validated data before creating/updating the model, or exclude it via `$request->except('image')` in the service. The correct pattern:**

In `NewsService.php`:
```php
public function create(array $data): News
{
    $imageFile = $data['image'] ?? null;
    unset($data['image']);  // prevent UploadedFile going into DB column

    $data['slug']       = $data['slug'] ?? Str::slug($data['title_ar']);
    $data['created_by'] = auth()->id();

    $news = News::create($data);

    $this->attachImage($news, $imageFile, 'news_images');
    $this->attachSeo($news, $data);

    return $news;
}

public function update(News $news, array $data): News
{
    $imageFile = $data['image'] ?? null;
    unset($data['image']);

    $news->update($data);

    $this->attachImage($news, $imageFile, 'news_images');
    $this->attachSeo($news, $data);

    return $news;
}
```

> [!IMPORTANT]
> This same bug affects **ProgramService**, **ProjectService**, **AboutSectionService**, and **TeamMemberService** — all pass the raw `UploadedFile` object into `Model::create($data)` without removing it first. Apply the same `unset($data['image'])` pattern to all of them.

---

### BUG-17 · `Project` model — Missing `seoMeta()` relationship + missing `HasSlug` slug options
**File:** `app/Models/Project.php`
**Problem 1:** `ProjectService::create()` calls `$this->attachSeo($project, $data)` which calls `$model->seoMeta()`. The `Project` model has NO `seoMeta()` relationship defined → `BadMethodCallException`.
**Problem 2:** `Project` model uses `HasSlug` trait but never defines `getSlugOptions()` → `LogicException` from Spatie Sluggable.

**Fix — Add both to `Project.php`:**
```php
use Spatie\Sluggable\SlugOptions;

public function getSlugOptions(): SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('title_ar')
        ->saveSlugsTo('slug');
}

public function seoMeta(): \Illuminate\Database\Eloquent\Relations\MorphOne
{
    return $this->morphOne(SeoMeta::class, 'seo_metable');
}
```
Also add `use Illuminate\Database\Eloquent\Relations\MorphOne;` and `use App\Models\SeoMeta;` to imports.

---

### BUG-18 · `NewsController` — Missing `show()` method (route registered but method absent)
**File:** `app/Http/Controllers/Dashboard/NewsController.php`
**Problem:** `Route::resource('news', NewsController::class)` registers a `show` route (`dashboard.news.show`) but the controller has no `show()` method. Visiting that URL causes `BadMethodCallException`.

The news `show.blade.php` view exists, so the view is ready — just add the method:

```php
public function show(News $news)
{
    $news->load('seoMeta');
    return view('dashboard.news.show', compact('news'));
}
```

---

### BUG-19 · `news/create.blade.php` uses `@include('dashboard.news._form')` but file is named `_from.blade.php`
**File:** `resources/views/dashboard/news/create.blade.php` — Line 17
**Problem:** The partial is named `_from.blade.php` (typo: missing 'r') but the view includes `_form`. This will throw `View [dashboard.news._form] not found` on create and edit pages.

**Fix — Rename the file:**
```
Rename: resources/views/dashboard/news/_from.blade.php → resources/views/dashboard/news/_form.blade.php
```

---

## MEDIUM SEVERITY ISSUES

---

### ISSUE-20 · `contact-messages` views — Missing `index.blade.php`
**Problem:** `ContactMessageController::index()` returns `view('dashboard.contact-messages.index')` but no `index.blade.php` exists in that directory (only `create.blade.php` and `show.blade.php`).

> Create `resources/views/dashboard/contact-messages/index.blade.php` with a table of contact messages.

---

### ISSUE-21 · `contact-messages/create.blade.php` — Wrong view for `show` route
**Problem:** The controller's `show()` method returns `view('dashboard.contact-messages.show', ...)` but only `create.blade.php` exists. The `create.blade.php` actually reads the message data as if it were a show page, but it's named `create`. This is a naming confusion. The controller sends the `show` view name but the file that exists is `create`.

**Fix:** Rename `create.blade.php` → `show.blade.php` in the `contact-messages` directory, or adjust the controller. The existing `show.blade.php` is the correct one to use.

---

### ISSUE-22 · `AboutSectionRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/AboutSectionRequest.php` — Line 30
**Problem:** `Rule::in(['draft', 'published'])` used without import.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### ISSUE-23 · `HomePageSectionRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/HomePageSectionRequest.php` — Line 26
**Problem:** `Rule::in(...)` used without import. Combined with BUG-02 (authorize=false), this is doubly broken.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### ISSUE-24 · `GovernanceDocumentRequest` — Missing `use Illuminate\Validation\Rule` import
**File:** `app/Http/Requests/GovernanceDocumentRequest.php` — Line 28
**Problem:** `Rule::in(...)` used without import.

```diff
  namespace App\Http\Requests;

+ use Illuminate\Validation\Rule;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
```

---

### ISSUE-25 · `ServiceRequest::message()` typo — should be `messages()`
**File:** `app/Http/Requests/ServiceRequest.php` — Line 41
**Problem:** Method named `message()` (singular). Laravel calls `messages()` (plural). The custom validation messages will never be used.

```diff
- public function message(): array
+ public function messages(): array
```

---

### ISSUE-26 · `Project` model — `$casts` uses old array syntax instead of method
**File:** `app/Models/Project.php` — Lines 29-32
**Problem:** Uses `protected $casts = [...]` (property style) while all other models use `protected function casts(): array` (method style, Laravel 10+). Not a crash bug but inconsistent.

```diff
- protected $casts = [
-     'start_date' => 'date',
-     'end_date'   => 'date',
- ];
+ protected function casts(): array
+ {
+     return [
+         'start_date' => 'date',
+         'end_date'   => 'date',
+     ];
+ }
```

---

### ISSUE-27 · `TeamMember` model — Missing `SoftDeletes` trait
**File:** `app/Models/TeamMember.php`
**Problem:** All other CMS models use `SoftDeletes` but `TeamMember` does not. The `team_members` migration also has no `softDeletes()`. Deleting a team member is permanent, which is inconsistent with the rest of the system.

**Fix (Optional but recommended for consistency):**
1. Add `use SoftDeletes;` to `TeamMember` model
2. Create a new migration: `php artisan make:migration add_soft_deletes_to_team_members_table`
```php
$table->softDeletes();
```

---

### ISSUE-28 · `governance_documents` migration — `file_path` is NOT NULL
**File:** `database/migrations/2026_07_26_084906_create_governance_documents_table.php` — Line 20
**Problem:** `$table->string('file_path')` is NOT NULL, but if `GovernanceDocumentService::create()` runs `GovernanceDocument::create($data)` BEFORE the media upload, `file_path` will be an empty string from the `$fillable`. The model is created first, then file_path is updated. This sequence works only because `file_path` is in `$fillable` and gets set to `''` from the mass assignment (since 'file_path' is in fillable). But the migration requires it to be non-null.

**Fix:**
```diff
- $table->string('file_path');
+ $table->string('file_path')->nullable();
```

---

### ISSUE-29 · `BaseService::attachSeo()` — `updateOrCreate` uses wrong condition
**File:** `app/Services/BaseServices.php` — Lines 20-21
**Problem:** The `updateOrCreate` call passes `seo_metable_id` and `seo_metable_type` as the match condition. But these are also set by the morph relationship automatically. A cleaner and safer approach is to use the relationship's `updateOrCreate` without manually specifying morph keys (they are resolved by the relation).

The current code works but is fragile — if the class name changes, the `seo_metable_type` won't match the stored value. Use `morphMap` or let the relation handle it:

```php
protected function attachSeo(Model $model, array $data): void
{
    $seoFields = array_filter([
        'meta_title_ar'       => $data['meta_title_ar'] ?? null,
        'meta_title_en'       => $data['meta_title_en'] ?? null,
        'meta_description_ar' => $data['meta_description_ar'] ?? null,
        'meta_description_en' => $data['meta_description_en'] ?? null,
        'og_image'            => $data['og_image'] ?? null,
    ], fn($v) => $v !== null);

    if (!empty($seoFields)) {
        $model->seoMeta()->updateOrCreate([], $seoFields);
    }
}
```

---

## LOW SEVERITY / CONSISTENCY

---

### ISSUE-30 · `ProjectController` — Direct Model queries in controller (minor business logic leak)
**File:** `app/Http/Controllers/Dashboard/ProjectController.php` — Lines 21, 28, 49
**Problem:** `Program::orderBy('order')->pluck('title_ar', 'id')` is called directly in the controller's `index()`, `create()`, and `edit()` methods. This is a minor violation of the "no business logic in controllers" rule.

**Fix — Move to `ProgramService`:**
```php
// In ProgramService
public function pluckForSelect(): \Illuminate\Support\Collection
{
    return Program::orderBy('order')->pluck('title_ar', 'id');
}
```
Then use `$this->programService->pluckForSelect()` in the controller (inject `ProgramService`).

---

### ISSUE-31 · `SurveyController` — Inline paginate call (minor)
**File:** `app/Http/Controllers/Dashboard/SurveyController.php` — Line 51
**Problem:** `$survey->responses()->latest()->paginate(20)` is in the controller's `responses()` method — minor logic leak. Should be in `SurveyService`.

---

### ISSUE-32 · `role:admin` middleware not registered
**File:** `routes/web.php` — Line 82
**Problem:** `Route::middleware('role:admin')` is used but there is no `role` middleware class registered anywhere in the application. No `app/Http/Middleware/` directory exists with a role middleware, and it's not registered in `bootstrap/app.php` or a service provider.

> [!WARNING]
> This means the User management routes have NO access control — any authenticated user can access `/dashboard/users`. The middleware silently fails or throws a `RuntimeException`.

**Fix:** Either install a package like `spatie/laravel-permission` and register its middleware, or create a simple role middleware:

```php
// app/Http/Middleware/RoleMiddleware.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403);
        }
        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);
})
```

And add `role` field to `User` model `$fillable` and the `users` migration.

---

### ISSUE-33 · `User` model — Missing `role` field
**File:** `app/Models/User.php`
**Problem:** `role:admin` middleware expects a `role` attribute on the User but the model and migration have no `role` field. Must add it.

**Fix:**
```diff
  protected $fillable = [
      'name',
      'email',
      'password',
+     'role',
  ];
```

And create a migration:
```php
$table->string('role')->default('admin');
```

---

### ISSUE-34 · `News` model — Missing `registerMediaCollections()`
**File:** `app/Models/News.php` (and all other HasMedia models)
**Problem:** None of the models define `registerMediaCollections()`. While Spatie Media Library works without it (collections are created on first use), explicitly defining collections is best practice for validation, single-file enforcement, and conversions.

**Recommended addition to `News.php`:**
```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('news_images')
         ->singleFile()
         ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
}
```

---

### ISSUE-35 · N+1 Query Risk — `GovernanceDocumentController::index()`
**File:** `app/Http/Controllers/Dashboard/GovernanceDocumentController.php` — Line 17
**Problem:** The service returns a paginated collection. If any view loops over documents and accesses their media (`getFirstMediaUrl()`), Spatie will execute one query per record.

**Fix — Add `withMedia()` in `GovernanceDocumentService::list()`:**
```php
return GovernanceDocument::query()
    ->withMedia('governance_files')
    ->when(...)
    ->paginate(15);
```

---

## SUMMARY TABLE

| # | Severity | Module | File | Issue |
|---|----------|--------|------|-------|
| BUG-01 | 🔴 CRITICAL | Governance | GovernanceDocumentRequest | `authorize()` returns false |
| BUG-02 | 🔴 CRITICAL | Home Sections | HomePageSectionRequest | `authorize()` returns false |
| BUG-03 | 🔴 CRITICAL | News | NewsRequest | Missing `Rule` import |
| BUG-04 | 🔴 CRITICAL | Projects | ProjectRequest | Missing `Rule` import |
| BUG-05 | 🔴 CRITICAL | Programs | ProgramRequest | Missing `Rule` import |
| BUG-06 | 🔴 CRITICAL | Services | ServiceRequest | Wrong rules (TeamMember copy-paste) |
| BUG-07 | 🔴 CRITICAL | Dashboard | — | Missing `DashboardController` |
| BUG-08 | 🔴 CRITICAL | Users | — | Missing `UserController` |
| BUG-09 | 🔴 CRITICAL | Frontend | — | 8 frontend controllers missing |
| BUG-10 | 🔴 CRITICAL | Team Members | TeamMemberService | Invalid `ZipStream` import |
| BUG-11 | 🔴 CRITICAL | Team Members | TeamMemberService | Collection name typo on update |
| BUG-12 | 🟠 HIGH | Home Sections | HomePageSectionRequest | Wrong type values + `data` not in fillable |
| BUG-13 | 🟠 HIGH | Contact | Migration | `subject` NOT NULL vs nullable request |
| BUG-14 | 🟠 HIGH | Surveys | SurveryService.php | Filename typo breaks autoloader |
| BUG-15 | 🟠 HIGH | Contact | ContectMessageService.php | Filename typo breaks autoloader |
| BUG-16 | 🟠 HIGH | All Media | Services | UploadedFile stored in DB string column |
| BUG-17 | 🟠 HIGH | Projects | Project model | Missing `seoMeta()` + `getSlugOptions()` |
| BUG-18 | 🟠 HIGH | News | NewsController | Missing `show()` method |
| BUG-19 | 🟠 HIGH | News | Views | `_from.blade.php` typo (should be `_form`) |
| ISSUE-20 | 🟡 MEDIUM | Contact | Views | Missing `index.blade.php` |
| ISSUE-21 | 🟡 MEDIUM | Contact | Views | `create.blade.php` used as `show` |
| ISSUE-22 | 🟡 MEDIUM | About | AboutSectionRequest | Missing `Rule` import |
| ISSUE-23 | 🟡 MEDIUM | Home Sections | HomePageSectionRequest | Missing `Rule` import |
| ISSUE-24 | 🟡 MEDIUM | Governance | GovernanceDocumentRequest | Missing `Rule` import |
| ISSUE-25 | 🟡 MEDIUM | Services | ServiceRequest | `message()` typo (should be `messages()`) |
| ISSUE-26 | 🟡 MEDIUM | Projects | Project model | Old `$casts` property syntax |
| ISSUE-27 | 🟡 MEDIUM | Team Members | TeamMember model | Missing `SoftDeletes` |
| ISSUE-28 | 🟡 MEDIUM | Governance | Migration | `file_path` NOT NULL constraint |
| ISSUE-29 | 🟡 MEDIUM | SEO | BaseService | Fragile `updateOrCreate` morph condition |
| ISSUE-30 | 🟢 LOW | Projects | ProjectController | Direct Model query in controller |
| ISSUE-31 | 🟢 LOW | Surveys | SurveyController | Inline paginate in controller |
| ISSUE-32 | 🟢 LOW | Users | Routes | `role:admin` middleware not registered |
| ISSUE-33 | 🟢 LOW | Users | User model | Missing `role` field |
| ISSUE-34 | 🟢 LOW | All Media | All HasMedia models | Missing `registerMediaCollections()` |
| ISSUE-35 | 🟢 LOW | Governance | Service | N+1 query risk on media |

---

## WHAT IS CORRECTLY IMPLEMENTED ✅

- Route structure and grouping under `dashboard.` prefix with `auth,verified` middleware ✅
- All service-to-controller delegation (no business logic in controllers) ✅  
- BaseService pattern for shared `attachImage()` and `attachSeo()` ✅
- Morph polymorphic SEO relation on News, Program, Service, AboutSection ✅
- Soft deletes on News, Program, Service, AboutSection ✅
- Sluggable on News, Program, Service (once BUG-17 is fixed, Project too) ✅
- Settings caching via `Cache::rememberForever` ✅
- Survey response throttling on public route ✅
- Contact honeypot field (website:prohibited) ✅
- Program → hasMany Projects relationship ✅
- Survey → hasMany SurveyResponse relationship ✅
- Governance documents media upload via Spatie ✅
- `prepareForValidation` IP injection in Contact and Survey requests ✅
- All dashboard controllers follow consistent CRUD pattern ✅
