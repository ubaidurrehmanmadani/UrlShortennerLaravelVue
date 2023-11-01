<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Companies Component</div>

                    <div class="card-body">
                        <div class="input-group">
                                <button type="button" id="addCompanyBtn" class="btn btn-info m-2" onclick="$('#myTable').hide();$('#addCompanyForm').show();">Add Company</button>
                                <button type="button" id="viewCompaniesBtn" class="btn btn-info m-2" onclick="$('#addCompanyForm').hide();$('#myTable').show();">View Companies</button>
                        </div>
                        <div id="addCompanyForm" class="form-group" style="display: none">
                            <div v-if="validationErrors" class="alert alert-danger">
                                <ul>
                                    <li v-for="(error, key) in validationErrors" :key="key">{{ error[0] }}</li>
                                </ul>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Name</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="name" id="name" v-model="name" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Email</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="email" id="email" v-model="email" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Logo</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="logo" id="logo" v-model="logo" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-2">
                                    <label for="name">Website</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="website" id="website" v-model="website" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <button v-if="!edit_company_id" type="button" class="btn btn-primary" @click="saveCompany()">Save</button>
                                <button v-else type="button" class="btn btn-primary" @click="updateCompany()">Update</button>
                            </div>
                        </div>
                        <table id="myTable" class="table table-bordered table-responsive display">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr v-for="(company, index) in companies.data" :key="index">
                                    <td>{{ index+1 }}</td>
                                    <td><a :href="company.website"> {{ company.name }}</a></td>
                                    <td>{{ company.email }}</td>
                                    <td><img :src="company.logo" height="20" width="20"></td>
                                    <td>
                                        <button class="btn btn-primary m-1" @click="editCompany(company)">Edit</button>
                                        <button class="btn btn-danger" @click="deleteCompany(company.id)">Delete</button>
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
// import VuePaginate from 'vue-paginate'

    export default {
        // components: {
        //     VuePaginate
        // },
        data(){
            return {
                companies: [],
                api: 'http://127.0.0.1:8000/api/companies',
                name: '',
                email: '',
                logo: '',
                website: '',
                edit_company_id: '',
                validationErrors: null,
            }
        },
        methods:{
            getCompanies() {
                this.axios.get(this.api).then(res => {
                    this.companies = res.data;
                });

            },
            saveCompany(){
                let data = {
                    'name': this.name,
                    'email': this.email,
                    'logo': this.logo,
                    'website': this.website
                };
                this.axios.post(this.api, data).then(res => {
                    this.getCompanies()
                    this.name = this.email = this.logo = this.website = '';
                    $('#viewCompaniesBtn').trigger('click');
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        // If the request returns a 422 status code, it indicates validation errors
                        this.validationErrors = error.response.data.errors;
                    } else {
                        console.error('Error:', error);
                    }
                });
            },
            deleteCompany(id){
                this.axios.delete(this.api+ '/' + id).then(res => {
                this.companies.data.pop(res);
                });
            },
            editCompany(company){
                $('#myTable').hide();
                $('#addCompanyForm').show();
                this.name = company.name;
                this.email = company.email;
                this.logo = company.logo;
                this.website = company.website;
                this.edit_company_id = company.id;
            },
            updateCompany(){
                let data = {
                    'name': this.name,
                    'email': this.email,
                    'logo': this.logo,
                    'website': this.website
                };
                this.axios.patch(this.api + '/' + this.edit_company_id, data).then(res => {
                    this.getCompanies()
                    $('#viewCompaniesBtn').trigger('click');
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
            this.getCompanies();
            // this.axios.get(this.api).then(res => {
            //     // this.companies = res.data;
            //     console.log(res.data)
            //     // $('#myTable').DataTable( {
            //     //     data: res.data,
            //     //     columns: [
            //     //         { data: 'id' },
            //     //         { data: 'name' },
            //     //         { data: 'email' },
            //     //         { data: 'logo' }
            //     //     ]
            //     // } );
            //
            // });
        },
    }
</script>
