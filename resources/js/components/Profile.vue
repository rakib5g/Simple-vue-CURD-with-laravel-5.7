<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Profile Component</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-gray">
                                    <div class="card-body">
                                        <form action="">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input v-model="form.name" type="text" name="name" id="name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="bio">Bio</label> <small class="text-muted">A short bio</small>
                                                <textarea name="bio" id="bio" class="form-control"></textarea>
                                            </div>
                                            <div class="">
                                                <label for="image">Profile image</label><br>
                                                <input type="file" name="image" id="image" class="" />
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Change</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div style="width: 100px; height: 120px; margin: auto" class="mb-2">
                                            <img class="img-circle img-fluid" src="/img/student.png" alt="profile.png">
                                        </div>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <i class="fa fa-user pr-2 text-danger"></i>
                                                <span>Name</span>
                                                <span class="badge badge-warning pl">Admin</span>
                                                <p class="text-muted">Mr name</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-envelope pr-2 text-danger"> </i><span>Email</span>
                                                <p class="text-muted">example@email.com</p>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fas fa-address-card pr-2 text-danger"> </i><span>Bio</span>
                                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur ea esse et laborum maxime modi placeat quibusdam repellendus rerum vitae.</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return {
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
        created(){
            axios.get('api/profile').then( ({ data }) => (this.form.fill(data)));
        }
    }
</script>
