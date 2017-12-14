<?php

$title = "Prove ur admin";

?>
<div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2">
    <h1>Submit the form</h1>
    <form method="post" action="/admin">
        <div class="form-group">
            <label class="col-form-label" for="inputPhone">Username:</label>
            <input type="text"
                   class="form-control"
                   name="username"
                   value=""
                   id="inputName">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="inputPassword">Password:</label>
            <input type="password"
                   class="form-control"
                   name="password"
                   value=""
                   id="inputPassword">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
