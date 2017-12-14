<!doctype html>
<html lang="en">
<head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/examples/narrow-jumbotron/narrow-jumbotron.css">
</head>
<body>
<div class="container">
    <header class="header clearfix">
        <nav>
            <ul class="nav nav-pills float-right">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/task/create">Create task</a>
                </li>
                <?php if(!\app\Application::getInstance()->getRequest()->getUser()): ?>
                <li class="nav-item">
                    <a class="nav-link active" href="/admin">Enter as admin</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <form action="/admin/logout" method="post">
                        <button type="submit" class="nav-link">Logout</button>
                    </form>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <h3 class="text-muted">Project</h3>
    </header>
    <div class="row">
        <?php echo $body; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script type="text/javascript">
    $('#exampleModal').on('show.bs.modal', function (event) {
        $('.data-clone').each(function (index, item) {
            var inputSelector = $(item).data('sourceField'),
                input = $(inputSelector)[0],
                reader;

            if (item.tagName == 'IMG') {
                if (input.files && input.files[0]) {
                    reader = new FileReader();

                    reader.onload = function(e) {
                        $(item).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $(item).html($(input).val());
            }
        })
    });

    $('.sendForm').on('click', function (event) {
        // debugger;
        var targetForm = event.target.dataset.submitForm;
        $(targetForm).submit();
    });
</script>

</body>
</html>