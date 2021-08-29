<template>
    <Spin :spinning="loading">
        <div class="pt-6 pb-4">
            <p class="font-semibold">Download the csv template to bulk upload your products.
                <a class="ml-2 p-1 rounded bg-blue-50 text-blue-500"
                    href="/assets/bulk_products_uploader.csv" download
                >Download</a>
            </p>
            <p class="text-yellow-500">After filling in the products in the downloaded template, upload the file using the form below.</p>
            <span class="bg-red-50 text-red-500 text-sm px-2 rounded-md font-semibold">In the sheet, for the Tax Inclusive field, please enter 1 for Yes and 0 for No.</span>
        </div>
        <div v-if="errors.length" class="flex bg-red-50 text-sm text-red-600 rounded-md p-2">
            <ExclamationCircleIcon class="w-8 h-8" />
            <div class="ml-4 flex-1">
                <p class="font-semibold">Please check the CSV file for the following errors.</p>
                <div v-for="(error, idx) in errors" :key="error" class="mr-4"><span class="rounded-md bg-red-500 text-white px-1 text-xs">{{ idx + 1 }}</span> {{ error }}</div>
            </div>
        </div>
        <div class="py-6">
            <file-pond
                name="upload"
                ref="pond"
                class-name="my-pond"
                label-idle="Click to Select file or Drop files here..."
                :allow-multiple="false"
                accepted-file-types=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                v-on:init="handleFilePondInit"
                v-on:updatefiles="handleFilePondUpdateFile"
            />
        </div>
        <div class="w-full px-0 pt-6 border-t border-gray-200 flex justify-between md:items-center">
            <div class="flex">
                <button @click.prevent="handleCancel" type="button" class="btn-secondary mr-3">Cancel</button>
                <button @click.prevent="handleUpload"
                    class="btn-primary"
                    :disabled="!file.length"
                >Upload</button>
            </div>
        </div>
    </Spin>
</template>

<script>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import Spin from '@/views/components/Spin.vue'
import { ExclamationCircleIcon } from '@heroicons/vue/outline'
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import axios from 'axios'

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
);

export default {
    components: {
        Spin,
        FilePond,
        ExclamationCircleIcon,
    },

    data: () => {
        return {
            loading: false,
            file: [],
            errors: [],
        };
    },

    methods: {
        handleFilePondInit() {
            console.log("FilePond has initialized");
            // this.$refs.pond.getFiles();
        },
        handleFilePondUpdateFile(files) {
            this.file = files.map(files => files.file)
        },
        handleUpload() {
            this.loading = true
            this.errors = []
            const formData = new FormData();
            formData.append('file', this.file[0]);
            axios.post('/api/products/upload', formData).then((response) => {
                this.loading = false
                this.$refs.pond.removeFile(this.file[0]);
                this.file = []
                this.$emit('uploaded')
            }).catch((e) => {
                this.loading = false
                this.errors = e.response.data.errors
                console.log(e)
            })
        },
        handleCancel() {
            this.file = []
            this.errors = []
            this.$refs.pond.removeFile(this.file[0]);
            this.$emit('cancel')
        }
    },

    setup() {
        const store = useStore()
        const router = useRouter()
        // console.log(store.getters['user/authenticated'])

        return {
            router,
        }
    }
}
</script>
