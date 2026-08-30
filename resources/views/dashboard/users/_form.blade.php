@csrf

<div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 max-w-2xl space-y-4">
    <x-forms.input
        name="name"
        label="{{ __('dashboard.users.name') }}"
        :value="old('name', $user->name ?? '')"
        required
    />

    <x-forms.input
        name="email"
        label="{{ __('dashboard.users.email') }}"
        type="email"
        :value="old('email', $user->email ?? '')"
        required
    />

    <x-forms.input
        name="password"
        label="{{ __('dashboard.users.password') }}"
        type="password"
        :required="!isset($user) || !$user->exists"
    />

    <x-forms.input
        name="password_confirmation"
        label="{{ __('dashboard.users.password_confirmation') }}"
        type="password"
    />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-buttons.primary type="submit">{{ __('dashboard.common.save') }}</x-buttons.primary>
    <a href="{{ route('dashboard.users.index') }}">
        <x-buttons.secondary type="button">{{ __('dashboard.common.cancel') }}</x-buttons.secondary>
    </a>
</div>
