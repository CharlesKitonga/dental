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
                        <th>Tag Name</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="tag in tags.data" :key="tag.id">
                      <td>{{tag.tag_name}}</td>
                      <td>{{tag.created_at  | myDate}}</td>
                      <td>
                          <a href="#" @click="editModal(tag)">
                              <i class="fa fa-edit text-blue"></i>
                          </a>

                           <a href="#" @click="deleteTag(tag.id)">
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
        <div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="tagModalMLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="tagModalLabel">Add Tag Details</h5>
                        <h5 class="modal-title" v-show="editmode" id="tagModalLabel">Update Tag Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateTag() :createTag()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.tag_name" type="text" name="tag_name" placeholder="Tag Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('tag_name') }">
                            <has-error :form="form" field="tag_name"></has-error>
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
                tags:{},
                // create a new form instance
                form: new Form({
                    id: '',
                    tag_name: ''
                })
            }
        },
        methods: {
            updateTag(){
                this.$Progress.start();
                this.form.put('api/tags/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#tagModal').modal('hide')
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
                $('#tagModal').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#tagModal').modal('show')
            },
            deleteTag(id){
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
                            this.form.delete('api/tags/'+id).then(()=>{
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
                axios.get("api/tags").then(({ data }) => (this.tags = data))
            },
            createTag(){
                // [Product.vue specific] When Product.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/tags')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Product.vue specific] When Product.vue is finish loading finish the progress bar
                    $('#tagModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Tag Added Successfully'
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

