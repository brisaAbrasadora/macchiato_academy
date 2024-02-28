<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left section-heading probootstrap-animate">
                <h1 class="mb0">Control Panel</h1>
            </div>
        </div>
    </div>
</section>

<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <?php include __DIR__ . '/parts/sidebar.php'; ?>
                    <div class="col-md-7 col-md-push-1 probootstrap-animate" id="probootstrap-content">
                        <h2>Register new user</h2>
                        <p>Register a student or a teacher.</p>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <form action="/validate-user-register" method="post" class="probootstrap-form" novalidate>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= $username ?? '' ?>" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" value="<?= $email ?? '' ?>" name="email" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <div class="form-group">
                                <label for="passwordConfirm">Password confirm</label>
                                <input type="password" class="form-control" name="passwordConfirm" />
                            </div>
                            <div class="form-group">
                                <label for="type">Type of user</label>
                                <select name="type" class="form-control">
                                    <option value="student" selected>Student</option>
                                    <option value="teacher">Teacher</option>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-lg">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>