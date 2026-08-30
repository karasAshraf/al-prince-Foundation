{{-- Readonly representation for contact message details --}}
<div class="rounded-xl border border-[#B49C6E]/20 bg-[#EAEAE9] p-5 shadow-sm space-y-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-forms.input name="name" label="الاسم الكامل" :value="$message->name ?? ''" disabled />
        <x-forms.input name="email" label="البريد الإلكتروني" :value="$message->email ?? ''" disabled />
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-forms.input name="phone" label="رقم الجوال" :value="$message->phone ?? ''" disabled />
        <x-forms.input name="type" label="نوع الرسالة" :value="($message->type ?? '') === 'complaint' ? 'تظلم/شكوى' : 'استفسار عام'" disabled />
    </div>

    <x-forms.input name="subject" label="موضوع الرسالة" :value="$message->subject ?? ''" disabled />
    <x-forms.textarea name="message" label="نص الرسالة" :value="$message->message ?? ''" rows="5" disabled />
</div>
