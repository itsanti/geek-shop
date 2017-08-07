function DropDown(el) {
    this.dd = el;
    this.placeholder = this.dd.children('span');
    this.opts = this.dd.find('ul.dropdown > li');
    this.val = '';
    this.index = -1;
    this.initEvents();
}
DropDown.prototype = {
    initEvents : function() {
        var obj = this;
        obj.placeholder.html(obj.opts[0].innerHTML);
        obj.dd.on('click', function(event){
            if ( $(this).hasClass('active') ) {
                $(this).removeClass('active');
            } else {
                $(".choose__select_select").removeClass('active');
                $(this).toggleClass('active');
            }
            return false;
        });

        obj.opts.on('click', function () {
            var opt = $(this);
            obj.val = opt.html();
            obj.index = opt.index();
            obj.placeholder.html(obj.val);
        });

    }
};

$(document).click(function() {
    $(".choose__select_select").removeClass('active');
});
