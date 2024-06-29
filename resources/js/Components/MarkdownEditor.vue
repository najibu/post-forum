<template>
    <div class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        <EditorContent :editor="editor" />
    </div>
</template>

<<script setup>
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { StarterKit } from '@tiptap/starter-kit'
import { Markdown } from 'tiptap-markdown'
import { watch } from 'vue';

const props = defineProps({
    modelValue: ''
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    extensions: [
        StarterKit,
        Markdown,
    ],
    editorProps: {
        attributes: {
            class: 'prose prose-sm min-h-[512px] max-w-none py-1.5 px-3',
        }
    },
    onUpdated: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown()),
})

watch(() => props.modelValue, (newValue) => {
    if (newValue === editor.value?.storage.markdown.getMarkdown()) {
        return
    }

    editor.value?.commands.setContent(newValue)
}, { immediate: true })
</script>
