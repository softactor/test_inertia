<template>
    <Head title="Note List" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Note List
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                
                
                <!-- List -->


                <div v-if="page.props.flash?.success" style="color: lightgreen;">
                    {{ page.props.flash.success }}
                </div>

                    

                    <ul v-if="notes.length > 0">
                        <li v-for="note in notes" :key="note.id">
                            <strong>{{ note.title }}</strong> &nbsp;
                            <small>{{ note.created_at }}</small>
                            <p>
                                {{ note.body }}
                            </p>
                        </li>
                    </ul>
                


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps({
    notes: Array
})

const page = usePage()



</script>
