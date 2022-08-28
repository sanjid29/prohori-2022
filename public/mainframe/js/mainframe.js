// noinspection JSUnusedGlobalSymbols
/**
 * Ckeditor config
 * @type json
 */
var editor_config_basic = {
    toolbarGroups: [{"name": "basicstyles", "groups": ["basicstyles"]}],
    removeButtons: 'CreateDiv,Styles,Format,Font',
    enterMode: CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
    autoParagraph: false // stop from automatically adding <p></p> tag
};

var editor_config_extended = {
    // readOnly: true, // make editor readonly
    // Define the toolbar groups as it is a more accessible solution.
    toolbarGroups: [{"name": "basicstyles", "groups": ["basicstyles"]}, {
        "name": "links",
        "groups": ["links"]
    }, {"name": "paragraph", "groups": ["list", "blocks"]}, {"name": "document", "groups": ["mode"]}, {
        "name": "insert",
        "groups": ["insert"]
    }, {"name": "styles", "groups": ["styles"]}, //{"name": "about", "groups": ["about"]}
    ], // Remove the redundant buttons from toolbar groups defined above.
    removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar', // width
    //width: 730,
    // extra plugins
    //extraPlugins: 'autogrow',
    //autoGrow_onStartup: true,
    //autoGrow_minHeight: 250,
    //autoGrow_maxHeight: 600
    autoParagraph: false // stop from automatically adding <p></p> tag
};

var editor_config_minimal = {
    // readOnly: true, // make editor readonly
    // Define the toolbar groups as it is a more accessible solution.
    toolbarGroups: [{"name": "basicstyles", "groups": ["basicstyles"]}, {
        "name": "links",
        "groups": ["links"]
    }, {"name": "paragraph", "groups": ["list", "blocks"]}, //{"name": "document", "groups": ["mode"]},
        {"name": "insert", "groups": ["insert"]}, {
            "name": "styles",
            "groups": ["styles"]
        }, //{"name": "about", "groups": ["about"]}
    ], // Remove the redundant buttons from toolbar groups defined above.
    removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Image,Flash,Smiley,HorizontalRule,SpecialChar,Format,Font,Iframe,PageBreak', // width
    //width: 730,
    // extra plugins
    //extraPlugins: 'autogrow',
    //autoGrow_onStartup: true,
    //autoGrow_minHeight: 250,
    //autoGrow_maxHeight: 600
    autoParagraph: false // stop from automatically adding <p></p> tag
};

/**
 * Instantiates a text area so that it it compatible with ajax based form submission
 * This function checks for any change in the editor and when changes is found
 * it updates the hidden input with the changed value in the editor
 *
 * @param id
 * @param config
 */
function initEditor(id, config = null) {

    if (!config) {
        config = editor_config_basic;
    }

    if ($('textarea#' + id).length) {
        CKEDITOR.replace(id, config);
        // update textarea as soon as something is updated in CKEditor
        CKEDITOR.instances[id].on('change', function () {
            CKEDITOR.instances[id].updateElement()
        });
    }
}

function reInitEditor(id, config = null) {
    CKEDITOR.instances[id].destroy();
    initEditor(id, config)
}


/**
 * jquery function to get outerHTML
 * @param s
 * @returns {*}
 */
jQuery.fn.outerHTML = function (s) {
    return s ? this.before(s).remove() : jQuery("<p>").append(this.eq(0).clone()).html();
};


/**
 * Get selected values as array from a multi-select select
 * @param selector
 * @param attr
 * @returns {Array}
 */
function getMultiSelectAsArray(selector, attr = null) {
    var arr = [];
    $(selector + ' :selected').each(function (i, selected) {
        if (attr) {
            val = $(selected).attr(attr);
        } else {
            val = $(selected).val();
        }

        arr[i] = val;
    });
    return arr;
}

// noinspection JSUnusedGlobalSymbols
/**
 * Get selected values as array from an array
 * @param selector
 * @returns {Array}
 */
function getInputAsArray(selector) {
    var arr = [];
    $(selector).each(function (i, input) {
        arr[i] = $(input).val();
    });
    return arr;
}


/**
 * Function to prepare the for that will POST to delete route.
 * Make delete button responsive to context. Click on delete button loads a modal
 * and a form with relevant values required for delete. These values include
 * the route that will trigger the delete. Also determines the redirect
 * path on successful delete and delete failure.
 */
function initGenericDeleteBtn() {

    prepareDeleteModalFormInputs();

    $('#deleteSubmit').click(function () {
        $('#deleteModal').modal('hide');
    });
}

function prepareDeleteModalFormInputs() {
    $('button[name=genericDeleteBtn]').click(function () {
        var route = $(this).attr('data-route');
        var redirect_success = $(this).attr('data-redirect_success');
        var redirect_fail = $(this).attr('data-redirect_fail');

        $('form[name=deleteForm]').attr('action', route);
        $('form[name=deleteForm] input[name=redirect_success]').val(redirect_success);
        $('form[name=deleteForm] input[name=redirect_fail]').val(redirect_fail);

    });
}

/**
 * Checks if returns json is valid json
 * @param val
 * @returns {*}
 */
function parseJson(val) {
    if (typeof val === 'object') {
        // if it is already a json object do nothing.
    } else {
        // else convert to json object
        val = JSON.parse(val);
    }
    return val;
}

// noinspection JSUnusedGlobalSymbols
/**
 * Disable all input
 */
function makeAllInputReadonly() {
    $('input, textarea, select').attr('readonly', 'readonly'); // make everything readonly
    $('button[name=genericDeleteBtn]').hide(); // hide delete buttons
    $('option:not(:selected)').attr('disabled', true).remove(); // remove all options that are not selected
    $("select").prop("disabled", true);
}

/**
 * Hide empty select options
 */
function hideEmptySelectOptions() {
    $('select option')
        .filter(function () {
            return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
        })
        .remove();
}

/**
 * Function is called in app/views/spyr/form/input-checkbox.blade.php
 * a checkbox and associative hidden input field is instantiated
 * based on existing value of the hidden input box.
 */
function initCheckbox() {

    /**
     * Go through each checkbox input field and if checkbox value is
     * equal to checked_val mark as checked(ticked). Otherwise
     * uncheck.the checkbox.
     */
    $('.spyr-checkbox').each(function () {

        var checkbox = $(this);
        var checked_val = checkbox.attr('data-checked-val');

        if (checkbox.val() == checked_val) {
            checkbox.prop('checked', true);
        } else {
            checkbox.prop('checked', false);
        }
        checkbox.trigger('change')
    });

    $('.spyr-checkbox').change(function () {
        var checkbox = $(this);
        var checked_val = checkbox.attr('data-checked-val');
        var unchecked_val = checkbox.attr('data-unchecked-val');
        var id = $(this).attr('data-checkbox-id');

        if (checkbox.is(':checked')) {
            $('input[class=' + id + ']').val(checked_val);
            checkbox.val(checked_val);
        } else {
            $('input[class=' + id + ']').val(unchecked_val);
            checkbox.val(unchecked_val);
        }
    });
}

/**
 * initUploader function initiates the generic uploader used commonly in modules
 *
 * documentations http://hayageek.com/docs/jquery-upload-file.php#doc
 * @param id : root div container of the uploader
 * @param url : url where uploader will post the data
 */
function initUploader(id, url) {
    $("#" + id + " .file-uploader").uploadFile({
        dragDropStr: "<span style='margin-left: 5px'> -OR- Drop files here</span>",
        url: url,
        method: "POST", //allowedTypes: "",
        fileName: "file",
        showStatusAfterSuccess: true,
        autoSubmit: true,
        dragDrop: true,
        dragdropWidth: '100%', //maxFileSize: 8,
        //maxFileCount: 1,
        //acceptFiles: "audio/*",
        multiple: true,
        statusBarWidth: '100%',
        uploadButtonClass: 'btn btn-default bg-white btn-sm',
        returnType: 'json',
        showPreview: true,
        showDone: true,
        doneStr: 'Done', // dynamicFormData: function () {                   // old implementation
        //     return {
        //         "ret": "json",
        //         "_token": $("#" + id + " input[name=_token]").val(),
        //         "tenant_id": $("#" + id + " input[name=tenant_id]").val(),
        //         "module_id": $("#" + id + " input[name=module_id]").val(),
        //         "element_id": $("#" + id + " input[name=element_id]").val(),
        //         "element_uuid": $("#" + id + " input[name=element_uuid]").val(),
        //         "type": $("#" + id + " input[name=type]").val()
        //     };
        // },
        dynamicFormData: function () {                      // New implementation using serialization
            // return $('#' + id + ' form').serialize();
            return $('div#' + id).find("select, textarea, input").serialize();
        }, //onSuccess: function (files, ret, xhr){};
        onSuccess: function (files, ret) {
            showResponseModal(parseJson(ret), 3000);

            //console.log(ret);
            if (ret.status == 'fail') {
                $('div.ajax-file-upload-green').hide();
            }
            // var path = ret.message.path;
        }, //onError: function (files, status, errMsg) {};
        onError: function () {
            $("#status").html("<span style='color: green;'>Something Wrong</span>");
        }
    });
}

/**
 * Function to check if a key exists in a tested json return.
 * https://stackoverflow.com/questions/2631001/javascript-test-for-existence-of-nested-object-key
 *
 * var test = {level1:{level2:{level3:'level3'}} };
 * checkNested(test, 'level1', 'level2', 'level3'); // true
 * checkNested(test, 'level1', 'level2', 'foo'); // false
 *
 * @param obj
 * @returns {boolean}
 */
function hasNestedKey(obj /*, level1, level2, ... levelN*/) {
    var args = Array.prototype.slice.call(arguments, 1);

    for (var i = 0; i < args.length; i++) {
        if (!obj || !obj.hasOwnProperty(args[i])) {
            return false;
        }
        obj = obj[args[i]];
    }
    return true;
}

if (!$.fn.bootstrapDatepicker && $.fn.datepicker && $.fn.datepicker.noConflict) {
    var datepicker = $.fn.datepicker.noConflict();
    $.fn.bootstrapDatepicker = datepicker;
}

/**
 * Init datepicker
 * @param selector
 * @param format
 * @returns {jQuery|undefined}
 */
function initBootstrapDatepicker(selector, format = 'dd-mm-yyyy') {
    return $(selector + '_formatted').bootstrapDatepicker({
        format: format, autoclose: true, clearBtn: true,
    }).on('clearDate', function (ev) {
        $(selector).val(null);
    }).on('changeDate', function (ev) {
        var validDate = null;
        var formattedDate = $(this).val();      // '01-04-2020'

        if (formattedDate.length) {

            var formatParts = format.split('-');   // ['01','04','2020']
            var dateParts = formattedDate.split('-');   // ['01','04','2020']

            var map = [];
            for (var i = 0; i < formatParts.length; i++) {
                map[formatParts[i]] = dateParts[i];
            }

            // console.log(map);

            var day = map['dd'];             // '01'
            var month = map['mm'];           // '04'
            var year = map['yyyy'];            // '2020'
            // console.log(year.length + " " + month.length + " " + day.length);
            if (year.length == 4 && month.length == 2 && day.length == 2) {
                validDate = year + '-' + month + '-' + day;
            }
        }
        $(selector).val(validDate);
    });
}

/**
 * Init datepicker
 * @param selector
 * @param format
 * @returns {jQuery|undefined}
 */
function initJQueryDatePicker(selector, format = 'dd-mm-yy') {
    return $(selector + '_formatted').datepicker({
        dateFormat: format, altFormat: "yy-mm-dd", // Standard datetime format
        altField: selector, changeMonth: true, changeYear: true
    });
}

/**
 * JS based api_token re-generator
 * @param len
 * @param charSet
 * @returns {string}
 */
function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz, randomPoz + 1);
    }
    return randomString;
}

/**
 * Format a text area that contains Json data.
 * @param css
 */
function initJsonTextarea(css) {

    if (!css) {
        css = 'json';
    }

    // console.log(css);
    $('textarea.' + css).each(function (i, el) {
        var elem = $(el);
        $(elem).bind('change', () => {
            var ugly = elem.val();
            if (ugly) {
                // console.log(elem);
                var obj = JSON.parse(ugly);
                var pretty = JSON.stringify(obj, undefined, 4);
                elem.val(pretty);
            }
        });
        elem.trigger('change');
    });

}

/**
 * Float the cta
 */
function ctaFloat() {
    $(".delete-cta").css({"margin-right": 0});
    $(".cta-block").css({"position": "relative", "border-top": "none"});
}

/**
 * Change default module CTA text
 * @param text
 * @param target
 */
function ctaText(text, target = ".module-save-btn") {
    $(target).html(text);
}

/**
 * Print page
 */
function printPage(buttonId = "btnPrint") {
    var printButton = document.getElementById(buttonId);
    printButton.style.visibility = 'hidden';
    window.print();
    printButton.style.visibility = 'visible';
}