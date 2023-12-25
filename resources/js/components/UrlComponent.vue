<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Urls Component</div>

                    <div class="card-body">
                        <div class="input-group">
                                <button type="button" id="addUrlBtn" class="btn btn-info m-2" onclick="$('#myTable').hide();$('#addUrlForm').show();">Add Url</button>
                                <button type="button" id="ViewUrlsBtn" class="btn btn-info m-2" onclick="$('#addUrlForm').hide();$('#myTable').show();">View Urls</button>
                        </div>
                        <div id="addUrlForm" class="form-group" style="display: none">
                            <div v-if="validationErrors" class="alert alert-danger">
                                <ul v-if="isArray(validationErrors)">
                                    <li v-for="(error, key) in validationErrors" :key="key">{{ error[0] }}</li>
                                </ul>
                                <span v-else>{{ validationErrors }}</span>

                            </div>
                            <div v-if="successMessage" class="alert alert-success"><span>{{ successMessage }}</span></div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="url">Url</label>
                                </div>
                                <div class="col-10">
                                    <input type="url" name="url" id="url" v-model="url" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button v-if="!edit_url_id" type="button" class="btn btn-primary" @click="saveUrl()">Save</button>
                                <button v-else type="button" class="btn btn-primary" @click="updateUrl()">Update</button>
                            </div>
                        </div>
                        <table id="myTable" class="table table-bordered table-responsive display">
                            <thead>
                                <th>ID</th>
                                <th>Url</th>
                                <th>Short Code</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="(url, index) in urls.data" :key="index">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ url.url }}</td>
                                    <td><a target="_blank" :href="`/generate_url/${url.short_code}`">{{ url.short_code }}</a></td>
                                    <td>
                                        <button class="btn btn-primary m-1" @click="editUrl(url)">Edit</button>
                                        <button class="btn btn-danger" @click="deleteUrl(url.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

    import {isArray} from "pdfmake/src/helpers.js";

    export default {
        data(){
            return {
                urls: [],
                api: 'http://127.0.0.1:8000/api/manage_url',
                url: '',
                edit_url_id: '',
                validationErrors: null,
                successMessage: null
            }
        },
        methods:{
            isArray,
            getUrls() {
                this.axios.get(this.api).then(res => {
                    this.urls = res.data;
                });

            },
            saveUrl(){
                let data = {
                    'url': this.url,
                };
                this.axios.post(this.api, data).then(res => {
                    this.getUrls()
                    this.url = '';
                    if(res.status){
                        this.successMessage = res.data.original.message;
                        console.log(res)
                        console.log(this.successMessage)
                    }else{
                        this.validationErrors = res.data.original.message;
                    }
                })
                .catch((error) => {
                    console.log(error)
                    if (error.response.status === 422) {
                        // If the request returns a 422 status code, it indicates validation errors
                        this.validationErrors = error.response.data.errors;
                    } else {
                        console.error('Error:', error);
                    }
                });
            },
            deleteUrl(id){
                this.axios.delete(this.api+ '/' + id).then(res => {
                this.urls.data.pop(res);
                });
            },
            editUrl(data){
                $('#myTable').hide();
                $('#addUrlForm').show();
                this.url = data.url;
                this.edit_url_id = data.id;
            },
            updateUrl(){
                let data = {
                    'url': this.url,
                };
                this.axios.patch(this.api + '/' + this.edit_url_id, data).then(res => {
                    this.getUrls();
                    $('#ViewUrlsBtn').trigger('click');
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        // If the request returns a 422 status code, it indicates validation errors
                        this.validationErrors = error.response.data.errors;
                    } else {
                        console.error('Error:', error);
                    }
                });
            }
        },
        mounted() {
            this.getUrls();
        },
    }
</script>
