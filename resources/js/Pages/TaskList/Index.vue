<template>

    <Head title="Task List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Task List
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">


                    <!-- List -->


                    <div v-if="page.props.flash?.success" style="color: lightgreen;">
                        {{ page.props.flash.success }}
                    </div>


                    <div class="mb-3">

                        <h3 class="fw-bold"> Create Task List</h3>

                        <div class="d-flex gap-2" style="max-width: 750px;">
                            
                            <input type="text" class="form-control" v-model="newListName">

                            <button class="btn btn-primary" @click="createTaskList">
                                New
                            </button>

                        </div>



                    </div>


                    <div v-if="taskList.length == 0">
                         <div class="alert alert-info"> No Task List Found!  </div>
                    </div>

                    <table v-else class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">List Name</th>
                                <th scope="col">Position</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list, index) in taskList" :key="list.id">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>
                                    <div v-if="editId != list.id">
                                        {{ list.name }}
                                    </div>
                                    
                                    <div v-else>
                                        <input type="text" v-model="editName" class="form-control form-control-sm">
                                    </div>
                                    
                                
                                </td>
                                <td>{{ list.position }}</td>
                                <td>

                                    <div v-if="editId != list.id">
                                        <button type="button" class="btn btn-sm btn-warning" @click=startEdit(list)>Edit</button>
                                    </div>


                                    <div v-else>
                                        <button type="button" class="btn btn-sm btn-success" @click=listUpdate(list)>Update</button>
                                    </div>

                                    


                                    <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>





                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

import { onMounted, ref } from 'vue';

const page = usePage()
const userId = page.props.auth?.user.id

const taskList = ref([])

const editId = ref('')
const editName = ref('')
const newListName = ref('')



async function createTaskList(){

    try{

        const response = await fetch(`/api/task-lists`, {
            method:'POST',
            headers:{
                'Content-Type': 'application/json'
             },
            body: JSON.stringify({ 
                name:  newListName.value.trim(),
                user_id:  userId
            })

        })

        newListName.value = ''

         fetchTaskList()

    }catch(e){
        console.log(e)
    }
}



function startEdit(list){
    editId.value = list.id
    editName.value = list.name
}


async function listUpdate(list){

    const updateId = list.id

    try{
        const response = await fetch(`/api/task-lists/${updateId}`, {
            method:'PUT',
            headers:{
                'Content-Type': 'application/json'
             },
            body: JSON.stringify({ name:  editName.value.trim()})

        })


        editId.value = ''
        editName.value = ''

        // const taskListData = await response.json();

        // taskList.value = taskListData.data || []

        fetchTaskList()

    }catch(e){

    }

}


async function fetchTaskList(){

    try{
        const response = await fetch(`/api/task-lists?user_id=${userId}`)
        const taskListData = await response.json();

        taskList.value = taskListData.data || []

    }catch(e){

    }

}


onMounted(() => {

    fetchTaskList()

})



</script>
