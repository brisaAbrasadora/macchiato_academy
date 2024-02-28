<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left login section-heading probootstrap-animate">
                <h1 class="mb0">Sign-up</h1>
            </div>
        </div>
    </div>
</section>

<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-6" id="probootstrap-sidebar">
                        <h2>Turn into a Macchiato Academy student!</h2>
                        <p>Sign up using the form below.</p>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <form action="/validate-student-register" method="post" class="probootstrap-form" novalidate>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= $username ?>" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" value="<?= $email ?>" name="email"  />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password"  />
                            </div>
                            <div class="form-group">
                                <label for="passwordConfirm">Password confirm</label>
                                <input type="password" class="form-control" name="passwordConfirm"  />
                            </div>
                            <button class="btn btn-primary btn-lg">Sign up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>