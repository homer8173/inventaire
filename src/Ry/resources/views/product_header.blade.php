@section("header")
    <?php
    llxHeader('', '', '', '', 0, 0,
            $arrayofjs=array(
                    "/prestashop/src/Ry/public/medias/angular/angular.min.js",
                    "/prestashop/src/Ry/public/medias/angular-ui-sortable/sortable.min.js",
                    "/prestashop/src/Ry/public/medias/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.min.js",
                    "/prestashop/src/Ry/public/medias/prestashop.js",
                    "/prestashop/src/Ry/public/medias/script.js"
            ),
            $arrayofcss=array(
                    "/prestashop/src/Ry/public/medias/angular-bootstrap-colorpicker/css/colorpicker.min.css"
            ));
    dol_fiche_head($head, $active = 'declinations', $title = 'Déclinaisons', $notab = 0, $picto = 'product');
    ?>
@stop