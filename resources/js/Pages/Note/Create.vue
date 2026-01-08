<template>
    <Head title="Note Create" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Note Create
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                
                <div v-if="page.props.flash?.success" style="color: lightgreen;">
                    {{ page.props.flash.success }}
                </div>
                <!-- bsform -->

                <form @submit.prevent="submitForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" v-model="form.title" class="form-control">
                        <div v-if="form.errors.title" style="color: red;">{{ form.errors.title }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="body" class="form-label">Body</label>
                        <textarea v-model="form.body" class="form-control"></textarea>                        
                        <div v-if="form.errors.body" style="color: red;">{{ form.errors.body }}</div>
                    </div>



                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const page = usePage()

const form = useForm({
    title:'',
    body:'',
})

function submitForm(){

    form.post('/note', {

        onSuccess: () => {
            form.reset()
        },

    })

}



</script>
