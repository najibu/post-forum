<template>
    <div v-if="editor"
        class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        <menu class="flex divide-x border-b">
            <li>
                <button @click="() => editor.chain().focus().toggleBold().run()" type="button"
                    class="px-3 py-2 rounded-tl-md"
                    :class="[editor.isActive('bold') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']">
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleItalic().run()" type="button" class="px-3 py-2"
                    :class="[editor.isActive('italic') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Italic">
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleStrike().run()" type="button" class="px-3 py-2"
                    :class="[editor.isActive('strike') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Strikethrough">
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBlockquote().run()" type="button" class="px-3 py-2"
                    :class="[editor.isActive('blockquote') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Blockquote">
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleBulletList().run() " type="button" class="px-3 py-2"
                    :class="[editor.isActive('bulletList') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Bullet list">
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleOrderedList().run()" type="button" class="px-3 py-2"
                    :class="[editor.isActive('orderedList') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Numeric list">
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button @click="promptUserForHref" type="button" class="px-3 py-2"
                    :class="[editor.isActive('link') ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Add Link">
                    <i class="ri-link-m"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 2 }).run()" type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 2 }) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Heading 1">
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 3 }).run()" type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 3 }) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Heading 2">
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button @click="() => editor.chain().focus().toggleHeading({ level: 4 }).run() " type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 4 }) ? 'bg-indigo-500 text-white' : 'hover:bg-gray-200']"
                    title="Heading 3">
                    <i class="ri-h-3"></i>
                </button>
            </li>

        </menu>
        <EditorContent :editor="editor" />
    </div>
</template>

<<script setup>
import { EditorContent, isActive, useEditor } from '@tiptap/vue-3';
import { StarterKit } from '@tiptap/starter-kit'
import { Markdown } from 'tiptap-markdown'
import { Link } from "@tiptap/extension-link"
import { watch } from 'vue';
import 'remixicon/fonts/remixicon.css'

const props = defineProps({
    modelValue: ''
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            code: false,
            codeBlock: false,
        }),
        Link,
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

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().focus().unsetLink().run()
    }

    const href = prompt('Enter the URL of the link:')

    if (! href) {
        return editor.value?.chain().focus().run()
    }

    return editor.value?.chain().focus().setLink({ href }).run()

}
</script>
