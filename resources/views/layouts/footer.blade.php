

<footer class="border-t border-[#B49C6E]/20 bg-secondary px-4 py-4 sm:px-6 lg:px-8">
    <div class="flex flex-col items-center justify-between gap-2 text-sm text-[#3D342A]/60 sm:flex-row">

        <p>
            &copy; {{ now()->year }} {{ config('app.name', 'مؤسسة الأمير عبد الرحمن') }}. جميع الحقوق محفوظة.
        </p>

        <div class="flex items-center gap-4">
            <span>الإصدار 1.0.0</span>
            <a href="{{ route('dashboard.contact-messages.index') }}" class="hover:text-[#A38B54]">
                الدعم الفني
            </a>
        </div>
    </div>
</footer>