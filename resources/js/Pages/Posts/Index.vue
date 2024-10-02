<template>
    <AppLayout>
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"/>
                <p v-if="selectedTopic" class="mt-1 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>

                <menu class="flex space-x-1 mt-3 overflow-x-auto pb-2 pt-1">
                    <li>
                        <Pill :href="route('posts.index')" :filled="!selectedTopic">
                            All Posts
                        </Pill>
                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill :href="route('posts.index', {topic: topic.slug})" :filled="selectedTopic?.id === topic.id">
                            {{ topic.name }}
                        </Pill>
                    </li>
                </menu>
            </div>

            <ul class=" mt-4 divide-y">
                <li
                    v-for="post in posts.data"
                    :key="post.id"
                    class="flex flex-col items-baseline md:flex-row justify-between"
                >
                    <Link :href="route('posts.index', {topic: post.topic.slug})" class="block group px-2 py-4">
                    <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                    <span class="first-letter:uppercase block pt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago
                        by {{ post.user.name }}</span>
                    </Link>

                    <Pill :href="route('posts.index', {topic: post.topic.slug})" >
                        {{ post.topic.name }}
                    </Pill>
                </li>
            </ul>

            <Pagination :meta="posts.meta" :only="['posts']" class="mt-2" />
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import PageHeading from "@/Components/PageHeading.vue";
import { Link } from "@inertiajs/vue3";
import { relativeDate } from "@/Utilities/date.js";
import Pill from "@/Components/Pill.vue"

defineProps(['posts', 'topics', "selectedTopic"]);

const formattedDate = (post) => relativeDate(post.created_at);
</script>
