<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left login section-heading probootstrap-animate">
                <h1 class="mb0">Login</h1>
            </div>
        </div>
    </div>
</section>

<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-4" id="probootstrap-sidebar">
                        <h2>It is so good to see you again!</h2>
                        <p>Login using the form below.</p>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <form action="/check-login" method="post" class="probootstrap-form">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" value="<?= $email ?? '' ?>" name="email" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <button class="btn btn-primary btn-lg">Login</button>
                            <div class="form-group">
                                <!-- <input
                  type="submit"
                  class="btn btn-primary btn-lg"
                  id="submit"
                  name="submit"
                  value="Login"
                /> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>