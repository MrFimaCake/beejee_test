<?php

/** @var \app\TaskEntity $model */

$title = 'Update task';

?>

<div class="col-md-12 col-lg-12">
    <h1><?php echo $title; ?></h1>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post"
          action="/task/update-save?id=<?php echo $model->id; ?>"
          enctype="multipart/form-data"
          id="taskSaveForm">
        <div class="form-group">
            <label class="col-form-label" for="inputUsername">Username:</label>
            <input type="text"
                   class="form-control"
                   name="username"
                   value="<?php echo $model->username; ?>"
                   required
                   id="inputUsername">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="inputEmail">Email:</label>
            <input type="email"
                   class="form-control"
                   name="email"
                   value="<?php echo $model->email; ?>"
                   required
                   id="inputEmail">
        </div>
        <div class="form-group">
            <label class="col-form-label" for="inputText">Text:</label>
            <textarea
                class="form-control"
                name="task_body"
                required
                id="inputText"><?php echo $model->task_body; ?></textarea>
        </div>
        <div class="form-group">
            <label class="col-form-label" for="inputText">Status "done":</label>
            <input type="hidden"
                   value="<?php echo \app\TaskEntity::TASK_STATUS_NEW; ?>"
                   name="task_status">
            <?php $valueDone = \app\TaskEntity::TASK_STATUS_DONE; ?>
            <input
                class="form-control"
                name="task_status"
                type="checkbox"
                <?php echo $model->task_status == $valueDone ? 'checked' : ''; ?>
                value="<?php echo \app\TaskEntity::TASK_STATUS_DONE; ?>"
                required
                id="inputStatus"/>
        </div>
        <div class="form-group">
            <input type="file" id="taskFile" name="task_file">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
            Preview
        </button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
     data-source-form="#taskSaveForm">
    <div class="container">
        <div class="modal-dialog" style="max-width: inherit" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Task example</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group" style="width: 100%;">
                        <!--        <div class="card">-->
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="card-body">
                                <img class="card-img-top data-clone"
                                     data-source-field="#taskFile"
                                     style="width: auto; height: auto;max-width: 320px; max-height: 240px;"
                                     src="" alt="">
                                <p>ID: &infin;</p>
                                <h5 class="data-clone" data-source-field="#inputText">task body</h5>
                                <p>User: <span class="data-clone" data-source-field="#inputUsername">user</span></p>
                                <p>Email: <span class="data-clone" data-source-field="#inputEmail">example@mail.com</span></p>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary sendForm" data-submit-form="#taskSaveForm">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>