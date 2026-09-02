@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
])

<div
    x-data="{
        content: @js(old($name, $value)),
        editor: null,
        init() {
            this.editor = new Quill(this.$refs.editorEl, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link'],
                        [{ align: [] }],
                        ['clean'],
                    ],
                },
            });

            // Load existing content when editing
            this.editor.root.innerHTML = this.content || '';

            // Keep the hidden input updated with the HTML as the admin types
            this.editor.on('text-change', () => {
                this.content = this.editor.root.innerHTML;
            });
        }
    }"
>
    @if($label)
        <label class="mb-1.5 block text-sm font-medium text-[#3D342A]">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Quill renders its editor UI here --}}
    <div
        x-ref="editorEl"
        class="min-h-[200px] rounded-b-lg border border-t-0 border-[#B49C6E]/40 bg-secondary text-sm text-[#3D342A]"
    ></div>

    {{-- Hidden real input — this is what actually gets submitted with the form --}}
    <input type="hidden" name="{{ $name }}" x-model="content">

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

