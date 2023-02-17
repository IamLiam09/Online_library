/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by HernÃ¡n Sartorio  */
    !function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);


    $(document).ready(function() {
        /********* On scroll heder Sticky *********/
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            if (scroll >= 50) {
                $("header").addClass("head-sticky");
            } else {
                $("header").removeClass("head-sticky");
            }
        }); 
        

        
    $('[data-confirm]').each(function () {
        var me = $(this),
            me_data = me.data('confirm');
        me_data = me_data.split("|");
        me.fireModal({
            title: me_data[0],
            body: me_data[1],
            buttons: [
                {
                    text: me.data('confirm-text-yes') || 'Yes',
                    class: 'firemodal_btn btn-primary btn-icon',
                    handler: function () {
                        eval(me.data('confirm-yes'));
                    }
                },
                {
                    text: me.data('confirm-text-cancel') || 'Cancel',
                    class: 'firemodal_btn btn-danger btn-icon text-dark',
                    handler: function (modal) {
                        $.destroyModal(modal);
                        eval(me.data('confirm-no'));
                    }
                }
            ]
        })
    });
        /********* Wrapper top space ********/
        var header_hright = $('header').outerHeight();
        $('header').next('.wrapper').css('margin-top', header_hright + 'px');  
        /********* Announcebar hide ********/
        $('#announceclose').click(function () {
            $('.announcebar').slideUp();
        }); 
        /********* Mobile Menu ********/ 
        $('.mobile-menu-button').on('click',function(e){
            e.preventDefault();
            setTimeout(function(){
                $('body').addClass('no-scroll active-menu');
                $(".mobile-menu-wrapper").toggleClass("active-menu");
                $('.overlay').addClass('menu-overlay');
            }, 50);
        }); 
        $('body').on('click','.overlay.menu-overlay, .menu-close-icon svg', function(e){
            e.preventDefault(); 
            $('body').removeClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").removeClass("active-menu");
            $('.overlay').removeClass('menu-overlay');
        });
 
        /********* Mobile Filter Popup ********/
        $('.filter-title').on('click',function(e){
            e.preventDefault();
            setTimeout(function(){
            $('body').addClass('no-scroll filter-open');
            $('.overlay').addClass('active');
            }, 50);
        }); 
        $('body').on('click','.overlay.active, .close-filter', function(e){
            e.preventDefault(); 
            $('.overlay').removeClass('active');
            $('body').removeClass('no-scroll filter-open');
        });
        /*********  Header Search Popup  ********/ 
        $(".search-header a").click(function() { 
            $(".search-popup").toggleClass("active"); 
            $("body").toggleClass("no-scroll");
        });
        $(".close-search").click(function() { 
            $(".search-popup").removeClass("active"); 
            $("body").removeClass("no-scroll");
        });

        /******  Nice Select  ******/ 
        $('select').niceSelect(); 
        /*********  Multi-level accordion nav  ********/ 
        $('.acnav-label').click(function () {
            var label = $(this);
            var parent = label.parent('.has-children');
            var list = label.siblings('.acnav-list');
            if (parent.hasClass('is-open')) {
                list.slideUp('fast');
                parent.removeClass('is-open');
            }
            else {
                list.slideDown('fast');
                parent.addClass('is-open');
            }
        }); 

        /****  TAB Js ****/

        $('ul.tabs li').click(function(){
            var $this = $(this);
            var $theTab = $(this).attr('data-tab');
            if($this.hasClass('active')){
            // do nothing
            } else{
            $this.closest('.tabs-wrapper').find('ul.tabs li, .tabs-container .tab-content').removeClass('active');
            $('.tabs-container .tab-content[id="'+$theTab+'"], ul.tabs li[data-tab="'+$theTab+']').addClass('active');
            }
            $('ul.tabs li').removeClass('active');
            $(this).addClass('active');
        });

        // client-logo-slider
        if($('.client-logo-slider').length > 0 ){
            $('.client-logo-slider').slick({
                autoplay: true, 
                slidesToShow: 5,
                speed: 1000,
                centerMode:true,
                centerPadding:0,
                slidesToScroll: 1,  
                prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                dots: false,
                buttons: false,
                responsive: [ 
                    {
                        breakpoint: 1200,
                        settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1   
                        }
                    },  
                    {
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1 
                        }
                    },
                    {
                    breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1 
                        }
                    }
                ]
            });
        }
        if($('.latest-course-slider').length > 0 ){
            $('.latest-course-slider').slick({
                slidesToShow: 3,
                speed: 1000,
                centerMode:true,
                centerPadding:0,
                slidesToScroll: 1,  
                prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                dots: false,
                buttons: false,
                responsive: [ 
                    {
                        breakpoint: 1200,
                        settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1   
                        }
                    },  
                    {
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1 
                        }
                    },
                    {
                    breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1 
                        }
                    }
                ]
            });
        }
        if($('.more-course-slider').length > 0 ){
            $('.more-course-slider').slick({
                slidesToShow: 3,
                speed: 1000,
                centerMode:true,
                centerPadding:0,
                slidesToScroll: 1,  
                prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                dots: false,
                buttons: false,
                responsive: [ 
                    {
                        breakpoint: 1200,
                        settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1   
                        }
                    },  
                    {
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1 
                        }
                    },
                    {
                    breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1 
                        }
                    }
                ]
            });
        }
        if($('.sale-slider').length > 0 ){
            $('.sale-slider').slick({
                slidesToShow: 3,
                speed: 1000,
                slidesToScroll: 1,  
                prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
                dots: false,
                buttons: false,
                responsive: [ 
                    {
                        breakpoint: 1200,
                        settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1   
                        }
                    },  
                    {
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1 
                        }
                    },
                    {
                    breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1 
                        }
                    }
                ]
            });
    }
 
    });

    $(function(){
        $('.product-search-drop-dwn > .select').on('click',function(event){
            event.preventDefault()
            $('.product-search-drop-dwn > .select').toggleClass('active');
            $(this).parent().find('ul').first().toggle(300);
            $(this).parent().siblings().find('ul').hide(200);
            //Hide menu when clicked outside
        //   $(this).parent().find('ul').mouseleave(function(){  
        //     var thisUI = $(this);
        //     $('html').click(function(){
        //       thisUI.hide();
        //       $('html').unbind('click');
        //     });
        //   });

        //   $('.product-search-drop-dwn > .select').mouseleave(function(){  
        //     var thisUI = $(this);
        //     $('html').click(function(){
        //       thisUI.removeClass('active');
        //       $('html').unbind('click');
        //     });
        //   });

        });
    });

      

    $(document).on('click', 'a[data-ajax-popup-blur="true"], button[data-ajax-popup-blur="true"], div[data-ajax-popup-blur="true"]', function () {
        var title = $(this).attr('data-title');
        var size = ($(this).attr('data-size') == '') ? 'md' : $(this).attr('data-size');
        var url = $(this).attr('data-url');
    
        $("#commonModalBlur .modal-title").html('');
        $("#commonModalBlur .modal-title").html(title);
        $("#commonModalBlur .modal-dialog").addClass('modal-' + size);
    
        $.ajax({
            url: url,
            success: function (data) {
                $('#commonModalBlur .modal-body').html('');
                $('#commonModalBlur .modal-body').html(data);
                $("#commonModalBlur").modal('show');
                common_bind("#commonModalBlur");
            },
            error: function (data) {
                data = data.responseJSON;
            }
        });
    
    });



    (function ($, window, i) {

        // Bootstrap 4 Modal
        $.fn.fireModal = function (options) {
            var options = $.extend({
                size: 'modal-md',
                center: false,
                animation: true,
                title: 'Modal Title',
                closeButton: true,
                header: true,
                bodyClass: '',
                footerClass: '',
                body: '',
                buttons: [],
                autoFocus: true,
                created: function () {
                },
                appended: function () {
                },
                onFormSubmit: function () {
                },
                modal: {}
            }, options);
    
            this.each(function () {
                i++;
                var id = 'fire-modal-' + i,
                    trigger_class = 'trigger--' + id,
                    trigger_button = $('.' + trigger_class);
    
                $(this).addClass(trigger_class);
    
                // Get modal body
                let body = options.body;
    
                if (typeof body == 'object') {
                    if (body.length) {
                        let part = body;
                        body = body.removeAttr('id').clone().removeClass('modal-part');
                        part.remove();
                    } else {
                        body = '<div class="text-danger">Modal part element not found!</div>';
                    }
                }
    
                // Modal base template
                var modal_template = '   <div class="modal custom_fire_modal' + (options.animation == true ? ' fade' : '') + '" tabindex="-1" role="dialog" id="' + id + '">  ' +
                    '     <div class="modal-dialog ' + options.size + (options.center ? ' modal-dialog-centered' : '') + '" role="document">  ' +
                    '       <div class="modal-content">  ' +
                    ((options.header == true) ?
                        '         <div class="modal-header">  ' +
                        '           <h5 class="modal-title">' + options.title + '</h5>  ' +
                        ((options.closeButton == true) ?
                            '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">  ' +
                            '             <span aria-hidden="true"> <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none"><path d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z" fill="white"></path></svg></span>  ' +
                            '           </button>  '
                            : '') +
                        '         </div>  '
                        : '') +
                    '         <div class="modal-body">  ' +
                    '         </div>  ' +
                    (options.buttons.length > 0 ?
                        '         <div class="modal-footer">  ' +
                        '         </div>  '
                        : '') +
                    '       </div>  ' +
                    '     </div>  ' +
                    '  </div>  ';
    
                // Convert modal to object
                var modal_template = $(modal_template);
    
                // Start creating buttons from 'buttons' option
                var this_button;
                options.buttons.forEach(function (item) {
                    // get option 'id'
                    let id = "id" in item ? item.id : '';
    
                    // Button template
                    this_button = '<button type="' + ("submit" in item && item.submit == true ? 'submit' : 'button') + '" class="' + item.class + '" id="' + id + '">' + item.text + '</button>';
    
                    // add click event to the button
                    this_button = $(this_button).off('click').on("click", function () {
                        // execute function from 'handler' option
                        item.handler.call(this, modal_template);
                    });
                    // append generated buttons to the modal footer
                    $(modal_template).find('.modal-footer').append(this_button);
                });
    
                // append a given body to the modal
                $(modal_template).find('.modal-body').append(body);
    
                // add additional body class
                if (options.bodyClass) $(modal_template).find('.modal-body').addClass(options.bodyClass);
    
                // add footer body class
                if (options.footerClass) $(modal_template).find('.modal-footer').addClass(options.footerClass);
    
                // execute 'created' callback
                options.created.call(this, modal_template, options);
    
                // modal form and submit form button
                let modal_form = $(modal_template).find('.modal-body form'),
                    form_submit_btn = modal_template.find('button[type=submit]');
    
                // append generated modal to the body
                $("body").append(modal_template);
    
                // execute 'appended' callback
                options.appended.call(this, $('#' + id), modal_form, options);
    
                // if modal contains form elements
                if (modal_form.length) {
                    // if `autoFocus` option is true
                    if (options.autoFocus) {
                        // when modal is shown
                        $(modal_template).on('shown.bs.modal', function () {
                            // if type of `autoFocus` option is `boolean`
                            if (typeof options.autoFocus == 'boolean')
                                modal_form.find('input:eq(0)').focus(); // the first input element will be focused
                            // if type of `autoFocus` option is `string` and `autoFocus` option is an HTML element
                            else if (typeof options.autoFocus == 'string' && modal_form.find(options.autoFocus).length)
                                modal_form.find(options.autoFocus).focus(); // find elements and focus on that
                        });
                    }
    
                    // form object
                    let form_object = {
                        startProgress: function () {
                            modal_template.addClass('modal-progress');
                        },
                        stopProgress: function () {
                            modal_template.removeClass('modal-progress');
                        }
                    };
    
                    // if form is not contains button element
                    if (!modal_form.find('button').length) $(modal_form).append('<button class="d-none" id="' + id + '-submit"></button>');
    
                    // add click event
                    form_submit_btn.on('click', function () {
                        modal_form.submit();
                    });
    
                    // add submit event
                    modal_form.submit(function (e) {
                        // start form progress
                        form_object.startProgress();
    
                        // execute `onFormSubmit` callback
                        options.onFormSubmit.call(this, modal_template, e, form_object);
                    });
                }
    
                $(document).on("click", '.' + trigger_class, function () {
                    $('#' + id).modal(options.modal);
    
                    return false;
                });
            });
        }
    
        // Bootstrap Modal Destroyer
        $.destroyModal = function (modal) {
            modal.modal('hide');
            modal.on('hidden.bs.modal', function () {
            });
        }
    })(jQuery, this, 0);
     
    function show_toastr(title, message, type) {
        
        var o, i;
        var icon = '';
        var cls = '';
        if (type == 'success') {
            icon = 'fas fa-check-circle';
            cls = 'success';
        } else {
            icon = 'fas fa-times-circle';
            cls = 'danger';
        }
        $.notify({icon: icon, title: " " + title, message: message, url: ""}, {
            element: "body",
            type: cls,
            allow_dismiss: !0,
            placement: {from: 'top', align: 'right'},
            offset: {x: 15, y: 15},
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 2000,
            url_target: "_blank",
            mouse_over: !1,
            animate: {enter: o, exit: i},
            template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
        });
    }


