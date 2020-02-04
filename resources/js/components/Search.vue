<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <form id="search-form" @submit="getFirstName">
                            <input id="search" type="text" v-model="firstName" placeholder="First name">
                            <button id="search-button" v-on:submit="getFirstName" type="submit">Search</button>
                        </form>
                        <ul v-if="results.length > 0">
                            <li v-for="result in results" :key="result.id">
                                <div class="row">
                                    <span class="label">Name: </span>
                                    <span v-text="result.label"></span>
                                </div>
                                <div class="row">
                                    <span class="label">Type: </span>
                                    <span v-text="result.type"></span>
                                </div>
                                <div class="row">
                                    <span class="label">Priority gender: </span>
                                    <span v-text="result.gender"></span>
                                </div>
                                <div class="row">
                                    <span class="label">Origin: </span>
                                    <span v-text="result.origin"></span>
                                </div>
                            </li>
                        </ul>
                        <div v-else>
                            No results yet
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    export default {
        mounted() {
            console.log('Search mounted.');
            document.getElementById('search-form').addEventListener('submit', function(e) {
                e.preventDefault();
            });
        },

        data() {
            return {
                firstName: null,
                results: []
            };
        },

        watch: {
            keywords(after, before) {
                this.fetch();
            }
        },

        methods: {
            getFirstName: _.debounce(
                function() {
                    axios.post('/api/search', {
                        label: this.firstName
                    }).then(
                        response => this.results = response.data
                    ).catch(
                        error => {
                    });
                }, 500),
            clearData: function(e) {
                if (e.target.id != 'search') {
                    this.firstName = null,
                    this.results = []
                }
            }
        }
    }
</script>
<style scoped>
    .card-body > ul{
        padding: 0;
    }
    .card-body > ul > li{
        margin-bottom: 15px;
        list-style: none;
    }
</style>
