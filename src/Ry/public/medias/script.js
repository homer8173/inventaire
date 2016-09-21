var ngApp = angular.module("ngApp", ['ui.sortable','colorpicker.module'])
    .filter("notselected", function(){
        return function(input, combis){
            if(input) {
                var ar = input.filter(function(item){
                	return !item.selected;
                });
                return ar;
            }
        }
    })
    .controller("ProductController", ["$scope", function($scope){
        $(".datepicker").datepicker();

        $scope.attributes = data.attributes;
        $scope.product = data.product;
        $scope.row = data.row;

        var row_default = {
            price_impact : "0",
            weight_impact : "0",
            unit_impact : "0",
            minimal_quantity : 1,
            absunity : 0,
            absweight : 0
        };

        $scope.row = angular.merge(row_default, $scope.row);

        $scope.row.price_impact += "";
        $scope.row.weight_impact += "";
        $scope.row.unit_impact += "";
        $scope.row.minimal_quantity = parseInt($scope.row.minimal_quantity);
        $scope.row.absprice = Math.abs(parseFloat($scope.row.price));
        //$scope.absweight = Math.abs($scope.weight);
        //$scope.absunity = Math.abs($scope.unity);

        /*combinaisons*/
        $scope.combinations = $scope.row.combinations ? $scope.row.combinations : [];

        if($scope.combinations.length > 0) {
            $scope.combinations.map(function(item){
                $scope.attributes.map(function(item2){
                    if(item2.id_attribute_group == item.id_attribute_group)
                        item2.selected = true;
                });
            });
        }

        $scope.add_combination = function(){
            $scope.attributes.map(function(item){
                if(item.id_attribute_group == $scope.attribute.id_attribute_group) {
                    item.selected = true;
                    item.values.map(function(item2){
                        if(item2.id_attribute == $scope.value) {
                            $scope.combinations.push({
                                value : item2,
                                attribute : $scope.attribute
                            });
                            item2.selected = true;
                        }
                    });
                }
            });
        };
        $scope.remove_combination = function(c){
            $scope.attributes.map(function(item){
                if(item.id_attribute_group == c.attribute.id_attribute_group) {
                    item.selected = null;
                    delete item.selected;
                    item.values.map(function(item2){
                        if(item2.id_attribute == c.value.id_attribute) {
                            item2.selected = null;
                            delete item2.selected;
                            var new_combinations = $scope.combinations.filter(function(item3){
                                if(item3.value.id_attribute == c.value.id_attribute)
                                    return false;
                                return true;
                            });
                            $scope.combinations = new_combinations;
                        }
                    });
                }
            });
        };

        /*calcImpactPriceTI*/
        $scope.$watch("row.price_impact", function(newvalue, oldvalue){
            calcImpactPriceTI($scope.row.absprice);
        });
        $scope.absprice_change = function(){
            calcImpactPriceTI($scope.row.absprice);
        };
        $scope.abspriceTI_change = function(newvalue, oldvalue){
            calcImpactPriceTE($scope.row.abspriceTI);
        };

        $scope.impec = function(){
            return $scope.combinations.length > 0;
        };
        
        $scope.combisubmit = function(){
        	$("[name='combi']").val(angular.toJson($scope.combinations));
        };

        //unite

        var getTaxes = function ()
        {
            /*if (noTax)
                taxesArray[taxId];

            var selectedTax = document.getElementById('id_tax_rules_group');
            var taxId = selectedTax.options[selectedTax.selectedIndex].value;*/
            return taxesArray[3];
        }

        var addTaxes = function(price)
        {
            var taxes = getTaxes();
            var price_with_taxes = price;
            if (taxes.computation_method == 0) {
                for (i in taxes.rates) {
                    price_with_taxes *= (1 + taxes.rates[i] / 100);
                    break;
                }
            }
            else if (taxes.computation_method == 1) {
                var rate = 0;
                for (i in taxes.rates) {
                    rate += taxes.rates[i];
                }
                price_with_taxes *= (1 + rate / 100);
            }
            else if (taxes.computation_method == 2) {
                for (i in taxes.rates) {
                    price_with_taxes *= (1 + taxes.rates[i] / 100);
                }
            }

            return price_with_taxes;
        }

        var removeTaxes = function (price)
        {
            var taxes = getTaxes();
            var price_without_taxes = price;
            if (taxes.computation_method == 0) {
                for (i in taxes.rates) {
                    price_without_taxes /= (1 + taxes.rates[i] / 100);
                    break;
                }
            }
            else if (taxes.computation_method == 1) {
                var rate = 0;
                for (i in taxes.rates) {
                    rate += taxes.rates[i];
                }
                price_without_taxes /= (1 + rate / 100);
            }
            else if (taxes.computation_method == 2) {
                for (i in taxes.rates) {
                    price_without_taxes /= (1 + taxes.rates[i] / 100);
                }
            }

            return price_without_taxes;
        }

        var calcImpactPriceTI = function(absprice)
        {
            var priceTE = parseFloat(absprice);
            var newPrice = addTaxes(priceTE);

            $scope.row.abspriceTI = (isNaN(newPrice) == true || newPrice < 0) ? '' : ps_round(newPrice, priceDisplayPrecision).toFixed(priceDisplayPrecision);

            var total = ps_round((parseFloat($scope.row.abspriceTI) * parseInt($scope.row.price_impact) + parseFloat($scope.product.price_ttc)), priceDisplayPrecision);
            if (isNaN(total) || total < 0)
                $scope.final_price = 0.00;
            else
                $scope.final_price = total;
        };

        var calcImpactPriceTE = function(abspriceTI)
        {
            console.log("oe sady voantso ko ty ");
            var priceTI = parseFloat(abspriceTI);
            priceTI = (isNaN(priceTI)) ? 0 : ps_round(priceTI);
            var newPrice = removeTaxes(ps_round(priceTI, priceDisplayPrecision));
            $scope.row.absprice = (isNaN(newPrice) == true || newPrice < 0) ? '' : ps_round(newPrice, 6).toFixed(6);
            var total = ps_round((parseFloat(abspriceTI) * parseInt($scope.row.price_impact) + parseFloat($scope.product.price_ttc)), priceDisplayPrecision);
            if (isNaN(total) || total < 0)
                $scope.final_price = 0.00;
            else
                $scope.final_price = total;
        }

        /*$scope.calcImpactPriceTI = function ()
        {
            var priceTE = parseFloat(document.getElementById('attribute_priceTEReal').value.replace(/,/g, '.'));
            var newPrice = addTaxes(priceTE);
            $('#attribute_priceTI').val((isNaN(newPrice) == true || newPrice < 0) ? '' : ps_round(newPrice, priceDisplayPrecision).toFixed(priceDisplayPrecision));
            var total = ps_round((parseFloat($('#attribute_priceTI').val()) * parseInt($('#attribute_price_impact').val()) + parseFloat($('#finalPrice').html())), priceDisplayPrecision);
            if (isNaN(total) || total < 0)
                $('#attribute_new_total_price').html('0.00');
            else
                $('#attribute_new_total_price').html(total);
        };*/

    }])
    .controller("AttributesController", ["$scope", function($scope){
    $scope.rows = data;
    var origOrder = data.map(function(i){
        return i.id_attribute_group;
    }).join(', ');
    $scope.orderChanged = false;
    $scope.sortableOptions = {
        handle: '.sortHandle',
        stop: function(){
            for(var i=0; i<data.length; i++) {
                data[i].position = i;
            }
            var order = data.map(function(i){
                return i.id_attribute_group;
            }).join(', ');
            console.log((order == origOrder));
            $scope.orderChanged = (order != origOrder);
        }
    };
}]).controller("ValuesController", ["$scope", function($scope){
    $scope.rows = data;
    var origOrder = data.map(function(i){
        return i.id_attribute;
    }).join(', ');
    $scope.orderChanged = false;
    $scope.sortableOptions = {
        handle: '.sortHandle',
        stop: function(){
            for(var i=0; i<data.length; i++) {
                data[i].position = i;
            }
            var order = data.map(function(i){
                return i.id_attribute;
            }).join(', ');
            console.log((order == origOrder));
            $scope.orderChanged = (order != origOrder);
        }
    };
}]);
