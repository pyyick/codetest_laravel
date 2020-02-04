<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Treatment</div>
                    <div class="card-body">
                        <form @submit="formSubmit" enctype="multipart/form-data">
                            <p>
                                <strong>File:</strong>
                                <input type="file" class="form-control" v-on:change="onFileChange">
                            </p>
                            <p>
                                <button :disabled="disabled == 1" class="btn">Validate</button>
                            </p>
                        </form>
                        <div v-if="success != ''" class="alert alert-success" role="alert">
                            {{success}}
                        </div>
                        <div v-if="error != ''" class="alert alert-danger" role="alert">
                            {{error}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Treatment mounted.')
        },
        data() {
            return {
                file: '',
                success: '',
                error: '',
                disabled: 0
            };
        },

        methods: {
            onFileChange(e){
                this.file = e.target.files[0];
            },
            formSubmit(e) {
                //e.preventDefault();
                let currentObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                let formData = new FormData();
                formData.append('file', this.file);
                this.disabled = 1;
                axios.post('/submit-ex2', formData, config)
                    .then(function (response) {
                        //Create download event
                        let csvContent = "data:text/csv;charset=utf-8," + response.data;
                        var encodedUri = encodeURI(csvContent);
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", "a-treaty.csv");
                        document.body.appendChild(link); // Required for FF
                        link.click();
                        currentObj.error = "";
                        currentObj.success = "Success, please download the CSV file";
                        currentObj.disabled = 0;
                    })
                    .catch(function (error) {
                        console.log(error.response);
                        currentObj.success = "";
                        currentObj.error = error.response.data.error;
                        currentObj.disabled = 0;
                    })
                ;
            }
        }
    }
</script>
<style>
    .alert{
        padding: 10px 2%;
    }
    .alert-danger{
        background-color: #fdd;
        color: #F00;
    }
    .alert-success{
        background-color: #dfd;
        color: #090;
    }
</style>
