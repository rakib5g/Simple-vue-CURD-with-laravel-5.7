<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users Info </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success"
                                    @click="myModal">
                                <span class="fa fa-user-plus fa-fw"></span>
                                Create new user</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody><tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Create at</th>
                                <th class="text-center" width="12%">Action</th>
                            </tr>
                            <tr v-for="(user, index) in users" :key="index">
                                <td>{{ index +1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.type | upCase}}</td>
                                <td>{{ user.created_at | dateAndTime }}</td>
                                <td class="text-center">
                                    <a href="#" @click="editUser(user)" class="pr-2">
                                        <i class="fa fa-edit text-primary"></i>
                                    </a>
                                    <a href="#" @click="deleteUser(user.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1"
             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gray-light">
                        <h5 v-show="!editMode" class="modal-title" >New User Create</h5>
                        <h5 v-show="editMode" class="modal-title">Edit User info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() :createUser()" @keydown="form.onKeydown($event)" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name" placeholder="Name"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="form.email" type="text" name="email" placeholder="Valid Email"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label>User Type</label>
                            <select v-model="form.type" name="type"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                <option>Select user type</option>
                                <option value="admin">Admin</option>
                                <option value="author">Author</option>
                                <option value="editor">Editor</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Bio</label> <small class="text-muted">(Short bio for user)</small>
                            <textarea v-model="form.bio" name="bio" placeholder="About User"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                            </textarea>
                            <has-error :form="form" field="bio"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input v-model="form.password" type="password" name="password" placeholder="Password"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-light">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                        <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
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
          return {
              editMode: false,
              users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    type: '',
                    image: '',
                    bio: '',
                    password: ''
                })
            }
        },
        methods: {
            myModal(){
                this.editMode = false;
                this.form.reset();
                $('#myModal').modal('show');
            },
            //all data read from user table....
            loadUser(){
              axios.get('api/user').then( ({ data })  => (this.users = data.data))
            },
            //Create user....
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then( () => {
                    Fire.$emit('AutoRefresh');
                    $('#myModal').modal('hide');
                    toast({
                        type: 'success',
                        title: 'User has been Created successfully'
                    });
                    this.$Progress.finish();
                })
                .catch( () => {
                    toast({
                        type: 'error',
                        title: 'Somethings went wrong.'
                    });
                });

            },
            //edit user....
            editUser(user){
                this.editMode = true;
                this.form.reset();
                $('#myModal').modal('show');
                this.form.fill(user);
            },
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/' + this.form.id)
                    .then(() => {
                        Fire.$emit('AutoRefresh');
                        $('#myModal').modal('hide');
                        toast({
                            type: 'success',
                            title: 'User has been Updated successfully'
                        });
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        toast({
                            type: 'error',
                            title: 'Somethings went wrong.'
                        });
                    });
            },
            //delete user....
            deleteUser(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/' + id)
                            .then(() => {
                                swal(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                Fire.$emit('AutoRefresh');
                            })
                            .catch( () => {
                                swal(
                                    'Failed!',
                                    'Somethings went wrong.',
                                    'warning'
                                );
                            });
                    }
                });

            }
        },
        created(){
            this.loadUser();
            //page reload...
            // setInterval(() => this.loadUser(), 3000);
            Fire.$on('AutoRefresh', () => {
                this.loadUser();
            });
        }
    }
</script>
