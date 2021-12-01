<template>
    <div class="container">
        <div class="row mt-5"  >
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Frequently Asked Questions</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add Info <i class="fas fa-plus-square fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Topic</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="topic in topics.data" :key="topic.id">
                      <td>{{topic.topic}}</td>
                      <td>{{topic.description}}</td>
                      <td>
                          <a href="#" @click="editModal(topic)">
                              <i class="fa fa-edit text-blue"></i>
                          </a>
                          /
                           <a href="#" @click="deleteTopic(topic.id)">
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
        <div class="modal fade" id="homeModal" tabindex="-1" role="dialog" aria-labelledby="homeModalMLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="homeModalLabel">Add Topic Details</h5>
                        <h5 class="modal-title" v-show="editmode" id="homeModalLabel">Update Topic Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateTopic() :createTopic()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.topic" type="text" name="topic" placeholder="Topic Heading"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('topic') }">
                            <has-error :form="form" field="topic"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.description" type="text" name="description" placeholder="Describe the topic"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                            <has-error :form="form" field="description"></has-error>
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
    export default {
        data(){
            return{
                //check if it's an edit function and switch to the modal
                editmode: false,
                //fetch products from db using axios
                topics:{},
                // create a new form instance
                form: new Form({
                    id: '',
                    service: '',
                    description: '',
                    photo: ''
                })
            }
        },
        methods: {
          // method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.topics = response.data;
                    });
            },
            updateTopic(){
                this.$Progress.start();
                this.form.put('api/topics/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#homeModal').modal('hide')
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
                $('#homeModal').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#homeModal').modal('show')
            },
            deleteTopic(id){
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
                            this.form.delete('api/topics/'+id).then(()=>{
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
            loadTopic(){
                axios.get("api/topics").then(({ data }) => (this.topics = data))
            },
            createTopic(){
                // [Product.vue specific] When Product.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/topics')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Product.vue specific] When Product.vue is finish loading finish the progress bar
                    $('#homeModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Home Details Added Successfully'
                    })
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();

                })
            }
        },
        created() {
            //listen to a search event and send an http request
            Fire.$on('searching', ()=>{
                //fetch data from the root instance of the application <app.js>
                let query = this.$parent.search;
                //use axios to query the db
                axios.get('api/findUser?q='+ query)
                .then((data)=>{
                    this.topics = data.data
                })
                .catch(()=>{

                })

            });
            this.loadTopic();
            /* this method sends http request every three seconds */
            //setInterval(() => this.loadTopic(), 3000);
            Fire.$on('AfterCreate', () =>{
                this.loadTopic();
            });
        }
    }
</script>

