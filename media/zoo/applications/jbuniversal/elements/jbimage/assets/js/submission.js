jQuery(document).ready(function ($) {

    var a = function () {
    };

    $.extend(a.prototype, {
        name      :"JBImageSubmission",
        options   :{
            uri:""
        },
        initialize:function (a, c) {
            this.options = $.extend({}, this.options, c);

            var b = this;
            this.element = a;
            this.advanced = a.find("select.image");
            this.fileSelect = a.find(".file-select");
            this.selectImage = this.advanced.length ? this.advanced : a.find("input.image");

            a.find("span.image-cancel").bind("click", function () {
                b.selectImage.val("");
                b.sanatize()
            });

            this.advanced.length && this.selectImage.bind("change", function () {
                a.find("img").attr("src", JB_URL_ROOT + b.selectImage.val());
                b.sanatize();
            });

            $(this.fileSelect).change(function () {
                var value = this.value.replace(/^.*[\/\\]/g, '');
                a.find('.filename').val(value);
            });

            b.sanatize()
        },
        sanatize  :function () {
            if (this.selectImage.val()) {
                this.element.find("div.image-select").addClass("hidden");
                this.element.find("div.image-preview").removeClass("hidden");
            } else {
                this.element.find("div.image-select").removeClass("hidden");
                this.element.find("div.image-preview").addClass("hidden");
            }
        }
    });

    $.fn[a.prototype.name] = function () {
        var e = arguments, c = e[0] ? e[0] : null;

        return this.each(function () {
            var b = $(this);

            if (a.prototype[c] && b.data(a.prototype.name) && c != "initialize") {
                b.data(a.prototype.name)[c].apply(b.data(a.prototype.name), Array.prototype.slice.call(e, 1));

            } else if (!c || $.isPlainObject(c)) {
                var f = new a;
                a.prototype.initialize && f.initialize.apply(f, $.merge([b], e));
                b.data(a.prototype.name, f);

            } else {
                $.error("Method " + c + " does not exist on jQuery." + a.name);
            }
        });
    };

    $('#item-submission .jbimage-submission').JBImageSubmission({});

    var $parent = $('#item-submission .jbimage-submission').closest('.repeat-elements');
    $parent.find('p.add').bind('click', function () {
        var $elementRow = $parent.find('.jbimage-submission:last');
        $elementRow.JBImageSubmission();
        $elementRow.find('.image-cancel').click();
        $elementRow.find('input').val('');
    });

});
