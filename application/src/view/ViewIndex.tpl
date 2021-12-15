<html>
<head>
    <title>Solomono</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
<main>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <span class="fs-4"><?php echo $data['text_categories']; ?></span>
                </a>
                <hr>
                <?php if($data['categories']) { ?>
                <ul class="nav nav-pills flex-column mb-auto">
                    <?php foreach ($data['categories'] as $category) { ?>
                    <li class="nav-item">
                        <?php if($data['filterCategory'] === $category['cat_id']){ ?>
                        <a href="" class="nav-link active"
                           aria-current="page" data-catid="<?php echo $category['cat_id']; ?>">
                            <?php echo $category['name']; ?> (<?php echo $category['products_total'];?>)
                        </a>
                        <?php } else { ?>
                        <a href="" class="nav-link"
                           aria-current="page" data-catid="<?php echo $category['cat_id']; ?>">
                            <?php echo $category['name']; ?> (<?php echo $category['products_total'];?>)
                        </a>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
                <hr>
            </div>

            <div class="col-md-10">
                <div class="row" style="padding: 0.2rem 0 2rem 0;">
                    <select class="form-select" id="sort">
                        <option value="default"><?php echo $data['text_sort'] ?></option>
                        <?php foreach ($data['sort'] as $sort){ ?>
                            <?php if($data['filterSort'] === $sort['name']){ ?>
                            <option value="<?php echo $sort['value']?>" selected="selected"><?php echo $sort['text'] ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $sort['value']?>"><?php echo $sort['text'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

                <div class="row row-cols-1 row-cols-md-3 mb-3 text-center" id="product_container">
                    <?php if($data['products']) { ?>
                    <?php foreach ($data['products'] as $product) { ?>
                    <div class="col" id="product_<?php echo $product['prod_id']; ?>">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal"><?php echo $product['name']; ?></h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title">$<?php echo $product['price']; ?></h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    <li><?php echo $data['text_date']; ?>: <?php echo $product['date']; ?></li>
                                    <?php echo $data['text_categories']; ?> :
                                    <?php foreach ($product['categories'] as $product_category) { ?>
                                    <li>
                                        <?php echo $product_category['name']; ?></li>
                                    <?php } ?>
                                </ul>
                                <button onclick="ButtonBuy('product_' + <?php echo $product['prod_id']; ?>)"
                                        type="button" data-product_id="<?php echo $product['prod_id']; ?>"
                                        class="button-buy w-100 btn btn-lg btn-outline-primary"><?php echo $data['text_buy']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

</main>
<div class="modal modal-sheet position-fixed d-none bg-secondary py-5" style="--bs-bg-opacity: .7;" tabindex="-1"
     role="dialog" id="modalSheet" onclick="ModalClose()">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-6 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title"><?php echo $data['text_full_product']; ?></h5>
                <button type="button" class="btn-close" onclick="ModalClose()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="row row-cols-1 row-cols-md-1 mb-1 text-center">
                <div class="modal-body py-0">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function ButtonBuy(prodId) {
        $('.modal').removeClass('d-none').addClass('d-block');
        $('#' + prodId).clone().appendTo('#modalSheet .modal-body');
        $('#modalSheet .modal-body #' + prodId + ' .button-buy').remove();
    }

    $(".modal-dialog").on("click", function (event) {
        event.stopPropagation();
    });

    function ModalClose() {
        $('.modal').removeClass('d-block').addClass('d-none');
        $('#modalSheet .modal-body').html('');

    }
</script>

<script type="text/javascript">
    $('.nav-link').on('click', function () {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        let categoryId = $(this).attr('data-catid');
        Reload(categoryId);
        return false;
    });

    $('#sort').on('change', function () {
        let categoryId = ($('.nav-link.active')[0]) ? $('.nav-link.active').attr('data-catid') : '';
        Reload(categoryId);
    });

    function Reload(categoryId) {
        let sorting = '';
        let sortingUrl = '';
        let categoryUrl = '';

        if(categoryId){
            categoryUrl = '&category=' +categoryId;
        }

        if($('#sort').val() !== 'default'){
            sorting = $('#sort').val();
            sortingUrl = '&sort=' + sorting;
        }
        //let sorting = ($('#sort').val() !== 'default') ? $('#sort').val() : '';
        let text_date = "<?php echo $data["text_date"] ?>";
        let text_buy = "<?php echo $data["text_buy"] ?>";
        let text_categories = "<?php echo $data["text_categories"] ?>";
        let nextURL = "/index.php?route=index/index" + categoryUrl + sortingUrl;

        $('#product_container').html('');

        $.ajax({
            url: 'index.php?route=index/reload',
            method: 'post',
            dataType: 'json',
            data: {category: categoryId, sort: sorting},
            success: function (json) {
                $.each(json, function (i, item) {
                    let html = '';
                    html += '<div class="col" id="product_' + item.prod_id + '">';
                    html += '<div class="card mb-4 rounded-3 shadow-sm">';
                    html += '<div class="card-header py-3">';
                    html += '<h4 class="my-0 fw-normal">' + item.name + '</h4>';
                    html += '</div>';
                    html += '<div class="card-body">';
                    html += '<h1 class="card-title pricing-card-title">$' + item.price + '</h1>';
                    html += '<ul class="list-unstyled mt-3 mb-4">';
                    html += '<li>' + text_date + ': ' + item.date + '</li>';
                    html += text_categories + ' :';
                    $.each(item.categories, function (c, cat)
                    {
                        html += '<li>' + cat.name + '</li>';
                    });
                    html += '</ul>';
                    html += '<button onclick="ButtonBuy(\'product_' + item.prod_id + '\')" type="button" data-product_id="' + item.prod_id + '" class="button-buy w-100 btn btn-lg btn-outline-primary">' + text_buy + ' </button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    $('#product_container').append(html);
                });

                //redirect w/o reload
                window.history.replaceState(null,null, nextURL);
            },
            error: function () {
                console.log('err')
            }
        });

    }
</script>

</body>

</html>
