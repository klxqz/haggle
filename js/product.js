function HaggleProduct(form, options) {
    this.form = $(form);
    this.add2cart = this.form.find(".add2cart");
    this.button = this.add2cart.find("input[type=submit]");
    for (var k in options) {
        this[k] = options[k];
    }
    var self = this;


    this.form.find(".skus input[type=radio]").click(function () {

        if ($(this).data('disabled')) {
            self.button.attr('disabled', 'disabled');
        } else {
            self.button.removeAttr('disabled');
        }
        var sku_id = $(this).val();


    });
    var $initial_cb = this.form.find(".skus input[type=radio]:checked:not(:disabled)");
    if (!$initial_cb.length) {
        $initial_cb = this.form.find(".skus input[type=radio]:not(:disabled):first").prop('checked', true).click();
    }
    $initial_cb.click();

    this.form.find("select.sku-feature").change(function () {
        var key = "";
        self.form.find("select.sku-feature").each(function () {
            key += $(this).data('feature-id') + ':' + $(this).val() + ';';
        });
        
        var sku = self.features[key];

        if (sku) {
            if (sku.available) {
                self.button.removeAttr('disabled');
            } else {
                self.form.find("div.stocks div").hide();
                self.form.find(".sku-no-stock").show();
                self.button.attr('disabled', 'disabled');
            }
            self.add2cart.find(".price").data('price', sku.price);
        } else {
            self.form.find("div.stocks div").hide();
            self.form.find(".sku-no-stock").show();
            self.button.attr('disabled', 'disabled');

            self.add2cart(".price").empty();
        }
    });
    this.form.find("select.sku-feature:first").change();

    if (!this.form.find(".skus input:radio:checked").length) {
        this.form.find(".skus input:radio:enabled:first").attr('checked', 'checked');
    }


}

