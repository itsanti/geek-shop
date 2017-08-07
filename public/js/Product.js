function Product() {
    this.title = "";
    this.description = "";
    this.price = 0;
    this.number = 0;
}

Product.prototype.add = function (url, method, product_id) {
    if (method == 'post') {
        $.ajax({
            url: url,
            type: 'POST',
            data:
                {
                    "product_id": product_id
                },
            context: this,
            success: function (data) {
                alert('товар добавлен. всего в корзине: ' + data.products_number);
            },
            dataType: 'json'
        });
    }
};
