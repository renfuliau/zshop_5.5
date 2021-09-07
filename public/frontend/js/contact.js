$(document).ready(function () {

    (function ($) {
        "use strict";


        jQuery.validator.addMethod('answercheck', function (value, element) {
            return this.optional(element) || /^\bcat\b$/.test(value)
        }, "type the correct answer -_-");

        // validate contactForm form
        $(function () {
            $('#contactForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    message: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "姓名為必填",
                        minlength: "至少2個字元"
                    },
                    email: {
                        required: "Email為必填"
                    },
                    message: {
                        required: "請留下您的寶貴意見",
                        minlength: "至少10個字元"
                    }
                },
                // 
                
            })
        })

    })(jQuery)
})
