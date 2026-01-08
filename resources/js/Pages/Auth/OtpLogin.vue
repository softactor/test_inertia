<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const page = usePage()

const form = useForm({
    email: '',
    code: '',
});

const sendOtp = () => {
    form.post(route('otp.send'));
};

const verifyOtp = () => {
    form.post(route('otp.verify'));
};

</script>

<template>
    <GuestLayout>
        <Head title="Login With Otp" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Login with OTP
        </div>

        <p v-if="page.props.flash?.success" style="color: green;">
            {{ page.props.flash.success }}
        </p>
        
        <p v-if="page.props.flash?.error" style="color: red;">
            {{ page.props.flash.error }}
        </p>

        <form>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <button type="button" class="mt-4 btn btn-sm" style="color: green;" @click="sendOtp">Send OTP</button>

            <div class="mt-4">
                <InputLabel for="code" value="Code" />

                <TextInput
                    id="code"
                    type="code"
                    class="mt-1 block w-full"
                    v-model="form.code"
                    required
                    autofocus
                    autocomplete="code"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <button type="button" class="mt-4 btn btn-sm" style="color: green;" @click="verifyOtp">Verify OTP</button>

            
        </form>

    </GuestLayout>
</template>
