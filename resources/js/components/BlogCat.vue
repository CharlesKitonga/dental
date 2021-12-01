<template>
    <div class="container">
        <div class="row mt-5"  >
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Category Details</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add Info <i class="fas fa-plus-square fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="category in categories.data" :key="category.id">
                      <td>{{category.category_name}}</td>
                      <td>{{category.status}}</td>
                      <td>
                          <a href="#" @click="editModal(category)">
                              <i class="fa fa-edit text-blue"></i>
                          </a>

                           <a href="#" @click="deleteCategory(category.id)">
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
        <div class="modal fade" id="blogModal" tabindex="-1" role="dialog" aria-labelledby="blogModalMLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="blogModalLabel">Add Blog Category Details</h5>
                        <h5 class="modal-title" v-show="editmode" id="blogModalLabel">Update Blog Category Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateBlog() :createBlog()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.category_name" type="text" name="category_name" placeholder="Category Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('category_name') }">
                            <has-error :form="form" field="category_name"></has-error>
                        </div>
                        <div class="form-check">
                            <input  v-model="form.status" type="checkbox" value="1" class="form-check-input" :class="{ 'is-invalid': form.errors.has('status') }" >
                            <label class="form-check-label" for="status">Enable Category</label>
                            <has-error :form="form" field="status"></has-error>
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
        name: "BlogCat",
        components: {
            VueEditor
        },
        data(){
            return{
                //check if it's an edit function and switch to the modal
                editmode: false,
                //fetch products from db using axios
                categories:{},
                // create a new form instance
                form: new Form({
                    id: '',
                    category_name: '',
                    status: ''
                })
            }
        },
        methods: {
            updateBlog(){
                this.$Progress.start();
                this.form.put('api/categories/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#blogModal').modal('hide')
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
                $('#blogModal').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#blogModal').modal('show')
            },
            deleteCategory(id){
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
                            this.form.delete('api/categories/'+id).then(()=>{
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
            loadBlogDetails(){
                axios.get("api/categories").then(({ data }) => (this.categories = data))
            },
            createBlog(){
                // [Product.vue specific] When Product.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/categories')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Product.vue specific] When Product.vue is finish loading finish the progress bar
                    $('#blogModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Category Added Successfully'
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
            /* this method sends http request every three seconds */
            //setInterval(() => this.loadBlogDetails(), 3000);
            Fire.$on('AfterCreate', () =>{
                this.loadBlogDetails();
            });
        }
    }
</script>

