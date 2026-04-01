;(function ($, window, document, undefined) {
    var pluginName = "editable",
        defaults = {
            keyboard: true,
            dblclick: true,
            button: true,
            buttonSelector: ".edit",
            maintainWidth: true,
            dropdowns: {},
            percent: {},
            url_edit: '',
            csrf_token: '',
            edit: function() {},
            save: function() {},
            cancel: function() {}
        };

    function editable(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    editable.prototype = {
        init: function() {
            this.editing = false;

            if (this.options.dblclick) {
                $(this.element)
                    .css('cursor', 'pointer')
                    .on('dblclick', this.toggle.bind(this));  
            }

            if (this.options.button) {
                $(this.options.buttonSelector, this.element)
                    .on('click', this.toggle.bind(this));
            }
        },

        toggle: function(e) {
            e.preventDefault();

            this.editing = !this.editing;

            if (this.editing) {
                this.edit();
            } else {
                this.save();
            }
        },

        edit: function() {
            var instance = this,
                values = {};

            $('td[data-field]', this.element).each(function() {
                
                var input,
                    field = $(this).data('field'),
                    value = $(this).text(),
                    width = $(this).width(),
                    select = $(this).children('.id').val();
                    po_date = $(this).children('.date').val();
                    shipping_date = $(this).children('.date').val();
                    atdc_date = $(this).children('.date').val();


                values[field] = value;

                $(this).empty();

                if (instance.options.maintainWidth) {
                    $(this).width(width);
                }

                if (field in instance.options.dropdowns) {
                    input = $('<select></select>');

                    for (var i = 0; i < instance.options.dropdowns[field].length; i++) {
                        key = Object.keys(instance.options.dropdowns[field][i]);
                        $('<option></option>')
                             .text(key)
                             .val(instance.options.dropdowns[field][i][key])
                             .appendTo(input);
                    };

                    input.val(select)
                         .data('old-value', value)
                         .dblclick(instance._captureEvent);
                } else if (field in instance.options.percent) {
                    input = $('<select ></select>');

                    for (var i = 0; i < instance.options.percent[field].length; i++) {
                        $('<option></option>')
                             .text(instance.options.percent[field][i])
                             .appendTo(input);
                    };

                    input.val(value)
                         .data('old-value', value)
                         .dblclick(instance._captureEvent);
                } else if (field === 'po_date'){ // date
                    //console.log('po_date:'+value);
                    input = $('<input type="date" style="width:120px" />')
                        .val(po_date)
                        .data('old-value', po_date)
                        .dblclick(instance._captureEvent);
                } else if (field === 'shipping_date'){ // date
                    //console.log('shipping_date:'+value);
                    input = $('<input type="date" style="width:120px" />')
                        .val(shipping_date)
                        .data('old-value', shipping_date)
                        .dblclick(instance._captureEvent);
                } else if (field === 'atdc_date'){ // date
                    //console.log('atdc_date:'+value);
                    input = $('<input type="date" style="width:120px" />')
                        .val(atdc_date)
                        .data('old-value', atdc_date)
                        .dblclick(instance._captureEvent);
                } else if (field === 'po_line_item'){ // number
                    input = $('<input type="number" style="width:100px" />')
                        .val(value)
                        .data('old-value', value)
                        .dblclick(instance._captureEvent);
                } else {
                    input = $('<input type="text" style="width:100px" />')
                        .val(value)
                        .data('old-value', value)
                        .dblclick(instance._captureEvent);
                }

                input.appendTo(this);

                if (instance.options.keyboard) {
                    input.keydown(instance._captureKey.bind(instance));
                }
            });

            this.options.edit.bind(this.element)(values);
        },

        save: function() {
            var instance = this,
                values = {},
                valEdit = {};

            $('td[data-field]', this.element).each(function() {
                var value = '';
                var hidden = '';
                var edit = '';
                var field = $(this).data('field');

                if(field === 'xbrand' || field === 'xcontainer_type'){
                    value = $(':input option:selected', this).text();
                    edit = $(':input option:selected', this).val();
                    hidden = '<input type="hidden" class="id" value="'+$(':input option:selected', this).val()+'"/>';
                }else if (field === 'po_date' || field === 'shipping_date' || field === 'atdc_date'){ // date

                    value = moment($(':input', this).val()).format('DD-MM-YYYY');
                    edit = $(':input', this).val();
                    hidden = '<input type="hidden" class="date" value="'+$(':input', this).val()+'"/>';
                
                }else{
                    value = $(':input', this).val();
                    edit = $(':input', this).val();
                }

                values[$(this).data('field')] = value;
                valEdit[$(this).data('field')] = edit;
                $(this).empty().html(value+hidden);
            });
            valEdit['po_article'] = $(this.element).attr('po_article');
            valEdit['article'] = $(this.element).attr('article');
            valEdit['_token'] = instance.options.csrf_token;

            console.log('_token:'+$('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: instance.options.url_edit,
                method:"POST",
                headers: {'X-CSRF-TOKEN': instance.options.csrf_token},
                data: valEdit,
                dataType:"json",
                success:function(data)
                {
                    console.log(data);
                }
             });

            this.options.save.bind(this.element)(values);
        },
        cancel: function() {
            var instance = this,
                values = {};

            $('td[data-field]', this.element).each(function() {
                var value = $(':input', this).data('old-value');

                values[$(this).data('field')] = value;

                $(this).empty()
                       .text(value);
            });

            this.options.cancel.bind(this.element)(values);
        },
        _captureEvent: function(e) {
            e.stopPropagation();
        },

        _captureKey: function(e) {
            if (e.which === 13) {
                this.editing = false;
                this.save();
            } else if (e.which === 27) {
                this.editing = false;
                this.cancel();
            }
        }
    };

    $.fn[pluginName] = function(options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new editable(this, options));
            }
        });
    };

})(jQuery, window, document);