<?php
/** @var \app\View $this */
/** @var \app\utils\Paginator $paginator */
/** @var array $list */
/** @var int $currentPage */
/** @var int $totalCount */
?>
<div class="btn-group" role="group" aria-label="sort buttons">

    <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            username
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <a class="dropdown-item" href="/tasks?sort-type=username&sort-value=asc">asc</a>
            <a class="dropdown-item" href="/tasks?sort-type=username&sort-value=desc">desc</a>
        </div>
    </div>
    <div class="btn-group" role="group">
        <button id="btnGroupDrop2" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            email
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
            <a class="dropdown-item" href="/tasks?sort-type=email&sort-value=asc">asc</a>
            <a class="dropdown-item" href="/tasks?sort-type=email&sort-value=desc">desc</a>
        </div>
    </div>
    <div class="btn-group" role="group">
        <button id="btnGroupDrop3" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            status
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
            <a class="dropdown-item" href="/tasks?sort-type=email&sort-value=asc">asc</a>
            <a class="dropdown-item" href="/tasks?sort-type=email&sort-value=desc">desc</a>
        </div>
    </div>
</div>
<!--<div class="col-md-6 offset-md-2 col-lg-6 offset-lg-2">-->
<div class="list-group" style="width: 100%;">
<!--        <div class="card">-->
    <?php foreach($list as $item): ?>
    <?php /** @var \app\TaskEntity $item */ ?>
        <div class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="card-body">
                <?php if($item->image_path): ?>
                    <img class="card-img-top"
                         style="width: auto; height: auto;max-width: 320px; max-height: 240px;"
                         src="<?php echo $item->image_path; ?>" alt="">
                <?php endif;?>
                <p>ID: <?php echo $item->id; ?>
                    <?php if($item->getIsDone()): ?>
                        <span class="badge badge-success">Task done</span>
                    <?php endif; ?>
                </p>
                <h5><?php echo $item->task_body; ?></h5>
                <p>User: <?php echo $item->username; ?></p>
                <p>Email: <?php echo $item->email; ?></p>
                <?php if(\app\Application::getInstance()->getRequest()->getUser()): ?>
                <a href="/task/update?id=<?php echo $item->id; ?>">Update</a>
                <a href="/task/delete?id=<?php echo $item->id; ?>">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;?>

    <nav aria-label="Page navigation example" style="margin-top: 20px;">
        <ul class="pagination">
            <?php [$key, $prev] = $paginator->getPrevValue(); ?>
            <li class="page-item <?php echo !$prev ? 'disabled' : '' ?>">
                <a class="page-link" href="<?php echo $prev ?>">Prev</a>
            </li>

        <?php foreach($paginator as $key => $link): ?>
            <li class="page-item"><a class="page-link" href="<?php echo $link ?>"><?php echo $key ?></a></li>
        <?php endforeach;?>
            <?php [$key, $next] = $paginator->getNextValue(); ?>
            <li class="page-item <?php echo !$next ? 'disabled' : '' ?>">
                <a class="page-link" href="<?php echo $next ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
