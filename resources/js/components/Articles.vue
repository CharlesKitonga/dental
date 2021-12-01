<template>
    <div class="container">
        <div class="row mt-5"  >
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Article Details</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add Info <i class="fas fa-plus-square fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Article Heading</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="article in articles.data" :key="article.id">
                        <td>{{article.heading}}</td>
                        <td>{{article.category_id}}</td>
                        <td >{{ article.description | truncate(100, '...')}}</td>
                        <td>
                            <img class="img-fluid mb-3" :src="'./images/articles/'+article.photo" style="width:70px;" alt=" Avatar">
                        </td>
                        <td>
                            <a href="#" @click="editModal(article)">
                                <i class="fa fa-edit text-blue"></i>
                            </a>

                            <a href="#" @click="deleteArticle(article.id)">
                                <i class="fa fa-trash text-red"></i>
                            </a>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card-footer">

            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="articleModal" tabindex="-1" role="dialog" aria-labelledby="articleModalMLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="articleModalLabel">Add Article Details</h5>
                        <h5 class="modal-title" v-show="editmode" id="articleModalLabel">Update Article Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateArticle() :createArticle()">
                    <div class="modal-body">

                        <div class="form-group">
                            <input v-model="form.heading" type="text" name="heading" placeholder="Article heading"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('heading') }">
                            <has-error :form="form" field="heading"></has-error>
                        </div>

                        <div class="col-sm-10">
                            <input type="hidden" v-model="form.userId" :userId='userId'>

                        </div>

                        <div class="form-group">
                            <select  v-model="form.category_id" id="category_id" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('category_id') }">
                                <option value="">Select Category </option>
                                <option v-for="category in categories.data" :key="category.id" :value="category.id ">
                                    {{category.category_name}}
                                </option>
                            </select>
                            <has-error :form="form" field="category_id"></has-error>
                        </div>

                        <div class="form-group">
                            <select  v-model="form.tag_id" id="tag_id" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('tag_id') }">
                                <option value="">Select Tags </option>
                                <option v-for="tag in tags.data" :key="tag.id" :value="tag.id ">
                                    {{tag.tag_name}}
                                </option>
                            </select>
                            <has-error :form="form" field="tag_id"></has-error>
                        </div>

                        <div class="form-group">
                            <vue-editor v-model="form.description"></vue-editor>
                        </div>

                        <div class="form-group row">
                            <label for="photo" class="col-sm-4 col-form-label">Article Photo</label>
                            <div class="col-sm-6">
                                <input type="file" v-show="!editmode" accept="image/*" @change="uploadphoto" class="form-input"/>
                                <input type="file" v-show="editmode" accept="image/*" @change="updateArticlePic"  class="form-input"/>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Save Details</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Basic Use - Covers most scenarios
import { VueEditor } from "vue2-editor";

    export default {
        name: "Article",
        components: {
            VueEditor
        },
        props: ['userId'],
        computed: {
            userID() {
                return this.userId
            }
        },
        mounted() {
            console.log(this.$userId)
        },
        data(){

            return{
                //check if it's an edit function and switch to the modal
                editmode: false,
                //fetch articles from db using axios
                articles:{},
                categories:{},
                tags:{},
                // create a new form instance
                form: new Form({
                    id: '',
                    heading: '',
                    userId:'',
                    category_id: '',
                    description: '',
                    photo: ''
                })
            }
        },
        methods: {
            updateArticlePic(e){
                //console.log('uploading');
                //grab the file we are uploading
                let file = e.target.files[0];
                console.log(file);
                //convert the file to base63
                let reader = new FileReader();
                if (file['size'] < 2111775) {
                    reader.onloadend = (file) =>{
                        //console.log('RESULT', reader.result);
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire("Oops...", "You are uploading a large file!", "error");

                }

            },

            updateArticle(){
                this.$Progress.start();
                this.form.put('api/articles/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#articleModal').modal('hide')
                    Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    //else throw an error
                    this.$Progress.fail();
                    swal("Failed!", "There was Something Wrong.", "Warning");
                });
            },
            editModal(product){
                this.editmode = true;
                $('#articleModal').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#articleModal').modal('show')
            },
            deleteArticle(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                        //send request to the server
                        if (result.value) {
                            this.form.delete('api/articles/'+id).then(()=>{
                                    Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                    )
                                Fire.$emit('AfterCreate');
                            }).catch(()=>{
                                swal("Failed!", "There was Something Wrong.", "Warning");
                            });
                        }
                    })
            },
            uploadphoto(e){
                //console.log('uploading');
                //grab the file we are uploading
                let file = e.target.files[0];
                //console.log(file);
                //convert the file to base63
                let reader = new FileReader();
                if (file['size'] < 2111775) {
                    reader.onloadend = (file) =>{
                        //console.log('RESULT', reader.result);
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire("Oops...", "You are uploading a large file!", "error");

                }

            },
            loadBlogDetails(){
                axios.get("api/articles").then(({ data }) => (this.articles = data))
            },
            loadCategories(){
                axios.get("api/categories").then(({ data }) => (this.categories = data));
            },
            loadTags(){
                axios.get("api/tags").then(({ data }) => (this.tags = data));

            },
            createArticle(){
                // [Product.vue specific] When Product.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/articles')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Product.vue specific] When Product.vue is finish loading finish the progress bar
                    $('#articleModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Article Details Added Successfully'
                    })
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();

                })
            }
        },
        created() {
            this.loadBlogDetails();
            this.loadCategories();
            this.loadTags();
            /* this method sends http request every three seconds */
            //setInterval(() => this.loadBlogDetails(), 3000);
            Fire.$on('AfterCreate', () =>{
                this.loadBlogDetails();
                this.loadCategories();
                this.loadTags();
            });


        }
    }

</script>

